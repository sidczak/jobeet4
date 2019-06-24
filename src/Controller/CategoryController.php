<?php 
namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Job;
use Knp\Component\Pager\PaginatorInterface;

class CategoryController extends Controller

{
    /**
     * @Route("/category/{slug}/{page}", name="category_show", methods="GET", defaults={"page": 1}, requirements={"page" = "\d+"})
     */
    public function show(Category $category,  int $page, PaginatorInterface $paginator) : Response
    {

        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Job::class)->getPaginatedActiveJobsByCategoryQuery($category),
            $page,
            $this->getParameter('max_jobs_on_category')
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'activeJobs' => $activeJobs,
        ]);
    }
}