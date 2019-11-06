<?php declare(strict_types=1);


namespace SwoftElaticsearch;


use Elasticsearch\ClientBuilder;
use Swoft\Log\Helper\Log;
use Swoft\Stdlib\Helper\Str;

trait Searchable
{
    public function getClient()
    {
        $client = EsClient::create();
        return $client;
    }

    public function shouldBeSearchable()
    {
        return true;
    }

    public function createIndex()
    {
        $table = $this->getTable();
        $params = [
            'index' => $table,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ]
            ]
        ];

        $this->getClient()->indices()->create($params);
        return $this;
    }

    /**
     * 索引文档
     * @return array|callable
     */
    public function makeIndexDoc()
    {
        $index = $this->getIndexTypeStruct();
        $body = $this->toSearchableArray();
        $params = array_merge($index, ['body' => $body]);

        $response = $this->getClient()->index($params);
        return $response;
    }

    /**
     * 更新现有文档
     * @return array|callable
     */
    public function updateIndexDoc() {
        $doc = $this->toSearchableArray();
        $params = [
            'body' => [
                'doc' => $doc
            ]
        ];
        $index = $this->getIndexTypeStruct();
        $params = array_merge($index, $params);

        $response = $this->getClient()->update($params);

        return $response;
    }

    /**
     * 创建所有表中记录到索引
     * @return bool
     */
    public function makeAllIndexDoc()
    {
        $keyMethod = 'get' . Str::camel($this->getKeyName());
        $bool = self::chunkById(1000, function ($models) use ($keyMethod) {
            foreach ($models as $model) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $model->getTable(),
                        '_type' => $model->getTable(),
                        '_id' => $model->{$keyMethod}(),
                    ]
                ];

                $params['body'][] = $model->toSearchableArray();
            }
            $this->getClient()->bulk($params);
        });

        return $bool;
    }

    public function getIndexTypeStruct() {
        $table = $this->getTable();
        $keyMethod = 'get' . Str::camel($this->getKeyName());

        return [
            'index' => $table,
            'type' => $table,
            'id' => $this->{$keyMethod}()
        ];
    }

    public function toSearchableArray()
    {
        return $this->toArray();
    }
}
