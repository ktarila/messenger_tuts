<?php

namespace App\Controller;

use App\Entity\Shape;
use App\Form\ShapeType;
use App\Repository\ShapeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class ShapeController extends AbstractController
{
    #[Route(name: 'app_shape_index', methods: ['GET'])]
    public function index(ShapeRepository $shapeRepository): Response
    {
        return $this->render('shape/index.html.twig', [
            'shapes' => $shapeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_shape_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $shape = new Shape();
        $form = $this->createForm(ShapeType::class, $shape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($shape);
            $entityManager->flush();

            sleep(2);
            return $this->redirectToRoute('app_shape_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shape/new.html.twig', [
            'shape' => $shape,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shape_show', methods: ['GET'])]
    public function show(Shape $shape): Response
    {
        return $this->render('shape/show.html.twig', [
            'shape' => $shape,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_shape_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Shape $shape, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ShapeType::class, $shape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            sleep(2);

            return $this->redirectToRoute('app_shape_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('shape/edit.html.twig', [
            'shape' => $shape,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_shape_delete', methods: ['POST'])]
    public function delete(Request $request, Shape $shape, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $shape->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($shape);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_shape_index', [], Response::HTTP_SEE_OTHER);
    }
}
