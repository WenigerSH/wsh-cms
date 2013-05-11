<?php
namespace Wsh\CmsBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Wsh\CmsBundle\Entity\Language;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Locale\Locale;


/**
 * fixture to load default languages
 */
class LanguagesFixture extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // languageCode => enabled
        $languages = array(
            'en' => true,
            'de' => true,
            'pl' => true
        );

        $names = Locale::getDisplayLanguages('en');

        foreach ($languages as $code => $enabled) {
            $language = new Language();
            $language->setCode($code);
            $language->setName($names[$code]);
            $language->setEnabled($enabled);
            $manager->persist($language);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
