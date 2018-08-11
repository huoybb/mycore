<?php
/**
 * Created by PhpStorm.
 * User: sks
 * Date: 2018/8/8
 * Time: 14:55
 */

namespace huoybb\core;


class myEvent
{
    /** @var \Phalcon\Events\Event */
    private $event;
    /**
     * @param \Phalcon\Events\Event $event
     */
    public function setPhalconEvent(\Phalcon\Events\Event $event)
    {
        $this->event = $event;
    }

    public function stop()
    {
        if($this->event->isCancelable()) $this->event->stop();
    }
}