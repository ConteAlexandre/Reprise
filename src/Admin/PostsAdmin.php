<?php


namespace App\Admin;


use App\Entity\Category;
use App\Entity\Post;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostsAdmin extends AbstractAdmin
{

    public function toString($object)
    {
        return $object instanceof Post
            ? $object->getTitle()
            : 'title'
            ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('title')
            ->add('created_at')
            ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('title')
            ->add('content')
            ->add('created_at')
            ->add('updated_at')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => []
                ]
            ])
            ;
    }

}