<?php
namespace App\Tests\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostsControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Test de la page d'accueil
     */
    public function test_index_should_list_all_posts()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $posts = $this->entityManager
            ->getRepository(Post::class)
            ->findAll();

        /*Doit renvoyer tjrs un code status de 200*/
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        /*
         * Doit afficher ces messages qui suivent
         * Le message de bienvenue puis le nombre de posts
        */
        $this->assertContains('Bienvenue sur le blog',  $crawler->filter('h1')->text());
        $this->assertContains(sizeof($posts),  $crawler->filter('.nbposts')->text(), 'Le nombre de posts n\'est pas defini sur la page index');

        /*Verifie que chaque colonne des titres affiche le meme que ceux de la base*/
        foreach ($posts as $key=>$post) {
            $selector = 'table tbody tr:nth-child('. ($key+1) .') td:nth-child(2)';
            $this->assertContains($post->getTitle(),  $crawler->filter($selector)->text());
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}