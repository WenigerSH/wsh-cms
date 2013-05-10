<?php

namespace Wsh\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Locale\Locale;

/**
 * @ORM\Entity(repositoryClass="Wsh\CmsBundle\Entity\Repository\Language")
 * @ORM\Table(name="language")
 * @DoctrineAssert\UniqueEntity("code")
 */
class Language
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=7, nullable=false)
     */
    protected $code;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    public function __construct()
    {
        $this->enabled = false;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $locales = Locale::getDisplayLanguages('en_US');
        if (!isset($locales[$code])) {
            throw new \Exception("Unknown language \"$code\" in ".__FUNCTION__);
        }
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}