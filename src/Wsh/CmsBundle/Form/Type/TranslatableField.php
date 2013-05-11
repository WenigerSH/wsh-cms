<?php

namespace Wsh\CmsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Wsh\CmsBundle\Form\Subscriber\AddTranslatedField;

class TranslatableField extends AbstractType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(! class_exists($options['personal_translation']))
        {
            throw new \InvalidArgumentException(sprintf("Unable to find personal translation class: '%s'", $options['personal_translation']));
        }

        $subscriber = new AddTranslatedField(
            $builder->getFormFactory(),
            $this->container,
            $options,
            $this->getDefaultOptions()
        );
        $builder->addEventSubscriber($subscriber);
    }

    public function finishView(
        \Symfony\Component\Form\FormView $view,
        \Symfony\Component\Form\FormInterface $form,
        array $options
    )
    {
        $help = $options['sonata_field_description']->getHelp();
        if (!empty($help)) {
            $view->vars['help'] = $help;
        }
    }

    public function getDefaultOptions(array $options = array())
    {
        // get all languages and convert to simple array
        $locales = $this->container->get('repository.language')->findAll(true);

        $options['remove_empty'] = true; //Personal Translations without content are removed
        $options['csrf_protection'] = false;
        $options['personal_translation'] = 'Gedmo\Translatable\Entity\Translation'; //Personal Translation class
        $options['locales'] = array_keys($locales); //the locales you wish to edit
        $options['required_locale'] = array(); //the required locales cannot be blank
        $options['widget'] = 'text'; //change this to another widget like 'texarea' if needed
        $options['entity_manager_removal'] = true; //auto removes the Personal Translation thru entity manager
        $options['property_path'] = 'translations';

        return $options;
    }

    public function getName()
    {
        return 'translatable';
    }
}