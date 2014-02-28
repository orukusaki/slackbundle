<?php
namespace Orukusaki\Bundle\SlackBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CommandRecievedEvent extends Event
{
    const KEY = 'slack.command.recieved';

    protected $command = [];
    protected $response = '';

    public function __construct($request)
    {
        $this->command = [
            'token'        => $request->get('token'),
            'team_id'      => $request->get('team_id'),
            'channel_id'   => $request->get('channel_id'),
            'channel_name' => $request->get('channel_name'),
            'user_id'      => $request->get('user_id'),
            'user_name'    => $request->get('user_name'),
            'command'      => $request->get('command'),
            'text'         => $request->get('text'),
        ];
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        if ('' != $response) {
            $this->stopPropagation();
        }
        $this->response = $response;
    }

    public function getCommand()
    {
        return $this->command;
    }
}
