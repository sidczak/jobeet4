<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * Finds and displays a category entity.
     *
     * @Route("/category/{slug}", name="category.show", methods="GET")
     *
     * @param Category $category
     *
     * @return Response
     */
    public function show(Category $category) : Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}