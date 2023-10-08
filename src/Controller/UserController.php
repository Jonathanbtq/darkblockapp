<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Form\UserModificationFormType;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/account_modification/{id}', name: 'account_modif')]
    public function modifUser($id, UserRepository $userRepo, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {  
        $user = $userRepo->findOneBy(['id' => $id]);
        if(!$user){
            return $this->redirectToRoute('login');
        }
        $form = $this->createForm(UserModificationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $userRepo->save($user, true);
            return $this->redirectToRoute('account');
        }
        return $this->render('user/accountmodif.html.twig', [
            'user' => $user,
            "form" => $form->createView(),
        ]);
    }
}
