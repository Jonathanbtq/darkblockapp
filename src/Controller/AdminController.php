<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CandidatureRepository $candidatureRepo, UserRepository $userRepo): Response
    {
        $candidatures = $candidatureRepo->findAll();
        $utilisateur = $userRepo->findAll();

        return $this->render('admin/admin.html.twig', [
            'candidatures' => $candidatures,
            'users' => $utilisateur
        ]);
    }
}
