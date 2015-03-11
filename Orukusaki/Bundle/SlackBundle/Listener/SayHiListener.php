<?php
namespace Orukusaki\Bundle\SlackBundle\Listener;

use Orukusaki\Bundle\SlackBundle\Event\MessageReceivedEvent;

/**
 * Class SayHiListener
 * @package Orukusaki\Bundle\SlackBundle\Listener
 */
class SayHiListener
{
    /**
     * @var
     */
    protected $client;

    /**
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * @param MessageReceivedEvent $event
     */
    public function handleMessageEvent(MessageReceivedEvent $event)
    {
        $message = $event->getMessage();

        if (strpos($message['text'], 'bot: hi') === 0) {
            $this->client->postMessage(
                [
                    'channel' => $event->getChannel(),
                    'text'    => 'Hi <@' . $message['user'] . '>',
                ]
            );
        }
    }
}
