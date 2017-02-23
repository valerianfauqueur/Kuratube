<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\TagType;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text')
            ->add('content', TextareaType::class)
            ->add('channel', 'text')
            ->add('category', EntityType::class, array(
                'class' => 'AppBundle:Category',
                'choice_label' => 'name',
            ))
            ->add('tags', CollectionType::class, array(
                'entry_type' => text,
                'allow_add' => true,
                'allow_delete' => true,
            ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('author.username')
            ->add('title')
            ->add('content')
            ->add('category.name', null, array( 'label' => 'Category'))
            ->add('created_at', 'doctrine_orm_date_range')
            ->add('updated_at', 'doctrine_orm_date_range');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('author.username')
            ->addIdentifier('title')
            ->addIdentifier('category')
            ->addIdentifier('created_at')
            ->addIdentifier('updated_at');
    }
}