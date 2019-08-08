<?php
namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ApiJobController extends ApiController
{
    /**
    * @Route("/api-jobs", methods="GET")
    */
    public function index(JobRepository $jobRepository)
    {
        $jobs = $jobRepository->transformAll();

        return $this->respond($jobs);
    }

}