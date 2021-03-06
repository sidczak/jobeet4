<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Category;
use App\Form\JobType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\FormInterface;
use App\Service\JobHistoryService;

/**
 * @Route("/job")
 */
class JobController extends AbstractController
{
    /**
     * @Route("/", name="job_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, JobHistoryService $jobHistoryService): Response
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
            'historyJobs' => $jobHistoryService->getJobs(),
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($job);
            $entityManager->flush();

            // return $this->redirectToRoute('job_index');
            return $this->redirectToRoute('job_preview', [
                'token' => $job->getToken()
            ]);
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
    public function show(Job $job, JobHistoryService $jobHistoryService): Response
    {
        $jobHistoryService->addJob($job);

        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("/preview/{token}", name="job_preview", methods="GET", requirements={"token" = "\w+"})
     */
    public function preview(Job $job) : Response
    {
        $deleteForm = $this->createDeleteForm($job);
        $publishForm = $this->createPublishForm($job);

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
            'publishForm' => $publishForm->createView(),
        ]);
    }

    /**
     * @Route("/{token}/edit", name="job_edit", methods={"GET","POST"}, requirements={"token" = "\w+"})
     */
    public function edit(Request $request, Job $job): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // return $this->redirectToRoute('job_index', [
            //     'id' => $job->getId(),
            // ]);
            return $this->redirectToRoute('job_preview', [
                'token' => $job->getToken()
            ]);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="job_delete", methods={"DELETE"})
    //  */
    // public function delete(Request $request, Job $job): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($job);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('job_index');
    // }
    
    /**
     * @Route("delete/{token}", name="job_delete", methods="DELETE", requirements={"token" = "\w+"})
     */
    public function delete(Request $request, Job $job) : Response
    {
        $form = $this->createDeleteForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_index');
    }
    
    private function createDeleteForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('job_delete', ['token' => $job->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("publish/{token}", name="job_publish", methods="POST", requirements={"token" = "\w+"})
     */
    public function publish(Request $request, Job $job) : Response
    {
        $form = $this->createPublishForm($job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setActivated(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('notice', 'Your job was published');
        }

        return $this->redirectToRoute('job_preview', [
            'token' => $job->getToken(),
        ]);
    }

    private function createPublishForm(Job $job) : FormInterface
    {
        return $this->createFormBuilder(['token' => $job->getToken()])
            ->setAction($this->generateUrl('job_publish', ['token' => $job->getToken()]))
            ->setMethod('POST')
            ->getForm();
    }
}
