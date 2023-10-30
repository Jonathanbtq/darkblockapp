<?php

namespace App\Service;

use App\Repository\VoteCountRepository;

class Tools{

    public function createimgfolder($photoDir, $img, $membre){
        $cheminDossier = $photoDir . '/' . $membre->getId();

        $filename = bin2hex(random_bytes(6)) . '.' . $img->guessExtension();
        // Vérification de l'éxistance d'un fichier nommé à l'id de l'user
        if(!file_exists($cheminDossier)){
            $photoDir = $cheminDossier;
            mkdir($photoDir, 0777, true);
            $img->move($cheminDossier, $filename);
        }else{
            $img->move($cheminDossier, $filename);
        }
        return $filename;
    }

    /**
     * Vérifier si l'utiliateur a deja voter
     *
     * @param [type] $data Ip User
     * @param [Repo] $voteCountRepo Repo voteCount
     * @param [data] $voteCount Nouveau vote
     * @return void
     */
    public function searchUser($data, VoteCountRepository $voteCountRepo, $voteCount){
        $dataExist = $voteCountRepo->findBy(['data_user' => $data]);
        
        if($dataExist){
            $foundMatchingVote = false;

            foreach($dataExist as $dataE){
                if($dataE->getVote()->getId() == $voteCount->getVote()->getId()){
                    // Si l'ip du vote existant n'est pas le meme que l'ip du nouveau vote
                    if($dataE->getDataUser() != $voteCount->getDataUser()){
                        return true;
                    }else{
                        $foundMatchingVote = true;
                    }
                }
            }
            // Après avoir parcouru tous les votes correspondants, décidez si le vote est autorisé
            if ($foundMatchingVote) {
                return false; // Un vote correspondant a été trouvé, mais l'IP est la même
            } else {
                return true; // Aucun vote correspondant avec l'IP différente
            }
        }else{
            return true;
        }   
    }
}