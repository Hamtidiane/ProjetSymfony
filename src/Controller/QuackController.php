<?php
	
	namespace App\Controller;
	
	use App\Entity\Ducks;
	use App\Entity\Quack;
	use App\Entity\Reply;
	use App\Form\QuackType;
	use App\Form\ReplyType;
	use App\Repository\QuackRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	
	#[Route('/quacks')]
	final class QuackController extends AbstractController
	{
		#[Route(name: 'app_quack_index', methods: ['GET'])]
		public function index(QuackRepository $quackRepository): Response
		{
			$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
			
			return $this->render('quack/index.html.twig', [
				'quacks' => $quackRepository->findAll(),
			]);
		}
		
		#[Route('/new', name: 'app_quack_new', methods: ['GET', 'POST'])]
		public function new(Request $request, EntityManagerInterface $entityManager): Response
		{
			$user = $this->getUser();
			
			if (!$user || !$user instanceof Ducks) {
				throw $this->createAccessDeniedException('Vous devez être connecté pour publier un quack.');
			}
			
			$quack = new Quack();
			$quack->setAuthor($user);
			$form = $this->createForm(QuackType::class, $quack);
			
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				$pictureFile = $form->get('picture')->getData();
				
				if ($pictureFile) {
					$allowedExtensions = ['jpg', 'jpeg', 'png'];
					$extension = $pictureFile->guessExtension();
					
					if (!in_array($extension, $allowedExtensions)) {
						throw new \Exception('Format de fichier non autorisé. Veuillez télécharger une image JPG, JPEG ou PNG.');
					}
					
					$newFilename = uniqid() . '.' . $extension;
					$pictureFile->move(
						$this->getParameter('upload_directory'),
						$newFilename
					);
					$quack->setPicture($newFilename);
				}
				
				$entityManager->persist($quack);
				$entityManager->flush();
				
				return $this->redirectToRoute('app_quack_index');
			}
			
			return $this->render('quack/new.html.twig', [
				'form' => $form->createView(),
			]);
		}
		
		#[Route('/{id}', name: 'app_quack_show', methods: ['GET', 'POST'])]
		public function show(int $id, Request $request, EntityManagerInterface $entityManager): Response
		{
			// Récupérer l'entité Quack à partir de l'ID passé dans l'URL
			$quack = $entityManager->getRepository(Quack::class)->find($id);
			
			// Vérifier si l'entité Quack existe
			if (!$quack) {
				// Si l'entité n'existe pas, générer une erreur 404
				throw $this->createNotFoundException('Quack not found');
			}
			
			// Créer une nouvelle réponse
			$reply = new Reply();
			$reply->setQuack($quack);
			
			// Vérifier que l'utilisateur est connecté et affecter l'auteur de la réponse
			if ($this->getUser()) {
				// L'utilisateur connecté est l'auteur de la réponse
				$reply->setAuthor($this->getUser());
			} else {
				// Gérer le cas où l'utilisateur n'est pas connecté (si nécessaire)
				// Vous pouvez, par exemple, ne pas permettre de réponse si l'utilisateur n'est pas authentifié
				$this->addFlash('error', 'Vous devez être connecté pour répondre.');
				return $this->redirectToRoute('app_quack_show', ['id' => $id]);
			}
			
			// Créer et traiter le formulaire de réponse
			$form = $this->createForm(ReplyType::class, $reply);
			$form->handleRequest($request);
			
			// Si le formulaire est soumis et valide, persister la réponse et rediriger
			if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->persist($reply);
				$entityManager->flush();
				
				// Rediriger pour actualiser les réponses affichées
				return $this->redirectToRoute('app_quack_show', ['id' => $id]);
			}
			
			// Récupérer les réponses associées au quack
			$replies = $quack->getReplies();  // Assurez-vous que la méthode `getReplies()` existe et retourne les bonnes données
			
			return $this->render('quack/show.html.twig', [
				'quack' => $quack,
				'form' => $form->createView(),
				'replies' => $replies,  // Passez les réponses à la vue
			]);
		}
		
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
		
		#[Route('/{id}', name: 'app_quack_delete', methods: ['POST'])]
		public function delete(Request $request, Quack $quack, EntityManagerInterface $entityManager): Response
		{
			$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
			
			if ($this->isCsrfTokenValid('delete' . $quack->getId(), $request->get('_token'))) {
				$entityManager->remove($quack);
				$entityManager->flush();
			}
			
			return $this->redirectToRoute('app_quack_index');
		}
	}
