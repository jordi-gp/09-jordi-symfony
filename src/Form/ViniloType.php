<?php

namespace App\Form;

use App\Entity\Artista;
use App\Entity\Vinilo;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ViniloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(
                        ['message' => 'Aquest camp no es pot deixar en blanc']
                    ),

                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'maxMessage' => 'Aquest camp no pot superar els {{ limit }} caracters'
                    ])
                ]
            ])
            ->add('price', IntegerType::class)
            ->add('fileCover', VichImageType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/png', 'image/jpeg', 'image/jpg'],
                        'mimeTypesMessage' => 'El fitxer pujat ha se der una imatge'
                    ])
                ]
            ])
            ->add('description', TextAreaType::class, [

            ])
            ->add('artista', EntityType::class,
                [
                    'class' => Artista::class,
                    'choice_label' => 'name'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vinilo::class,
        ]);
    }
}
