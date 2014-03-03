<?php

namespace Orukusaki\Bundle\SlackBundle\Context;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Guzzle\Plugin\Mock\MockPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * Slack context.
 */
class SlackContext extends BehatContext
{
    use KernelDictionary;

    /**
     * @BeforeScenario
     */
    public function prepare()
    {
        $slack = $this->getContainer()->get('slack.client');
        $this->plugin = new MockPlugin();
        $slack->addSubscriber($this->plugin);
        $this->identity = $this->getContainer()->get('slack.identity');
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

        $this->plugin->addResponse(new \Guzzle\Http\Message\Response(200));

        $request = Request::create(
            '/slack/slashcommand',
            'POST',
            array(
                'channel_id' => $this->channel,
                'command'    => $cmd,
                'text'       => $text,
            )
        );
        $this->response = $this->getKernel()->handle($request);
    }

    /**
     * @Given /^I say "([^"]*)"$/
     */
    public function iSay($text)
    {
        $this->plugin->addResponse(new \Guzzle\Http\Message\Response(200));

        $request = Request::create(
            '/slack/webhook',
            'POST',
            array(
                'channel_id' => $this->channel,
                'text'       => $text,
            )
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
        $this->iShouldSeeTheResponse(new PyStringNode(''));
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

        if ($requests[0]->getPostField('username') != $this->identity->username) {
            throw new \Exception(sprintf('Unexpected username: %s', $requests[0]->getPostField('username')));
        }

        if ($requests[0]->getPostField('icon_emoji') != $this->identity->emoji) {
            throw new \Exception(sprintf('Unexpected emoji: %s', $requests[0]->getPostField('icon_emoji')));
        }
    }

    /**
     * @Given /^the message identity should be correct$/
     */
    public function theMessageIdentityShouldBeCorrect()
    {
        $requests = $this->plugin->getReceivedRequests();

        if ($requests[0]->getPostField('username') != $this->identity->username) {
            throw new \Exception(sprintf('Unexpected username: %s', $requests[0]->getPostField('username')));
        }

        if ($requests[0]->getPostField('icon_emoji') != $this->identity->emoji) {
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
}
