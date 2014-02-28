<?php

namespace Orukusaki\Bundle\SlackBundle\Controller;

use Orukusaki\Bundle\SlackBundle\Event\CommandRecievedEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/slashcommand", service="slack.slash.command.controller")
 */
class SlashCommandController extends Controller
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
        $commandEvent = new CommandRecievedEvent($request);
        $this->dispatcher->dispatch(CommandRecievedEvent::KEY, $commandEvent);
        $response = new Response($commandEvent->getResponse());

        return $response;
    }
}
