<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Playlist;
class PlaylistType extends AbstractType
{
    /**
     * Build the form for creating or editing a Playlist.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'=> 'Nom de la playlist',
                'required' => true,
            ])

            ->add('description', TextType::class, [
                'label'=> 'Description de la playlist',
                'required' => true,
            ])

            ->add('submit', SubmitType::class, [
                'label'=> 'Enregistrer'
                ])
        ;
    }

    /**
     * Configure the options for this form.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}