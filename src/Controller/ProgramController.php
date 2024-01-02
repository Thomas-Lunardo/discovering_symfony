<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Service\ProgramDuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Form\ProgramType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();

        // Create the form, linked with $category
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        // Was the form submitted ?
            if ($form->isSubmitted() && $form->isValid()) {
                $slug = $slugger->slug($program->getTitle());
                $program->setSlug($slug);
                $program->setOwner($this->getUser());
                
                $entityManager->persist($program);
                $email = (new Email())
                    ->from($this->getParameter('mailer_from'))
                    ->to('your_email@example.com')
                    ->subject('Une nouvelle série vient d\'être publiée !')
                    ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
    
            $mailer->send($email);

            $entityManager->flush();

            $this->addFlash('success', 'The new program has been created');

            return $this->redirectToRoute('program_index');
            }

            return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', requirements: ['page'=>'\d+'], methods: ['GET'], name:'show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'slug' => $program->getSlug(),
            'programDuration' => $programDuration->calculate($program),
        ]);
    }

    #[Route('/{slug}/season/{season}', name:'season_show')]
    public function showSesaon(Program $program, Season $season): Response
    {
            return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'slug' => $program->getSlug(),
            'season' => $season,
        ]);
    }

    #[Route('/{slug}/season/{season_id}/episode/{episode_id}', name:'episode_show')]
    public function showEpisode(
        #[MapEntity(mapping: ['slug' => 'slug'])] Program $program, 
        #[MapEntity(mapping: ['season_id' => 'id'])] Season $season,
        #[MapEntity(mapping: ['episode_id' => 'id'])] Episode $episode,
        ): Response
    {
            return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CONTRIBUTOR')]
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $program->getOwner()) {
            // if not the owner, throws a 403 Access Denied exeption
            throw $this->createAccessDeniedException('Only the owner can edit the program!');
        }
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'The new program has been updated');

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager->remove($program);
            $entityManager->flush();
            $this->addFlash('danger', 'The program has been deleted');
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}