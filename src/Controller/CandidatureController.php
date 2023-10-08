<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Image;
use App\Entity\Url;
use App\Form\CandidatureFormType;
use App\Repository\CandidatureRepository;
use App\Repository\ImageRepository;
use App\Repository\UrlRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Doctrine\ORM\EntityManagerInterface;

class CandidatureController extends AbstractController
{
    #[Route('/candidature', name: 'candidature')]
    public function index(Request $request, CandidatureRepository $candidatureRepo, UrlRepository $urlRepo, ImageRepository $imageRepo, #[Autowire('%candidature_photo_dir%')] string $photoDir): Response
    {
        $candidature = new Candidature();
        $form = $this->createForm(CandidatureFormType::class, $candidature);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $candidature->setUser($this->getUser());
            $candidature->setDateCandidature(new \DateTime());
            $candidatureRepo->save($candidature, true);

            if($urltext = $form['url']->getData()){
                $url = new Url();
                $url->setTextUrl($urltext);
                $url->setCandidature($candidature);
                $urlRepo->save($url, true);
            }

            if($multipleImg = $form['productImgs']->getData()){
                $filenames = $this->createFolderImgs($photoDir, $multipleImg, $candidature);
                foreach($filenames as $filename){
                    $candidImg = new Image();
                    $candidImg->setName($filename);
                    $candidImg->setCandidature($candidature);
                    $imageRepo->save($candidImg, true);
                }
            }

            return $this->redirectToRoute('main');
        }
        return $this->render('candidature/index.html.twig', [
            'controller_name' => 'CandidatureController',
            'form' => $form
        ]);
    }

    public function createFolderImgs($photoDir, $img, $newProduit){
        $cheminDossierProduit = $photoDir . '/' . $newProduit->getId();
        $filenames = [];

        foreach($img as $imgs){
            $filename = bin2hex(random_bytes(6)) . '.' . $imgs->guessExtension();
            if(!file_exists($cheminDossierProduit)){
                $photoDir = $cheminDossierProduit;
                mkdir($photoDir, 0777, true);
                $imgs->move($cheminDossierProduit, $filename);
            }else{
                $imgs->move($cheminDossierProduit, $filename);
            }
            $filenames[] = $filename;
        }
        return $filenames;
    }

    #[Route('/candidature/{id}', name: 'show_candidature')]
    public function showcandid($id, CandidatureRepository $candidatureRepo, UrlRepository $urlRepo, ImageRepository $imageRepo, #[Autowire('%candidature_photo_dir%')] string $photoDir): Response
    {
        $candid = $candidatureRepo->findOneBy(['id' => $id]);
        return $this->render('candidature/showcandid.html.twig', [
            'candidature' => $candid
        ]);
    }
}
