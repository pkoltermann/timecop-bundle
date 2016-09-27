<?php

namespace Kolemp\TimecopBundle\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestBasedTimeGenerator.
 */
class RequestBasedTimeGenerator
{
    /*
     * string
     */
    private $environment;

    /**
     * RequestBasedTimeGenerator constructor.
     *
     * @param $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \ErrorException
     */
    public function onRequest(GetResponseEvent $event)
    {
        if ($this->environment !== 'dev' || !$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $fakeTimeString = $request->get('fakeTime');
        if ($fakeTimeString === null) {
            return;
        }

        $fakeTime = strtotime($fakeTimeString);

        if ($fakeTime === false) {
            throw new \InvalidArgumentException('Given fake time is invalid: '.$fakeTimeString);
        }

        if (!function_exists('timecop_travel')) {
            throw new \ErrorException('Timecop module not loaded. Timetravels not possible');
        }

        timecop_travel($fakeTime);
    }
}
