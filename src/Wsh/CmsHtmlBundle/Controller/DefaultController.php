<?php

namespace Wsh\CmsHtmlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
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
     * @Route("/blog", name="blog")
     * @Template()
     */
    public function blogAction()
    {
        $faker = \Faker\Factory::create();
        $pages = [];
        for($i = 1; $i <= rand(50, 100); $i++) {
            $pages[$i]['title'] = $faker->sentence();
            $pages[$i]['leadText'] = $faker->paragraph();
            $pages[$i]['author'] = $faker->name();
            $pages[$i]['categories'] = $faker->words(3);
            $pages[$i]['tags']  = $faker->words(5);
            $pages[$i]['created'] = $faker->dateTime();
            $pages[$i]['lastModifiedAt'] = $faker->dateTime();
            $pages[$i]['lastModifiedBy'] = $faker->name();
        }
        return array(
            'pages' => $pages
        );
    }

    /**
     * @Route("/blog/form", name="blogForm")
     * @Template()
     */
    public function blogFormAction()
    {
        return array();
    }

    /**
     * @Route("/page/form", name="pageForm")
     * @Template()
     */
    public function pageFormAction()
    {
        return array();
    }

    /**
     * @Route("/qaa", name="qaa")
     * @Template("WshCmsHtmlBundle:Default:qaa.html.twig")
     */
    public function questionsAndAnwsersAction()
    {
        $faker = \Faker\Factory::create();
        $pages = [];
        for($i = 1; $i <= rand(10, 25); $i++) {
            $pages[$i]['question'] = $faker->sentence();
            $pages[$i]['anwser'] = $faker->sentence();
            $pages[$i]['created'] = $faker->dateTime();
            $pages[$i]['lastModifiedAt'] = $faker->dateTime();
            $pages[$i]['lastModifiedBy'] = $faker->name();
        }
        return array(
            'pages' => $pages
        );    
    }

    /**
     * @Route("/qaa/form", name="qaaForm")
     * @Template("WshCmsHtmlBundle:Default:qaaForm.html.twig")
     */
    public function qaaFormAction()
    {
        return array();
    }

    /**
     * @Route("/carousel", name="carousel")
     * @Template()
     */
    public function carouselAction()
    {
        $faker = \Faker\Factory::create();
        $pages = [];
        $statuses = array('active', 'disabled');
        for($i = 1; $i <= rand(5, 15); $i++) {
            $pages[$i]['caption'] = $faker->sentence();
            $pages[$i]['status'] = $statuses[array_rand($statuses)];
            $pages[$i]['created'] = $faker->dateTime();
            $pages[$i]['lastModifiedAt'] = $faker->dateTime();
            $pages[$i]['lastModifiedBy'] = $faker->name();
        }
        return array(
            'pages' => $pages
        );    
    }

    /**
     * @Route("/carousel/form", name="carouselForm")
     * @Template()
     */
    public function carouselFormAction()
    {
        return array();
    }

    /**
     * @Route("/ads", name="ads")
     * @Template()
     */
    public function adsAction()
    {
        $faker = \Faker\Factory::create();
        $pages = [];
        $zones = array('homepage-right', 'contact-bottom', 'homepage-top', 'footer');
        for($i = 1; $i <= rand(5, 15); $i++) {
            $pages[$i]['name'] = $faker->word();
            $pages[$i]['zone'] = $zones[array_rand($zones)];
            $pages[$i]['views'] = rand(200, 400);
            $pages[$i]['clicks'] = rand(20, 50);
            $pages[$i]['created'] = $faker->dateTime();
            $pages[$i]['lastModifiedAt'] = $faker->dateTime();
            $pages[$i]['lastModifiedBy'] = $faker->name();
        }
        return array(
            'pages' => $pages
        );    
        return array();
    }

    /**
     * @Route("/files-manager", name="fileManager")
     * @Template()
     */
    public function filesManagerAction()
    {
        return array();
    }

    /**
     * @Route("/menus", name="menus")
     * @Template()
     */
    public function menusAction()
    {
        return array();
    }

    /**
     * @Route("/preferences", name="preferences")
     * @Template()
     */
    public function preferencesAction()
    {
        return array();
    }

    /**
     * @Route("/languages", name="languages")
     * @Template()
     */
    public function languagesAction()
    {
        return array();
    }

    /**
     * @Route("/users", name="users")
     * @Template()
     */
    public function usersAction()
    {
        return array();
    }
}
