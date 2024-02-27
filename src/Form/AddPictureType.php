<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictures', EntityType::class, [
                'class' => Picture::class,
                'label' => 'images disponible',
                'qurey_builder' => function(PictureRepository $pictureRepository) {
                    return $pictureRepository ->findAllWithoutEvent;
                },
                'chocie_label' => 'Filename'
            ])
            ->add('save', SubmitType::class, [
                'label' => "Ajouter les photos"
            ])
      
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
