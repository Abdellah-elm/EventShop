<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commande')]
#[IsGranted('ROLE_USER')] // Sécurité : Il faut être connecté pour gérer ses commandes
final class CommandeController extends AbstractController
{
    /**
     * Affiche l'historique des commandes de l'utilisateur connecté
     */
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(): Response
    {
        // On récupère uniquement les commandes de l'utilisateur connecté
        $user = $this->getUser();
        
        return $this->render('commande/index.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }

    /**
     * LA MÉTHODE LA PLUS IMPORTANTE : Transforme le Panier en Commande
     */
    #[Route('/valider', name: 'app_commande_validate', methods: ['GET'])]
    public function validate(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $panierItems = $user->getPaniers(); // On récupère le contenu du panier

        // 1. Si le panier est vide, on rejette
        if ($panierItems->isEmpty()) {
            $this->addFlash('warning', 'Votre panier est vide.');
            return $this->redirectToRoute('app_event_index');
        }

        // 2. Création de la Commande globale
        $commande = new Commande();
        $commande->setUser($user);
        $commande->setDateCommande(new \DateTime()); // Utilise la méthode qu'on a ajoutée dans l'entité
        $commande->setStatut('Payée'); // Par défaut, on considère que c'est payé
        
        $em->persist($commande);

        // 3. Boucle sur le panier pour créer les Billets
        foreach ($panierItems as $item) {
            
            // On crée autant de billets que la quantité demandée (ex: 3 billets pour le même concert)
            for ($i = 0; $i < $item->getQuantite(); $i++) {
                $billet = new Billet();
                $billet->setUser($user);
                $billet->setEvent($item->getEvent());
                $billet->setCommande($commande); // On relie le billet à la commande
                $billet->setStatus('Valide');
                
                $em->persist($billet);
            }

            // 4. On supprime l'article du panier une fois transformé en billet
            $em->remove($item);
        }

        // 5. On sauvegarde tout en base de données d'un coup
        $em->flush();

        $this->addFlash('success', 'Votre commande a été validée avec succès ! Vos billets sont disponibles.');
        
        // Redirection vers la page "Mes Billets"
        return $this->redirectToRoute('app_my_tickets');
    }

    /**
     * Affiche les détails d'une commande spécifique
     */
    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        // Sécurité : On vérifie que la commande appartient bien à l'utilisateur (ou que c'est un admin)
        if ($commande->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas voir cette commande.');
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
}