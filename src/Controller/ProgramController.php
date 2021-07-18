<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Season;
use App\Entity\Comment;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Category;

use App\Service\Slugify;
use App\Form\CommentType;

use App\Form\ProgramType;

use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/programs", name="program_")
 */

class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findAll();
        return $this->render('program/index.html.twig', [
        'website' => 'Wild Séries',
        'programs' => $programs
        ]);
    }
    /**
     * Getting a program by id
     *
     * @Route("/show/{slug}", name="show")
     * @return Response
     */
    public function show(Program $program): Response
    {
       $seasons = $this->getDoctrine()
        ->getRepository(Season::class)
        ->findByProgram([$program -> getId()]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * Getting a program by id program and by id season
     *
     * @Route("/{program<^[0-9]+$>}/season/{season<^[0-9]+$>}", name="program_season_show")
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.' found in program\'s table.'
            );
        } 
        if (!$season) {
            throw $this->createNotFoundException(
                'No program with id : '.' found in program\'s table.'
            );
        } 

        $episodes = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->findBySeason([$season->getID()], ['number' => 'ASC']);

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * Getting a program by id program and by id season
     *
     * @Route("/{program<^[0-9]+$>}/season/{season<^[0-9]+$>}/episode/{episode<^[0-9]+$>}", name="program_episode_show")
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode, Request $request){
        
        $comment =new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getUser()) {
                
                $entityManager = $this->getDoctrine()->getManager();
                $comment->setUser($this->getUser());
                $comment->setEpisode($episode);
                $entityManager->persist($comment);
                $entityManager->flush();
                return $this->redirect($request->getUri());
            }
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            "form" => $form->createView(),
        ]);
    }

    /**
     * The controlle for the program add form
     * 
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to('your_email@example.com')
            ->subject('Une nouvelle série vient d\'être publiée !')
            ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer->send($email);

            // Finally redirect to categories list
        return $this->redirectToRoute('program_index');

        }
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);

    }
}
    