<?php

namespace Wsh\CmsBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DashboardController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array(
        	'admin_pool' => $this->container->get('sonata.admin.pool')
        );
    }
}
