<?php

namespace App\Form;

use App\Entity\Participants;
use App\Entity\Sites;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NouvelUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login',TextType::class)
            ->add('nom',TextType::class)
            ->add('prenom',TextType::class)
            ->add('telephone',TextType::class,array('required' => false))
            ->add('mail',EmailType::class)
            ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les 2 mots de passe ne sont pas identiques.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Répéter le mot de passe'],])
            ->add('administrateur',ChoiceType::class,[
                'choices'  => [
                    'Oui' => true,
                    'Non' => false],])
            ->add('actif',ChoiceType::class,[
                'choices'  => [
                    'Oui' => true,
                    'Non' => false],])
            ->add('photo',FileType::class, array('label' => 'Votre photo' , 'required' => false))
            ->add('site',EntityType::class,['class'=>Sites::class,'choice_label' =>'nom_site'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participants::class,
        ]);
    }
}
