<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class ErrorController extends AbstractController
{
    

    public function show(Request $request)
    {
        $comeBack = $request->headers->get('referer');  

        var_dump($comeBack);  
        
        return $this->render('error/index.html.twig', [
            
        ]);
    }
}
