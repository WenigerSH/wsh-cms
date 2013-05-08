<?php

namespace Wsh\CmsBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string avatar file url
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $avatarPath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $avatarFile;

    /**
     * Set user e-mail
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->setUsername($email);
        return $this;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->avatarFile = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->avatarFile;
    }

    /**
     * Shortcut method for getting avatar web path
     *
     * @return null|string
     */
    public function getAvatar()
    {
        return $this->getWebPath();
    }

    public function getAbsolutePath()
    {
        return null === $this->avatarPath
            ? null
            : $this->getUploadRootDir().'/'.$this->avatarPath;
    }

    public function getWebPath()
    {
        return null === $this->avatarPath
            ? null
            : $this->getUploadDir().'/'.$this->avatarPath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/user/avatars';
    }
}
