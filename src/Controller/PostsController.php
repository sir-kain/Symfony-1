<?php
namespace App\Controller;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends Controller {

    /**
     * @Route("/", name="posts.index")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        $products = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        return $this->render('posts/index.html.twig', compact('products'));
    }

    /**
     * @Route("/posts/{id<\d+>}", name="posts.show")
     * @Method("GET")
     */
    public function show($id) {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);
        return $this->render('posts/show.html.twig', compact("post"));
    }

    /**
     * @Route("posts/new", name="posts.create", methods={"GET", "POST"})
     */
    public function create(Request $request, ObjectManager $manager, Session $session) {
        $post = new Post();
        /**
         * création du formulaire
         */
        $form = $this->createForm(PostType::class, $post);
        /**
         * Vérification de la requète
         */
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * On crée l'article et on le persiste dans la base de données
             */
            $manager->persist($post);
            $manager->flush();
            /**
             * on redirige vers la page de l'article avec un message flash
             */
            $session->getFlashBag()->add("success", "L'article a bien été créé");
            return $this->redirectToRoute('posts.show', ['id' => $post->getId()]);
        }
        /**
         * On rend la vue
         */
        return $this->render('posts/create.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/posts/{id}/delete", name="posts.delete")
     */
    public function destroy($id, ObjectManager $manager) {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);
        $manager->remove($post);
        $manager->flush();
        return $this->redirectToRoute('posts.index');
    }

}