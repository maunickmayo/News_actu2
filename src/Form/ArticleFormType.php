<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Titre'
        ])
        ->add('subtitle', TextType::class, [
            'label' => 'Sous-titre'
        ])
        ->add('content', TextareaType::class, [
            'label' => 'Contenu'
        ])
        ->add('photo', FileType::class, [
            'label' => 'Photo',
            'data_class' => null,
            'attr' => [
                'data-default-file' => $options['photo']
            ],
            'required' => false,
            'mapped' => false,
        ])
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
            'label' => 'Catégorie',
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Valider',
            'validate' => false,
            'attr' => [
                'class' => 'd-block mx-auto my-3 col-3 btn btn-success'
            ],
        ])
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'allow_file_upload' => true,
            'photo' => null    // on écrit :null  parcequ'on va se servir du meme prototype de formulaire soit pour le create   soit l'update, en creaate on va rien n y passer ça va etre null mais dans l'update  faudra déclarer la variable avant. donc on met null par defaut parce qu on  declare une variable photo. c est que pour l update et il y plusieurs facon de faire ...
        ]);
    }
}
