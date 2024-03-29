<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function transform(Post $post)
    {
        return [
                'id'    => (int) $post->getId(),
                'message' => (string) $post->getMessage(),
                'count' => (int) $post->getCount()
        ];
    }

    public function transformAll()
    {
        $posts = $this->findAll();
        $postsArray = [];

        foreach ($posts as $post) {
            $postsArray[] = $this->transform($post);
        }

        return $postsArray;
    }

}