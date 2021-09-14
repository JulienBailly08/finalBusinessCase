<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/back/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/cat/{id}', name: 'product_cat', methods: ['GET', 'POST'])]
    public function indexCat($id,ProductRepository $productRepository,Category $category): Response
    {

        $results=[]; // a finaliser !! localisation de la fn ? portée des variables ?
        

        function recursCat($category){   
        global $results;
        
        if($category->getParent()->getParent() != null):
        $results[]=['name'=>$category->getParent()->getName()];
        recursCat($category->getParent());
        else :

        $results[]=['name'=>$category->getParent()->getName()];  
        endif;

        return $results;
        }
        
        $arianeArray=array_reverse(recursCat($category));
        dump($arianeArray);
        
        return $this->render('product/indexCat.html.twig', [
            'products' => $productRepository->findBy(['category'=>$id]),
            'category' =>$category,
            'arianeArray'=>$arianeArray
        ]);
    }


    #[Route('/new/{id}', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger,Category $category): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image1 = $form->get('picture1')->getData();
            $image2 = $form->get('picture2')->getData();
            $image3 = $form->get('picture3')->getData();

            if ($image1) {
                $originalFileName = pathinfo($image1->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image1->guessExtension();

                try {
                    $image1->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $product->setPicture1($newFileName);
            }
            if ($image2) {
                $originalFileName = pathinfo($image2->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image2->guessExtension();

                try {
                    $image2->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $product->setPicture2($newFileName);
            }
            if ($image3) {
                $originalFileName = pathinfo($image3->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image3->guessExtension();

                try {
                    $image3->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $product->setPicture3($newFileName);
            }
            $product->setCategory($category);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image1 = $form->get('picture1')->getData();
            $image2 = $form->get('picture2')->getData();
            $image3 = $form->get('picture3')->getData();

            if ($image1) {
                $originalFileName = pathinfo($image1->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image1->guessExtension();

                try {
                    $image1->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $product->setPicture1($newFileName);
            }
            if ($image2) {
                $originalFileName = pathinfo($image2->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image2->guessExtension();

                try {
                    $image2->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $product->setPicture2($newFileName);
            }
            if ($image3) {
                $originalFileName = pathinfo($image3->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image3->guessExtension();

                try {
                    $image3->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $product->setPicture3($newFileName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
}
