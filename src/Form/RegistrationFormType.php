<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // Important
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver; 
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    // src/Form/RegistrationFormType.php
// ...
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email') //
        // src/Form/RegistrationFormType.php

// ...
//>add('nom')     //
->add('nom', TextType::class, [
    'label' => 'Nom complet',
    'attr' => ['class' => 'form-control', 'placeholder' => 'Votre nom']
])
->add('adresse') //
->add('agreeTerms', CheckboxType::class, [
    'mapped' => false,
    'constraints' => [
        // CORRECTION : Utilisez l'argument nommé 'message:' au lieu d'un tableau []
        new IsTrue(message: 'Vous devez accepter les conditions.'),
    ],
])
->add('plainPassword', PasswordType::class, [
    'mapped' => false,
    'attr' => ['autocomplete' => 'new-password'],
    // ...
        ])
    ;
    }
// ...

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
