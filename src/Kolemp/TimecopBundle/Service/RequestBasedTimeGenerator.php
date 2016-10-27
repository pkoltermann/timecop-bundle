<?php

namespace Kolemp\TimecopBundle\Service;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestBasedTimeGenerator.
 */
class RequestBasedTimeGenerator
{
    const COOKIE_NAME = 'fakeTime';
    const QUERY_PARAMETER_NAME = 'fakeTime';

    /*
     * bool
     */
    private $enabled;
    private $readCookie;
    private $readQueryParameter;

    /**
     * RequestBasedTimeGenerator constructor.
     *
     * @param bool $enabled
     * @param bool $readQueryParameter
     * @param bool $readCookie
     */
    public function __construct($enabled, $readQueryParameter, $readCookie)
    {
        $this->enabled = $enabled;
        $this->readCookie = $readCookie;
        $this->readQueryParameter = $readQueryParameter;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \ErrorException
     */
    public function onRequest(GetResponseEvent $event)
    {
        if (!$this->enabled || !$event->isMasterRequest()) {
            return;
        }

        $fakeTimeString = null;
        $request = $event->getRequest();

        if ($this->readCookie) {
            $cookies = $request->cookies;
            if ($cookies->has(static::COOKIE_NAME)) {
                $fakeTimeString = $cookies->get(static::COOKIE_NAME);
            }
        }

        if ($this->readQueryParameter) {
            if ($request->get(static::QUERY_PARAMETER_NAME, null) !== null) {
                $fakeTimeString = $request->get(static::QUERY_PARAMETER_NAME);
            }
        }

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
