<?php

namespace Orukusaki\Bundle\SlackBundle\Subscriber;

use Guzzle\Common\Event;
use Guzzle\Http\Exception\BadResponseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApiErrorSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array('request.sent' => array('onRequestSent', -1));
    }

    public function onRequestSent(Event $event)
    {
        $body = json_decode($event['response']->getBody(true), true);

        if (!isset($body['ok']) || !$body['ok']) {

            throw BadResponseException::factory($event['request'], $event['response']);
        }
    }
}
