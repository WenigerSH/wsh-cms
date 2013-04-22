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
        return array();
    }    
}
