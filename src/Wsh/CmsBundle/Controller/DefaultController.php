<?php

namespace Wsh\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Locale\Locale;
use Wsh\CmsBundle\Entity\Page;
use Wsh\CmsBundle\Helper\ControllerMethods;

class DefaultController extends Controller
{
    use ControllerMethods;

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {

        return array(
        );
    }
}
