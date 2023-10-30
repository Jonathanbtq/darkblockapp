<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Entity\VoteCount;
use App\Form\VoteFormType;
use App\Repository\ImageMembreRepository;
use App\Repository\MembreRepository;
use App\Repository\VoteCountRepository;
use App\Repository\VoteRepository;
use App\Service\Tools;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/vote', name: 'vote')]
    public function vote(Request $request, MembreRepository $membreRepo, VoteRepository $voteRepo): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteFormType::class, $vote);
        $form->handleRequest($request);
        
        if($this->getUser()){
            $user = $this->getUser();
        }
        if ($form->isSubmitted() && $form->isValid()) {
            if($user){
                $vote->setUser($user);
            }
            $vote->setDateCreated(new \DateTime());
            $vote->setNbVote(0);

            $voteRepo->save($vote, true);
            return $this->redirectToRoute('vote');
        }

        $votes = $voteRepo->findAll();

        return $this->render('main/vote.html.twig', [
            'form' => $form,
            'votes' => $votes
        ]);
    }

    /**
     * Répondre oui à un vote
     */
    #[Route('/voteoui/{idvote}', name: 'voteoui')]
    public function voteOui($idvote, VoteCountRepository $voteCountRepo, VoteRepository $voteRepo)
    {
        $vote = $voteRepo->findOneBy(['id' => $idvote]);
        $this->responseVote($voteCountRepo, 'oui', $vote);
        return $this->redirectToRoute('vote');
    }

    /**
     * Répondre non à un vote
     */
    #[Route('votenon/{idvote}', name:'votenon')]
    public function voteNon($idvote, VoteCountRepository $voteCountRepo, VoteRepository $voteRepo)
    {
        $vote = $voteRepo->findOneBy(['id' => $idvote]);
        $this->responseVote($voteCountRepo, 'non', $vote);
        return $this->redirectToRoute('vote');
    }

    /**
     * Permet de créer une réponse au vote
     */
    public function responseVote($voteCountRepo, $response, $idvote){
        $tools = new Tools();

        $addIp = $_SERVER['REMOTE_ADDR'];
        if($tools->searchUser($addIp, $voteCountRepo)){
            $voteCount = new VoteCount();
            $voteCount->setReponse($response);
            $voteCount->setDataUser($addIp);
            $voteCount->setVote($idvote);

            $voteCountRepo->save($voteCount, true);
            return $voteCount;
        }else{
            return false;
        }
    }
}
