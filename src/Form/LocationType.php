<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('city',null, [
            'attr'=> [
                'placeholder' => 'Enter city name'
            ],
        ])
        ->add ('country',ChoiceType::class,[
            'choices' => [
                'Poland'=> 'PL',
                'Germany'=> 'DE',
                'France'=> 'FR',
                'Spain'=> 'ES',
                'Italy'=> 'IT',
                'United States'=> 'US',
                'United Kingdom'=> 'GB'
            ],
        ])
        ->add('latitude', TextType::class, [
                'label' => 'Latitude',
                'required' => true,
            ])
        ->add('longitude', TextType::class, [
                'label' => 'Longitude',
                'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
