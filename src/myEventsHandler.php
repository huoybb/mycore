<?php
/**
 * Created by PhpStorm.
 * User: sks
 * Date: 2018/8/18
 * Time: 20:47
 */

namespace huoybb\core;


class myEventsHandler
{
    public function getDomainObjectName(){
        return str_replace('EventsHandler','',static ::class);
    }


    public function isDomainModelHasPropery($domainModleName,$propertyName){
        return $domainModleName == $this->getDomainObjectName() && property_exists($this->getDomainObjectName(),$propertyName);
    }
}