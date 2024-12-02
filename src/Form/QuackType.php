<?php

namespace App\Form;

use App\Entity\Quack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class QuackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content')
	    ->add('picture', FileType::class,[
	    'label' => false,
	    'required' => false,
	    'mapped' => false,
	    'constraints' => [
		    new Image([
			    'mimeTypes' => [
				    'image/jpeg',
				    'image/png',
				    'image/gif',
			    ],
			    'mimeTypesMessage' => "Veuillez télécharger une image valide",
			    "maxSize" => '4M',
			    'maxSizeMessage' => "Votre image fait {{size}} {{suffix}}, La limite est de {{ limit }} {{suffix}}"
		    ]),
	    ]
    ])
    
    
    ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quack::class,
        ]);
    }
}
