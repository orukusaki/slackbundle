<?php

namespace Orukusaki\Bundle\SlackBundle\Controller;

use Orukusaki\Bundle\SlackBundle\Event\MessageRecievedEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/webhook", service="slack.webhook.controller")
 */
class WebhookController extends Controller
{
    protected $dispatcher;

    public function __construct($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route()
     * @Method("POST")
     */
    public function commandAction(Request $request)
    {
        $event = new MessageRecievedEvent(
            [
                'token'        => $request->get('token'),
                'team_id'      => $request->get('team_id'),
                'channel_name' => $request->get('channel_name'),
                'user_id'      => $request->get('user_id'),
                'user_name'    => $request->get('user_name'),
                'timestamp'    => $request->get('timestamp'),
                'text'         => $request->get('text'),
            ],
            $request->get('channel_id')
        );

        $this->dispatcher->dispatch(MessageRecievedEvent::KEY, $event);

        return  new Response();
    }
}
