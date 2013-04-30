<?php
namespace Wsh\CmsBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Wsh\CmsBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * fixture to load default users
 */
class UsersFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const ADMINS_COUNT = 1;

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::ADMINS_COUNT; $i++) {
            $admin = new User();
            $admin->setEmail(empty($i) ? 'admin@example.com' : 'admin-'.$i.'@example.com');
            $admin->setCreatedAt(new \DateTime());
            $admin->setPlainPassword('123');
            $admin->setRoles(array('ROLE_SUPER_ADMIN', 'ROLE_SONATA_ADMIN'));
            $admin->setEnabled(true);
            $manager->persist($admin);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
