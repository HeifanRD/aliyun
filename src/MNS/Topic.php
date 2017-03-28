<?php
namespace Heifan\MNS;

use Heifan\MNS\Http\HttpClient;
use Heifan\MNS\AsyncCallback;
use Heifan\MNS\Model\TopicAttributes;
use Heifan\MNS\Model\SubscriptionAttributes;
use Heifan\MNS\Model\UpdateSubscriptionAttributes;
use Heifan\MNS\Requests\SetTopicAttributeRequest;
use Heifan\MNS\Responses\SetTopicAttributeResponse;
use Heifan\MNS\Requests\GetTopicAttributeRequest;
use Heifan\MNS\Responses\GetTopicAttributeResponse;
use Heifan\MNS\Requests\PublishMessageRequest;
use Heifan\MNS\Responses\PublishMessageResponse;
use Heifan\MNS\Requests\SubscribeRequest;
use Heifan\MNS\Responses\SubscribeResponse;
use Heifan\MNS\Requests\UnsubscribeRequest;
use Heifan\MNS\Responses\UnsubscribeResponse;
use Heifan\MNS\Requests\GetSubscriptionAttributeRequest;
use Heifan\MNS\Responses\GetSubscriptionAttributeResponse;
use Heifan\MNS\Requests\SetSubscriptionAttributeRequest;
use Heifan\MNS\Responses\SetSubscriptionAttributeResponse;
use Heifan\MNS\Requests\ListSubscriptionRequest;
use Heifan\MNS\Responses\ListSubscriptionResponse;

class Topic
{
    private $topicName;
    private $client;

    public function __construct(HttpClient $client, $topicName)
    {
        $this->client = $client;
        $this->topicName = $topicName;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function setAttribute(TopicAttributes $attributes)
    {
        $request = new SetTopicAttributeRequest($this->topicName, $attributes);
        $response = new SetTopicAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function getAttribute()
    {
        $request = new GetTopicAttributeRequest($this->topicName);
        $response = new GetTopicAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function generateQueueEndpoint($queueName)
    {
        return "acs:mns:" . $this->client->getRegion() . ":" . $this->client->getAccountId() . ":queues/" . $queueName;
    }

    public function generateMailEndpoint($mailAddress)
    {
        return "mail:directmail:" . $mailAddress;
    }

    public function generateSmsEndpoint($phone = null)
    {
        if ($phone)
        {
            return "sms:directsms:" . $phone;
        }
        else
        {
            return "sms:directsms:anonymous";
        }
    }

    public function generateBatchSmsEndpoint()
    {
        return "sms:directsms:anonymous";
    }

    public function publishMessage(PublishMessageRequest $request)
    {
        $request->setTopicName($this->topicName);
        $response = new PublishMessageResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function subscribe(SubscriptionAttributes $attributes)
    {
        $attributes->setTopicName($this->topicName);
        $request = new SubscribeRequest($attributes);
        $response = new SubscribeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function unsubscribe($subscriptionName)
    {
        $request = new UnsubscribeRequest($this->topicName, $subscriptionName);
        $response = new UnsubscribeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function getSubscriptionAttribute($subscriptionName)
    {
        $request = new GetSubscriptionAttributeRequest($this->topicName, $subscriptionName);
        $response = new GetSubscriptionAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function setSubscriptionAttribute(UpdateSubscriptionAttributes $attributes)
    {
        $attributes->setTopicName($this->topicName);
        $request = new SetSubscriptionAttributeRequest($attributes);
        $response = new SetSubscriptionAttributeResponse();
        return $this->client->sendRequest($request, $response);
    }

    public function listSubscription($retNum = NULL, $prefix = NULL, $marker = NULL)
    {
        $request = new ListSubscriptionRequest($this->topicName, $retNum, $prefix, $marker);
        $response = new ListSubscriptionResponse();
        return $this->client->sendRequest($request, $response);
    }
}

?>
