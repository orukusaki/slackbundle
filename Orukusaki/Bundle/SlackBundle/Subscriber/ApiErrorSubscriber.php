<?php

namespace Orukusaki\Bundle\SlackBundle\Subscriber;

use Guzzle\Common\Event;
use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ApiErrorSubscriber
 * @package Orukusaki\Bundle\SlackBundle\Subscriber
 */
class ApiErrorSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return ['request.sent' => ['onRequestSent', -1]];
    }

    /**
     * @param Event $event
     */
    public function onRequestSent(Event $event)
    {
        $body = json_decode($event['response']->getBody(true), true);

        if (!isset($body['ok']) || !$body['ok']) {

            throw BadResponseException::factory($event['request'], $event['response']);
        }
    }
}
