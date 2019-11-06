<?php


namespace SwoftElasticsearch;


use Elasticsearch\ClientBuilder;
use Swoole\Coroutine;

class EsClient
{
    public function create($config = [])
    {
        $client = ClientBuilder::create();
        if (Coroutine::getCid() > 0) {
            $client->setHandler(new CoroutineHandler());
        }
        return $client->setHosts(['192.168.152.56:9200'])->build();
    }
}
