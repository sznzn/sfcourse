<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('attachement', FileType::class, [
                'mapped' => false])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-2 float-end'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
