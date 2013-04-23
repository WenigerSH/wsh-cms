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
                'label' => '<i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Dashboard</span>',
                'extras' => array('safe_label' => true)
            )
        );

        $menu->addChild('Content', array(
            'uri' => '#',
            'label' => '<i class="fa-icon-pencil"></i><span class="hidden-tablet"> Content</span>',
            'extras' => array('safe_label' => true),
        ));
        $menu['Content']->setLinkAttribute('class', 'dropmenu');
        $menu['Content']->setChildrenAttribute('style', 'display: none');

        $menu['Content']->addChild(
            'Pages', array(
                'route' => 'pages',
                'label' => '<i class="fa-icon-font"></i><span class="hidden-tablet"> Pages</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu['Content']->addChild(
            'Blog', array(
                'route' => 'blog',
                'label' => '<i class="fa-icon-comments"></i><span class="hidden-tablet"> Blog</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu['Content']->addChild(
            'Qaa', array(
                'route' => 'qaa',
                'label' => '<i class="fa-icon-question-sign"></i><span class="hidden-tablet"> Q&A</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu['Content']->addChild(
            'Carousel', array(
                'route' => 'carousel',
                'label' => '<i class="fa-icon-picture"></i><span class="hidden-tablet"> Carousel</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu['Content']->addChild(
            'Ads', array(
                'route' => 'ads',
                'label' => '<i class="fa-icon-bar-chart"></i><span class="hidden-tablet"> Ads</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu['Content']->addChild(
            'File manager', array(
                'route' => 'fileManager',
                'label' => '<i class="fa-icon-folder-open"></i><span class="hidden-tablet"> File manager</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu->addChild('Settings', array(
            'uri' => '#',
            'label' => '<i class="fa-icon-cogs"></i><span class="hidden-tablet"> Settings</span>',
            'extras' => array('safe_label' => true),
        ));
        $menu['Settings']->setLinkAttribute('class', 'dropmenu');
        $menu['Settings']->setChildrenAttribute('style', 'display: none');
        $menu['Settings']->addChild(
            'Preferences', array(
                'route' => 'preferences',
                'label' => '<i class="fa-icon-hdd"></i><span class="hidden-tablet"> Preferences</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu['Settings']->addChild(
            'Languages', array(
                'route' => 'languages',
                'label' => '<i class="fa-icon-globe"></i><span class="hidden-tablet"> Language versions</span>',
                'extras' => array('safe_label' => true),
            )
        );

        $menu->addChild('Users', array(
                'route' => 'users',
                'label' => '<i class="fa-icon-group"></i><span class="hidden-tablet"> Users</span>',
                'extras' => array('safe_label' => true)
            )
        );

        $menu->addChild('Hide menu', array(
                'uri' => '#',
                'label' => '<i class="fa-icon-chevron-left"></i><span class="hidden-tablet"> Hide menu</span>',
                'extras' => array('safe_label' => true)
            )
        );

        // setting current on submenu items
        $request = $this->container->get('request');
        $routeName = $request->get('_route');
        $reqUri = $request->getRequestUri();


        foreach ($menu as $menuItemName => $item) {

            if (strstr($item->getUri(), $reqUri)) {
                $menu->getChild($menuItemName)->setCurrent(true);
            } elseif (count($item->getChildren() > 0)) {
                foreach($item->getChildren() as $menuItemName => $item) {
                    if(strstr($reqUri, $item->getUri())) {
                        $item->getParent()->setCurrent(true);
                        $item->setCurrent(true);
                        // show menu that is selected
                        $item->getParent()->setChildrenAttribute('style', 'display: block;');
                    }
                }
            }
        }

        return $menu;
	}
}