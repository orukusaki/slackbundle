<?php
namespace Orukusaki\Bundle\SlackBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CommandRecievedEvent extends Event
{
    const KEY = 'slack.command.recieved';

    public $token;
    public $team_id;
    public $channel_id;
    public $channel_name;
    public $user_id;
    public $user_name;
    public $command;
    public $text;

    protected $response = '';

    public static function fromRequest($request)
    {
        $e = new self;
        $e->token        = $request->get('token');
        $e->team_id      = $request->get('team_id');
        $e->channel_id   = $request->get('channel_id');
        $e->channel_name = $request->get('channel_name');
        $e->user_id      = $request->get('user_id');
        $e->user_name    = $request->get('user_name');
        $e->command      = $request->get('command');
        $e->text         = $request->get('text');

        return $e;
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
}
