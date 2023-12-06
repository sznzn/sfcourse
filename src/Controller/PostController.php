<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    #[Route('/post', name: 'post')]
    public function index(PostRepository $postRepository)
    {   
        $posts = $postRepository->findAll();
        dump($posts);
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'posts' => $posts,
        ]);
    }

    #[Route('/create', name: 'create')]
    
    public function create(EntityManagerInterface $entityManager): Response
    {
        // create a new post with title
        $post = new Post();
        $post->setTitle('This is going to be a title');
        // entity manager - tell Doctrine you want to save this entity
        $entityManager->persist($post);
        
        // flush - save the data to the database, actually executes the queries (ie, the INSERT query)
        $entityManager->flush();
        // return a response
        return $this->redirect($this->generateUrl('post'));
    }
    #[Route('post/show/{id}', name: 'show')]
    public function show($id, PostRepository $postRepository){
        $post = $postRepository->find($id);
        // dump($post);
        // die;
        return $this->render('post/show.html.twig',
        ['post' => $post]);
    }

    #[Route('delete/{id}', name: 'delete')]
    public function remove(EntityManagerInterface $em, Post $post) {
        $em->remove($post);
        $em->flush();

        return $this->redirect($this->generateUrl('post'));
    }
}
