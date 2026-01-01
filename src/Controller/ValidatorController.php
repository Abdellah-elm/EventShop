<?php

namespace App\Controller;

use App\Entity\Billet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/scan')]
#[IsGranted('ROLE_ADMIN')] // Seul le staff peut scanner
class ValidatorController extends AbstractController
{
    #[Route('/{id}', name: 'app_validate_ticket')]
    public function validate(Billet $billet, EntityManagerInterface $em): Response
    {
        // 1. Si le billet est déjà scanné -> ERREUR
        if ($billet->getStatus() === 'UTILISÉ') {
            return $this->render('validator/result.html.twig', [
                'billet' => $billet,
                'success' => false,
                'message' => 'ATTENTION : Ce billet a DÉJÀ été utilisé !'
            ]);
        }

        // 2. Sinon -> VALIDATION
        $billet->setStatus('UTILISÉ'); // On "brûle" le billet
        $em->flush();

        return $this->render('validator/result.html.twig', [
            'billet' => $billet,
            'success' => true,
            'message' => 'Billet VALIDE. Entrée autorisée.'
        ]);
    }
}