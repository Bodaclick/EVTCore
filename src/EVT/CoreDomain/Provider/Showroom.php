<?php
namespace EVT\CoreDomain\Provider;

/**
 * Showroom
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class Showroom
{
    private $slug;
    private $phone;
    private $name;
    private $score;
    private $provider;
    private $vertical;
    private $id;

    public function __construct(Provider $provider, Vertical $vertical, $score = 0)
    {
        $this->provider = $provider;
        $this->vertical = $vertical;
        $this->score = $score;
    }

    public function changeSlug($slug)
    {
        $this->slug = $slug;
    }

    public function changeName($name)
    {
        $this->name = $name;
    }

    public function changePhone($phone)
    {
        $this->phone = $phone;
    }

    public function getSlug()
    {
        return ($this->slug)?:$this->provider->getSlug();
    }

    public function getPhone()
    {
        return ($this->phone)?:$this->provider->getPhone();
    }

    public function getName()
    {
        return ($this->name)?:$this->provider->getName();
    }

    public function getUrl()
    {
        $slug = $this->slug;
        if (!$this->slug) {
            $slug = $this->provider->getSlug();
        }

        return $this->vertical->getDomain() . '/' . $slug;
    }

    public function belongsToProvider(Provider $provider)
    {
        return $this->provider->getId() === $provider->getId();
    }

    public function getScore()
    {
        return $this->score;
    }
}
