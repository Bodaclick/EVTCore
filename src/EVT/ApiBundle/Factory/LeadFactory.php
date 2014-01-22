<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\LeadRepositoryInterface;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use Symfony\Bridge\Monolog\Logger;

class LeadFactory
{
    protected $showroomRepo;
    protected $leadRepo;
    protected $logger;

    public function __construct(
        ShowroomRepositoryInterface $showroomRepo,
        LeadRepositoryInterface $leadRepo,
        Logger $logger
    ) {
        $this->showroomRepo = $showroomRepo;
        $this->leadRepo = $leadRepo;
        $this->logger = $logger;
    }

    /**
     *
     * @param User    $user The user that does the lead
     * @param array $lead
     *     'lead' => [
     *          'user' => [
     *              'name' => 'testUserName',
     *              'surname' => 'testUserSurname',
     *              'email' => 'valid@email.com',
     *              'phone' => '+34 0123456789'
     *          ],
     *          'event' => [
     *              'date' => '2015/12/31',
     *              'type' => '1',
     *              'location' => [
     *                  'lat' => 10,
     *                  'long' => 10,
     *                  'admin_level_1' => 'Getafe',
     *                  'admin_level_2' => 'Madrid',
     *                  'country' => 'Spain'
     *              ]
     *          ],
     *          'showroom' => [
     *              'id' => '1'
     *          ]
     *      ]
     *
     *  @return Lead The created lead
     */
    public function createLead(User $user, $lead)
    {
        // Validate the array throw InvalidArgumentException if any error
        $this->validateFirstLevel($lead);
        $this->validateShowroom($lead['showroom']);

        $showroom = $this->showroomRepo->find($lead['showroom']['id']);
        if (null === $showroom) {
            $this->logger->emergency(sprintf('Showroom id %s not found', $lead['showroom']['id']));
            throw new \InvalidArgumentException('Showroom not found');
        }

        // Validate the array throw InvalidArgumentException if any error
        $this->validateEvent($lead['event']);
        $event = new Event(
            new EventType($lead['event']['type']),
            new Location(
                $lead['event']['location']['lat'],
                $lead['event']['location']['long'],
                $lead['event']['location']['admin_level_1'],
                $lead['event']['location']['admin_level_2'],
                $lead['event']['location']['country']
            ),
            new \DateTime($lead['event']['date'], new \DateTimeZone('UTC'))
        );

        try {
            $lead = $user->doLead($showroom, $event);
            $this->leadRepo->save($lead);
        } catch (\Exception $e) {
            $this->logger->emergency('Lead Error, run for your life: ' . $e->getTraceAsString());
        }

        return $lead;
    }

    private function validateFirstLevel($array)
    {
        $arrayValidator = new ArrayValidator(['user', 'event', 'showroom']);
        $arrayValidator->validate($array);
    }

    private function validateShowroom($array)
    {
        $arrayValidator = new ArrayValidator(['id']);
        $arrayValidator->validate($array);
    }

    private function validateEvent($array)
    {
        $arrayValidator = new ArrayValidator(['date', 'type', 'location']);
        $arrayValidator->validate($array);

        $this->validateLocation($array['location']);
    }

    private function validateLocation($array)
    {
        $arrayValidator = new ArrayValidator(['lat', 'long', 'admin_level_1', 'admin_level_2', 'country']);
        $arrayValidator->validate($array);
    }
}
