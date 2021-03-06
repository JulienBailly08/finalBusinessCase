<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        
        $user = new User();
        $adresse = new Adress();
        $date = new DateTime();
                
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt($date);
            //ajouter des champs de validation pour le $adresse

            $adresse->setNumber($form->get('adress')->get('number')->getData());
            $adresse->setType($form->get('adress')->get('type')->getData());
            $adresse->setName($form->get('adress')->get('name')->getData());
            $adresse->setPostalCode($form->get('adress')->get('postalCode')->getData());
            $adresse->setCity($form->get('adress')->get('city')->getData());
            $adresse->setCountry($form->get('adress')->get('country')->getData());
            $adresse->setAdressName('Principale');
                      
            $entityManager->persist($user);
                     
            $entityManager->flush();

            $userId=$this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$user->getEmail()]);
            $adresse->setIdUser($userId);
            $entityManager->persist($adresse);
            $entityManager->flush();
   
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('julienbailly08@gmail.com', 'La Nimes Alerie contact'))
                    ->to($user->getEmail())
                    ->subject('Merci de confirmer votre adresse E-mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
