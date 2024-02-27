<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Picture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filename', TextType::class, ['label' => "Nom du fichier ou URL"])
            ->add('Place', TextType::class, ['label' => "Lieu"])
            ->add('Date', DateType::class, ['label' => "Date de création"])
            ->add('Event', EntityType::class, [
                'class' => Event::class, 
                'choice_label' => 'title',
                'placeholder' => 'Sans événement', // Ajouter l'option "Sans événement"
                'required' => false, // Rendre le champ facultatif
             
      
            ])
            ->add('save', SubmitType::class, ['label' => "Enregistrer"]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}

