<?php

namespace Kolemp\TimecopBundle\Event;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TimecopTravelInvoked
 */
class TimecopTravelInvoked extends Event
{
    const NAME = 'timecop_bundle.timecop_travel_invoked';

    /**
     * @var \DateTime
     */
    private $newDateTime;

    /**
     * TimecopTravelInvoked constructor.
     * @param \DateTime $newDateTime
     */
    public function __construct(\DateTime $newDateTime)
    {
        $this->newDateTime = $newDateTime;
    }

    /**
     * @return \DateTime
     */
    public function getNewDateTime()
    {
        return $this->newDateTime;
    }
}