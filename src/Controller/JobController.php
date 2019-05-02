<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Job;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list")
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em) : Response
    {
        // $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();
        
        // $query = $em->createQuery(
        //     'SELECT j FROM App:Job j WHERE j.createdAt > :date'
        // )->setParameter('date', new \DateTime('-30 days'));

        // $query = $em->createQuery(
        //     'SELECT j FROM App:Job j WHERE j.expiresAt > :date'
        // )->setParameter('date', new \DateTime());
        // $jobs = $query->getResult();

        $jobs = $em->getRepository(Job::class)->findActiveJobs();
        
        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("/job/{id}", name="job.show")
     *
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job) : Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}