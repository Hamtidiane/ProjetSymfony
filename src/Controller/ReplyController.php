<?php
	
	namespace App\Controller;
	
	use App\Entity\Ducks;
	use App\Entity\Reply;
	use App\Entity\Quack;
	use App\Form\ReplyType;
	use App\Repository\QuackRepository;
	use App\Repository\ReplyRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	class ReplyController extends AbstractController
	{  /**
	 * @Route("/quack/{id}/reply", name="app_quack_reply", methods={"GET", "POST"})
	 */
		public function reply(Quack $quack, Request $request, EntityManagerInterface $entityManager): Response
		{
			// Cast explicite de l'utilisateur connecté en Ducks
			$duck = $this->getUser();  // Symfony récupère un objet de type UserInterface
			if (!$duck instanceof Ducks) {
				throw $this->createAccessDeniedException('Utilisateur non trouvé ou non authentifié.');
			}
			
			// Créer la réponse
			$reply = new Reply();
			$reply->setQuack($quack);  // Lier la réponse au Quack
			$reply->setAuthor($duck);  // Lier l'entité Ducks à l'auteur de la réponse
			
			// Créer et traiter le formulaire de la réponse
			$form = $this->createForm(ReplyType::class, $reply);
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				// Sauvegarder la réponse dans la base de données
				$entityManager->persist($reply);
				$entityManager->flush();
				
				// Rediriger vers la page du Quack avec un message de succès
				return $this->redirectToRoute('app_quack_show', ['id' => $quack->getId()]);
			}
			
			// Retourner la vue avec le formulaire de réponse
			return $this->render('quack/reply.html.twig', [
				'quack' => $quack,
				'form' => $form->createView(),
			]);
		}
		
	
		/**
		 * @Route("/quack/{id}/replies", name="app_quack_replies", methods={"GET"})
		 */
		public function viewReplies(Quack $quack, ReplyRepository $replyRepository): Response
		{
			// Récupérer toutes les réponses associées au Quack
			$replies = $replyRepository->findBy(['quack' => $quack], ['createdAt' => 'DESC']);
			
			// Rendre la vue avec les réponses
			return $this->render('quack/show_replies.html.twig', [
				'quack' => $quack,
				'replies' => $replies,
			]);
		}
	}