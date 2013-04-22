<?php

namespace Wsh\CmsHtmlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/pages", name="pages")
     * @Template()
     */
    public function pagesAction()
    {
        $faker = \Faker\Factory::create();
        $pages = [];
        for($i = 1; $i <= rand(50, 100); $i++) {
            $pages[$i]['title'] = $faker->sentence();
            $pages[$i]['content'] = $faker->paragraph();
            $pages[$i]['created'] = $faker->dateTime();
            $pages[$i]['lastModifiedAt'] = $faker->dateTime();
            $pages[$i]['lastModifiedBy'] = $faker->name();
        }
        return array(
            'pages' => $pages
        );
    }    

    /**
     * @Route("/page/form", name="pageForm")
     * @Template()
     */
    public function pageFormAction()
    {
        return array();
    }
}
