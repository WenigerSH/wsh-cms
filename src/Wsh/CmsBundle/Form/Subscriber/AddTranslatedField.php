<?php

namespace Wsh\CmsBundle\Form\Subscriber;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormError;

class AddTranslatedField implements EventSubscriberInterface
{
    private $factory;
    private $options;
    private $container;
    private $defaultOptions;

    public function __construct(
        FormFactoryInterface $factory,
        ContainerInterface $container,
        array $options,
        array $defaultOptions
    )
    {
        $this->factory = $factory;
        $this->options = $options;
        $this->container = $container;
        $this->defaultOptions = $defaultOptions;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // , form.post_data and form.bind_norm_data event
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_BIND => 'postBind',
            FormEvents::BIND => 'bind'
        );
    }

    private function bindTranslations($entity, $fieldName)
    {
        //Small helper function to extract all translations
        //from the Entity for the field we are interested in
        //and combines it with the fields

        $collection = array();
        $availableTranslations = array();

        $repo = $this->container->get('repository.translation');
        $translations = $repo->findTranslations($entity);

        foreach($translations as $locale => $fields)
        {
            if(isset($fields[$fieldName])) {
                $availableTranslations[$locale][$fieldName] = $fields[$fieldName];
            }
        }

        // add translations for default locale
        $availableTranslations[$this->container->getParameter('locale')][$fieldName] = call_user_func(
            array($entity, 'get'.ucfirst($fieldName))
        );

        foreach($this->getFieldNames($fieldName) as $locale => $formFieldName)
        {
            if(isset($availableTranslations[$locale]))
            {
                $translation = $availableTranslations[$locale][$fieldName];
            }
            else
            {
                $translation = '';
            }

            $collection[] = array(
                'locale'      => $locale,
                'fieldName'   => $formFieldName,
                'translation' => $translation,
            );
        }

        return $collection;
    }

    private function getFieldNames($fieldName)
    {
        //helper function to generate all field names in format:
        // '<locale>' => '<field>:<locale>'
        $collection = array();

        foreach($this->options['locales'] as $locale)
        {
            $collection[$locale] = $fieldName .":". $locale;
        }

        return $collection;
    }

    public function bind(FormEvent $event)
    {
        //Validates the submitted form
        $form = $event->getForm();
        $entity = $form->getParent()->getData();

        $validator = $this->container->get('validator');

        foreach($this->getFieldNames($form->getName()) as $locale => $fieldName)
        {
            $content = $form->get($fieldName)->getData();

            if(
                NULL === $content &&
                in_array($locale, $this->options['required_locale']))
            {
                $form->addError(
                    new FormError(
                        sprintf("Field '%s' for locale '%s' cannot be blank", $form->getName(), $locale)
                    )
                );
                $form->get(
                    sprintf("%s:%s", $form->getName(), $locale)
                )->addError(
                    new FormError(
                        sprintf("Field '%s' for locale '%s' cannot be blank", $form->getName(), $locale)
                    )
                );
            }
            else
            {
                call_user_func(array($entity, 'set'.ucfirst($form->getName())), $content);
                $errors = $validator->validateProperty($entity, $form->getName());
                if(count($errors) > 0)
                {
                    foreach($errors as $error)
                    {
                        $form->get($fieldName)->addError(new FormError($error->getMessage()));
                    }
                }
            }
        }
    }

    public function postBind(FormEvent $event)
    {
        //if the form passed the validation then set the corresponding Personal Translations
        $form = $event->getForm();

        $entity = $form->getParent()->getData();

        $repo = $this->container->get('repository.translation');

        foreach($this->bindTranslations($entity, $form->getName()) as $binded)
        {
            $content = $form->get($binded['fieldName'])->getData();

            $tmp = explode(':', $binded['fieldName']);

            $field = $tmp[0];
            $locale = $tmp[1];
            if ($locale === $this->container->getParameter('locale')) {
                call_user_func(array($entity, 'set'.ucfirst($field)), $content);
            } else {
                $repo->translate($entity, $field, $locale, $content);
            }
        }
    }

    public function preSetData(FormEvent $event)
    {
        //Builds the custom 'form' based on the provided locales
        $data = $event->getData();
        $form = $event->getForm();

        $entity = $form->getParent()->getData();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data && empty($entity))
        {
            return;
        }

        //todo: how to pass all valid field options to the generated translation fields?
        //$options = array_diff_key($this->options, $this->defaultOptions);
        $options['attr'] = empty($this->options['attr']) ? array() : $this->options['attr'];
        $options['property_path'] = false;

        if (!empty($this->options['required'])) {
            if (empty($this->options['required_locale'])) {
                $this->options['required_locale'] = array($this->container->getParameter('locale'));
            }
        }

        $availableLocales = $this->container->get('repository.language')->findAll(true);

        foreach($this->bindTranslations($entity, $form->getName()) as $binded)
        {
            $options['label'] = $this->options['label'].' '.$availableLocales[$binded['locale']];
            //for the time being there is no required validator for ckeditor widget
            //todo: devise a workaround
            $options['required'] =
                $this->options['widget'] !== 'ckeditor' &&
                in_array($binded['locale'], $this->options['required_locale']) !== false;


            $form->add($this->factory->createNamed(
                $binded['fieldName'],
                $this->options['widget'],
                $binded['translation'],
                $options
            ));
        }
    }
}