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
            foreach($dataExist as $dataE){
                if($dataE->getVote()->getId() == $voteCount->getVote()->getId()){
                    // Si l'ip du vote existant n'est pas le meme que l'ip du nouveau vote
                    if($dataE->getDataUser() != $voteCount->getDataUser()){
                        return true;
                    }else{
                        if ($dataE->getReponse() == $voteCount->getReponse()) {
                            return false; // L'utilisateur a déjà voté pour cette réponse
                        } else {
                            // Supprimez l'ancien vote et permettez à l'utilisateur de revoter
                            $voteCountRepo->remove($dataE, true);
                            return true;
                        }
                    }
                }
            }
        }else{
            return true;
        }   
    }
}