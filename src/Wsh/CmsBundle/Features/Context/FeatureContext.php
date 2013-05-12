<?php

namespace Wsh\CmsBundle\Features\Context;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\CommonContexts\MinkExtraContext;
use Behat\CommonContexts\SymfonyMailerContext;
use Wsh\CmsBundle\Entity\Language;
use Wsh\CmsBundle\Entity\Page;
use Wsh\CmsBundle\Entity\User;
use Behat\Behat\Context\Step\Given;
use Behat\Behat\Context\Step\When;
use Behat\Behat\Context\Step\Then;


//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class FeatureContext extends MinkContext //MinkContext if you want to test web
                  implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->useContext('symfony_extra', new MinkExtraContext());
        $this->useContext('symfony_mailer', new SymfonyMailerContext());
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * A method to retrieve services in a controller-like fashion.
     *
     * @param string $serviceName
     * @return mixed
     */
    private function get($serviceName)
    {
        return $this->kernel->getContainer()->get($serviceName);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getManager()
    {
        return $this->get('doctrine')->getManager();
    }

    /**
     * @param string $name The repository name
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getRepository($name)
    {
        return $this->getManager()->getRepository($name);
    }

    /**
     * @param string $name The repository name
     */
    private function removeEntities($name)
    {
        $entities = $this->getRepository($name)->findAll();
        foreach ($entities as $entity) {
            $this->getManager()->remove($entity);
        }

        $this->getManager()->flush();
    }

    /**
     * @param string $url
     * @return string The url with the query and fragment strings removed from the end
     */
    private function removeQueryFromUrl($url)
    {
        $returnUrl = $url;
        $queryPos = strpos($url, '?');
        if ($queryPos !== false) {
            $returnUrl = substr($url, 0, $queryPos);
        }

        $fragmentPos = strpos($url, '#');
        if ($fragmentPos !== false) {
            $returnUrl = substr($url, 0, $fragmentPos);
        }

        return $returnUrl;
    }

    /**
     * @param  string                      $username
     * @param  string                      $password
     * @param  string                      $email
     * @param  string                      $role
     * @param  bool                        $enabled  default = true
     * @return \Wsh\CmsBundle\Entity\User
     */
    private function assertUserExists($username, $password, $role, $email, $enabled=true)
    {
        $em = $this->getManager();
        $repo = $this->getRepository("WshCmsBundle:User");

        $user = $repo->findOneByUsername($username);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        $role = ucfirst($role);
        switch($role) {
            case "Admin":
                $user = new User();
                $user->setEmail('admin@example.com');
                $user->setCreatedAt(new \DateTime());
                $user->setPlainPassword('123');
                $user->setRoles(array('ROLE_SUPER_ADMIN', 'ROLE_SONATA_ADMIN'));
                $user->setEnabled($enabled);
                break;
            default:
                throw new \Exception('Unknown user role "'.$role.'" specified in '.__FUNCTION__);
        }
        $em->persist($user);
        $em->flush();
    }

    /**
     * @BeforeScenario @logout
     *
     * @param $event
     */
    public function assertLogout($event = null)
    {
        $this->getMink()->resetSessions();
    }



    // --- STEP DEFINITIONS ---


    // --- Given ---

    /**
     * @Given /^user "([^"]*)" with role "([^"]*)" and password "([^"]*)" exists$/
     */
    public function userExists($username, $role, $password)
    {
        $this->assertUserExists($username, $password, $role, $username);
    }

    /**
     * @Given /^user "([^"]*)" does not exist$/
     */
    public function userDoesNotExist($username)
    {
        $em = $this->getManager();
        // find if user exist allready
        $repo = $this->getRepository("WshCmsBundle:User");
        $user = $repo->findOneByUsername($username);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }

    /**
     * @Given /^I am on "([^"]*)" route$/
     */
    public function iAmOnRoute($routeName)
    {
        $this->visit($this->get('router')->generate($routeName));
    }

    /**
     * @Given /^I am on "([^"]*)" "([^"]*)" route$/
     */
    public function iAmOnAdminRoute($adminName, $routeName)
    {
        $this->visit($this->get($adminName)->generateUrl($routeName));
    }

    /**
     * @Given /^I am on "([^"]*)" "([^"]*)" route of object with "([^"]*)" "([^"]*)"$/
     */
    public function iAmOnRouteOfObject($sonataServiceName, $route, $property, $value)
    {
        $admin = $this->get($sonataServiceName);
        $object = $this->getRepository($admin->getClass())->findOneBy(array($property => $value));
        if (empty($object)) {
            throw new \Exception("Object with $property=\"$value\" not found");
        }

        $this->visit($this->get($sonataServiceName)->generateObjectUrl($route, $object, array(), true));
    }

    /**
     * Creates and/or logs in a user
     *
     * @Given /^I am logged in as "([^"]*)" with role "([^"]*)"$/
     */
    public function iAmLoggedInAs($username, $role)
    {
        $this->assertLogout();
        $this->assertUserExists($username, '123', $role, $username);
        return array(
            new Given("I am on \"/admin/login\""),
            new When("I fill in \"username\" with \"$username\""),
            new When("I fill in \"password\" with \"123\""),
            new When("I press \"Login\""),
        );
    }

    /**
     * @Given /^the following languages exist$/
     */
    public function followingLanguagesExist(TableNode $languagesTable)
    {
        $this->removeEntities('WshCmsBundle:Language');
        foreach ($languagesTable->getHash() as $languageHash) {
            $lang = new Language();
            $lang->setCode($languageHash['code']);
            $lang->setName($languageHash['name']);
            $this->getManager()->persist($lang);
        }

        $this->getManager()->flush();
    }

    /**
     * @Given /^the following pages exist$/
     */
    public function followingPagesExist(TableNode $pagesTable)
    {
        $this->removeEntities('WshCmsBundle:Page');
        $translationsRepo = $this->get('repository.translation');
        $languages = $this->get('repository.language')->findAll(true);
        foreach ($pagesTable->getHash() as $pageHash) {
            $page = new Page();
            $page->setTitle($pageHash['title']);
            $page->setBody($pageHash['body']);
            foreach ($languages as $code => $name) {
                if ($code === $this->kernel->getContainer()->getParameter('locale')) {
                    continue;
                }
                $translationsRepo->translate($page, 'title', $code, $page->getTitle().' '.$name);
                $translationsRepo->translate($page, 'body', $code, $page->getBody().' '.$name);
            }
            $this->getManager()->persist($page);
        }

        $this->getManager()->flush();
    }

    /**
     * @Given /^there is no "([^"]*)"$/
     */
    public function thereIsNo($repositoryName)
    {
        $this->removeEntities($repositoryName);
    }

    /**
     * @Given /^page with title "([^"]*)" (is|is not) published$/
     */
    public function pageWithTitleIsPublished($title, $not)
    {
        $published = $not === 'is';
        $page = $this->get('repository.page')->findOneByTitle($title);
        if (empty($page)) {
            throw new \Exception("Page with title \"$title\" not found");
        }

        $page->setIsPublished($published);
        $this->getManager()->persist($page);
        $this->getManager()->flush();
    }

    // --- Then ---


    /**
     * @Then /^I should be on "([^"]*)" "([^"]*)" route$/
     */
    public function iShouldBeOnRoute($sonataServiceName, $route)
    {
        $currentUrl = $this->removeQueryFromUrl($this->getMink()->getSession()->getCurrentUrl());
        $expectedUrl = $this->get($sonataServiceName)->generateUrl($route, array(), true);
        if ($currentUrl !== $expectedUrl) {
            throw new \Exception("Url is \"$currentUrl\", but \"$expectedUrl\" expected");
        }
    }

    /**
     * @Then /^I should be on "([^"]*)" "([^"]*)" route of object with "([^"]*)" "([^"]*)"$/
     */
    public function iShouldBeOnRouteOfObject($sonataServiceName, $route, $property, $value)
    {
        $currentUrl = $this->removeQueryFromUrl($this->getMink()->getSession()->getCurrentUrl());
        $admin = $this->get($sonataServiceName);
        $object = $this->getRepository($admin->getClass())->findOneBy(array($property => $value));
        if (empty($object)) {
            throw new \Exception("Object with $property=\"$value\" not found");
        }

        $expectedUrl = $this->get($sonataServiceName)->generateObjectUrl($route, $object, array(), true);
        if ($currentUrl !== $expectedUrl) {
            throw new \Exception("Url is \"$currentUrl\", but \"$expectedUrl\" expected");
        }
    }

    /**
     * @Then /^page with title "([^"]*)" should (be|not be) published$/
     */
    public function pageWithTitleShouldBePublished($title, $not)
    {
        $published = $not === 'be';
        $page = $this->get('repository.page')->findOneByTitle($title);
        if (empty($page)) {
            throw new \Exception("Page with title \"$title\" not found");
        }

        if ($published && !$page->isPublished()) {
            throw new \Exception("Page with title \"$title\" should be published but it is not");
        } else if (!$published && $page->isPublished()) {
            throw new \Exception("Page with title \"$title\" should be not published but it is");
        }
    }
//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        $container = $this->kernel->getContainer();
//        $container->get('some_service')->doSomethingWith($argument);
//    }
//
}
