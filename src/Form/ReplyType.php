<?php
	// src/Form/ReplyType.php
	
	namespace App\Form;
	
	use App\Entity\Reply;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\TextareaType;
	use Symfony\Component\Form\Extension\Core\Type\SubmitType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	
	class ReplyType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
				// Champ pour le contenu de la réponse
				->add('content', TextareaType::class, [
					'label' => 'Réponse',
					'attr' => ['placeholder' => 'Votre réponse...'],
					'required' => true,
				])
				// Champ de soumission du formulaire
				->add('save', SubmitType::class, [
					'label' => 'Envoyer la réponse',
					'attr' => ['class' => 'fas fa-paper-plane'],
				]);
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([
				'data_class' => Reply::class, // Associe ce formulaire à l'entité Reply
			]);
		}
	}
	
	