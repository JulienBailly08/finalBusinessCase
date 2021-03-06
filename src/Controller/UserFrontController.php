<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Form\ChangePasswordType;
use App\Form\UserTypeFront;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
       
        $orders = $orderRepository->findByOrdersPaid($this->getUser());
        return $this->render('user_front/orders.html.twig', [
            'orders'=> $orders
        ]);
    }

    #[Route('/user/order/{id}', name: 'user_order_details')]
    public function showOrderDetail($id, OrderRepository $orderRepository): Response
    {
       
        $order = $orderRepository->findOneById($id);
        return $this->render('user_front/orderShow.html.twig', [
            'order'=> $order
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

    #[Route('/userAdress/edit', name: 'user_front_adress_edit')]
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
    
    #[Route('/userpassword/edit', name: 'user_front_password_edit')]
    public function passwordEdition(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm( ChangePasswordType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()):
            $newPassword = $form->get('new_password')->getData();
            $password = $encoder->encodePassword($user, $newPassword);
             
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Mot de passe mis ?? jour');

            return $this->redirectToRoute('user');

        endif;

        
        return $this->renderForm('user_front/password.html.twig', [
           'form'=>$form,
        ]);
    }

}
