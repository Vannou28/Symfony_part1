<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Form\ProgramType;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Actor;
/**
 * @Route("/actors", name="actor_")
 */

class ActorController extends AbstractController
{

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(Actor $actor): Response
    {
        if (!$actor) {
            throw $this->createNotFoundException(
                'No actor with id : '.$id.' found in program\'s table.'
            );
        }

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

}
    