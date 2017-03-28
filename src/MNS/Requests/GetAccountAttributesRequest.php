<?php
namespace Heifan\MNS\Requests;

use Heifan\MNS\Requests\BaseRequest;

class GetAccountAttributesRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct('get', '/?accountmeta=true');
    }

    public function generateBody()
    {
        return NULL;
    }

    public function generateQueryString()
    {
        return NULL;
    }
}
?>
