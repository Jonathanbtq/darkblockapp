<?php

namespace App\Controller;

use App\Entity\Url;
use App\Entity\Image;
use App\Entity\Candidature;
use App\Form\CandidatureFormType;
use App\Repository\UrlRepository;
use Symfony\Component\Mime\Email;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CandidatureRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CandidatureController extends AbstractController
{
    #[Route('/candidature', name: 'candidature')]
    public function index(Request $request, CandidatureRepository $candidatureRepo, UrlRepository $urlRepo, ImageRepository $imageRepo, MailerInterface $mailer, #[Autowire('%candidature_photo_dir%')] string $photoDir): Response
    {
        $user = $this->getUser();
        $addIp = $_SERVER['REMOTE_ADDR'];

        $candidature = new Candidature();
        $form = $this->createForm(CandidatureFormType::class, $candidature);
        $form->handleRequest($request);

        $message = '';
        $candidExist = $candidatureRepo->findBy(['user' => $this->getUser()]);
        if(count($candidExist) > 5 ){
            return $this->redirectToRoute('error_candid');
        }else{
            if($form->isSubmitted() && $form->isValid()){
                $candidature->setUser($this->getUser());
                $candidature->setDateCandidature(new \DateTime());
                $candidature->setStatus('En vérification');
                $candidature->setAdresseIp($addIp);
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
        }

        $email = (new Email())
            ->from('contact@LordBlock.com')
            ->subject('Nouvelle Candidature')
            ->to('botquin.jonathan@yahoo.fr')
            ->subject('Sujet de l\'e-mail')
            ->text('un utilisateur a envoyé une candidature pour rejoindre la guilde LordBlock !')
            ->html('<p>Contenu HTML de l\'e-mail</p>');
            // ->htmlTemplate('_partials/templateemail/mailcandid.html.twig')

            // ->context([
            //     'contact' => $user
            // ]);

        $mailer->send($email);
        
        return $this->render('candidature/index.html.twig', [
            'controller_name' => 'CandidatureController',
            'form' => $form,
            'message' => $message
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

    #[Route('/toomuchcandid', name: 'error_candid')]
    public function errorcandid(CandidatureRepository $candidatureRepo, UrlRepository $urlRepo, ImageRepository $imageRepo, #[Autowire('%candidature_photo_dir%')] string $photoDir): Response
    {
        $message = 'Vous avez fait plus de 5 candidatures, veuillez contacter l\'admin du site';
        return $this->render('candidature/errorcandid.html.twig', [
            'message' => $message
        ]);
    }
}
