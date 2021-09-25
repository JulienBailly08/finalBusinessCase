<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderBackType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/back/order')]
class OrderBackController extends AbstractController
{
    #[Route('/', name: 'order_back_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order_back/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'order_back_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderBackType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order_back/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'order_back_show', methods: ['GET'])]
    public function show(Order $order): Response
    {
        return $this->render('order_back/show.html.twig', [
            'order' => $order,
        ]);
    }

    #[Route('/{id}/edit', name: 'order_back_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrderBackType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order_back/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'order_back_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
