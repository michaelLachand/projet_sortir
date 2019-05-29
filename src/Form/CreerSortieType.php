<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Villes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_sortie', TextType::class, [
                'label' => 'Nom de la sortie : ',
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ]
            ])
            ->add('datedebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : ',
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute',
                ]
            ])
            ->add('duree', IntegerType::class, [
                'label' => 'Durée de la sortie en minutes : ',
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ],
            ])
            ->add('datecloture', DateType::class, [
                'label' => 'Date limite d\'insciption : ',
                'format' => 'dd MM yyyy',
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ]
            ])
            ->add('nbinscriptionsmax', IntegerType::class, [
                'label' => 'Nombre de places : ',
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ]
            ])
            ->add('descriptionsinfos', TextareaType::class, [
                'label' => 'Description et infos : ',
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ]
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieux::class,
                'choice_label' => 'nom_lieu',
                'attr' => [
                    'col-sm-10form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ],
                'label' => 'Lieu : '
            ])
            ->add('ville', EntityType::class, [
                'class' => Villes::class,
                'choice_label' => 'nom_ville',
                'mapped' => false,
                'attr' => [
                    'col-sm-10 form-control-plaintext'
                ],
                'label_attr' => [
                    'class' => 'class="col-sm-2 col-form-label">'
                ],
                'label' => 'Ville : '
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
