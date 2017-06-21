<?php
namespace huoybb\core;
use Phalcon\Di\FactoryDefault;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/6/6
 * Time: 11:23
 */
class myDI extends FactoryDefault
{
    public function myRegister(array $providers)
    {
        foreach($providers as $name => $provider){ new $provider($name,$this);}
    }
    public static function make($serviceName){
        return static::getDefault()->get($serviceName);
    }

}