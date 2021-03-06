<?php

namespace Orukusaki\Bundle\SlackBundle\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Guzzle\Http\Message\Response as GuzzleResponse;
use Guzzle\Plugin\Mock\MockPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Slack context.
 */
class SlackContext implements Context
{
    use KernelDictionary;

    /**
     * @var MockPlugin
     */
    protected $plugin;
    protected $slashUrl;
    protected $webhookUrl;
    protected $channel;
    protected $response;
    protected $identity;
    protected $emoji;

    /**
     * @BeforeScenario
     */
    public function prepare()
    {
        $container = $this->getContainer();
        $slack = $container->get('slack.client');
        $this->plugin = new MockPlugin();
        $slack->addSubscriber($this->plugin);

        $this->identity = $container->getParameter('slack.identity');

        $router = $container->get('router');

        $this->slashUrl = $router->generate('orukusaki_slack_slashcommand_command');
        $this->webhookUrl = $router->generate('orukusaki_slack_webhook_command');
    }

    /**
     * @Given /^Slack will respond ok to all requests$/
     */
    public function slackRespondsOk()
    {
        $this->plugin->addResponse($this->getSuccessResponse());
        $this->plugin->getEventDispatcher()->addListener(
            'mock.request',
            function () {
                $this->plugin->addResponse($this->getSuccessResponse());
            }
        );
    }

    /**
     * @Given /^I am in channel "([^"]*)"$/
     */
    public function iAmInChannel($channel)
    {
        $this->channel = $channel;
    }

    /**
     * @Given /^I send the slash command "([^"]*)"$/
     */
    public function iSendTheSlashCommand($command)
    {
        $parts = explode(' ', $command);
        $cmd = array_shift($parts);
        $text = implode(' ', $parts);


        $request = Request::create(
            $this->slashUrl,
            'POST',
            [
                'channel_id' => $this->channel,
                'command'    => $cmd,
                'text'       => $text,
            ]
        );
        $this->response = $this->getKernel()->handle($request);
    }

    /**
     * @Given /^I say "([^"]*)"$/
     */
    public function iSay($text)
    {
        $request = Request::create(
            $this->webhookUrl,
            'POST',
            [
                'channel_id' => $this->channel,
                'text'       => $text,
            ]
        );
        $this->response = $this->getKernel()->handle($request);
    }

    /**
     * @Then /^I should see the response:$/
     */
    public function iShouldSeeTheResponse(PyStringNode $string)
    {
        if ($this->response->getContent() != (string) $string) {
            throw new \Exception(sprintf('Unexpected Response: %s', $this->response->getContent()));
        }
    }

    /**
     * @Given /^I should not see a direct response$/
     */
    public function iShouldNotSeeADirectResponse()
    {
        $this->iShouldSeeTheResponse(new PyStringNode([], 0));
    }

    /**
     * @Then /^I should see in the channel:$/
     */
    public function iShouldSeeInTheChannel(PyStringNode $string)
    {
        $requests = $this->plugin->getReceivedRequests();

        if (!count($requests)) {
            throw new \Exception('No Requests were sent');
        }
        if ($requests[0]->getPostField('channel') != $this->channel) {
            throw new \Exception(sprintf('Wrong or missing channel: %s', $requests[0]->getPostField('channel')));
        }

        if ($requests[0]->getPostField('text') != (string) $string) {
            throw new \Exception(sprintf('Unexpected text sent: %s', $requests[0]->getPostField('text')));
        }
    }

    /**
     * @Given /^the message identity should match the defaults$/
     */
    public function theMessageIdentityShouldMatchTheDefaults()
    {
        $requests = $this->plugin->getReceivedRequests();

        if ($requests[0]->getPostField('username') != $this->identity['username']) {
            throw new \Exception(sprintf('Unexpected username: %s', $requests[0]->getPostField('username')));
        }

        if ($requests[0]->getPostField('icon_emoji') != $this->identity['icon_emoji']) {
            throw new \Exception(sprintf('Unexpected emoji: %s', $requests[0]->getPostField('icon_emoji')));
        }
    }

    /**
     * @Given /^I should not see a message in the channel$/
     */
    public function iShouldNotSeeAMessageInTheChannel()
    {
        $requests = $this->plugin->getReceivedRequests();

        if (count($requests)) {
            throw new \Exception('Did not expect to see any API calls made.');
        }
    }

    private function getSuccessResponse()
    {
        return new GuzzleResponse(200, null, '{"ok":true}');
    }
}
