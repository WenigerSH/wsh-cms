<?php

namespace Wsh\CmsBundle\Helper;

/**
 * Trait ControllerMethods
 * contains common methods used by controllers
 *
 * @package Wsh\CmsBundle\Helper
 */
trait ControllerMethods {

    public function getManager()
    {
        return $this->getDoctrine()->getManager();
    }

    public function getRepository($name)
    {
        return $this->getManager()->getRepository($name);
    }

    public function findBy(
        $repositoryName,
        array $searchParams,
        $throwIfNotFound = true,
        $exceptionMessage = 'The object could not be found'
    )
    {
        $objects = $this->getRepository($repositoryName)->findBy($searchParams);
        if ($throwIfNotFound && empty($objects)) {
            throw $this->createNotFoundException($exceptionMessage);
        }

        return $objects;
    }

    public function find(
        $repositoryName,
        $id,
        $throwIfNotFound = true,
        $exceptionMessage = 'The object could not be found'
    )
    {
        return $this->findBy($repositoryName, array('id' => $id), $throwIfNotFound, $exceptionMessage);
    }

}