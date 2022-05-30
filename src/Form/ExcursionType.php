<?php

namespace App\Form;

use App\Entity\Excursion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
class ExcursionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description', CKEditorType::class)

            
            ->add('inclus')
            ->add('Non_Inclus')
            ->add('agence')
            ->add('categorie')

            ->add('pays')
            
            ->add('images', FileType::class,[
                'label' => "Images",
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Excursion::class,
        ]);
    }
}
