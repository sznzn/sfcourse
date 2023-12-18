<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher)
    {
        $form = $this->createFormBuilder()
            ->add('username', TextType::class, ['label' => 'Username'])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success float-end mt-3']
            ])
            ->getForm()
            ;

            $form->handleRequest($request); 
            if($form->isSubmitted()) {
                $data = $form->getData();
                $user = new User();
                $user->setUsername($data['username']);
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $data['password']
                    )
                );
                //dump($user);
                //die;
                //$user->setUsername($data['username']);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->redirect($this->generateUrl('app_login'));
            }
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
