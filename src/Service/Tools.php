<?php

namespace App\Service;

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
}