<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Class FormationType
 * @package App\Form
 */

class FormationType extends AbstractType
{
    /**
     * Summary of buildForm
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la formation',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la formation',
                'required' => false
            ])
            ->add ('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'data' => isset($options['data']) && $options['data'] ->getPublishedAt() != null ?
                    $options['data']->getPublishedAt() : new \DateTime('now'),
                'label' => 'Date de publication',
                'required' => true
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'multiple' => true,
                'required' => true,
                'choice_label' => 'name',
                'label' => 'Catégorie de la formation'
            ])
            ->add('videoId')
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'required' => false,
                'multiple' => false,
                'choice_label' => 'name',
                'label' => 'Playlist de la formation'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }

    /**
     * Summary of configureOptions
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
    

}
