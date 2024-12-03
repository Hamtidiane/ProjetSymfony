<?php
	
	namespace App\Controller;
	
	use App\Entity\Quack;
	use App\Form\QuackType;
	use App\Repository\QuackRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Attribute\Route;
	
	#[Route('/quacks')]
	final class QuackController extends AbstractController
	{
		// Protéger la route avec l'attribut is_granted
		#[Route('/new', name: 'app_quack_new', methods: ['GET', 'POST'])]
		public function new(Request $request, EntityManagerInterface $entityManager): Response
		{
			// Vérifier si l'utilisateur est authentifié
			$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
			
			$quack = new Quack();
			$form = $this->createForm(QuackType::class, $quack);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				// Récupérer le fichier d'image téléchargé
				$pictureFile = $form->get('picture')->getData();
				
				if ($pictureFile) {
					// Générer un nom unique pour le fichier
					$newFilename = uniqid() . '.' . $pictureFile->guessExtension();
					
					// Déplacer le fichier dans le répertoire public/uploads
					$pictureFile->move(
						$this->getParameter('upload_directory'), // Chemin du répertoire public/uploads
						$newFilename
					);
					
					// Enregistrer le chemin du fichier dans l'entité Quack
					$quack->setPicture($newFilename);
				}
				
				// Sauvegarder l'entité Quack en base de données
				$entityManager->persist($quack);
				$entityManager->flush();
				
				// Rediriger vers la page d'index après la création
				return $this->redirectToRoute('app_quack_index');
			}
			
			return $this->render('quack/new.html.twig', [
				'form' => $form->createView(),
			]);
		}
		
		// Afficher un Quack spécifique
		#[Route('/{id}', name: 'app_quack_show', methods: ['GET'])]
		public function show(Quack $quack): Response
		{
			return $this->render('quack/show.html.twig', [
				'quack' => $quack,
			]);
		}
		
		// Protéger la route de modification d'un Quack
		#[Route('/{id}/edit', name: 'app_quack_edit', methods: ['GET', 'POST'])]
		public function edit(Request $request, Quack $quack, EntityManagerInterface $entityManager): Response
		{
			$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
			
			$form = $this->createForm(QuackType::class, $quack);
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->flush();
				return $this->redirectToRoute('app_quack_index');
			}
			
			return $this->render('quack/edit.html.twig', [
				'quack' => $quack,
				'form' => $form->createView(),
			]);
		}
		
		// Protéger la route de suppression d'un Quack
		#[Route('/{id}', name: 'app_quack_delete', methods: ['POST'])]
		public function delete(Request $request, Quack $quack, EntityManagerInterface $entityManager): Response
		{
			$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
			
			if ($this->isCsrfTokenValid('delete'.$quack->getId(), $request->get('_token'))) {
				$entityManager->remove($quack);
				$entityManager->flush();
			}
			
			return $this->redirectToRoute('app_quack_index');
		}
		
		// Protéger l'index
		#[Route(name: 'app_quack_index', methods: ['GET'])]
		public function index(QuackRepository $quackRepository): Response
		{
			// Vérifier si l'utilisateur est authentifié
			$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
			
			return $this->render('quack/index.html.twig', [
				'quacks' => $quackRepository->findAll(),
			]);
		}
	}
