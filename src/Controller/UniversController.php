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
        $arrayIds=[];
        
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
        
        return $this->render('univers/index.html.twig', [
            'products' => $products,
            'category' => $category,
            'categorySons'=>$categorySons,
            'arrayIds'=>$arrayIds
        ]);
    }
}
