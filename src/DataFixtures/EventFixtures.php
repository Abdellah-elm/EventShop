<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $event = new Event();
            // Utilisation des propriétés définies dans Event.php
            $event->setTitle("Événement de test n°$i");
            $event->setDescription("Ceci est la description détaillée de l'événement numéro $i.");
            $event->setLocation("Lieu $i, Casablanca");
            $event->setCapacity(50 * $i);
            
            // Dates au format DateTimeInterface comme requis
            $event->setDateStart(new \DateTime("+ $i days"));
            $event->setDateEnd(new \DateTime("+ " . ($i + 1) . " days"));

            $manager->persist($event);
        }

        $manager->flush();
    }
}