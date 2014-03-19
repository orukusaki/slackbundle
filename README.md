Slack Bundle
=================

This bundle provides

 - A Guzzle client to access the Slack API
 - A Cli command to monitor a group on an event loop, dispatching events to handle messages received.
 - Controllers for recieving slash commands and outgoing webhooks
 - A Behat context class to help with functional testing of your bot

This bundle is very much in alpha status

Installation
-------

    composer require 'orukusaki/slackbundle'

Add these lines you your AppKernal::registerBundles

    ...
    new Orukusaki\Bundle\SlackBundle\OrukusakiSlackBundle(),
    new Misd\GuzzleBundle\MisdGuzzleBundle(),
    ...

Add config to config.yml:

    orukusaki_slack:
        api_key: <Your API Key>
        identity:
            username: My Api Bot
            emoji: ":space_invader:"

Recieving Slash commands and webhooks
-------------------------------------

Add the bundle to your routing.yml

    orukusaki_slack:
        resource: "@OrukusakiSlackBundle/Controller/"
        type:     annotation
        prefix:   /slack

In the Slack Integrations page, create a slash command pointing to <domain>/slack/slashcommand or a webhook pointing to <domain>/slack/webhook

Running as a Bot
----------------

  app/console slack:run < group name >

Adding your own commands
------------------------

Add an Event Listener which will be triggered every time a message is received.  There are a couple of examples in the services.xml for this bundle:

    <service id="slack.listener.sayhi" class="Orukusaki\Bundle\SlackBundle\Listener\SayHiListener">
        <argument type="service" id="slack.client" />
        <tag name="kernel.event_listener" event="slack.message.received" method="handleMessageEvent" />
    </service>

To see what commands you can run against the API, have a look at Resources/config/webservices.xml

Behat Context
-------------
This bundle includes a [Behat](http://behat.org/) context to help you create functional tests for your bot. To use it, you much first enable the Symfony2 Behat extension in your behat.yml:

    composer require "behat/symfony2-extension" "*"

In behat.yml:

    default:
        extensions:
            Behat\Symfony2Extension\Extension: ~

Then in the constructor of your FeatureContext, import the SlackContext:

    $this->useContext('slack', new \Orukusaki\Bundle\SlackBundle\Context\SlackContext());

Use

    bin/behat -dl

To see the steps now available to you.

Contributing
============
Any bug reports / feature requests should be raised on this [github page](https://github.com/orukusaki/slackbundle/issues)

PRs are welcome, please branch from master, and run your code through phpcs (PSR-2 standard) and phpcs-fixer before submitting.
