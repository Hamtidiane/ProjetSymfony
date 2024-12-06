<?php
	
	namespace App\Form;
	
	use App\Entity\Ducks;
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
	use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\TextType;
	use Symfony\Component\Form\Extension\Core\Type\EmailType; // Importer EmailType
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints\IsTrue;
	use Symfony\Component\Validator\Constraints\Length;
	use Symfony\Component\Validator\Constraints\NotBlank;
	
	class RegistrationFormType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options): void
		{
			$builder
				->add('firstname', TextType::class, [
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter your first name'
						]),
					]
				])
				->add('lastname', TextType::class, [
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter your last name'
						])
					]
				])
				->add('duckname', TextType::class, [
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter your duck name'
						])
					]
				])
				->add('email', EmailType::class, [ // Utiliser EmailType ici
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter a valid email address'
						]),
					]
				])
				->add('agreeTerms', CheckboxType::class, [
					'mapped' => false,
					'constraints' => [
						new IsTrue([
							'message' => 'You should agree to our terms.',
						]),
					],
				])
				->add('plainPassword', PasswordType::class, [
					'mapped' => false,
					'attr' => ['autocomplete' => 'new-password'],
					'constraints' => [
						new NotBlank([
							'message' => 'Please enter a password',
						]),
						new Length([
							'min' => 6,
							'minMessage' => 'Your password should be at least {{ limit }} characters',
							'max' => 4096,
						]),
					],
				])
				->add('role', ChoiceType::class, [
					'mapped' => false, // Indique que ce champ n'est pas directement lié à l'entité
					'choices' => [
						'User' => 'ROLE_USER',
						'Admin' => 'ROLE_ADMIN',
					],
					'label' => 'Select your role',
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
