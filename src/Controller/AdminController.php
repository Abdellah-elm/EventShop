<?php

namespace App\Controller;

use App\Entity\Event; 
use App\Repository\BilletRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse; 
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')] 
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(
        EventRepository $eventRepo,
        BilletRepository $billetRepo,
        UserRepository $userRepo
    ): Response
    {
        $totalEvents = $eventRepo->count([]);
        $totalUsers = $userRepo->count([]);
        $totalBillets = $billetRepo->count([]);

        $events = $eventRepo->findAll();
        $chiffreAffaires = 0;
        
        $eventNames = [];
        $ticketCounts = [];

        foreach ($events as $event) {
            $sold = count($event->getBillets());
            
            $realPrice = $event->getPrice() ?? 0; 
            
            $chiffreAffaires += ($sold * $realPrice);

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

    #[Route('/event/{id}/export', name: 'app_admin_export_guests')]
    public function exportGuests(Event $event): Response
    {
        $csvContent = [];
        
        $csvContent[] = "\xEF\xBB\xBF" . implode(';', ['ID Billet', 'Nom', 'Email', 'Date Emission', 'Etat']);

        foreach ($event->getBillets() as $billet) {
            $user = $billet->getUser();
            
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