<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProductRepository $productRepository): Response
    {
        $phares = $productRepository->likedProducts();

        foreach ($phares as $value) {
            $arrayIds[] = $value->getId();
        }
        shuffle($arrayIds);

        $arrayIdslength = count($arrayIds);
        
        if ($arrayIdslength < 9) {
            for ($i = 0; $i < $arrayIdslength; $i++) {
                $endArray[] = $arrayIds[$i];                
            }
        } else {
            for ($i = 0; $i < 9; $i++) {
                $endArray[] = $arrayIds[$i];
            }
        }

        return $this->render('home/index.html.twig', [
            'phares' => $phares,
            'shuffleArray' => $endArray
        ]);
    }
}
