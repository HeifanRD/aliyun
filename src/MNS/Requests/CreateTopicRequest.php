<?php
namespace Aliyun\MNS\Requests;

use Aliyun\MNS\Constants;
use Aliyun\MNS\Requests\BaseRequest;
use Aliyun\MNS\Model\TopicAttributes;

class CreateTopicRequest extends BaseRequest
{
    private $topicName;
    private $attributes;

    public function __construct($topicName, TopicAttributes $attributes = NULL)
    {
        parent::__construct('put', 'topics/' . $topicName);

        if ($attributes == NULL)
        {
            $attributes = new TopicAttributes;
        }

        $this->topicName = $topicName;
        $this->attributes = $attributes;
    }

    public function getTopicName()
    {
        return $this->topicName;
    }

    public function getTopicAttributes()
    {
        return $this->attributes;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Topic", Constants::Aliyun\MNS_XML_NAMESPACE);
        $this->attributes->writeXML($xmlWriter);
        $xmlWriter->endElement();
        $xmlWriter->endDocument();
        return $xmlWriter->outputMemory();
    }

    public function generateQueryString()
    {
        return NULL;
    }
}
?>
