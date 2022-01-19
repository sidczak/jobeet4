<?php
namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager) : void
    {
        $post1 = new Post();
        $post1->setMessage('Post1 Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $post1->setCount(3);

        $post2 = new Post();
        $post2->setMessage('Post2 Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $post2->setCount(2);

        $manager->persist($post1);
        $manager->persist($post2);

        for ($i = 10; $i <= 20; $i++) {
            $post = new Post();
            $post->setMessage('Post '. $i .' Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $post->setCount(1);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
