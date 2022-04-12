<?php


namespace app\components;


class ResponseHttp
{
    protected $_ch;
    protected $_response;

    public function __construct($curl, $response)
    {
        $this->_ch = $curl;
        $this->_response = $response;
    }

    public function getContent(){
        return $this->_response;
    }

    public function getStatusCode(){
        return  curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);

    }
}