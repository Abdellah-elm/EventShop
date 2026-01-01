<?php

namespace App\DataFixtures;

use App\Entity\Billet;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // --- 1. ADMIN ---
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setNom('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAdresse('admin');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        // --- 2. CLIENTS ---
        $users = [];
        $noms = ['Bennani', 'Alami', 'Tazi', 'Idrissi', 'Benjelloun'];
        
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail('client' . $i . '@gmail.com');
            $user->setNom('Client ' . $noms[$i % count($noms)]);
            $user->setRoles(['ROLE_USER']);
            $user->setAdresse('Quartier Maarif, Casablanca');
            $user->setPassword($this->hasher->hashPassword($user, 'password123'));
            
            $manager->persist($user);
            $users[] = $user;
        }

        // --- 3. ÉVÉNEMENTS ---
        $eventsData = [
            [
                'title' => 'Marrakech du Rire 2026',
                'desc' => 'Le plus grand festival d\'humour. Jamel et ses amis au Palais Badii.',
                'loc' => 'Marrakech',
                'price' => 800,
                'cap' => 1500,
                'start' => '+2 months', 'end' => '+2 months 4 days'
            ],
            [
                'title' => 'Festival Gnaoua',
                'desc' => 'Musique du monde et traditions gnaouies à Essaouira.',
                'loc' => 'Essaouira',
                'price' => 300,
                'cap' => 800,
                'start' => '+3 months', 'end' => '+3 months 3 days'
            ],
            [
                'title' => 'Derby: Raja vs Wydad',
                'desc' => 'Le grand derby casablancais au complexe Mohammed V.',
                'loc' => 'Casablanca',
                'price' => 50,
                'cap' => 45000,
                'start' => '+1 week', 'end' => '+1 week 3 hours'
            ],
            [
                'title' => 'Mawazine - Scène Internationale',
                'desc' => 'Les stars mondiales se donnent rendez-vous à Rabat.',
                'loc' => 'Rabat',
                'price' => 1200,
                'cap' => 100,
                'start' => '+1 month', 'end' => '+1 month 6 days'
            ]
        ];

        foreach ($eventsData as $data) {
            $event = new Event();
            $event->setTitle($data['title']);
            $event->setDescription($data['desc']);
            $event->setLocation($data['loc']);
            $event->setPrice($data['price']);
            $event->setCapacity($data['cap']);
            $event->setDateStart($faker->dateTimeBetween('now', $data['start']));
            $event->setDateEnd($faker->dateTimeBetween($data['start'], $data['end']));

            $manager->persist($event);

            // --- 4. BILLETS ---
            $nbBillets = rand(5, 30);
            if($data['cap'] == 100) $nbBillets = 95;

            for ($j = 0; $j < $nbBillets; $j++) {
                $billet = new Billet();
                $billet->setEvent($event);
                $billet->setUser($users[array_rand($users)]);
                
                // --- CORRECTION : AJOUT DU STATUT OBLIGATOIRE ---
                $billet->setStatus('VALIDE'); 
                
                // Ajout de la date de création aussi pour être sûr


                $manager->persist($billet);
            }
        }

        $manager->flush();
    }
}