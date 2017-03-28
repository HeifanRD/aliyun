<?php
namespace Heifan\MNS\Responses;

use Heifan\MNS\Constants;
use Heifan\MNS\Exception\MnsException;
use Heifan\MNS\Exception\QueueAlreadyExistException;
use Heifan\MNS\Exception\InvalidArgumentException;
use Heifan\MNS\Responses\BaseResponse;
use Heifan\MNS\Common\XMLParser;

class CreateQueueResponse extends BaseResponse
{
    private $queueName;

    public function __construct($queueName)
    {
        $this->queueName = $queueName;
    }

    public function parseResponse($statusCode, $content)
    {
        $this->statusCode = $statusCode;
        if ($statusCode == 201 || $statusCode == 204) {
            $this->succeed = TRUE;
        } else {
            $this->parseErrorResponse($statusCode, $content);
        }
    }

    public function parseErrorResponse($statusCode, $content, MnsException $exception = NULL)
    {
        $this->succeed = FALSE;
        $xmlReader = $this->loadXmlContent($content);

        try {
            $result = XMLParser::parseNormalError($xmlReader);

            if ($result['Code'] == Constants::INVALID_ARGUMENT)
            {
                throw new InvalidArgumentException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            if ($result['Code'] == Constants::QUEUE_ALREADY_EXIST)
            {
                throw new QueueAlreadyExistException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
            }
            throw new MnsException($statusCode, $result['Message'], $exception, $result['Code'], $result['RequestId'], $result['HostId']);
        } catch (\Exception $e) {
            if ($exception != NULL) {
                throw $exception;
            } elseif($e instanceof MnsException) {
                throw $e;
            } else {
                throw new MnsException($statusCode, $e->getMessage());
            }
        } catch (\Throwable $t) {
            throw new MnsException($statusCode, $t->getMessage());
        }
    }

    public function getQueueName()
    {
        return $this->queueName;
    }
}

?>
