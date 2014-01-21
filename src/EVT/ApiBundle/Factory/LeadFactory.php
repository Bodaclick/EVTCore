<?php

namespace EVT\ApiBundle\Factory;

use Symfony\Component\Validator\Constraints\All;
use EVT\CoreDomain\Lead\Location;
use EVT\CoreDomain\Lead\Lead;
use EVT\CoreDomain\Lead\EventType;
use EVT\CoreDomain\Lead\Event;
use EVT\CoreDomain\Lead\LeadRepositoryInterface;
use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\Provider\ShowroomRepositoryInterface;
use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;

class LeadFactory
{
    protected $showroomRepo;
    protected $leadRepo;

    public function __construct(ShowroomRepositoryInterface $showroomRepo, LeadRepositoryInterface $leadRepo)
    {
        $this->showroomRepo = $showroomRepo;
        $this->leadRepo = $leadRepo;
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
        $showroom = $this->showroomRepo->find($lead['showroom']['id']);

        $event = new Event(
            new EventType('wedding'),
            new Location(
                $lead['event']['location']['lat'],
                $lead['event']['location']['long'],
                $lead['event']['location']['admin_level_1'],
                $lead['event']['location']['admin_level_2'],
                $lead['event']['location']['country']
            ),
            new \DateTime($lead['event']['date'], new \DateTimeZone('UTC'))
        );

        $lead = $user->doLead($showroom, $event);
        $this->leadRepo->save($lead);

        return $lead;
    }
}
