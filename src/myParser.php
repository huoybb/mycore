<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2016/6/11
 * Time: 19:07
 */

namespace huoybb\core;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

abstract class myParser
{
    protected $crawler;

    /**
     * serialParser constructor.
     * @param $crawler
     */
    public function __construct($url = null)
    {
        if($url) $this->crawler = $this->getCrawler($url);
    }
    abstract public function parse();
    /**
     * @param $url
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    public function getCrawler($url)
    {
        $client  = new Client();
        //下面两行，避免了SSL的验证，在正式的web环境中已经设置了，但在命令行中可以直接取消掉验证
        $httpClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setClient($httpClient);

        $crawler = $client->request('get',$url);
        return $crawler;
    }
    public function setCrawlerByUrl($url)
    {
        $client  = new Client();
        //下面两行，避免了SSL的验证，在正式的web环境中已经设置了，但在命令行中可以直接取消掉验证
        $httpClient = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setClient($httpClient);

        $this->crawler = $client->request('get',$url);
        return $this;
    }
    public function setCrawlerByContent($content)
    {
        $crawler = new Crawler();
        $crawler->addContent($content);
        $this->crawler = $crawler;
        return $this;
    }
    public static function parseUrl($url){
        return (new static($url))->parse();
    }
}