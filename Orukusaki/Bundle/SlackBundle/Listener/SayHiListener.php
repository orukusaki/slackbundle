<?php
namespace Orukusaki\Bundle\SlackBundle\Listener;

class SayHiListener
{
    protected $client;

    public function __construct($client, $identity)
    {
        $this->client = $client;
        $this->identity = $identity;
    }

    public function handleMessageEvent($event)
    {
        $message = $event->getMessage();

        if (strpos($message['text'], 'bot: hi') === 0) {
            $this->client->postMessage(
                [
                    'channel'    => $event->getChannel(),
                    'text'       => 'Hi <@' . $message['user'] . '>',
                    'username'   => $this->identity->username,
                    'icon_emoji' => $this->identity->emoji,
                ]
            );
        }
    }
}
