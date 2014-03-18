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
    private $id;
    private $slug; // Only use for serialize
    private $name; // Only use for serialize
    private $phone; // Only use for serialize
    private $score;
    private $type;
    private $provider;
    private $vertical;
    private $informationBag;
    private $extraData;

    public function __construct(
        Provider $provider,
        Vertical $vertical,
        ShowroomType $type,
        InformationBag $informationBag = null,
        $extraData = ''
    ) {
        $this->provider = $provider;
        $this->vertical = $vertical;
        $this->changeType($type);
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

    public function getType()
    {
        return $this->type;
    }

    public function getExtraData()
    {
        return $this->extraData;
    }

    public function changeType(ShowroomType $newType)
    {
        $this->type = $newType;
        $this->updateScoreFromType();
    }

    private function updateScoreFromType()
    {
        switch ($this->type->getType()) {
            case ShowroomType::FREE:
                $this->score = 0;
                break;
            default:
                $this->score = 1;
        }
    }
}
