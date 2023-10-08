<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/account', name: 'account')]
    public function index(UserRepository $useRepo, CandidatureRepository $candidRepo): Response
    {  
        $user = $this->getUser();
        $candidatures = $candidRepo->findBy(['user' => $user]);
        if($user){

        }else{
            return $this->redirectToRoute('login');
        }
        return $this->render('user/account.html.twig', [
            'user' => $user,
            'candidatures' => $candidatures
        ]);
    }
}
