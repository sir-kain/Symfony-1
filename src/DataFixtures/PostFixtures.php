<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < 12; $i++) {
            $post = new Post();
            $post->setTitle("Lorem title ceci est un titre");
            $post->setContent("Lorem content un long texte est présent dans la phrase que vous êtes en train de lire");
            $manager->persist($post);
        }
        $manager->flush();
    }
}
