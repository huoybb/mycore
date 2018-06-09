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
     * 利用这个加载某一个目录下的命令，方便操作
     * @param null $path
     */
    public function load($path = null)
    {
        $path = $path ?? APP_PATH.'/commands';
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->in($path);//这里的目录定义是一个问题，需要后续解决，怎么能够识别出来
        foreach ($finder as $file){
            $command = '\\Commands\\'.preg_replace('|.php$|','',$file->getBasename());
            $this->add(new $command);
        }
    }
}