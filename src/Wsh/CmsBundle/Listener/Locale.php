<?php

namespace Wsh\CmsBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Locale
{
    private $container;
    private $defaultLocale;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->defaultLocale = $container->getParameter('locale');
        /*
         * TODO: add exception handling after language management is done
        if (!in_array($this->defaultLocale, array_keys($container->get('settings')->get('general.availableLocales')))) {
            throw new \Exception('Default locale not found in availableLocales');
        }
        */
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        if (!$this->container->has('session')) {
            return;
        }

        $session = $this->container->get('session');
        if ($session->has('_locale')) {
            $event->getRequest()->setLocale($session->get('_locale'));
        } else {
            $event->getRequest()->setLocale($this->defaultLocale);
        }

    }
}