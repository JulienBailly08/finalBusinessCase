<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UniversController extends AbstractController
{
    #[Route('/univers/{id}', name: 'univers')]
    public function index($id, ProductRepository $productRepository): Response
    {
        return $this->render('univers/index.html.twig', [
            'products' => $productRepository->findBy(['category'=>$id]),
        ]);
    }
}