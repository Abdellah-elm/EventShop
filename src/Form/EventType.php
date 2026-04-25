<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\File;

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
            
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text', 
                'required' => true
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text',
                'required' => false
            ])

            ->add('location', TextType::class, [
                'label' => 'Lieu'
            ])
            
            ->add('price', MoneyType::class, [
                'label' => 'Prix du billet',
                'currency' => 'MAD',
                'divisor' => 1,
                'required' => false
            ])
            
            ->add('capacity', IntegerType::class, [
                'label' => 'Nombre de places'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l\'événement',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(maxSize: '5M'),
                ],
                'attr' => ['accept' => 'image/*'],
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