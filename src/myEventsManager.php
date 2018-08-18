<?php
namespace huoybb\core;
use Phalcon\Di;
use Phalcon\Events\Event;

/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/1/11
 * Time: 20:20
 * 提供一个全局的事件处理管理器
 */
class myEventsManager extends \Phalcon\Events\Manager
{
    // $this->listen('auth:login','authEventHandler::login');
    /**
     * @param $eventName
     * @param $handlerAction
     */
    public function listen($eventName, $handlerAction)
    {
        $this->attach($eventName,function($event,$object=null,$data=null) use($handlerAction){
            if (preg_match('/(.+)::(.+)/m', $handlerAction, $regs)) {
                $handlerName = $regs[1];
                $action = $regs[2];
                $handler = new $handlerName;
                $handler->$action($event,$object,$data);
            }
        });
    }
    //便于后续卸载事件handler时用到，这个原有框架没有提供
    protected $handlerObjectsArray = [];

    public function register(array $handlerClassArray)
    {
        foreach($handlerClassArray as $handler){
            $handlerObject = $this->getHandlerFunction($handler);
            $this->handlerObjectsArray[$handler]=$handlerObject;

            $this->attach($this->getEventPrefix(),$handlerObject);
        }
    }


    public function unregister(array $handlerClassArray)
    {
        foreach($handlerClassArray as $handler){
            $handlerObject = $this->handlerObjectsArray[$handler];
            $this->detach($this->getEventPrefix(),$handlerObject);
        }
    }



    public function trigger($event)
    {
        $eventName = $this->getEventName($event);
        $this->fire($eventName,$event);
    }


//    ---------------helper functions ---------------------
    protected function getHandlerFunction($handler){
        return function(Event $e,myEvent $eventObject) use($handler){
            $eventObject->setPhalconEvent($e);
            $actionName = 'when'.get_class($eventObject);
            $handler = myDI::getDefault()->get($handler);
            if(method_exists($handler,$actionName)){
                $handler->$actionName($eventObject);
            }
        };
    }
    public function getAllEvents()
    {
        return $this->_events;
    }
    private function getEventName($event)
    {
        return $this->getEventPrefix().':'.get_class($event);
    }
    public function getEventPrefix()
    {
        return Di::getDefault()->get('config')->application->eventPrefix;
    }

}