<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre Nom : '
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email : '
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet : '
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
            ])
            ->add('envoyer', SubmitType::class, [
                'label' => 'Envoyer'
            ])
            ;
    }
}