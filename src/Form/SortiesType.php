<?php

namespace App\Form;

use App\Entity\Sites;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site', EntityType::class, [
                'class' => Sites::class,
                'choice_label' => 'nom_site',
                'label'=>'Site : ',

            ])
            ->add('nom_sortie', TextType::class, ['label' => 'Le nom de la sortie contient : ',
                'required'=>false
            ])
            ->add('entreDate', DateType::class, ['label' => 'Entre ',
                'required'=>false,
                'format'=>'dd MM yyyy',
                'placeholder'=>[
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('etDate', DateType::class, ['label' => 'et ',
                'required'=>false,
                'format'=>'dd MM yyyy',
                'placeholder'=>[
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    ]
            ])
            ->add('organisateur', CheckboxType::class, ['label' => 'Sorties dont je suis l\'organisateur/trice',
                'required'=>false
            ])
            ->add('inscrit', CheckboxType::class, ['label' => 'Sorties auxquelles je suis inscrit/e',
                'required'=>false
            ])
            ->add('nonInscrit', CheckboxType::class, ['label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required'=>false
            ])
            ->add('passe', CheckboxType::class, ['label' => 'Sorties passées',
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
