<?php
namespace Orukusaki\Bundle\SlackBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class MessageReceivedEvent
 * @package Orukusaki\Bundle\SlackBundle\Event
 */
class MessageReceivedEvent extends Event
{
    const KEY = 'slack.message.received';

    /**
     * @var string
     */
    protected $message;
    /**
     * @var string
     */
    protected $channel;

    /**
     * @param $message
     * @param $channel
     */
    public function __construct($message, $channel)
    {
        $this->message = $message;
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
