<?php

namespace App\Controller;

use App\Repository\ImageMembreRepository;
use App\Repository\MembreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(MembreRepository $membreRepo): Response
    {
        $membres = $membreRepo->findAll();
        $nbMember = count($membres);
        return $this->render('main/index.html.twig', [
            'membres' => $membres,
            'nbMember' => $nbMember
        ]);
    }

    #[Route('/portfolio', name: 'portfolio')]
    public function portfolio(MembreRepository $membreRepo, ImageMembreRepository $imageMembreRepo): Response
    {
        $membres = $membreRepo->findAll();
        $imgMembre = $imageMembreRepo->findAll();
        return $this->render('main/portfolio.html.twig', [
            'membres' => $membres,
            'imgmembre' => $imgMembre
        ]);
    }
}
