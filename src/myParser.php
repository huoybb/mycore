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

    protected function stripBlank($word)
    {
        return trim(preg_replace('|\s+|',' ',$word));
    }


    protected function getText($css_selector){
        $filter = $this->crawler->filter($css_selector);
        if($filter->count()) return $this->stripBlank($filter->text());
        return null;
    }
    protected function getTextFromArrayNodes($css_selector,$seperator = ' '){
        $filter = $this->crawler->filter($css_selector);
        if($filter->count()){
            return collect($filter->each(function(Crawler $item){
                return $this->stripBlank($item->text());
            }))->implode($seperator);
        }
        return null;
    }

    protected function getHtml($css_selector)
    {
        $filter = $this->crawler->filter($css_selector);
        if($filter->count()) return $filter->html();
        return null;
    }

    protected function getHTMLFromArrayNodes($css_selector,$seperator = ' <br> '){
        $filter = $this->crawler->filter($css_selector);
        if($filter->count()){
            return collect($filter->each(function(Crawler $item){
                $result = $item->html();
                if(preg_match('|if\(less1280\)|',$result)) return null;
                return $result;
            }))->implode($seperator);
        }
        return null;
    }
}