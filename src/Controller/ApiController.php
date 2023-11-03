<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Repository\ImageRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    #[Route('/api/hello', name: 'app_api', methods: ['GET'])]
    public function index()
    {
        return new JsonResponse(['message' => 'Bonjour tout le monde !']);
    }

    #[Route('/api/candidatureget', name:'apiCandidget', methods: ['GET'])]
    public function getApiCandid(CandidatureRepository $candidRepo){
        $data = $candidRepo->findAll();

        // Formatez les données en tableau ou en structure appropriée
        $formattedData = [];

        foreach ($data as $item) {
            $formattedData[] = [
                'id' => $item->getId(),
                'text' => $item->getText(),
                'pseudo' => $item->getPseudoInGame(),
                'date' => $item->getDateCandidature(),
                'status' => $item->getStatus(),
                'description' => 'Détails candidature'
            ];
        }
        return new JsonResponse($formattedData);
    }

    #[Route('/api/candidatureby/{pseudo}', name:'apiCandidget', methods: ['GET'])]
    public function getApiCandidBy($pseudo, CandidatureRepository $candidRepo){
        $data = $candidRepo->findOneBy(['pseudo_in_game' => $pseudo]);

        $candid = [
            'id' => $data->getId(),
            'text' => $data->getText(),
            'pseudo' => $data->getPseudoInGame(),
            'date' => $data->getDateCandidature(),
            'status' => $data->getStatus(),
            'description' => 'Détails candidature'
        ];

        return new JsonResponse([$candid]);
    }
}
