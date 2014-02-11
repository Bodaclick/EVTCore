<?php
namespace EVT\ApiBundle\Tests\Command;

use EVT\ApiBundle\Command\AddUrlToHookCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * AddUrlToHookCommandTest
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class AddUrlToHookCommandTest extends WebTestCase
{
    protected $application;

    protected $container;

    /**
     * setUp
     */
    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->application = new Application($kernel);
        $this->container = $kernel->getContainer();

        // Create the empty db structure
        $this->loadFixtures([]);

        $this->application->add(new AddUrlToHookCommand());
    }

    /**
     * executeCommand - Executes a Symfony 2 Console command
     *
     * @param string $commandName
     * @param array  $options
     *
     * @return \Symfony\Component\Console\Tester\CommandTester;
     */
    protected function executeCommand($commandName, $options = array())
    {
        $command = $this->application->find($commandName);
        $commandTester = new CommandTester($command);
        $defaultOptions = ['command' => $command->getName()];

        $options = array_merge($defaultOptions, $options);

        $commandTester->execute($options);

        return $commandTester;
    }

    public function testAddUrl()
    {
        $commandTester = $this->executeCommand(
            'evt:hooks:add',
            ['event' => 'evt.event.showroom_create', 'url' => urlencode('http://my.hooks.url/?apikey=api&parameter=2')]
        );

        $this->assertRegExp(
            '/^Url: http:\\/\\/my.hooks.url\\/\?apikey=api&parameter=2 added to the event: evt.event.showroom_create/',
            $commandTester->getDisplay()
        );

        $hooksRepo = $this->container->get('doctrine.orm.default_entity_manager')
            ->getRepository('EVTEvtApplication:Hook');

        $hooks = $hooksRepo->findAll();
        $this->assertCount(1, $hooks);
    }

    public function testAddUnexistingEvent()
    {
        $commandTester = $this->executeCommand(
            'evt:hooks:add',
            ['event' => 'user_created', 'url' => urlencode('http://my.hooks.url/?apikey=api&parameter=2')]
        );

        $this->assertRegExp(
            '/^Fail/',
            $commandTester->getDisplay()
        );

        $hooksRepo = $this->container->get('doctrine.orm.default_entity_manager')
            ->getRepository('EVTEvtApplication:Hook');

        $hooks = $hooksRepo->findAll();
        $this->assertCount(0, $hooks);
    }
}
