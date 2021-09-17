<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Entity\Status;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Repository\StatusRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    
    
    
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

            return $this->render('order/index.html.twig', [
            'form'=> $form->createView(),
            'items' => $basketFull,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC
            
        ]);
    }

    #[Route('/commande/recap', name: 'order_recap')]
    public function add(SessionInterface $session, ProductRepository $productRepository, Request $request, StatusRepository $statusRepo): Response
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
            $date = new DateTime();
            $shipment = $form->get('shipment')->getData();
            $payment = $form->get('payment')->getData();
            $deliveryAdress = $form->get('adresses')->getData();
            $user = $this->getUser();
            $deliveryContent = $user->getFirstName().' '.$user->getLastname().'<br>'
            .$deliveryAdress->getNumber().' '.$deliveryAdress->getType().' '.$deliveryAdress->getName().'<br>'
            .$deliveryAdress->getPostalCode().' '.$deliveryAdress->getCity().'<br'
            .$deliveryAdress->getCountry();
          
            $status = $statusRepo->findOneBy(['id'=>'6']);
                     
            //enregistrement commande

            $order = new Order();
            $order->setUser(($this->getUser()));
            $order->setCreatedAt($date);
            $order->setShipment($shipment->getName());
            $order->setShipmentPrice($shipment->getPrice());
            $order->setPaymentChoice($payment->getName());
            $order->setIsPaid(0);
            $order->setStatus($status);
            
            if($shipment->getName() == 'Click and Collect'):
                $order->setDelivery($user->getFirstName().' '.$user->getLastname().'<br>Lanimesalerie');
            else :
                $order->setDelivery($deliveryContent);   
            endif;
            
           

            $this->entityManager->persist($order);
            $adressFront = $order->getDelivery();
 
            
            //detail de la commande
 
            foreach ($basketFull as $item) :
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($item['product']->getTitle());
                $orderDetails->setQuantity($item['quantity']);
                $orderDetails->setTva($item['product']->getTvaRate()->getRate());
                $orderDetails->setPrice($item['product']->getPrice());
                $orderDetails->setTotal(($item['product']->getTvaRate()->getRate())*($item['product']->getPrice())*$item['quantity']);
                $this->entityManager->persist($orderDetails);  
            endforeach;

            //$this->entityManager->flush();  
 
        endif;

        return $this->render('order/add.html.twig', [          
            'items' => $basketFull,
            'totalHT' => $totalHT,
            'totalTTC' => $totalTTC,
            'adress'=> $adressFront,
            'shipping'=> $shipment,
                 
        ]);
    }

}
