<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('username', 'text')
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success float-end']
            ])
            -getForm()
            ;

            $form->handleRequest($request); // ici arreter 07.12
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
