<?php

namespace spec\Orukusaki\Bundle\SlackBundle\Subscriber;

use Guzzle\Common\Event;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use PhpSpec\ObjectBehavior;

class ApiErrorSubscriberSpec extends ObjectBehavior
{
    public function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    public function it_subscribes_to_sent_event()
    {
        $this::getSubscribedEvents()->shouldReturn(['request.sent' => ['onRequestSent', -1]]);
    }

    public function it_raises_exception_on_non_ok_response(RequestInterface $request)
    {
        $response = new Response(200, null, '{"ok":false,"error":"something_bad"}');
        $event = new Event;
        $event['request'] = $request->getWrappedObject();
        $event['response'] = $response;

        $this
            ->shouldThrow('\\Guzzle\\Http\\Exception\\BadResponseException')
            ->duringOnRequestSent($event);
    }

    public function it_raises_exception_on_non_json_response(RequestInterface $request)
    {
        $response = new Response(200, null, 'blah');
        $event = new Event;
        $event['request'] = $request->getWrappedObject();
        $event['response'] = $response;

        $this
            ->shouldThrow('\\Guzzle\\Http\\Exception\\BadResponseException')
            ->duringOnRequestSent($event);
    }

    public function it_raises_no_exception_on_ok_response(RequestInterface $request)
    {
        $response = new Response(200, null, '{"ok":true,"error":"something_good"}');
        $event = new Event;
        $event['request'] = $request->getWrappedObject();
        $event['response'] = $response;

        $this
            ->shouldNotThrow('\\Guzzle\\Http\\Exception\\BadResponseException')
            ->duringOnRequestSent($event);
    }
}
