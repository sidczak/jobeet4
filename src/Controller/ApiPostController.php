<?php
namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ApiPostController extends ApiController
{
    /**
    * @Route("/api-posts", methods="GET")
    */
    public function index(PostRepository $postRepository)
    {
        $posts = $postRepository->transformAll();

        return $this->respond($posts);
    }

    /**
    * @Route("/api-posts", methods="POST")
    */
    public function create(Request $request, PostRepository $postRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate the title
        if (! $request->get('title')) {
            return $this->respondValidationError('Please provide a title!');
        }

        // persist the new post
        $post = new Post;
        $post->setMessage($request->get('message'));
        $post->setCount(0);
        $em->persist($post);
        $em->flush();

        return $this->respondCreated($postRepository->transform($post));
    }

    /**
    * @Route("/api-posts/{id}/count", methods="POST")
    */
    public function increaseCount($id, EntityManagerInterface $em, PostRepository $postRepository)
    {
        $post = $postRepository->find($id);

        if (! $post) {
            return $this->respondNotFound();
        }

        $post->setCount($post->getCount() + 1);
        $em->persist($post);
        $em->flush();

        return $this->respond([
            'count' => $post->getCount()
        ]);

    }

}