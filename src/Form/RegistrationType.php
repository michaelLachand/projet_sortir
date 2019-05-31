<?php

namespace App\Form;

use App\Entity\Participants;
use App\Entity\Sites;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login',TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom',TextType::class)
            ->add('telephone', TextType::class,array('required' => false))
            ->add('mail', EmailType::class)
            ->add('site', EntityType::class,['class' => Sites::class,'choice_label' => 'nom_site'])
            ->add('photo', FileType::class, array('label' => 'Votre photo' , 'required' => false))
                     ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participants::class
        ]);
    }

}
