<?php


namespace App\Admin;


use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryAdmin extends AbstractAdmin
{

    public function toString($object)
    {
        return $object instanceof Category
            ? $object->getName()
            : 'Nom';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', TextType::class)
            ->add('is_enabled', null, [
                'label' => 'Actif'
            ])
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name')
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->add('created_at')
            ->add('updated_at')
            ->add('is_enabled')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => []
                ]
            ])
            ;
    }

}