<?php
// src/Controller/categoriesController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Program;
use App\Entity\Category;
use App\Form\CategoryType;

/**
 * @Route("/categories", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findAll();
        return $this->render('category/index.html.twig', [
        'categories' => $categories
        ]);
    }

    /**
     * The controlle for the category add form
     * 
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            // Deal with the submitted data
            // Get the Entity Manager
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($category);
            // Flush the persisted object
            $entityManager->flush();
            // Finally redirect to categories list
        return $this->redirectToRoute('category_index');

        }
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    /**
     * Getting a program by id
     *
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->findOneBy(['name' => $categoryName]);
        
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with '.$categoryName.' found in program\'s table.'
            );
        }
        else {
            $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBycategory([$category->getId()], ['id' => 'DESC'], 3);
            if (!$programs) {
                throw $this->createNotFoundException(
                    'No program with '.$categoryName.' found in program\'s table.'
                );
            } 
        }
        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'category'=> $category->getName()
        ]);
    }

   
}
