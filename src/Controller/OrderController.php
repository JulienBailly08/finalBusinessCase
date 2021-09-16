<?php

namespace App\Controller;


use App\Form\OrderType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'order')]
    public function index(SessionInterface $session, ProductRepository $productRepository, Request $request): Response
    {
        $basket = $session->get('basket', []);
       
        $basketFull=[];

        foreach ($basket as $id => $quantity) {
            $basketFull[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }
        $totalHT = 0;
        $totalTTC = 0;

        foreach ($basketFull as $item) {
            $totalItem = $item['product']->getPrice()*$item['quantity'];
            $totalHT+= $totalItem;
        }
        foreach ($basketFull as $item) {
            
            $totalItem = $item['product']->getPrice()*$item['product']->getTvaRate()->getRate()*$item['quantity'];
            $totalTTC+= $totalItem;
        }      
        
        
        $form = $this->createForm( OrderType::class, null,[
            'user'=>$this->getUser(),
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()):
            dd($form->getData());
        endif;

        return $this->render('order/index.html.twig', [
            'form'=> $form->createView(),
            'items' => $basketFull,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC
            
        ]);
    }
}
