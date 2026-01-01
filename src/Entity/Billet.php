<?php

namespace App\Entity;

use App\Repository\BilletRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Billet Entity
 * Mise à jour : Ajout de la relation avec Commande
 */
#[ORM\Entity(repositoryClass: BilletRepository::class)]
#[ORM\Table(name: 'billet')]
class Billet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $status = null;

    /**
     * Relation vers l'Utilisateur qui a le billet
     */
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'billets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Relation vers l'Événement concerné
     */
    #[ORM\ManyToOne(targetEntity: Event::class, inversedBy: 'billets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    /**
     * --- NOUVELLE PROPRIÉTÉ AJOUTÉE ---
     * Relation vers la Commande (Pour corriger l'erreur Twig)
     * On met nullable=true car les anciens billets n'ont peut-être pas de commande
     */
    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'billets')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Commande $commande = null;

    // --- GETTERS ET SETTERS ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;
        return $this;
    }

    /**
     * --- NOUVEAUX GETTERS/SETTERS POUR COMMANDE ---
     */
    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;
        return $this;
    }
}