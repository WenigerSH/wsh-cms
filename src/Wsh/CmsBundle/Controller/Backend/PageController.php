<?php

namespace Wsh\CmsBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Admin\Admin;

/**
 * Class PageController
 * @package Wsh\CmsBundle\Controller\Backend
 *
 * @Route("/admin")
 */
class PageController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function createAction(Request $request)
    {
        $admin = $this->get('sonata.admin.page');
        if (false === $admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $admin->setRequest($request);
        if ($request->get('uniqid')) {
            $admin->setUniqid($request->get('uniqid'));
        }

        $object = $admin->getNewInstance();

        $admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));


            if ($form->isValid()) {
                $admin->create($object);

                $this->get('session')->setFlash('sonata_flash_success','flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object, $admin);
            } else {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $admin->getFormTheme());

        return $this->render(
            $admin->getTemplate('edit'),
            array(
                'action' => 'create',
                'form'   => $view,
                'object' => $object,
                'admin' => $admin,
                'base_template' => $admin->getTemplate('layout'),
                'admin_pool' => $this->get('sonata.admin.pool')
            )
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function editAction(Request $request, $id)
    {
        $admin = $this->get('sonata.admin.page');
        if (false === $admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $admin->setRequest($request);
        if ($request->get('uniqid')) {
            $admin->setUniqid($request->get('uniqid'));
        }

        $object = $admin->getObject($id);

        $admin->setSubject($object);

        /** @var $form \Symfony\Component\Form\Form */
        $form = $admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));

            if ($form->isValid()) {
                $admin->update($object);

                $this->get('session')->setFlash('sonata_flash_success','flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object, $admin);
            } else {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $admin->getFormTheme());

        return $this->render(
            $admin->getTemplate('edit'),
            array(
                'action' => 'update',
                'form'   => $view,
                'object' => $object,
                'admin' => $admin,
                'base_template' => $admin->getTemplate('layout'),
                'admin_pool' => $this->get('sonata.admin.pool')
            )
        );
    }

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

        $this->get('session')->setFlash('sonata_flash_success','flash_create_success');

        return $this->redirect(
            $next === 'list' ? $admin->generateUrl('list') : $admin->generateObjectUrl($next, $object)
        );
    }

    /**
     * redirect the user depend on this choice
     * variation of redirectTo from Sonata's CRUD controller
     *
     * @param object $object
     * @param \Sonata\AdminBundle\Admin\Admin $admin
     *
     * @return RedirectResponse
     */
    public function redirectTo($object, Admin $admin)
    {
        $url = false;

        if ($this->get('request')->get('btn_create_and_publish')) {
            $url = $admin->generateUrl(
                'status',
                array(
                    'id' => $object->getId(),
                    'action' => 'publish',
                    'next' => 'edit'
                )
            );
        }

        if ($this->get('request')->get('btn_create_and_preview')) {
            $url = $admin->generateObjectUrl('show', $object);
        }

        if ($this->get('request')->get('btn_update_and_change_status')) {
            $url = $admin->generateUrl(
                'status',
                array(
                    'id' => $object->getId(),
                    'action' => 'invertPublish',
                    'next' => 'edit'
                )
            );
        }

        if ($this->get('request')->get('btn_update_and_list')) {
            $url = $admin->generateUrl('list');
        }
        if ($this->get('request')->get('btn_create_and_list')) {
            $url = $admin->generateUrl('list');
        }

        if ($this->get('request')->get('btn_create_and_create')) {
            $params = array();
            if ($admin->hasActiveSubClass()) {
                $params['subclass'] = $this->get('request')->get('subclass');
            }
            $url = $admin->generateUrl('create', $params);
        }

        if (!$url) {
            $url = $admin->generateObjectUrl('edit', $object);
        }

        return new RedirectResponse($url);
    }
}
