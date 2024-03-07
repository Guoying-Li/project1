<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class AddUserEventType extends AbstractType
{   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->userRepository = new UserRepository(); // Initialisation de $userRepository
        
        $event = $builder->getData();
        $createdBy = $event->getCreatedBy();

        $users = $this->userRepository->findAll();
        $users = array_diff($users, [$createdBy]);

        $builder

            ->add('sharedTo', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullname',
                'choices' => $users, // Correction de 'choice' à 'choices'
                'multiple'=> true,
                'expanded'=> true,
 
            ])
            ->add('save', SubmitType::class, [
                'label' => "Mettre à jour le partage"
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
