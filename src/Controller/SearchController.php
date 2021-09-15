<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(): Response
    {
       
        
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    
    public function searchBar()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('searchResults'))
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un mot-clé'
                ]
            ])
            ->add('recherche', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-2'
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/results", name="searchResults")
     * @param Request $request
     */
    public function handleSearch(Request $request, ProductRepository $repo)
    {
        $query = $request->request->get('form')['query'];
        if($query) {
            $products = $repo->findProductByName($query);
        }
        return $this->render('search/index.html.twig', [
            'products' => $products
        ]);
    }
}
