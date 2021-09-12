<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact', methods:['GET', 'POST'])]
    public function index(Request $request, MailerInterface $mailer)
    {
        $comeBack = $request->headers->get('referer');
        
        if (!empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['email']) && !empty($_POST['message'])):
           
            $message = (new Email())
            ->from($_POST['email'])
            ->to('julienbailly08@gmail.com')
            ->subject('Demande en provenance du site')
            ->text($_POST['firstname'].' '.$_POST['lastname'].' depuis '.$_POST['email'].' souhaite transmettre ce message : '.$_POST['message'], 'text/plain');
            $mailer->send($message);
            
            $this->addFlash('mailSuccess', 'Votre message est transmis');
            return new RedirectResponse($comeBack);    
               
        else:   
            $this->addFlash('mailFail', 'Votre message n\'a pu être transmis');
            return new RedirectResponse($comeBack);
        
        endif;
       



        
        
            
    }
}
