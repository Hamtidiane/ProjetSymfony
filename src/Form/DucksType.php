<?php

namespace App\Form;

use App\Entity\Ducks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class DucksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
	        ->add('firstname')
	        ->add('lastname')
	        ->add('duckname')
            ->add('email')
            ->add('password')
	        ->add('roles', ChoiceType::class, [
		        'choices' => [
			        'Admin' => 'ROLE_ADMIN',
			        'User' => 'ROLE_USER',
		        ],
		        'multiple' => true,  // Permet de sélectionner plusieurs rôles
		        'expanded' => true,  // Affiche les rôles sous forme de cases à cocher
	        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ducks::class,
        ]);
    }
}
