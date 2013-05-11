<?php

namespace Wsh\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Text page entity
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Wsh\CmsBundle\Entity\Repository\Page")
 */
class Page extends PageAbstract
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isSystem", type="boolean")
     */
    private $isSystem;

    public function __construct()
    {
        parent::__construct();
        $this->isSystem = false;
        $this->isPublished = false;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $isSystem
     */
    public function setIsSystem($isSystem)
    {
        $this->isSystem = $isSystem;
    }

    /**
     * @return boolean
     */
    public function getIsSystem()
    {
        return $this->isSystem;
    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        if ($this->getIsSystem() == true) {
            throw new \Exception('This page is a system page and can\'t be removed');
        }
    }

	public function __toString()
	{
		return $this->getTitle();
	}

}
