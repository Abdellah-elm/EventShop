<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Panier;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panier')]
#[IsGranted('ROLE_USER')] // Sécurité : Seul un membre connecté peut accéder au panier
final class PanierController extends AbstractController
{
    // 1. AFFICHER LE PANIER
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();
        
        // On récupère UNIQUEMENT les articles de CE client (via la relation User -> Paniers)
        // C'est la correction majeure par rapport au findAll() qui affichait tout le monde
        return $this->render('panier/index.html.twig', [
            'items' => $user->getPaniers(), 
        ]);
    }

    // 2. AJOUTER UN ÉVÉNEMENT AU PANIER
    #[Route('/add/{id}', name: 'app_panier_add', methods: ['GET'])]
    public function add(Event $event, EntityManagerInterface $em, PanierRepository $panierRepo): Response
    {
        $user = $this->getUser();

        // Vérifier si cet événement est DÉJÀ dans le panier de l'utilisateur
        $panierItem = $panierRepo->findOneBy(['user' => $user, 'event' => $event]);

        if ($panierItem) {
            // Si oui, on augmente juste la quantité
            $panierItem->setQuantite($panierItem->getQuantite() + 1);
        } else {
            // Si non, on crée une nouvelle ligne dans le panier
            $panierItem = new Panier();
            $panierItem->setUser($user);
            $panierItem->setEvent($event);
            $panierItem->setQuantite(1);
            $em->persist($panierItem);
        }

        $em->flush(); // Sauvegarde en base de données

        $this->addFlash('success', 'Article ajouté au panier !');
        
        // On redirige vers la liste des paniers pour voir le résultat
        return $this->redirectToRoute('app_panier_index'); 
    }

    // 3. SUPPRIMER UN ARTICLE DU PANIER
    #[Route('/delete/{id}', name: 'app_panier_delete', methods: ['GET'])]
    public function delete(Panier $panier, EntityManagerInterface $em): Response
    {
        // Sécurité : On vérifie que le panier appartient bien à l'utilisateur connecté
        if ($panier->getUser() === $this->getUser()) {
            $em->remove($panier);
            $em->flush();
            $this->addFlash('info', 'Article retiré du panier.');
        }

        return $this->redirectToRoute('app_panier_index');
    }
}