<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChatbotController extends AbstractController
{
    #[Route('/chatbot/ask', name: 'chatbot_ask', methods: ['POST'])]
    public function ask(
        Request $request,
        EventRepository $eventRepository,
        HttpClientInterface $httpClient
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $userMessage = trim($data['message'] ?? '');

        if (empty($userMessage)) {
            return $this->json(['reply' => 'Veuillez poser une question.'], 400);
        }

        $events = $eventRepository->findAll();
        $eventContext = $this->buildEventContext($events);

        $systemPrompt = "Tu es l'assistant virtuel de Event Shop Maroc, une plateforme de billetterie en ligne.\n\n"
            . "Ton rôle :\n"
            . "- Répondre aux questions sur les événements disponibles\n"
            . "- Aider les clients à réserver des billets\n"
            . "- Donner les infos pratiques (dates, lieux, prix, places disponibles)\n"
            . "- Fournir un support client général (compte, commandes, billets PDF)\n\n"
            . "Règles :\n"
            . "- Réponds TOUJOURS en français\n"
            . "- Sois concis, amical et professionnel\n"
            . "- Si tu ne connais pas la réponse, oriente vers le support : support@eventshop.ma\n"
            . "- Pour réserver, dirige vers la page de l'événement sur le site\n"
            . "- Ne donne jamais de fausses informations\n\n"
            . "Événements disponibles :\n" . $eventContext;

        try {
            $apiKey = $this->getParameter('app.groq_api_key');

            $response = $httpClient->request('POST', 'https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ],
            ]);

            $result = $response->toArray();
            $reply = $result['choices'][0]['message']['content'] ?? "Désolé, je n'ai pas pu générer une réponse.";

            return $this->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return $this->json([
                'reply' => 'Service indisponible'()
            ], 500);
        }
    }

    private function buildEventContext(array $events): string
    {
        if (empty($events)) {
            return "Aucun événement disponible pour le moment.";
        }

        $lines = [];
        foreach ($events as $event) {
            $billets = $event->getBillets()->count();
            $placesRestantes = $event->getCapacity() - $billets;

            $lines[] = sprintf(
                "- %s | Du %s au %s | Lieu: %s | Prix: %s MAD | Capacité: %d | Places restantes: %d | %s",
                $event->getTitle(),
                $event->getDateStart()?->format('d/m/Y H:i'),
                $event->getDateEnd()?->format('d/m/Y H:i'),
                $event->getLocation(),
                $event->getPrice() ?? 'Gratuit',
                $event->getCapacity(),
                max(0, $placesRestantes),
                $event->getDescription() ?? ''
            );
        }

        return implode("\n", $lines);
    }
}
