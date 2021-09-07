<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    #[Route('/basket', name: 'basket')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
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
              
        return $this->render('basket/index.html.twig', [
            'items' => $basketFull,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC
        ]);
    }

    #[Route('/basket/add/{id}', name: 'basket_add', methods:['GET', 'POST'])]
    public function add($id, SessionInterface $session)
    {
        $basket = $session->get('basket', []);
         

        if (!empty($basket[$id])) {
            $basket[$id]+=$_POST['quantityToAdd'];
        } else {
            $basket[$id] = $_POST['quantityToAdd'];
        }

        $session->set('basket', $basket);
        $this->addFlash('success', 'Article ajouté à votre');

        return $this->redirectToRoute('product_front',['id'=> $id]);

    
    }
    #[Route('/basket/remove/{id}', name: 'basket_remove')]
    public function remove($id, SessionInterface $session){

        $basket = $session->get('basket', []);
        if (!empty($basket[$id])){
            unset($basket[$id]);
        }
        $session->set('basket', $basket);
        return $this->redirectToRoute("basket");

    }
}
