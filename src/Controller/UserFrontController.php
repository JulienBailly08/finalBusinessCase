<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Form\UserTypeFront;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserFrontController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        $user = $this->getUser();
        $adresses = $user->getAdresses();
        return $this->render('user_front/index.html.twig', [
            'user' => $user,
            'adresses' => $adresses
        ]);
    }
    #[Route('/user/orders', name: 'user_orders')]
    public function showOrders(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        //$orders = $orderRepository->findOrdersPaid($user);
        $orders = $this->getUser()->getOrders();
        return $this->render('user_front/orders.html.twig', [
            'user' => $user,
            'orders'=> $orders
        ]);
    }

    #[Route('/user/edit', name: 'user_front_edit')]
    public function userEdition(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserTypeFront::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('user_front/userEdit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/userAdress/edit', name: 'user_front-adress_edit')]
    public function adressEdition(Request $request, SessionInterface $session): Response
    {
        $user = $this->getUser();
        $adresses = new Adress;
        $adresses = $user->getAdresses();

        foreach ($adresses as $key => $adress) {
            $form = $this->createForm(AdressType::class, $adress);
        }

        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if($session->get('basket')):
                return $this->redirectToRoute('order');
            endif;

            return $this->redirectToRoute('user', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('user_front/adressEdit.html.twig', [
            'user' => $user,
            'adress' =>$adress,
            'form' => $form
        ]);
    }
}
