<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/5/31
 * Time: 18:10
 */

namespace huoybb\core;


class aria2
{
    protected $ch;
    protected $token;

    function __construct($server='http://127.0.0.1:6800/jsonrpc', $token=null)
    {
        $this->ch = curl_init($server);
        curl_setopt_array($this->ch, [
            CURLOPT_POST=>true,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_HEADER=>false
        ]);
        if(!is_null($token)) {
            $this->token = $token;
        }
    }

    function __destruct()
    {
        curl_close($this->ch);
    }

    protected function req($data)
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        return curl_exec($this->ch);
    }

    function __call($name, $arg)
    {
        if(!is_null($this->token)) {
            array_unshift($arg, 'token:'.$this->token);
        }
        $data = [
            'jsonrpc'=>'2.0',
            'id'=>'1',
            'method'=>'aria2.'.$name,
            'params'=>$arg
        ];
        $data = json_encode($data);
        $response = $this->req($data);
        if($response===false) {
            trigger_error(curl_error($this->ch));
        }
        return json_decode($response, 1);
    }
}