<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class ErrorController extends AbstractController
{
    
    public function show(Request $request): Response
    {
        $comeBack = $request->headers->get('referer');
       
        return $this->render('error/index.html.twig', [
            
        ]);
    }
}
