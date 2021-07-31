<?php

namespace App\Controller;

use App\Entity\ImgBanners;
use App\Form\ImgBannersType;
use App\Repository\ImgBannersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/img/banners')]
class ImgBannersController extends AbstractController
{
    #[Route('/', name: 'img_banners_index', methods: ['GET'])]
    public function index(ImgBannersRepository $imgBannersRepository): Response
    {
        return $this->render('img_banners/index.html.twig', [
            'img_banners' => $imgBannersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'img_banners_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $imgBanner = new ImgBanners();
        $form = $this->createForm(ImgBannersType::class, $imgBanner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('url')->getData();

            if ($image) {
                $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $imgBanner->setUrl($newFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imgBanner);
            $entityManager->flush();

            return $this->redirectToRoute('img_banners_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('img_banners/new.html.twig', [
            'img_banner' => $imgBanner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'img_banners_show', methods: ['GET'])]
    public function show(ImgBanners $imgBanner): Response
    {
        return $this->render('img_banners/show.html.twig', [
            'img_banner' => $imgBanner,
        ]);
    }

    #[Route('/{id}/edit', name: 'img_banners_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImgBanners $imgBanner, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ImgBannersType::class, $imgBanner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('url')->getData();

            if ($image) {
                $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($this->getParameter('upload_directory'), $newFileName);
                } catch (FileException $e) {
                    var_dump($e);
                }
                $imgBanner->setUrl($newFileName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('img_banners_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('img_banners/edit.html.twig', [
            'img_banner' => $imgBanner,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'img_banners_delete', methods: ['POST'])]
    public function delete(Request $request, ImgBanners $imgBanner): Response
    {
        if ($this->isCsrfTokenValid('delete' . $imgBanner->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($imgBanner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('img_banners_index', [], Response::HTTP_SEE_OTHER);
    }
}
