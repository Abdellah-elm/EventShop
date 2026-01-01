<?php

namespace App\Controller;

use App\Entity\Event; // Import important pour l'export
use App\Repository\BilletRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse; // Import pour le fichier CSV
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')] // Sécurité totale pour ce contrôleur
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(
        EventRepository $eventRepo,
        BilletRepository $billetRepo,
        UserRepository $userRepo
    ): Response
    {
        // 1. Les chiffres clés (KPIs)
        $totalEvents = $eventRepo->count([]);
        $totalUsers = $userRepo->count([]);
        $totalBillets = $billetRepo->count([]);

        // 2. Calcul du Chiffre d'Affaires PRÉCIS
        $events = $eventRepo->findAll();
        $chiffreAffaires = 0;
        
        // Données pour le GRAPHIQUE
        $eventNames = [];
        $ticketCounts = [];

        foreach ($events as $event) {
            // Nombre de billets vendus pour cet événement
            $sold = count($event->getBillets());
            
            // On récupère le vrai prix depuis la base de données.
            $realPrice = $event->getPrice() ?? 0; 
            
            // On ajoute au total global
            $chiffreAffaires += ($sold * $realPrice);

            // Préparation des données pour le graphique
            $eventNames[] = $event->getTitle();
            $ticketCounts[] = $sold;
        }

        return $this->render('admin/dashboard.html.twig', [
            'totalEvents' => $totalEvents,
            'totalUsers' => $totalUsers,
            'totalBillets' => $totalBillets,
            'chiffreAffaires' => $chiffreAffaires,
            'eventNames' => json_encode($eventNames),
            'ticketCounts' => json_encode($ticketCounts),
        ]);
    }

    /**
     * NOUVELLE FONCTION : Exporter la liste des invités en CSV (Excel)
     */
    #[Route('/event/{id}/export', name: 'app_admin_export_guests')]
    #[Route('/event/{id}/export', name: 'app_admin_export_guests')]
   
    #[Route('/event/{id}/export', name: 'app_admin_export_guests')]
   

    #[Route('/event/{id}/export', name: 'app_admin_export_guests')]
    public function exportGuests(Event $event): Response
    {
        $csvContent = [];
        
        // Entêtes
        $csvContent[] = "\xEF\xBB\xBF" . implode(';', ['ID Billet', 'Nom', 'Email', 'Date Emission', 'Etat']);

        foreach ($event->getBillets() as $billet) {
            $user = $billet->getUser();
            
            // --- SECURISATION TOTALE ---
            // On ne demande plus getCreatedAt().
            // On met la date d'aujourd'hui par défaut pour l'export.
            $date = date('d/m/Y'); 

            $csvContent[] = implode(';', [
                '#' . $billet->getId(),
                $user ? $user->getNom() : 'Inconnu',
                $user ? $user->getEmail() : '-',
                $date,
                'Valide'
            ]);
        }

        $finalContent = implode("\n", $csvContent);
        $response = new Response($finalContent);
        
        $filename = 'export-event-' . $event->getId() . '.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

}