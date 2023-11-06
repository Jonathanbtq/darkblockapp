<?php

namespace App\Controller;

use App\Entity\Membre;
use App\Service\Tools;
use GuzzleHttp\Client;
use App\Entity\ImageMembre;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Repository\MembreRepository;
use App\Controller\CandidatureController;
use App\Repository\CandidatureRepository;
use App\Repository\ImageMembreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CandidatureRepository $candidatureRepo, UserRepository $userRepo, Request $request, ImageMembreRepository $imgMembreRepo, #[Autowire('%membre_portfolio_dir%')] string $photoDir, MembreRepository $membreRepo): Response
    {
        $candidatures = $candidatureRepo->findAll();
        $utilisateur = $userRepo->findAll();

        $form = $this->createFormBuilder()
        ->add('pseudo', EntityType::class, [
            'class' => Membre::class,
            'choice_label' => 'pseudo'
        ])
        ->add('img', FileType::class)
        ->add('save', SubmitType::class, ['label' => 'Ajouter Image'])
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $membre = $data['pseudo'];
            $img = $data['img'];

            // Le reste de votre logique pour traiter les données du formulaire
            $tools = new Tools();
            $imagePortfolio = new ImageMembre();
            $filename = $tools->createimgfolder($photoDir, $img, $membre);
            $imagePortfolio->setMembre($membre);
            $imagePortfolio->setName($filename);

            $imgMembreRepo->save($imagePortfolio, true);
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/admin.html.twig', [
            'candidatures' => $candidatures,
            'users' => $utilisateur,
            'form' => $form->createView()
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
        $client = new Client();
        $pseudo = $candid->getPseudoInGame();
        $response = $client->get("https://api.mojang.com/users/profiles/minecraft/{$pseudo}");

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            $uuid = $data['id'];
            $membre->setUuid($uuid);
        } else {
            $membre->setUuid('null');
        }
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

    #[Route('/delmember/{idmember}', name: 'delmember')]
    public function deleteMember($idmember, MembreRepository $memberRepo)
    {
        $member = $memberRepo->findObeBy(['id' => $idmember]);

        $memberRepo->remove($member, true);
        return $this->redirectToRoute('admin_index');
    }
}
