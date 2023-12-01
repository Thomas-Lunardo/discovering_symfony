<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;

class ProgramController extends AbstractController
{
    #[Route('/program/', name: 'program_index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    #[Route('/program/{id}', requirements: ['page'=>'\d+'], methods: ['GET'], name:'program_show')]
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->find($id);
        $seasons = $seasonRepository->find($id);

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons,]);
    }

    #[Route('/program/{programId}/season/{seasonId}', name:'program_season_show')]
    public function showSesaon(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneById($programId);
        // $programCategory = $program->getC => mettre la bidirectionnalité
        $season = $seasonRepository->findOneById($seasonId);

        return $this->render('program/season_show.html.twig', ['program' => $program, 'season' => $season,]);
    }
}