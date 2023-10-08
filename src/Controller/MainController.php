<?php

namespace App\Controller;

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
        return $this->render('main/index.html.twig', [
            'membres' => $membres
        ]);
    }
}
