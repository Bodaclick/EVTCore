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
    private $score;
    private $provider;
    private $vertical;
    
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
    
    public function getProvider()
    {
        return $this->provider;
    }
    
    public function getVertical()
    {
        return $this->vertical;
    }
    
    public function getUrl()
    {
        $slug = $this->slug;
        if (!$this->slug) {
            $slug = $this->provider->getSlug();
        }
        
        return $this->vertical->getDomain() . '/' . $slug;        
    }
    
    public function getScore()
    {
        return $this->score;
    }
}
