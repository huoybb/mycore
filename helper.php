<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/12/6
 * Time: 12:11
 */
use Illuminate\Support\Debug\Dumper;
use huoybb\core\myDI;
use huoybb\core\myFactory;
use huoybb\core\myEventsManager;
if(! function_exists('auth')){
    /**
     * @return huoybb\core\myAuth
     */
    function auth(){
        return myDI::make('auth');
    }
}
if(! function_exists('url')){
    /**
     * @param array $routeArray
     * @return string
     */
    function url(array $routeArray){
        return myDI::make('url')->get($routeArray);
    }
}

if(! function_exists('flash')){
    /**
     * @return \Phalcon\Flash\Session
     */
    function flash(){
        return myDI::make('flash');
    }
}
if(! function_exists('modelsManager')){
    /**
     * @return \Phalcon\Mvc\Model\Manager
     */
    function modelsManager(){
        return myDI::make('modelsManager');
    }
}

if(! function_exists('eventsManager')){
    /**
     * Returns the custom events manager
     *
     * @return myEventsManager
     */
    function eventsManager() {
        return myDI::make('eventsManager');
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function ($x) {
            (new Dumper)->dump($x);
        }, func_get_args());

        die(1);
    }
}
/**
 * 便于区分web，Cli等不同运行环境设置的函数
 */
if(! function_exists('getMyEnv')){
    /**
     * @return string
     */
    function getMyEnv(){
        return 'web';
    }
}

if(! function_exists('factory')){
    function factory(string $className){
        return new myFactory($className);
    }
}