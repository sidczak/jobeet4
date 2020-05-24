<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Category;
use App\Form\JobType;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/job")
 */
class JobController extends Controller
{
    /**
     * @Route("/", name="job_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em): Response
    {
        // $jobs = $this->getDoctrine()
        //     ->getRepository(Job::class)
        //     ->findAll();

        // $query = $em->createQuery(
        //     'SELECT j FROM App:Job j WHERE j.createdAt > :date'
        // )->setParameter('date', new \DateTime('-30 days'));
        // $jobs = $query->getResult();

        // $query = $em->createQuery(
        //     'SELECT j FROM App:Job j WHERE j.expiresAt > :date'
        // )->setParameter('date', new \DateTime());
        // $jobs = $query->getResult();

        // $jobs = $em->getRepository(Job::class)->findActiveJobs();

        // return $this->render('job/index.html.twig', [
        //     'jobs' => $jobs,
        // ]);

        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="job_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();
            if ($logoFile instanceof UploadedFile) {
                $fileName = \bin2hex(\random_bytes(10)) . '.' . $logoFile->guessExtension();
                // moves the file to the directory where brochures are stored
                $logoFile->move(
                    $this->getParameter('jobs_directory'),
                    $fileName
                );
                $job->setLogo($fileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('job_index');
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{company}/{location}/{id}/{position}", name="job_show", methods={"GET"}, requirements={"id" = "\d+"})
     * @Entity("job", expr="repository.findActiveJob(id)")
     */
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="job_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_index', [
                'id' => $job->getId(),
            ]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="job_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Job $job): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_index');
    }
}
