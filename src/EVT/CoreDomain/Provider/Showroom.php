<?php

namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\InformationBag;

/**
 * Showroom
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class Showroom
{
    private $slug;
    private $score;
    private $provider;
    private $vertical;
    private $informationBag;
    private $id;
    private $extraData;

    public function __construct(
        Provider $provider,
        Vertical $vertical,
        $score = 0,
        InformationBag $informationBag = null,
        $extraData = ''
    ) {

        $this->provider = $provider;
        $this->vertical = $vertical;
        $this->score = (int)$score;
        $this->informationBag = ($informationBag) ? $informationBag : new InformationBag();
        $this->extraData = $extraData;
    }

    public function getId()
    {
        return $this->id;
    }

    public function changeSlug($slug)
    {
        return $this->informationBag->set('slug', $slug);
    }

    public function changeName($name)
    {
        return $this->informationBag->set('name', $name);
    }

    public function changePhone($phone)
    {
        return $this->informationBag->set('phone', $phone);
    }

    public function getSlug()
    {
        return $this->informationBag->get('slug', $this->provider->getSlug());
    }

    public function getPhone()
    {
        return $this->informationBag->get('phone', $this->provider->getPhone());
    }

    public function getName()
    {
        return $this->informationBag->get('name', $this->provider->getName());
    }

    public function getUrl()
    {
        return $this->vertical->getDomain() . '/' . $this->informationBag->get('slug', $this->provider->getSlug());
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getVertical()
    {
        return $this->vertical;
    }

    public function belongsToProvider(Provider $provider)
    {
        return $this->provider->getId() === $provider->getId();
    }

    public function getScore()
    {
        return $this->score;
    }

    public function getExtraData()
    {
        return $this->extraData;
    }
}
