<?php

namespace App\Form;

use App\Entity\Rondonnee;
use Symfony\Component\Form\AbstractType;
use App\Entity\Pays;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RondonneeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('categorie')

           
            ->add('agence')
            ->add('pays', EntityType::class, [
                // looks for choices from this entity
                'class' => Pays::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'nom',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
               
            ])
            
            ->add('inclus',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
            ->add('Non_Inclus',TextareaType::class, [
                'attr' => ['class' => 'tinymce'],
            ])
        
            ->add('description')
       
            ->add('images', FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'label'=>"Images"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rondonnee::class,
        ]);
    }
}
