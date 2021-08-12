<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductFrontController extends AbstractController
{
    #[Route('/product/{id}', name: 'product_front')]
    public function index($id, ProductRepository $productRepository): Response
    {
            $product =$productRepository->findOneBy(['id'=> $id]);

             return $this->render('product_front/index.html.twig', [
            'product' => $productRepository->findOneBy(['id'=> $id]),
            'similars' => $productRepository->similarProducts($product),
        ]);
    }
}
