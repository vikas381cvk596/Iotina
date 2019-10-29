<?php

namespace App\Services;

use MongoDB\Client;
//use MongoDB\BSON\ObjectId;
use DateTime;
use MongoDB\BSON\UTCDateTime;
class CollectionService
{
    public function getCollectionsData() {
        $client = new Client;
        $collection = $client->eap->staTable;
        /*$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        //$connect = connect();
        //$document = $collection->findOne(['_id' => '123']);

        //$mongoId = '5dad7d014d17a5393a0991ff';

        //$document = $collection->find(['_id'=> ObjectId("$mongoId")]);*/
        /*$cursor = $collection->find(['IPV4Add' => '192.168.76.1']);
        $docs = [];
        foreach ($cursor as $document) {
            $docs[] = $document['_id'];
        }*/

        /*$cursor = $collection->aggregate([
            ['$group' => ['_id' => '$state', 'count' => ['$sum' => 1]]],
            ['$sort' => ['count' => -1]],
            ['$limit' => 5],
        ]);*/

        $pipeline = [ 
            [ 
                '$match' => [ 
                    'org_id' => 1,
                    'timestamp' => [ 
                        '$gt' => 1571650955404
                    ] 
 
                ] 
            ], [ 
                '$sort' => [ 
                    'sta_id' => -1.0 
                ] 
            ], [ 
                '$group' => [ 
                    '_id' => '$sta_id', 
                    'data' => [ 
                        '$first' => '$$ROOT' 
                    ] 
                ] 
            ] 
        ]; 

        $options = [];
        $cursor = $collection->aggregate($pipeline, $options); 

        $data = [];
        foreach ($cursor as $document) { 
            $data[$document['_id']] = $document['data']; 
        }

        $results = new \stdClass();
        $results->count = sizeof($data);
        $results->sta_data = $data;
        $results = json_encode($results);
        return $results;
    }

    public function getClientsTrafficGraphData() {
        $client = new Client;
        $collection = $client->eap->apTable;
        $date = new UTCDateTime(0);
        $pipeline = [
            [
                '$match' => [
                    '$and' => [
                        [
                            'org_id' => 1
                        ], [
                            'timestamp' => [
                                '$gt' => 1571650989999
                            ]
                        ], [
                            'timestamp' => [
                                '$lt' => 1572003684094
                            ]
                        ]
                    ]
                ]
            ], [
                '$group' => [
                    '_id' => [
                        'year' => [
                            '$year' => [
                                '$add' => [
                                    $date, '$timestamp'
                                ]
                            ]
                        ],
                        'dayOfYear' => [
                            '$dayOfYear' => [
                                '$add' => [
                                    $date, '$timestamp'
                                ]
                            ]
                        ],
                        'hour' => [
                            '$hour' => [
                                '$add' => [
                                    $date, '$timestamp'
                                ]
                            ]
                        ],
                        'interval' => [
                            '$subtract' => [
                                [
                                    '$minute' => [
                                        '$add' => [
                                            $date, '$timestamp'
                                        ]
                                    ]
                                ], [
                                    '$mod' => [
                                        [
                                            '$minute' => [
                                                '$add' => [
                                                    $date, '$timestamp'
                                                ]
                                            ]
                                        ], 1.0
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'count' => [
                        '$sum' => '$NumberOfSTA'
                    ]
                ]
            ], [
                '$project' => [
                    '_id' => 0.0,
                    'count' => 1.0
                ]
            ]
        ];




        $options = [];
        $cursor = $collection->aggregate($pipeline, $options); 

        $data = [];
        $count = [];
        $count = 0;
        foreach ($cursor as $document) { 
            $data[] = $document['count'];
            $count = $count + 1; 
            //$count[] = $document['_count'];
        }

        $results = new \stdClass();
        $results->dataPointsCount = $count;
        $results->dataPoints = $data;
        $results = json_encode($results);
        return $results;
        //return var_dump($cursor);
        //return json_encode($data);   
    }
}