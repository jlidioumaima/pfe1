<?php

namespace App\Form;

use App\Entity\Sites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class SitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('nom')
            ->add('description',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('pays')
            ->add('images', FileType::class, [
                'label' => "Images",
                'multiple' => true,
                'mapped' => false,
                'required'=> false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sites::class,
        ]);
    }
}
