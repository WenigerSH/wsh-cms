<?php

namespace Wsh\CmsBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Controller\CRUDController;

/**
 * Class PageController
 * @package Wsh\CmsBundle\Controller\Backend
 *
 * @Route("/admin")
 */
class PageController extends CRUDController
{
    /**
     * @param Request $request
     * @param $id
     * @param $action
     * @param string $next
     * @return RedirectResponse
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function statusAction(Request $request, $id, $action, $next = 'list')
    {
        $admin = $this->get('sonata.admin.page');
        if (false === $admin->isGranted('UPDATE')) {
            throw new AccessDeniedException();
        }

        $admin->setRequest($request);

        $object = $admin->getObject($id);

        $admin->setSubject($object);

        switch ($action) {
            case 'publish':
                $object->setIsPublished(true);
                break;
            case 'hide':
                $object->setIsPublished(false);
                break;
            case 'invertPublish':
                $object->setIsPublished(!$object->getIsPublished());
                break;
        }

        $admin->update($object);

        $this->get('session')->setFlash('sonata_flash_success','flash_edit_success');

        return $this->redirect(
            $next === 'list' ? $admin->generateUrl('list') : $admin->generateObjectUrl($next, $object)
        );
    }

    /**
     * redirect the user depend on this choice
     *
     * @param object $object
     *
     * @return RedirectResponse
     */
    public function redirectTo($object)
    {
        $url = false;

        if ($this->get('request')->get('btn_create_and_publish')) {
            $url = $this->admin->generateUrl(
                'status',
                array(
                    'id' => $object->getId(),
                    'action' => 'publish',
                    'next' => 'edit'
                )
            );
        }

        if ($this->get('request')->get('btn_create_and_preview')) {
            $url = $this->admin->generateObjectUrl('show', $object);
        }

        if ($this->get('request')->get('btn_update_and_change_status')) {
            $url = $this->admin->generateUrl(
                'status',
                array(
                    'id' => $object->getId(),
                    'action' => 'invertPublish',
                    'next' => 'edit'
                )
            );
        }

        if ($this->get('request')->get('btn_update_and_list')) {
            $url = $this->admin->generateUrl('list');
        }
        if ($this->get('request')->get('btn_create_and_list')) {
            $url = $this->admin->generateUrl('list');
        }

        if ($this->get('request')->get('btn_create_and_create')) {
            $params = array();
            if ($this->admin->hasActiveSubClass()) {
                $params['subclass'] = $this->get('request')->get('subclass');
            }
            $url = $this->admin->generateUrl('create', $params);
        }

        if (!$url) {
            $url = $this->admin->generateObjectUrl('edit', $object);
        }

        return new RedirectResponse($url);
    }
}
