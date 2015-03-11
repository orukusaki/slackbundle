<?php
namespace Orukusaki\Bundle\SlackBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class CommandReceivedEvent
 * @package Orukusaki\Bundle\SlackBundle\Event
 */
class CommandReceivedEvent extends Event
{
    const KEY = 'slack.command.received';

    /**
     * @var array
     */
    protected $command = array();

    /**
     * @var string
     */
    protected $response = '';

    /**
     * @param ParameterBag $request
     */
    public function __construct(ParameterBag $request)
    {
        $this->command = array(
            'token'        => $request->get('token'),
            'team_id'      => $request->get('team_id'),
            'channel_id'   => $request->get('channel_id'),
            'channel_name' => $request->get('channel_name'),
            'user_id'      => $request->get('user_id'),
            'user_name'    => $request->get('user_name'),
            'command'      => $request->get('command'),
            'text'         => $request->get('text'),
        );
    }

    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        if ('' != $response) {
            $this->stopPropagation();
        }
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getCommand()
    {
        return $this->command;
    }
}
