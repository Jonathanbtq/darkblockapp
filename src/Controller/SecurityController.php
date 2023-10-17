<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout()
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return $this->redirectToRoute('login');
    }

    #[Route(path: '/oubli-pass', name: "forgotten_password")]
    public function forgottentPassword(Request $request, UserRepository $userRepo, TokenGeneratorInterface $tokenGeneratorInterface): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $user = $userRepo->findOneByEmail($email);

            if($user){
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);

                $userRepo->save($user, true);

                //Permet de générer une url compléte
                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // On créer les données du mail
                $context = compact('url', 'user');
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/oubli-pass/{token}', name:'reset_pass')]
    public function resetPass(): Response
    {

    }
}
