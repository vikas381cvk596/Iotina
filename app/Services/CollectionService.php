<?php

namespace App\Services;

use MongoDB\Client;
use MongoDB\Driver\Exception\ConnectionException;
use MongoDB\Driver\Exception\ConnectionTimeoutException;
//use MongoDB\BSON\ObjectId;
use DateTime;
use MongoDB\BSON\UTCDateTime;
use App\Services\OrganisationService;

class CollectionService
{
    public function getCollectionsData($input_data) {
        //$client = new Client;
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $client = new Client("mongodb://3.6.250.97:27017");
        $collection = $client->eapDb->staTable;

        $time_interval = round(strtotime('-5 minutes') * 1000); // Last 5 Minutes
        // $time_interval = 1584426282000;

        if ($input_data['venue_id'] != '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'ap_id' => $input_data['ap_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] != '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'ap_id' => $input_data['ap_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        }
        
        $pipeline = [ 
            [ 
                '$match' => $matchOptions
            ], [ 
                '$sort' => [ 
                    'timestamp' => -1.0 
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
        $results = new \stdClass();
        $data = [];
        
        try {
            $cursor = $collection->aggregate($pipeline, $options); 
        } catch (ConnectionException $e) {
            $results->count = sizeof($data);
            $results->sta_data = $data;
            $results = json_encode($results);
            return $results;
        } catch (ConnectionTimeoutException $e) {
            $results->count = sizeof($data);
            $results->sta_data = $data;
            $results = json_encode($results);
            return $results;
        }
        foreach ($cursor as $document) { 
            $data[$document['_id']] = $document['data']; 
        }
        
        //$query = [];

        //$options = [];

        /*$cursor = $collection->find($query, $options);
        //$cursor = $collection->find(['_id'=> ObjectId("$mongoId")]); 
        $data = [];
        foreach ($cursor as $document) { 
            $data[] = $document['_id']; 
        }*/

        //Hard code for testing purposes
        
        $results->count = sizeof($data);
        $results->sta_data = $data;
        // $results->time_interval = $time_interval;
        $results = json_encode($results, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $results;
    }

    public function getClientsTrafficGraphData($input_data, $page) {
        //$client = new Client;
        $setting_time_interval = 5; // default last 5 minutes
        $duration = '-60 minutes'; // default
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        /*$org_interval = $organisationService->getTimeInterval();

        if ($org_interval != '') {
            $org_interval = (float)$org_interval/60;
            $org_interval = (float)$org_interval;

            if ($org_interval > 0) {
                $setting_time_interval = $org_interval;
            }
        }     */               

        // if ($page == 'api') {
            if ($input_data['time_interval'] != '') {
                $setting_time_interval = (float)$input_data['time_interval'];    
            }
            if ($input_data['duration'] != '') {
                $duration = '-'.$input_data['duration'].' minutes';    
            }
        // }

        $client = new Client("mongodb://3.6.250.97:27017");
        
        $collection = $client->eapDb->apTable;
        $date = new UTCDateTime(0);

        $time_interval = round(strtotime($duration) * 1000); // Last 6 Minutes
        $current_time = round(microtime(true) * 1000);

        $matchOptions = [];
        if ($input_data['venue_id'] != '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'ap_id' => $input_data['ap_id']
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
                );
        } else if ($input_data['venue_id'] != '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
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
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'ap_id' => $input_data['ap_id'] 
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
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
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
                );
        }

        $pipeline = [   
            [
                '$match' => $matchOptions
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
                    ],
                    'Tx' => [
                        '$sum' => [
                            '$add' => [
                                '$ucastBytesTx', '$mcastBytesTx', '$bcastBytesTx'
                            ]
                        ]
                    ],
                    'McastTx' => [
                        '$sum' => '$mcastBytesTx' 
                    ],
                    'McastRx' => [
                        '$sum' => '$mcastBytesRx' 
                    ],
                    'BcastTx' => [
                        '$sum' => '$bcastBytesTx' 
                    ],
                    'BcastRx' => [
                        '$sum' => '$bcastBytesRx' 
                    ],
                    'Rx' => [
                        '$sum' => [
                            '$add' => [
                                '$ucastBytesRx', '$mcastBytesRx', '$bcastBytesRx'
                            ]
                        ]
                    ],
                    'Total' => [
                        '$sum' => [
                            '$add' => [
                                '$ucastBytesTx', '$ucastBytesRx','$mcastBytesTx', '$mcastBytesRx','$bcastBytesTx', '$bcastBytesRx'
                            ]
                        ]
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
                    'Tx' => 1.0,
                    'Rx' => 1.0,
                    'McastTx' => 1.0,
                    'McastRx' => 1.0,
                    'BcastTx' => 1.0,
                    'BcastRx' => 1.0,
                    'Total' => 1.0,
                    'time_stamp' => 1.0
                ],
            ]
        ];

        $options = [];

        //$dataInterval = [];
        $data = [];
        $all_data = [];
        $count = [];
        $count = 0;

        try {
            $cursor = $collection->aggregate($pipeline, $options); 
        } catch (ConnectionException $e) {
            $results = new \stdClass();
            $results->dataPointsCount = $count;
            $results->dataPoints = $data;
            $results->all_data = $all_data;
            $results->setting_time_interval = $setting_time_interval;
            //$results->finalArray = json_encode($finalArray);
            $results = json_encode($results);
            return $results;
        } catch (ConnectionTimeoutException $e) {
            $results = new \stdClass();
            $results->dataPointsCount = $count;
            $results->dataPoints = $data;
            $results->all_data = $all_data;
            $results->setting_time_interval = $setting_time_interval;
            //$results->finalArray = json_encode($finalArray);
            $results = json_encode($results);
            return $results;
        }

        foreach ($cursor as $document) { 
            //$dataInterval[] = $document['_id'];
            $all_data[] = $document;
            $data[] = $document['count'];
            $count = $count + 1; 
            if ($count >= 13) 
                break;
            //$count[] = $document['_count'];
        }
        $results = new \stdClass();

        while ($count <= 12) {
            $data[] = 0;
            $count = $count + 1;
        }

        $data_display_format = [];
        $data_date_format = [];
        date_default_timezone_set('Asia/Calcutta');

        $current_date = new \DateTime();
        $current_date = $current_date->format('Y-m-d H:i');                    
        
        for ($i=1; $i<=$count; $i++) {
            $interval_cycle = '-'.($i*$setting_time_interval).' minutes';
            
            $current_date_time = strtotime('+5 minute',strtotime($current_date));
            $display_format = date('H:i', strtotime($interval_cycle, $current_date_time));
            $date_format = date("Y-m-d H:i:s", strtotime($interval_cycle, $current_date_time));
            $data_date_format[] = $date_format;
            $data_display_format[] = $display_format;
        }

        $results->count_datapoints = $count;
        $results->clients_count = $data;
        
        $results->time_intervals = array_reverse($data_display_format);
        $results->time_intervals_date_time = array_reverse($data_date_format);
        $results->setting_time_interval = $setting_time_interval;
        $results = json_encode($results);
        return $results;
    }

    function checkAPStatus($ap_identifier, $ap_key, $org_id, $current_status) {
        $output = new \stdClass();
        $output->final_status = 'not_yet_connected';
        $output->ip_address = '';
        $output->ap_serial = '';
        $output->ap_mac_address = '';
        
        $cursor = [];
        $client = new Client("mongodb://3.6.250.97:27017");
        $collection = $client->eapDb->apTable;

        if ($current_status == 'not_yet_connected') {
            $output->final_status = 'not_yet_connected';
            if ($ap_identifier == "MAC Address") {
                try {
                    $cursor = $collection->find(['ap_id' => $ap_key, 'org_id' => $org_id], ['sort' => ['timestamp' => -1]]);
                } catch (ConnectionException $e) {
                    // return $clients_count;
                } catch (ConnectionTimeoutException $e) {
                    // return $clients_count;
                }
            } else {
                try {
                    $cursor = $collection->find(['SerialNo' => $ap_key, 'org_id' => $org_id], ['sort' => ['timestamp' => -1]]);
                } catch (ConnectionException $e) {
                } catch (ConnectionTimeoutException $e) {
                }
            }

            foreach ($cursor as $document) { 
                // $output_intermediate = checkStatus($ap_identifier, $ap_key, $org_id, 'disconnected');
                $output->final_status = 'disconnected';
                $output->ip_address = $document['IPV4Add'];
                if (array_key_exists('SerialNo', $document)) {
                    $output->ap_serial = $document['SerialNo'];
                }
                if (array_key_exists('ap_id', $document)) {
                    $output->ap_mac_address = $document['ap_id'];
                }
                break;
            }
        } else if ($current_status == 'connected' || $current_status == 'disconnected') {
            $output->final_status = 'disconnected';
            
            //$time_interval = round(microtime(true) * 1000); //Right Now
            $time_interval = round(strtotime('-5 minutes') * 1000); // Last 10 Minutes
            // $time_interval = 1584426282000;
            if ($ap_identifier == "MAC Address") {
                $cursor = $collection->find(['ap_id' => $ap_key, 'org_id' => $org_id, 'timestamp' => ['$gt' => $time_interval]]);
            } else {
                $cursor = $collection->find(['SerialNo' => $ap_key, 'org_id' => $org_id, 'timestamp' => ['$gt' => $time_interval]], ['sort' => ['timestamp' => -1]]);
            }
            
            foreach ($cursor as $document) { 
                $output->final_status = "connected";
                $output->ip_address = $document['IPV4Add'];
                if (array_key_exists('SerialNo', $document)) {
                    $output->ap_serial = $document['SerialNo'];
                }
                if (array_key_exists('ap_id', $document)) {
                    $output->ap_mac_address = $document['ap_id'];
                }
                break;
            }
        }

        $output = json_encode($output);
        return $output;
    }

    public function getAccessPointStatus($input_fields) {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        $ap_identifier = $input_fields['ap_identifier'];
        $ap_serial = $input_fields['ap_serial']; 
        $ap_mac_address = $input_fields['ap_mac_address']; 
        $ap_current_status = $input_fields['ap_current_status'];

        $ap_key = $ap_serial; 
        if ($ap_identifier == 'MAC Address') {
            $ap_key = $ap_mac_address;
        }

        $final_status = '';
        $ip_address = '';
        $ap_serial = '';
        $ap_mac_address = '';

        $output = new \stdClass();
        if ($ap_current_status == 'not_yet_connected') {
            $output_check = $this->checkAPStatus($ap_identifier, $ap_key, $org_id, $ap_current_status);
            $output_check = json_decode($output_check);

            $final_status = $output_check->final_status;
            $ip_address = $output_check->ip_address;
            $ap_serial = $output_check->ap_serial;
            $ap_mac_address = $output_check->ap_mac_address;

            if ($output_check->final_status == 'disconnected') {
                $output = $this->checkAPStatus($ap_identifier, $ap_key, $org_id, $output_check->final_status);

                $output = json_decode($output);
                $final_status = $output->final_status;
            } 
        } else {
            $output = $this->checkAPStatus($ap_identifier, $ap_key, $org_id, $ap_current_status);  
            $output = json_decode($output);

            $final_status = $output->final_status;
            $ip_address = $output->ip_address;
            $ap_serial = $output->ap_serial;
            $ap_mac_address = $output->ap_mac_address;  
        }

        
        $ap = new \stdClass();

        $ap->ap_status = $final_status;
        $ap->ap_ip_address = $ip_address;
        $ap->ap_serial = $ap_serial;
        $ap->ap_mac_address = $ap_mac_address;

        $ap = json_encode($ap);
        return $ap;
    }

    public function getAPStatus($org_id, $ap_identifier, $ap_search, $time_status) {
        $status = '';
        $org_id = (int)$org_id;
        $client = new Client("mongodb://3.6.250.97:27017");
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
            $cursor = [];
            if ($ap_identifier == "MAC Address") {
                try {
                    $cursor = $collection->find(['ap_id' => $ap_search, 'org_id' => $org_id], ['sort' => ['timestamp' => -1]]);
                } catch (ConnectionException $e) {
                    // return $clients_count;
                } catch (ConnectionTimeoutException $e) {
                    // return $clients_count;
                }
            } else {
                try {
                    $cursor = $collection->find(['SerialNo' => $ap_search, 'org_id' => $org_id], ['sort' => ['timestamp' => -1]]);
                } catch (ConnectionException $e) {
                } catch (ConnectionTimeoutException $e) {
                }
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
            $client = new Client("mongodb://3.6.250.97:27017");
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
            try {
                $clients_count = $collection->count($query, $options);
            } catch (ConnectionException $e) {
                return $clients_count;
            } catch (ConnectionTimeoutException $e) {
                return $clients_count;
            }
        } else if ($page == 'ap_page') {
            $client = new Client("mongodb://3.6.250.97:27017");
            $collection = $client->eapDb->apTable;
            
            $org_id = (int)$input_filters->org_id;
            $ap_id = $input_filters->ap_mac_address; 
            $ap_status = $input_filters->ap_status;
            $count = 0;
            if ($ap_status == 'connected') {
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
                $cursor = [];
                //$clients_count = $collection->count($query, $options);
                try {
                    $cursor = $collection->find($query, $options);
                } catch (ConnectionException $e) {
                    return "0";
                } catch (ConnectionTimeoutException $e) {
                    return "0";
                }
                foreach ($cursor as $document) { 
                    $status = "connected";
                    if (array_key_exists('NumberOfSTA', $document)) {
                        $count = $count + (int)$document['NumberOfSTA'];
                    }
                }
            }
            $clients_count = strval($count);
        } else if ($page == 'network_page') {
            $client = new Client("mongodb://3.6.250.97:27017");
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
            try {
                $clients_count = $collection->count($query, $options);
            } catch (ConnectionException $e) {
                return $clients_count;
            } catch (ConnectionTimeoutException $e) {
                return $clients_count;
            }
        } else if ($page == 'dashboard_page') {
            $client = new Client("mongodb://3.6.250.97:27017");
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
            try {
                $clients_count = $collection->count($query, $options);
            } catch (ConnectionException $e) {
                return $clients_count;
            } catch (ConnectionTimeoutException $e) {
                return $clients_count;
            }
            
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

    public function getTrafficByClients($input_data) {
        //$client = new Client;
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $client = new Client("mongodb://3.6.250.97:27017");
        $collection = $client->eapDb->staTable;

        $time_interval = round(strtotime('-24 hours') * 1000); // Last 24 hours
        if ($input_data['duration'] != '') {
            $duration = '-'.$input_data['duration'].' minutes';
            $time_interval = round(strtotime($duration) * 1000);   
        }
        // $time_interval = 1584426282000;

        $limit = 10;
        if ($input_data['limit'] != '') {
            $limit = (int)$input_data['limit'];   
        }

        if ($input_data['venue_id'] != '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'ap_id' => $input_data['ap_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] != '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'ap_id' => $input_data['ap_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        }


        $pipeline = [ 
            [ 
                '$match' => $matchOptions
            ], [ 
                '$group' => [ 
                    '_id' => '$sta_id', 
                    'Tx' => [
                        '$sum' => '$BytesSent'
                    ],
                    'Rx' => [
                        '$sum' => '$BytesReceived'
                    ],
                    'Total' => [
                        '$sum' => [
                            '$add' => ['$BytesSent', '$BytesReceived']
                        ]
                    ]
                ] 
            ], [ 
                '$sort' => [ 
                    'Total' => -1.0 
                ] 
            ],
             [
                '$limit' => $limit
            ], [
                '$project' => [
                    '_id' => 1.0,
                    'Tx'  => 1.0,
                    'Rx'  => 1.0,
                    'Total'  => 1.0,
                    'STA'  => 1.0
                ]
            ] 
        ]; 

        $options = [];
        $results = new \stdClass();
        $data = [];
        
        try {
            $cursor = $collection->aggregate($pipeline, $options); 
        } catch (ConnectionException $e) {
            $results->count = sizeof($data);
            $results->sta_data = $data;
            $results = json_encode($results);
            return $results;
        } catch (ConnectionTimeoutException $e) {
            $results->count = sizeof($data);
            $results->sta_data = $data;
            $results = json_encode($results);
            return $results;
        }
        foreach ($cursor as $document) { 
            // $data[$document['_id']] = $document['Tx'];
            $trafficData = new \stdClass();
            $trafficData->Tx = json_decode($document['Tx']);
            $trafficData->Rx = json_decode($document['Rx']);
            $trafficData->mac_address = $document['_id'];
            if (isset($document['STA'])) {
                $trafficData->STA = json_decode($document['STA']);    
            }
            $trafficData->Total = json_decode($document['Total']);
            $data[] = $trafficData; 
        }
        
        $results->count = sizeof($data);
        $results->sta_data = $data;
        // $results->time_interval = $time_interval;
        $results = json_encode($results, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $results;
    }

    public function getTrafficByAccessPoints($input_data) {
        $organisationService = new OrganisationService();
        $org_id = $organisationService->getOrganisationID();
        
        $client = new Client("mongodb://3.6.250.97:27017");
        $collection = $client->eapDb->apTable;

        $time_interval = round(strtotime('-24 hours') * 1000); // Last 5 Minutes
        if ($input_data['duration'] != '') {
            $duration = '-'.$input_data['duration'].' minutes';
            $time_interval = round(strtotime($duration) * 1000);   
        }
        // $time_interval = 1584426282000;

        $limit = 10;
        if ($input_data['limit'] != '') {
            $limit = (int)$input_data['limit'];   
        }

        if ($input_data['venue_id'] != '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'ap_id' => $input_data['ap_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] != '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'venue_id' => (int)$input_data['venue_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] != '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'ap_id' => $input_data['ap_id']
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        } else if ($input_data['venue_id'] == '' && $input_data['ap_id'] == '') {
            $matchOptions = array(
                '$and' => [
                        [
                            'org_id' => $org_id
                        ], [
                            'timestamp' => [
                                '$gt' => $time_interval
                            ]
                        ]
                    ]
                );
        }


        $pipeline = [ 
            [ 
                '$match' => $matchOptions
            ], [ 
                '$group' => [ 
                    '_id' => '$ap_id', 
                    'Tx' => [
                        '$sum' => [
                            '$add' => ['$ucastBytesTx', '$mcastBytesTx', '$bcastBytesTx']
                        ]
                    ],
                    'Rx' => [
                        '$sum' => [
                            '$add' => ['$ucastBytesRx', '$mcastBytesRx', '$bcastBytesRx']
                        ]
                    ],
                    'McastTx' => [
                        '$sum' => '$mcastBytesTx'
                    ],
                    'McastRx' => [
                        '$sum' => '$mcastBytesRx'
                    ],
                    'BcastTx' => [
                        '$sum' => '$bcastBytesTx'
                    ],
                    'BcastRx' => [
                        '$sum' => '$bcastBytesRx'
                    ],
                    'Total' => [
                        '$sum' => [
                            '$add' => ['$ucastBytesTx', '$ucastBytesRx']
                        ]
                    ]
                ] 
            ], [ 
                '$sort' => [ 
                    'Total' => -1.0 
                ] 
            ], [
                '$limit' => $limit
            ], [
                '$project' => [
                    '_id' => 1.0,
                    'Tx'  => 1.0,
                    'Rx'  => 1.0,
                    'Total'  => 1.0
                ]
            ] 
        ]; 

        $options = [];
        $results = new \stdClass();
        $data = [];
        
        try {
            $cursor = $collection->aggregate($pipeline, $options); 
        } catch (ConnectionException $e) {
            $results->count = sizeof($data);
            $results->sta_data = $data;
            $results = json_encode($results);
            return $results;
        } catch (ConnectionTimeoutException $e) {
            $results->count = sizeof($data);
            $results->sta_data = $data;
            $results = json_encode($results);
            return $results;
        }
        foreach ($cursor as $document) { 
            // $data[$document['_id']] = $document['Tx'];
            $trafficData = new \stdClass();
            $trafficData->Tx = json_decode($document['Tx']);
            $trafficData->Rx = json_decode($document['Rx']);
            $trafficData->mac_address = $document['_id'];
            if (isset($document['STA'])) {
                $trafficData->STA = json_decode($document['STA']);    
            }
            $trafficData->Total = json_decode($document['Total']);
            $data[] = $trafficData; 
        }
        
        $results->count = sizeof($data);
        $results->sta_data = $data;
        // $results->time_interval = $time_interval;
        $results = json_encode($results, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
        return $results;
    }

    public function testAPData() {
        $client = new Client("mongodb://3.6.250.97:27017");
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
        $client = new Client("mongodb://3.6.250.97:27017");
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