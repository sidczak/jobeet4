<?php 

namespace App\Controller\Admin;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Form\JobType;
use Symfony\Component\HttpFoundation\Request;

class JobController extends AbstractController
{
    /**
     * Lists all jobs entities.
     *
     * @Route("/admin/jobs/{page}",
     *     name="admin.job.list",
     *     methods="GET",
     *     defaults={"page": 1},
     *     requirements={"page" = "\d+"}
     * )
     *
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param int $page
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page) : Response
    {
        $jobs = $paginator->paginate(
            $em->getRepository(Job::class)->createQueryBuilder('j'),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'j.createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'DESC',
            ]
        );

        return $this->render('admin/job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Create job.
     *
     * @Route("/admin/job/create", name="admin.job.create", methods="GET|POST")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('admin.job.list');
        }

        return $this->render('admin/job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}