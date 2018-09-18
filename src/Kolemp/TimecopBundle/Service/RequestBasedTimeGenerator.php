<?php

namespace Kolemp\TimecopBundle\Service;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Kolemp\TimecopBundle\Event\TimecopTravelInvoked;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RequestBasedTimeGenerator.
 */
class RequestBasedTimeGenerator
{
    const COOKIE_NAME = 'fakeTime';
    const QUERY_PARAMETER_NAME = 'fakeTime';
    const HEADER_NAME = 'X-FAKETIME';

    /*
     * bool
     */
    private $enabled;
    private $readCookie;
    private $readQueryParameter;
    private $readHeader;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * RequestBasedTimeGenerator constructor.
     *
     * @param bool $enabled
     * @param bool $readQueryParameter
     * @param bool $readCookie
     * @param bool $readHeader
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct($enabled, $readQueryParameter, $readCookie, $readHeader, EventDispatcherInterface $eventDispatcher)
    {
        $this->enabled = $enabled;
        $this->readCookie = $readCookie;
        $this->readQueryParameter = $readQueryParameter;
        $this->eventDispatcher = $eventDispatcher;
        $this->readHeader = $readHeader;
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

        if ($this->readHeader) {
            if ($request->headers->get(static::HEADER_NAME, null) !== null) {
                $fakeTimeString = $request->headers->get(static::HEADER_NAME);
            }
        }

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

        if ($fakeTimeString === null || $fakeTimeString === "disabled") {
            return;
        }

        $fakeTime = strtotime(urldecode($fakeTimeString));

        if ($fakeTime === false) {
            throw new \InvalidArgumentException('Given fake time is invalid: '.$fakeTimeString);
        }

        if (!function_exists('timecop_travel')) {
            throw new \ErrorException('Timecop module not loaded. Timetravels not possible');
        }

        timecop_travel($fakeTime);
        
        $this->eventDispatcher->dispatch(
            TimecopTravelInvoked::NAME,
            new TimecopTravelInvoked(
                new \DateTime()
            )
        );
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        if ($request->get(static::QUERY_PARAMETER_NAME, null) !== null) {
            $fakeTime = $request->get(static::QUERY_PARAMETER_NAME);

            if ($fakeTime !== "disabled") {
                $cookie = new Cookie(static::COOKIE_NAME, $fakeTime);
                $response->headers->setCookie($cookie);
            } else {
                $response->headers->clearCookie(static::COOKIE_NAME);
            }
        }

    }
}
