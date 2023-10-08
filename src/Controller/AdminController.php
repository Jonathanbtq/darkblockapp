<?php

namespace App\Controller;

use App\Entity\Membre;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\CandidatureRepository;
use App\Repository\MembreRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/mail', name: 'mail')]
    public function mail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('contact@LordBlock.com')
            ->subject('Nouvelle Candidature')
            ->to('botquin.jonathan@yahoo.fr')
            ->text('un utilisateur a envoyé une candidature pour rejoindre la guilde LordBlock !')
            ->html('<p>Contenu HTML de l\'e-mail</p>');
            // ->htmlTemplate('_partials/templateemail/mailcandid.html.twig')

            // ->context([
            //     'contact' => $user
            // ]);

        $mailer->send($email);

        return $this->redirectToRoute('admin_index');
    }

    #[Route('/acceptcandid/{idcandid}', name: 'accept_candid')]
    public function acceptcandid($idcandid, CandidatureRepository $candidatureRepo, MembreRepository $membreRepo)
    {
        $candid = $candidatureRepo->findOneBy(['id' => $idcandid]);
        $candid->setStatus('Accepter');
        $candidatureRepo->save($candid, true);

        $membre = new Membre();
        $membre->setPseudo($candid->getPseudoInGame());
        $membre->setDateAccepted(new \DateTime());
        $membreRepo->save($membre, true);
        
        return $this->redirectToRoute('admin_index');
    }

    #[Route('/refusedcandid/{idcandid}', name: 'refused_candid')]
    public function refusedcandid($idcandid, CandidatureRepository $candidatureRepo)
    {
        $candid = $candidatureRepo->findOneBy(['id' => $idcandid]);
        $candid->setStatus('Refused');
        $candidatureRepo->save($candid, true);
        return $this->redirectToRoute('admin_index');
    }
}
