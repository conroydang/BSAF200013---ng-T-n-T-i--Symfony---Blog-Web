<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, [
                'attr' => array(
                    'class' => 'form-control bg-gray',
                    'placeholder' => 'Enter the title of blog in here'
                )
            ])
            ->add('summary',TextareaType::class,  [
                'attr' => array(
                    'class' => 'form-control bg-gray',
                    'placeholder' => 'Enter the summary of blog in here'
                )
            ])
            ->add('content',TextareaType::class,  [
                'attr' => array(
                    'class' => 'form-control bg-gray',
                    'placeholder' => 'Enter the content of blog in here'
                )
            ])
            ->add('categoryId',EntityType::class,[
                'attr' => array(
                'class' => 'form-select form-select-lg mb-3 bg-gray',
            ),
            'class'=>'App\Entity\Category','choice_label'=>"title"])
            ->add('image',FileType::class, [
                 'required' => false,
                 'mapped' => false,
                 
                'attr' => array('class' => 'form-control-file', 'id'=>'exampleFormControlFile1')
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
