<?php

namespace spec\Orukusaki\Bundle\SlackBundle\Event;

use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;

class CommandRecievedEventSpec extends ObjectBehavior
{
    public function let()
    {
        $request = new ParameterBag;
        $request->set('token', '12345678');
        $request->set('team_id', 'team');
        $request->set('channel_id', 'CX12345');
        $request->set('channel_name', 'My Channel');
        $request->set('user_id', 'user_1234');
        $request->set('user_name', 'myName');
        $request->set('command', '/slash');
        $request->set('text', 'command');

        $this->beConstructedWith($request);
    }

    public function it_is_an_event()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\Event');
    }

    public function it_stops_propagation_when_response_set()
    {
        $this->isPropagationStopped()->shouldReturn(false);
        $this->setResponse('A nice response');
        $this->isPropagationStopped()->shouldReturn(true);
        $this->getResponse()->shouldReturn('A nice response');
    }

   public function it_exposes_a_command()
    {
        $this->getCommand()->shouldReturn(
            [
                'token'        => '12345678',
                'team_id'      => 'team',
                'channel_id'   => 'CX12345',
                'channel_name' => 'My Channel',
                'user_id'      => 'user_1234',
                'user_name'    => 'myName',
                'command'      => '/slash',
                'text'         => 'command',
            ]
        );
    }
}
