<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Category;
use Doctrine\ORM\AbstractQuery;
use App\Entity\Affiliate;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

    // /**
    //  * @return Job[] Returns an array of Job objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Job
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param int|null $categoryId
     *
     * @return Job[]
     */
    public function findActiveJobs(int $categoryId = null)
    {
        $qb = $this->createQueryBuilder('j')
            ->where('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->orderBy('j.expiresAt', 'DESC');

        if ($categoryId) {
            $qb->andWhere('j.category = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $id
     *
     * @return Job|null
     */
    public function findActiveJob(int $id) : ?Job
    {
        return $this->createQueryBuilder('j')
            ->where('j.id = :id')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('id', $id)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Category $category
     *
     * @return AbstractQuery
     */
    public function getPaginatedActiveJobsByCategoryQuery(Category $category) : AbstractQuery
    {
        return $this->createQueryBuilder('j')
            ->where('j.category = :category')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('category', $category)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery();
    }

    public function transform(Job $job)
    {
        return [
            'id'    => (int) $job->getId(),
            'company' => (string) $job->getCompany(),
            'url' => (string) $job->getUrl(),
            'position' => (string) $job->getPosition(),
            'location' => (string) $job->getLocation()
        ];
    }

    public function transformAll()
    {
        $jobs = $this->findAll();
        $jobsArray = [];

        foreach ($jobs as $job) {
            $jobsArray[] = $this->transform($job);
        }

        return $jobsArray;
    }

    /**
     * @param Affiliate $affiliate
     *
     * @return Job[]
     */
    public function findActiveJobsForAffiliate(Affiliate $affiliate)
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('j.category', 'c')
            ->leftJoin('c.affiliates', 'a')
            ->where('a.id = :affiliate')
            ->andWhere('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('affiliate', $affiliate)
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->orderBy('j.expiresAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
