<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2018/5/31
 * Time: 17:59
 */
namespace huoybb\core;


class myCliApp extends \Symfony\Component\Console\Application
{


    /**
     * myCliApp constructor.
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name,$version);
        $this->load();
    }

    public function load()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in(APP_PATH.'/commands');//这里的目录定义是一个问题，需要后续解决，怎么能够识别出来
        foreach ($finder as $file){
            $command = '\\Commands\\'.preg_replace('|.php$|','',$file->getBasename());
            $this->add(new $command);
        }
    }
}