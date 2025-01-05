<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('visibility', CheckboxType::class, [
                'required' => false
            ])
            ->add('mobile_number', TelType::class, [
                'required' => false,
                'attr' => [
                    'pattern' => '^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{0,2}){0,4}|\d{0,2}(?:[\s.-]?\d{0,3}){0,2})$',
                    'maxlength' => 24,
                ],
            ])
            ->add('landline_number', TelType::class, [
                'required' => false,
                'attr' => [
                    'pattern' => '^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$',
                    'maxlength' => 24,
                ],
            ])
            ->add('code', TextType::class,[
                'required' => false,
                'empty_data' => '',
                'attr' => [
                    'pattern' => '^[a-zA-Z0-9]+$',
                    'maxlength' => 20,
                ],
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('department', TextType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('function', TextType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('postal_adresse', TextType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('first_name', TextType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('last_name', TextType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                ],
            ])
            ->add('modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
