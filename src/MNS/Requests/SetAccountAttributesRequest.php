<?php
namespace Heifan\MNS\Requests;

use Heifan\MNS\Constants;
use Heifan\MNS\Requests\BaseRequest;
use Heifan\MNS\Model\AccountAttributes;

class SetAccountAttributesRequest extends BaseRequest
{
    private $attributes;

    public function __construct(AccountAttributes $attributes = NULL)
    {
        parent::__construct('put', '/?accountmeta=true');

        if ($attributes == NULL)
        {
            $attributes = new AccountAttributes;
        }

        $this->attributes = $attributes;
    }

    public function getAccountAttributes()
    {
        return $this->attributes;
    }

    public function generateBody()
    {
        $xmlWriter = new \XMLWriter;
        $xmlWriter->openMemory();
        $xmlWriter->startDocument("1.0", "UTF-8");
        $xmlWriter->startElementNS(NULL, "Account", Constants::MNS_XML_NAMESPACE);
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
