<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;
use App\Form\PostType;

class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/post', name: 'post')]
    public function index(PostRepository $postRepository)
    {   
        $posts = $postRepository->findAll();
        // dump($posts);
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
        ]);
    }

    #[Route('/create', name: 'create')]
    
    public function create(Request $request): Response
    {
        // create a new post with title
        $post = new Post();
        $form = $this->createForm(PostType::class,$post);

        $form->handleRequest($request);
        $form->getErrors();
        if ($form->isSubmitted() && $form->isValid()){
            // dump($post);
        $this->entityManager->persist($post);
        $this->entityManager->flush();
            return $this->redirect($this->generateUrl('post'));
        }
        
        return $this->render('post/create.html.twig',[
            'form'=>$form->createView()
        ]);
    }





        //$post->setTitle('This is going to be a title');
        // entity manager - tell Doctrine you want to save this entity
        
        // flush - save the data to the database, actually executes the queries (ie, the INSERT query)
        // return a response
    #[Route('show/{id}', name: 'show')]
    function show($id, PostRepository $postRepository){
        $post = $postRepository->find($id);
        // dump($post);
        // die;
        return $this->render('post/show.html.twig',
        ['post' => $post]);
    }

    #[Route('delete/{id}', name: 'delete')]
    function remove(EntityManagerInterface $em, Post $post) {
        $em->remove($post);
        $em->flush();
        
        return $this->redirect($this->generateUrl('post'));
    }
}
