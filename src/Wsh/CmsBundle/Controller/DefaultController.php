<?php

namespace Wsh\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        /*
         *
         * TODO: The code below is to be removed when the translatable forms are in place
         *
         */

        $page = $this->findBy('WshCmsBundle:Page', array('title' => 'translatable test en'), false);
        $repository = $this->getDoctrine()->getManager()->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        if (empty($page)) {
            $page = new Page();
            $page->setTitle('translatable test en');
            $page->setBody('body en');

            $repository->translate($page, 'title', 'de_DE', 'translatable test de');
            $repository->translate($page, 'body', 'de_DE', 'body de');

            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();
        } else {
            $page = reset($page);
        }

        return array(
            'page' => $page,
            'repository' => $repository
        );
    }
}
