<?php

namespace spec\Orukusaki\Bundle\SlackBundle\Event;

use PhpSpec\ObjectBehavior;

class MessageReceivedEventSpec extends ObjectBehavior
{
    public function it_is_an_event()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\Event');
    }

    public function let()
    {
        $this->beConstructedWith(array('text' => 'hi'), 'CK12345');
    }

    public function it_should_expose_message_and_channel()
    {
        $this->getMessage()->shouldReturn(array('text' => 'hi'));
        $this->getChannel()->shouldReturn('CK12345');
    }
}
