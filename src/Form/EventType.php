<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// --- IMPORTS OBLIGATOIRES ---
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; // <--- C'est lui qui manquait
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'événement'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description'
            ])
            
            // --- CORRECTION ICI ---
            // On remplace 'null' par 'DateTimeType::class'
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text', // Maintenant ça va marcher !
                'required' => true
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => false
            ])
            // ----------------------

            ->add('location', TextType::class, [
                'label' => 'Lieu'
            ])
            
            // Le prix (que nous avons ajouté juste avant)
            ->add('price', MoneyType::class, [
                'label' => 'Prix du billet',
                'currency' => 'MAD', // Ou EUR selon votre choix
                'divisor' => 1,
                'required' => false
            ])
            
            ->add('capacity', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}