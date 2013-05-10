<?php

namespace Wsh\CmsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class Page extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title', 'translatable')
                ->add('body', 'translatable')
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

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'WshCmsBundle:Backend/Page:base_edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
        $collection->remove('create');

        $collection->add(
            'edit',
            $this->getRouterIdParameter().'/edit',
            array('_controller' => 'WshCmsBundle:Backend/Page:edit'),
            array('id' => '\d+')
        );

        $collection->add(
            'create',
            null,
            array('_controller' => 'WshCmsBundle:Backend/Page:create')
        );

        $collection->add(
            'status',
            $this->getRouterIdParameter().'/status/{action}/{next}',
            array('_controller' => 'WshCmsBundle:Backend/Page:status'),
            array('id' => '\d+')
        );
    }
}
