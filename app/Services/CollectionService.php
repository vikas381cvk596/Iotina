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

        $time_interval = round(strtotime('-5 minutes') * 1000); // Last 6 Minutes

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
        $setting_time_interval = 5;
        
        $org_interval = $organisationService->getTimeInterval();

        if ($org_interval != '') {
            $org_interval = (float)$org_interval/60;
            $org_interval = (float)$org_interval;

            if ($org_interval > 0) {
                $setting_time_interval = $org_interval;
            }
        }                    

        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        
        $collection = $client->eapDb->apTable;
        $date = new UTCDateTime(0);

        $time_interval = round(strtotime('-60 minutes') * 1000); // Last 6 Minutes
        $current_time = round(microtime(true) * 1000);

        $graph_interval = 
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
                                        ], $setting_time_interval
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'count' => [
                        '$sum' => '$NumberOfSTA'
                    ],
                    'time_stamp' => [
                        '$addToSet' => '$timestamp'
                    ]
                    
                ]
            ], [
                '$sort' => [
                    '_id' => 1.0
                ],
            ], [
                '$project' => [
                    '_id' => 1.0,
                    'count' => 1.0,
                    'time_stamp' => 1.0
                ],
            ]
        ];




        $options = [];
        $cursor = $collection->aggregate($pipeline, $options); 

        //$dataInterval = [];
        $data = [];
        $all_data = [];
        $count = [];
        $count = 0;
        foreach ($cursor as $document) { 
            //$dataInterval[] = $document['_id'];
            $all_data[] = $document;
            $data[] = $document['count'];
            $count = $count + 1; 
            if ($count >= 13) 
                break;
            //$count[] = $document['_count'];
        }

        /*$data[0] = 10;
        $data[1] = 20;
        $data[2] = 30;
        $data[3] = 40;
        $data[4] = 50;
        $data[5] = 60;
        $data[6] = 70;
        $data[7] = 80;
        $data[8] = 90;
        $data[9] = 100;
        $data[10] = 110;
        $data[11] = 120;
        
        $dataInterval[0]['interval'] = 0;   // 2 Clients Connected
        $dataInterval[1]['interval'] = 5;   // 4 Clients Connected
        $dataInterval[2]['interval'] = 10;  // 1 Clients Connected
        $dataInterval[3]['interval'] = 15;  // 2 Clients Connected
        $dataInterval[4]['interval'] = 20;  // 1 Clients Connected
        $dataInterval[5]['interval'] = 25;  // 1 Clients Connected 
        $dataInterval[6]['interval'] = 30;  // 9 Clients Connected 0 0
        $dataInterval[7]['interval'] = 45;  // 8 Clients Connected
        $dataInterval[8]['interval'] = 50;  // 5 Clients Connected
        $dataInterval[9]['interval'] = 55;  // 8 Clients Connected
        $dataInterval[10]['interval'] = 60; // 7 Clients Connected
        $dataInterval[11]['interval'] = 65; // 6 Clients Connected

        $interval_time = 5;
        $final[] = array();
        foreach ($dataInterval as $key => $data_record) {
            if (isset($dataInterval[$key+1])) {
                $interval_value_current = $dataInterval[$key]['interval'];
                $interval_value_next = $dataInterval[$key+1]['interval'];

                $finalArray[$key]['interval'] = $interval_value_current;
                $finalArray[$key]['count'] = $data[$key];

                if ((($interval_value_next - $interval_value_current) > $interval_time)) {
                    //$data[$key] = 0;
                    //$arr = array('A','B','C');
                    array_splice($data, $key+1, 0, array(0));
                    array_shift($data);
                    //array_pop($data);
                }
            }

            //$interval_value = 10;

            //$dataInterval[$key]['interval'] = $interval_value;
        }*/

        $results = new \stdClass();
        $results->dataPointsCount = $count;
        $results->dataPoints = $data;
        $results->all_data = $all_data;
        $results->setting_time_interval = $setting_time_interval;
        //$results->finalArray = json_encode($finalArray);
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
            $time_interval = round(strtotime('-10 minutes') * 1000); // Last 10 Minutes, 2 missed msgs 

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
            $time_interval = round(strtotime('-5 minutes') * 1000); // Last 6 Minutes
            $current_time = round(microtime(true) * 1000);

            $query = [
                '$and' => [
                    [
                        'org_id' => $org_id
                    ],[
                        'venue_id' => $venue_id
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
            $time_interval = round(strtotime('-5 minutes') * 1000); // Last 6 Minutes
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
            $time_interval = round(strtotime('-5 minutes') * 1000); // Last 6 Minutes
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
            $collection = $client->eapDb->staTable;
            
            $org_id = (int)$input_filters->org_id;
            $time_interval = round(strtotime('-5 minutes') * 1000); // Last 6 Minutes
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

            $options = [];
            $clients_count = $collection->count($query, $options);
            
            /*$query = [
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

            //$clients_count = $collection->count($query, $options);
            $cursor = $collection->find($query, $options);
            $count = 0;
            foreach ($cursor as $document) { 
                //$status = "connected";
                if (array_key_exists('NumberOfSTA', $document)) {
                    //$count = 10;
                    $count = $count + (int)$document['NumberOfSTA'];
                }
            }
            $clients_count = strval($count);*/
        }


        return $clients_count;
    }

    public function testAPData() {
        $client = new Client("mongodb://ec2-15-206-63-2.ap-south-1.compute.amazonaws.com:27017");
        $collection = $client->eapDb->apTable;

        $ap = new \stdClass();
        $time_interval = round(strtotime('-5 minutes') * 1000); // Last 24 Hours

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