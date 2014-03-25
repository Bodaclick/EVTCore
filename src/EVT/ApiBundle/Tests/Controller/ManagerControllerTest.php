<?php

namespace EVT\ApiBundle\Tests\Controller;

use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use FOS\RestBundle\Util\Codes;
use EVT\CoreDomainBundle\Entity\GenericUser as User;

/**
 * LeadControllerTest
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ManagerControllerTest extends WebTestCase
{
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;
    protected $header;

    /**
     * Create a client to test request and mock services
     */
    public function setUp()
    {
        $this->client = static::createClient();
        $this->header = ['Content-Type' => 'application/x-www-form-urlencoded', 'HTTP_ACCEPT' => 'application/json'];

    }

    public function mockContainer()
    {
        $userManager = $this->getMockBuilder('FOS\UserBundle\Model\UserManager')->disableOriginalConstructor()
            ->getMock();
        $userManager->expects($this->once())->method('createUser')->will($this->returnValue(new User()));
        $userManager->expects($this->once())->method('updateUser')->will(
            $this->returnCallback(
                function ($entity) {
                    $rflUser = new \ReflectionClass($entity);
                    $rflId = $rflUser->getProperty('id');
                    $rflId->setAccessible(true);
                    $rflId->setValue($entity, 1);
                }
            )
        );

        $this->client->getContainer()->set('fos_user.user_manager', $userManager);
    }

    public function testCreateManager()
    {
        $params = [
            'user' => [
                'email' => 'valid@email.com',
                'username' => 'username_manager',
                'plainPassword' => ['first' => '1234', 'second' => '1234']
            ]
        ];

        $this->mockContainer();
        $this->client->request('POST', '/api/managers?apikey=apikeyValue', $params, [], $this->header);

        $this->assertEquals(
            Codes::HTTP_CREATED,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );

        $this->assertRegExp(
            '/\/api\/managers\/\d+/',
            json_decode($this->client->getResponse()->getContent(), true)['user'],
            $this->client->getResponse()->getContent()
        );
    }

    public function managersProvider()
    {
        return [
            [
                [
                    'user' => [
                        'name'     => 'name',
                        'email'    => 'valid@email.com',
                        'password' => 'pass'
                    ],
                ]
            ]
        ];
    }

    /**
     * @dataProvider managersProvider
     */
    public function testManagerDataIsInvalid($params)
    {
        $this->client->request(
            'POST',
            '/api/managers?apikey=apikeyValue',
            $params,
            [],
            $this->header
        );

        $this->assertEquals(
            Codes::HTTP_BAD_REQUEST,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
    }

    public function testManagerAlreadyExists()
    {
        $params = [
            'user' => [
                'email' => 'valid@email.com',
                'username' => 'username_manager',
                'plainPassword' => ['first' => '1234', 'second' => '1234']
            ]
        ];

        $formMock = $this->getMockBuilder('EVT\CoreDomainBundle\Form\Type\GenericUserFormType')
            ->setMethods(['setData', 'handleRequest', 'isValid'])->disableOriginalConstructor()->getMock();
        $formMock->expects($this->once())->method('setData')->will($this->returnValue(true));
        $formMock->expects($this->once())->method('handleRequest')->will($this->returnValue(true));
        $formMock->expects($this->once())->method('isValid')->will($this->returnValue(false));

        $factoryMock = $this->getMockBuilder('Symfony\Component\Form\FormFactory')->disableOriginalConstructor()
            ->getMock();
        $factoryMock->expects($this->once())->method('create')->will($this->returnValue($formMock));

        $dUser = $this->getMockBuilder('EVT\CoreDomain\User\User')->disableOriginalConstructor()->getMock();
        $dUser->expects($this->once())->method('getId')->will($this->returnValue(1));

        $userManager = $this->getMockBuilder('FOS\UserBundle\Model\UserManager')->disableOriginalConstructor()
            ->getMock();
        $userManager->expects($this->once())->method('createUser')->will($this->returnValue(new User()));
        $userManager->expects($this->never())->method('updateUser');

        $userRepo = $this->getMockBuilder('EVT\CoreDomainBundle\Repository\UserRepository')
            ->disableOriginalConstructor()->setMethods(['findOneByEmail'])->getMock();
        $userRepo->expects($this->once())->method('findOneByEmail')->will($this->returnValue($dUser));

        $this->client->getContainer()->set('fos_user.user_manager', $userManager);
        $this->client->getContainer()->set('form.factory', $factoryMock);
        $this->client->getContainer()->set('evt.repository.user', $userRepo);

        $this->client->request('POST', '/api/managers?apikey=apikeyValue', $params, [], $this->header);

        $this->assertEquals(
            Codes::HTTP_CONFLICT,
            $this->client->getResponse()->getStatusCode(),
            $this->client->getResponse()->getContent()
        );
        $this->assertRegExp(
            '/\/api\/managers\/\d+/',
            json_decode($this->client->getResponse()->getContent(), true)['user'],
            $this->client->getResponse()->getContent()
        );
    }
}
