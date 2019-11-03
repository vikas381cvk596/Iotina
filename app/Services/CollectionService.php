<?php

namespace App\Services;

use MongoDB\Client;
//use MongoDB\BSON\ObjectId;
use DateTime;
use MongoDB\BSON\UTCDateTime;
use App\Services\OrganisationService;

class CollectionService
{
    public function getCollectionsData() {
        //$client = new Client;
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        $collection = $client->eapDb->staTable;

        $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes

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
                    'org_id' => $org_id,
                    'timestamp' => [ 
                        '$gt' => $time_interval
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
        
        /*$query = [];

        $options = [];

        $cursor = $collection->find($query, $options);
        //$cursor = $collection->find(['_id'=> ObjectId("$mongoId")]); 
        $data = [];
        foreach ($cursor as $document) { 
            $data[] = $document['_id']; 
        }*/
        
        $results = new \stdClass();
        $results->count = sizeof($data);
        $results->sta_data = $data;
        $results = json_encode($results);
        return $results;
    }

    public function getClientsTrafficGraphData() {
        //$client = new Client;
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
            

        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        
        $collection = $client->eapDb->apTable;
        $date = new UTCDateTime(0);

        $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes
        $current_time = round(microtime(true) * 1000);
        $pipeline = [
            [
                '$match' => [
                    '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ], [
                            'timestamp' => [
                                '$lt' => $current_time
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
                                        ], 5.0
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
            if ($count >= 10) 
                break;
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

    public function getAPStatus($org_id, $ap_identifier, $ap_search, $time_status) {
        $status = '';
        $org_id = (int)$org_id;
        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        $collection = $client->eapDb->apTable;

        $ap = new \stdClass();
        $ap->status = '';
        if ($time_status == 'all_time') {

            $status = 'not_yet_connected';
            $ip_address = '';
            
            /*$cursor = $collection->find(['NumberOfSTA' => '36']);

            foreach ($cursor as $document) {
                $status = "connected";
                $ip_address = $document['ip_address'];            
                break;
            }*/

            //$cursor = $collection->find($query, $options);
            if ($ap_identifier == "MAC Address") {
                $cursor = $collection->find(['ap_id' => $ap_search, 'org_id' => $org_id], ['sort' => ['timestamp' => -1]]);
            } else {
                $cursor = $collection->find(['SerialNo' => $ap_search, 'org_id' => $org_id], ['sort' => ['timestamp' => -1]]);
            }
            
            foreach ($cursor as $document) { 
                $status = "connected";
                $ip_address = $document['IPV4Add'];
                if (array_key_exists('SerialNo', $document)) {
                    $ap->ap_serial = $document['SerialNo'];
                }
                if (array_key_exists('ap_id', $document)) {
                    $ap->ap_mac_address = $document['ap_id'];
                }
                break;
            }

            $ap->status = $status;
            $ap->ip_address = $ip_address;

        } else if ($time_status == 'last_24_hours') {
            $status = 'disconnected';
            
            //$time_interval = round(microtime(true) * 1000); //Right Now
            $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes

            if ($ap_identifier == "MAC Address") {
                $cursor = $collection->find(['ap_id' => $ap_search, 'org_id' => $org_id, 'timestamp' => ['$gt' => $time_interval]]);
            } else {
                $cursor = $collection->find(['SerialNo' => $ap_search, 'org_id' => $org_id, 'timestamp' => ['$gt' => $time_interval]], ['sort' => ['timestamp' => -1]]);
            }
            
            foreach ($cursor as $document) { 
                $status = "connected";
                if (array_key_exists('SerialNo', $document)) {
                    $ap->ap_serial = $document['SerialNo'];
                }
                if (array_key_exists('ap_id', $document)) {
                    $ap->ap_mac_address = $document['ap_id'];
                }
                break;
            }
            $ap->status = $status;
        }

        $ap = json_encode($ap);
        return $ap;
    }

    public function getAllClientsConnected ($input_filters, $page) {
        $input_filters = json_decode($input_filters);
        $clients_count = '0';

        if ($page == 'venue_page') {
            $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
            $collection = $client->eapDb->staTable;
            
            $org_id = (int)$input_filters->org_id;
            $venue_id = (int)$input_filters->venue_id; 
            $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes
            $current_time = round(microtime(true) * 1000);

            $query = [
                '$and' => [
                    [
                        'org_id' => 1
                    ],[
                        'venue_id' => 1
                    ], [
                        'timestamp' => [
                            '$gte' => $time_interval
                        ]
                    ], [
                        'timestamp' => [
                            '$lte' => $current_time
                        ]
                    ]
                ]
            ];

            $options = [];
            $clients_count = $collection->count($query, $options);
        } else if ($page == 'ap_page') {
            $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
            $collection = $client->eapDb->apTable;
            
            $org_id = (int)$input_filters->org_id;
            $ap_id = $input_filters->ap_mac_address; 
            $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes
            $current_time = round(microtime(true) * 1000);

            $query = [
                '$and' => [
                    [
                        'org_id' => $org_id
                    ], [
                        'ap_id' => $ap_id
                    ], [
                        'timestamp' => [
                            '$gte' => $time_interval
                        ]
                    ], [
                        'timestamp' => [
                            '$lte' => $current_time
                        ]
                    ]
                ]
            ];

            $options = [
                'projection' => [
                    'NumberOfSTA' => 1.0,
                    '_id' => 0.0
                ],
                'sort' => [
                    'timestamp' => -1.0
                ],
                'limit' => 1
            ];
            
            $options = [];

            //$clients_count = $collection->count($query, $options);
            $cursor = $collection->find($query, $options);
            $count = 0;
            foreach ($cursor as $document) { 
                $status = "connected";
                if (array_key_exists('NumberOfSTA', $document)) {
                    $count = $count + (int)$document['NumberOfSTA'];
                }
            }
            $clients_count = strval($count);
        } else if ($page == 'network_page') {
            $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
            $collection = $client->eapDb->staTable;
            
            $org_id = (int)$input_filters->org_id;
            $network_name = $input_filters->network_name; 
            $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes
            $current_time = round(microtime(true) * 1000);
            //$network_name = 'ssid0';
            $query = [
                '$and' => [
                    [
                        'org_id' => $org_id
                    ], [
                        'network_id' => $network_name
                    ], [
                        'timestamp' => [
                            '$gte' => $time_interval
                        ]
                    ], [
                        'timestamp' => [
                            '$lte' => $current_time
                        ]
                    ]
                ]
            ];

            $options = [];
            $clients_count = $collection->count($query, $options);
        } else if ($page == 'dashboard_page') {
            $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
            $collection = $client->eapDb->apTable;
            
            $org_id = (int)$input_filters->org_id;
            $time_interval = round(strtotime('-6 minutes') * 1000); // Last 6 Minutes
            $current_time = round(microtime(true) * 1000);

            $query = [
                '$and' => [
                    [
                        'org_id' => $org_id
                    ], [
                        'timestamp' => [
                            '$gte' => $time_interval
                        ]
                    ], [
                        'timestamp' => [
                            '$lte' => $current_time
                        ]
                    ]
                ]
            ];

            $options = [
                'projection' => [
                    'NumberOfSTA' => 1.0,
                    '_id' => 0.0
                ],
                'sort' => [
                    'timestamp' => -1.0
                ],
                'limit' => 1
            ];
            
            $options = [];

            $clients_count = $collection->count($query, $options);
        }


        return $clients_count;
    }

    public function testAPData() {
        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        $collection = $client->eapDb->apTable;

        $ap = new \stdClass();
        $time_interval = round(strtotime('-6 minutes') * 1000); // Last 24 Hours

        $cursor = $collection->find(['org_id' => 1, 'SerialNo' => '123456', 'timestamp' => ['$gt' => $time_interval]], ['sort' => ['timestamp' => -1]]);

        $data = [];
        foreach ($cursor as $document) { 
            $data[] = $document; 
            if (array_key_exists('SerialNo', $document)) {
                $data[] = $document['SerialNo'];
            }
            break;
        }
        
        $results = new \stdClass();
        $results->count = sizeof($data);
        $results->ap_data = $data;
        $results = json_encode($results);
        return $results;
    }

    public function testClientCount() {
        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        $collection = $client->eapDb->staTable;
        
        $org_id = 1;
        $venue_id = 1; 
        $time_interval = round(strtotime('-600 minutes') * 1000); // Last 6 Minutes
        $current_time = round(microtime(true) * 1000);

        $query = [
            '$and' => [
                [
                    'org_id' => 1
                ],[
                    'venue_id' => 1
                ], [
                    'timestamp' => [
                        '$gte' => $time_interval
                    ]
                ], [
                    'timestamp' => [
                        '$lte' => $current_time
                    ]
                ]
            ]
        ];

        $options = [];
        $cursor = $collection->count($query, $options);
        
        $results = new \stdClass();
        $results->ap_count_data = $cursor;
        $results = json_encode($results);
        return $results;
    }
}