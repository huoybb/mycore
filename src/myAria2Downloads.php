<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/5/31
 * Time: 18:11
 */

namespace huoybb\core;


class myAria2Downloads
{
    use myPresenterTrait;
    /**
     * @var
     * 读取的aria2的相关信息
     */
    public $data;
    /**
     * myAria2 constructor.
     */
    public function __construct($gid = null)
    {
        if($gid) $this->data = self::tellStatus($gid);
    }
    public static function getInstanceByData($data){
        $instance = new self();
        $instance->data = $data;
        return $instance;
    }

    public static function getResults($data)
    {
        $result = [];
        foreach ($data as $file){
            array_unshift($result,self::getInstanceByData($file));
        }
        return collect($result);
    }

    public static function isValid($aria2)
    {
        return ! isset($aria2['error']);
    }

    public function infoArray()
    {
        return [
            'gid'=>'gid',
            'myurl'=>'url',
            'status'=>'状态',
            'errorCode'=>'错误代码',
            'errorMessage'=>'错误信息',
            'size'=>'文件大小',
            'percent'=>'完成%',
            'ETA'=>'所剩时间s',
            'dir'=>'所在目录',
            'operation'=>'操作',
        ];
    }


    /**
     * @param $url
     * @return string
     */
    public static function addUri($url){
        return (new Aria2())->addUri([$url])['result'];
    }

    /**
     * @param $gid
     * 状态的几种形式：complete、error、active、waiting、paused
     * 如果是error则会有更加详细的错误信息，如果完成，则对应的有file的信息
     * @return mixed
     */

    public static function tellActive()
    {
        return (new Aria2())->tellActive()['result'];
    }
    public static function tellWaiting()
    {
        return (new Aria2())->tellWaiting(0,1000)['result'];
    }
    public static function tellStopped()
    {
        return (new Aria2())->tellStopped(0,1000)['result'];
    }

    public static function tellStatus($gid)
    {
        $aria2 = (new Aria2())->tellStatus($gid);

        if(self::isValid($aria2)) return (new Aria2())->tellStatus($gid)['result'];
        return null;
    }

    /**
     * @param string $gid
     * @return mixed
     */
    public static function removeDownloadResult(string $gid){
        $result = (new Aria2())->removeDownloadResult($gid);
        if(isset($result['error'])) return $result['error']['message'];
        return $result['result'];
    }


    public static function getFiles($gid){
        return (new Aria2())->getFiles($gid)['result'];
    }
}