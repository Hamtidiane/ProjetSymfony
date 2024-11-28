<?php

namespace App\Controller;

use App\Entity\Ducks;
use App\Form\DucksType;
use App\Repository\DucksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ducks')]
final class DucksController extends AbstractController
{
    #[Route(name: 'app_ducks_index', methods: ['GET'])]
    public function index(DucksRepository $ducksRepository): Response
    {
        return $this->render('ducks/index.html.twig', [
            'ducks' => $ducksRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ducks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $duck = new Ducks();
        $form = $this->createForm(DucksType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($duck);
            $entityManager->flush();

            return $this->redirectToRoute('app_ducks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ducks/new.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ducks_show', methods: ['GET'])]
    public function show(Ducks $duck): Response
    {
        return $this->render('ducks/show.html.twig', [
            'duck' => $duck,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ducks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ducks $duck, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DucksType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ducks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ducks/edit.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ducks_delete', methods: ['POST'])]
    public function delete(Request $request, Ducks $duck, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($duck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ducks_index', [], Response::HTTP_SEE_OTHER);
    }
}
