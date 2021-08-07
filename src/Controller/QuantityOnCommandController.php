<?php

namespace App\Controller;

use App\Entity\QuantityOnCommand;
use App\Form\QuantityOnCommandType;
use App\Repository\QuantityOnCommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/quantity/on/command')]
class QuantityOnCommandController extends AbstractController
{
    #[Route('/', name: 'quantity_on_command_index', methods: ['GET'])]
    public function index(QuantityOnCommandRepository $quantityOnCommandRepository): Response
    {
        return $this->render('quantity_on_command/index.html.twig', [
            'quantity_on_commands' => $quantityOnCommandRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'quantity_on_command_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $quantityOnCommand = new QuantityOnCommand();
        $form = $this->createForm(QuantityOnCommandType::class, $quantityOnCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quantityOnCommand);
            $entityManager->flush();

            return $this->redirectToRoute('quantity_on_command_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantity_on_command/new.html.twig', [
            'quantity_on_command' => $quantityOnCommand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'quantity_on_command_show', methods: ['GET'])]
    public function show(QuantityOnCommand $quantityOnCommand): Response
    {
        return $this->render('quantity_on_command/show.html.twig', [
            'quantity_on_command' => $quantityOnCommand,
        ]);
    }

    #[Route('/{id}/edit', name: 'quantity_on_command_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, QuantityOnCommand $quantityOnCommand): Response
    {
        $form = $this->createForm(QuantityOnCommandType::class, $quantityOnCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quantity_on_command_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('quantity_on_command/edit.html.twig', [
            'quantity_on_command' => $quantityOnCommand,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'quantity_on_command_delete', methods: ['POST'])]
    public function delete(Request $request, QuantityOnCommand $quantityOnCommand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quantityOnCommand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quantityOnCommand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quantity_on_command_index', [], Response::HTTP_SEE_OTHER);
    }
}
