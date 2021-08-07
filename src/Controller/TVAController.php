<?php

namespace App\Controller;

use App\Entity\TVA;
use App\Form\TVAType;
use App\Repository\TVARepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/tva')]
class TVAController extends AbstractController
{
    #[Route('/', name: 'tva_index', methods: ['GET'])]
    public function index(TVARepository $tVARepository): Response
    {
        return $this->render('tva/index.html.twig', [
            't_v_as' => $tVARepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'tva_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $tVA = new TVA();
        $form = $this->createForm(TVAType::class, $tVA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tVA);
            $entityManager->flush();

            return $this->redirectToRoute('tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tva/new.html.twig', [
            't_v_a' => $tVA,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'tva_show', methods: ['GET'])]
    public function show(TVA $tVA): Response
    {
        return $this->render('tva/show.html.twig', [
            't_v_a' => $tVA,
        ]);
    }

    #[Route('/{id}/edit', name: 'tva_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TVA $tVA): Response
    {
        $form = $this->createForm(TVAType::class, $tVA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tva_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tva/edit.html.twig', [
            't_v_a' => $tVA,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'tva_delete', methods: ['POST'])]
    public function delete(Request $request, TVA $tVA): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tVA->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tVA);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tva_index', [], Response::HTTP_SEE_OTHER);
    }
}
