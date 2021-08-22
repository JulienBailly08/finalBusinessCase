<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UniversController extends AbstractController
{
    #[Route('/univers/{id}', name: 'univers')]
    public function index($id, ProductRepository $productRepository,CategoryRepository $categoryRepository): Response
    {
        $products =$productRepository->findBy(['category'=>$id]);
        $category=$categoryRepository->findBy(['id'=>$id]);
        $categorySons=$categoryRepository->findBy(['parent'=>$id]);
        $arrayIds = [];
        $results=[]; // a finaliser !! localisation de la fn ? portée des variables ?
        

        function recursCat($category){   
        global $results;
        
        if($category->getParent()->getParent() != null):
        $results[]=['name'=>$category->getParent()->getName(),'id'=>$category->getParent()->getId(),'root'=>false,'current'=>false];
        recursCat($category->getParent());
        else :

        $results[]=['name'=>$category->getParent()->getName(),'id'=>$category->getParent()->getId(),'root'=>true,'current'=>false];  
        endif;

        return $results;
        }   
       
        // recuperation ID des produits des sous cat dans array pour effet de shuffle sur page univers vide
        if (empty($products)):
            foreach ($categorySons as $value) {
                $int=$value->getProducts();
                foreach ($int as $value) {
                   $arrayIds[]=$value->getId();
                }
            }
        shuffle($arrayIds);                
        endif;

        // creation tableau de données fil d'arianne et injection page active en dernière position
        $arianeArray=array_reverse(recursCat($category[0]));
        $arianeArray[]=['name'=>$category[0]->getName(),'id'=>$category[0]->getId(),'root'=>false,'current'=>true];
        
        return $this->render('univers/index.html.twig', [
            'products' => $products,
            'category' => $category,
            'categorySons'=>$categorySons,
            'arrayIds'=>$arrayIds,
            'arianeArray'=>$arianeArray
        ]);
    }
}
