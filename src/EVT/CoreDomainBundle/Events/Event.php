<?php

namespace EVT\CoreDomainBundle\Events;

final class Event
{
    const ON_CREATE_SHOWROOM = 'evt.event.showroom_create';
    const ON_UPDATE_SHOWROOM = 'evt.event.showroom_update';
    const ON_CREATE_LEAD = 'evt.event.lead_create';
    const ON_UPDATE_LEAD = 'evt.event.lead_update';
    const ON_CREATE_USER = 'evt.event.user_create';
    const ON_UPDATE_USER = 'evt.event.user_update';
    const ON_CREATE_MANAGER = 'evt.event.manager_create';
    const ON_CREATE_EMPLOYEE = 'evt.event.employee_create';
}
