<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::get('/', 'AuthController@showHomePage');
Route::get('/', [
        'as' => 'login2',
        'uses' => 'AuthController@showHomePage'
    ]);
Route::get('/admin/login', 'AuthController@showHomePage');
Route::get('/admin/register', 'AuthController@showRegisterPage');
Route::post('/admin/user_login', 'AuthController@loginUser');
Route::post('/admin/registerUser', 'AuthController@registerUser');
Route::get('/admin/logout', 'AuthController@logoutUser');
Route::get('/admin/testGetCollectionsData', 'AppController@testGetCollectionsData');
Route::get('/admin/testAPData', 'AppController@testAPData');
Route::get('/admin/testClientCount', 'AppController@testClientCount');

Route::get('/admin/{page?}', 'AuthController@anyPage')->name('anyPage');
Route::post('/admin/createVenue', array('as' => 'createVenue', 'uses' => 'AppController@createVenue'));
Route::post('/admin/getAllVenues', array('as' => 'getAllVenues', 'uses' => 'AppController@getAllVenues'));
Route::post('/admin/createAccessPoint', array('as' => 'createAccessPoint', 'uses' => 'AppController@createAccessPoint'));
Route::post('/admin/getAllAccessPoints', array('as' => 'getAllAccessPoints', 'uses' => 'AppController@getAllAccessPoints'));
Route::post('/admin/createNetwork', array('as' => 'createNetwork', 'uses' => 'AppController@createNetwork'));
Route::post('/admin/getAllWifiNetworks', array('as' => 'getAllWifiNetworks', 'uses' => 'AppController@getAllWifiNetworks'));
Route::post('/admin/getCollectionsData', array('as' => 'getCollectionsData', 'uses' => 'AppController@getCollectionsData'));
Route::post('/admin/getClientsTrafficGraphData', array('as' => 'getClientsTrafficGraphData', 'uses' => 'AppController@getClientsTrafficGraphData'));
Route::post('/getClientsTrafficGraphData', array('as' => 'getClientsTrafficGraphData', 'uses' => 'AppController@getClientsTrafficGraphData'));
Route::post('/admin/getDashboardData', array('as' => 'getDashboardData', 'uses' => 'AppController@getDashboardData'));
Route::post('/getDashboardData', array('as' => 'getDashboardData', 'uses' => 'AppController@getDashboardData'));

Route::post('/admin/duplicateNetworkName', array('as' => 'duplicateNetworkName', 'uses' => 'AppController@duplicateNetworkName'));
Route::post('/api/accesspoint', array('as' => 'getAPData', 'uses' => 'AppController@getAPData'));
Route::post('/admin/setTimeInterval', array('as' => 'setTimeInterval', 'uses' => 'AppController@setTimeInterval'));
Route::post('/admin/getTimeInterval', array('as' => 'getTimeInterval', 'uses' => 'AppController@getTimeInterval'));
Route::post('/getTimeInterval', array('as' => 'getTimeInterval', 'uses' => 'AppController@getTimeInterval'));


Route::post('/admin/deleteVenue', array('as' => 'createVenue', 'uses' => 'AppController@deleteVenue'));
Route::post('/admin/updateVenue', array('as' => 'updateVenue', 'uses' => 'AppController@updateVenue'));
Route::post('/admin/delAccessPoint', array('as' => 'delAccessPoint', 'uses' => 'AppController@delAccessPoint'));
Route::post('/admin/updateAccessPoint', array('as' => 'updateAccessPoint', 'uses' => 'AppController@updateAccessPoint'));
Route::post('/admin/delWifiNetwork', array('as' => 'delWifiNetwork', 'uses' => 'AppController@delWifiNetwork'));
Route::post('/admin/editWifiNetwork', array('as' => 'editWifiNetwork', 'uses' => 'AppController@editWifiNetwork'));



//Route::get('/api/organisation/{token?}', array('as' => 'getOrganisationDetails', 'uses' => 'AppController@getOrganisationDetails'));

Route::middleware('auth:api')->get('api/organisation', array('as' => 'getOrganisationDetails', 'uses' => 'AppController@getOrganisationDetails'));

Route::middleware('auth:api')->get('api/clusters/{cluster_id}', array('as' => 'getClusterDetails', 'uses' => 'AppController@getClusterDetails'));

Route::middleware('auth:api')->get('api/clusters', array('as' => 'getAllClusters', 'uses' => 'AppController@getAllClusters'));

Route::middleware('auth:api')->post('api/clusters', array('as' => 'createCluster', 'uses' => 'AppController@createCluster'));

Route::middleware('auth:api')->put('api/clusters/{cluster_id}', array('as' => 'updateCluster', 'uses' => 'AppController@updateCluster'));

Route::middleware('auth:api')->get('api/accesspoints/{ap_id}', array('as' => 'getAPDetails', 'uses' => 'AppController@getAPDetails'));

Route::middleware('auth:api')->get('api/accesspoints', array('as' => 'getAllAPs', 'uses' => 'AppController@getAllAPs'));

Route::middleware('auth:api')->post('api/accesspoints', array('as' => 'createAP', 'uses' => 'AppController@createAP'));

Route::middleware('auth:api')->put('api/accesspoints/{ap_id}', array('as' => 'updateAP', 'uses' => 'AppController@updateAP'));

Route::middleware('auth:api')->get('api/wifi_network/{network_id}', array('as' => 'getWifiNetworkDetails', 'uses' => 'AppController@getWifiNetworkDetails'));

Route::middleware('auth:api')->get('api/wifi_network', array('as' => 'getAllWifiNetworksAPI', 'uses' => 'AppController@getAllWifiNetworksAPI'));

Route::middleware('auth:api')->post('api/wifi_network', array('as' => 'createWifiNetwork', 'uses' => 'AppController@createWifiNetwork'));

Route::middleware('auth:api')->put('api/wifi_network/{network_id}', array('as' => 'updateWifiNetwork', 'uses' => 'AppController@updateWifiNetwork'));

Route::middleware('auth:api')->get('api/connected_clients', array('as' => 'getAllConnectedClients', 'uses' => 'AppController@getAllConnectedClients'));

Route::middleware('auth:api')->get('api/graph/connected_clients', array('as' => 'getAllConnectedClientsGraph', 'uses' => 'AppController@getAllConnectedClientsGraph'));

Route::post('api/login_user', array('as' => 'loginUser', 'uses' => 'AppController@loginUser'));

Route::post('api/create_user', array('as' => 'createUser', 'uses' => 'AppController@createUser'));

Route::middleware('auth:api')->delete('api/wifi_network/delete/{network_id}', array('as' => 'deleteWifiNetwork', 'uses' => 'AppController@deleteWifiNetwork'));

Route::middleware('auth:api')->delete('api/accesspoints/delete/{ap_id}', array('as' => 'deleteAccessPoint', 'uses' => 'AppController@deleteAccessPoint'));

Route::middleware('auth:api')->delete('api/clusters/delete/{cluster_id}', array('as' => 'deleteCluster', 'uses' => 'AppController@deleteCluster'));

Route::middleware('auth:api')->get('api/count_entities', array('as' => 'getDashboardData', 'uses' => 'AppController@getDashboardData'));

Route::middleware('auth:api')->get('api/trafficByClients', array('as' => 'getTrafficByClients', 'uses' => 'AppController@getTrafficByClients'));

Route::middleware('auth:api')->get('api/trafficByAccessPoints', array('as' => 'getTrafficByAccessPoints', 'uses' => 'AppController@getTrafficByAccessPoints'));


