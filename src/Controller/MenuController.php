<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;



class MenuController extends AbstractController
{

    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('content/header/menu.html.twig', [
            'categories' =>$categoryRepository->findAll(),
        ]);
    }
}
