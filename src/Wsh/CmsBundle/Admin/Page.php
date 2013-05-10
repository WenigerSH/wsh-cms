<?php

namespace Wsh\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class Page extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title', 'translatable')
                ->add('body', 'translatable')
                ->add(
                    'isPublished',
                    null,
                    array(
                        'required' => false,
                        'attr'  => array(
                            'class' => 'published'
                        )
                    )
                )
            ->end()
                ->with('Meta data')
                ->add(
                    'metaTitle',
                    null,
                    array(
                        'help' => 'If left empty then document title will be used',
                        'required' => false
                    )
                )
                ->add(
                    'metaDescription',
                    null,
                    array(
                        'help' => 'If left empty then document body (first 255 characters) will be used',
                        'required' => false
                    )
                )
                ->add('metaKeywords')
                ->add(
                    'slug',
                    null,
                    array(
                        'help' => 'If left blank auto slug from title will be generated',
                        'required' => false
                    )
                )
            ->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('isSystem')
            ->add('isPublished')
            ->add('createdAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('createdAt')
            ->add('isPublished')
            ->add('isSystem')
            ->add('slug');

    }

    public function validate(ErrorElement $errorElement, $object)
    {
    }
}
