<?php
namespace Wsh\CmsHtmlBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware {
	public function mainMenu(FactoryInterface $factory, array $options)
	{
		$menu = $factory->createItem('root');
		$menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked main-menu');

        $menu->addChild('Dashboard', array(
        	'route' => 'dashboard', 
        	'label' => '<i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Dashboard</span></a>',
            'extra' => array(
                'safe_label' => true
                )
        	)
        );
        $menu->addChild('Content', array(
            'uri' => '#',
        ));
        // ... add more children

        return $menu;
	}
}