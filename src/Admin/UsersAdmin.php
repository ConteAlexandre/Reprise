<?php


namespace App\Admin;


use App\Entity\Users;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UsersAdmin extends AbstractAdmin
{

    public function toString($object)
    {
        return $object instanceof Users
            ? $object->getPseudo()
            : 'Pseudo'
            ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('Firstname', TextType::class, [
                'required' => false
            ])
            ->add('Lastname', TextType::class, [
                'required' => false
            ])
            ->add('pseudo', TextType::class)
            ->add('email', EmailType::class)
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('pseudo')
            ->add('created_at')
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('Firstname')
            ->add('Lastname')
            ->addIdentifier('pseudo')
            ->add('email')
            ->add('created_at')
            ->add('updated_at')
        ;
    }

}