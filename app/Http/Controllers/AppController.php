<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VenueService;
use App\Services\OrganisationService;
use App\Services\AccessPointService;
use App\Services\NetworkService;
use App\Services\CollectionService;
use App\Services\APIService;


class AppController extends Controller
{
    public function createVenue()
    {
        $venueService = new VenueService();
        $result = $venueService->createVenue($_POST['venue_name'], $_POST['venue_desc'], $_POST['venue_add'], $_POST['venue_add_notes']);
        return $result;
    }

    public function getAllVenues()
    {
        $venueService = new VenueService();
        $result = $venueService->getAllVenues();
        return $result;
    }  

    public function createAccessPoint()
    {
        $apService = new AccessPointService();
        $result = $apService->createAccessPoint($_POST['venue_id'], $_POST['ap_name'], $_POST['ap_desc'], $_POST['ap_identifier'], $_POST['ap_serial'], $_POST['ap_tags']);
        return $result;   
    }  

    public function createNetwork()
    {
        $networkService = new NetworkService();
        $result = $networkService->createNetwork($_POST['networkData']);
        return $result;
    }

    public function duplicateNetworkName()
    {
        $networkService = new NetworkService();
        $result = $networkService->duplicateNetworkName($_POST['network_name']);
        return $result;
    }

    public function getAllAccessPoints()
    {
        $input_filters = [];
        $input_filters['page_num'] = $_POST['page_num'];
        $input_filters['cluster_id'] = $_POST['cluster_id'];
        $input_filters['limit'] = $_POST['limit'];

        $apService = new AccessPointService();
        $result = $apService->getAllAccessPoints($input_filters);
        return $result;
    }      

    public function getAllWifiNetworks() {
        $networkService = new NetworkService();
        $result = $networkService->getAllWifiNetworks();
        return $result;
    }

    public function getCollectionsData() {
        $collectionService = new CollectionService();
        $input_data = [];
        $input_data['venue_id'] = $_POST['venue_id'];
        $input_data['ap_id'] = $_POST['ap_id'];
        
        $result = $collectionService->getCollectionsData($input_data);
        return $result;    
    }

    public function testGetCollectionsData() {
        $collectionService = new CollectionService();
        $result = $collectionService->getCollectionsData();
        //$results = 'abc';
        return view('admin/test')->with(['results' => $result]);;    
    }

    public function testAPData() {
        $collectionService = new CollectionService();
        $result = $collectionService->testAPData();
        //$results = 'abc';
        return view('admin/test')->with(['results' => $result]);;    
    }

    public function testClientCount() {
        $collectionService = new CollectionService();
        $result = $collectionService->testClientCount();
        //$results = 'abc';
        return view('admin/test')->with(['results' => $result]);;    
    }
    
    
    public function getClientsTrafficGraphData() {
        $collectionService = new CollectionService();
        $input_data = [];
        $input_data['venue_id'] = $_POST['venue_id'];
        $input_data['ap_id'] = $_POST['ap_id'];
        $input_data['duration'] = $_POST['duration'];
        $input_data['time_interval'] = $_POST['time_interval'];
        
        $page = 'web';
        $result = $collectionService->getClientsTrafficGraphData($input_data, $page);
        return $result;    
    }

    public function getAPData(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getAPData($request->input('ap_serial'));
        //$result='';
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getOrganisationDetails() {
        $apiService = new APIService();
        $result = $apiService->getOrganisationDetails();
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getClusterDetails($cluster_id) {
        $apiService = new APIService();
        $result = $apiService->getClusterDetails($cluster_id);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getAllClusters(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getAllClusters($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function createCluster(Request $request) {
        $apiService = new APIService();
        $result = $apiService->createCluster($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function updateCluster($cluster_id, Request $request) {
        $apiService = new APIService();
        $result = $apiService->updateCluster($cluster_id, $request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getAPDetails($ap_id) {
        $apiService = new APIService();
        $result = $apiService->getAPDetails($ap_id);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getAllAPs(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getAllAPs($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function createAP(Request $request) {
        $apiService = new APIService();
        $result = $apiService->createAP($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function updateAP($ap_id, Request $request) {
        $apiService = new APIService();
        $result = $apiService->updateAP($ap_id, $request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getWifiNetworkDetails($network_id) {
        $apiService = new APIService();
        $result = $apiService->getWifiNetworkDetails($network_id);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getAllWifiNetworksAPI(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getAllWifiNetworksAPI($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function createWifiNetwork(Request $request) {
        $apiService = new APIService();
        $result = $apiService->createWifiNetwork($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function updateWifiNetwork($network_id, Request $request) {
        $apiService = new APIService();
        $result = $apiService->updateWifiNetwork($network_id, $request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getAllConnectedClients(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getAllConnectedClients($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getAllConnectedClientsGraph(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getAllConnectedClientsGraph($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function loginUser(Request $request) {
        $apiService = new APIService();
        $result = $apiService->loginUser($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function createUser(Request $request) {
        $apiService = new APIService();
        $result = $apiService->createUser($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }
    
    public function getDashboardData() {
        $organisationService = new OrganisationService();
        $result = $organisationService->getDashboardData();
        return $result;    
    }

    public function setTimeInterval(Request $request) {
        $organisationService = new OrganisationService();
        $result = $organisationService->setTimeInterval($request->input('setting_time_interval'));
        return $result;    
    }

    public function getTimeInterval() {
        $organisationService = new OrganisationService();
        $result = $organisationService->getTimeInterval();
        return $result;    
    }

    public function deleteWifiNetwork($network_id) {
        $apiService = new APIService();
        $result = $apiService->deleteWifiNetwork($network_id);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function deleteAccessPoint($ap_id) {
        $apiService = new APIService();
        $result = $apiService->deleteAccessPoint($ap_id);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function deleteCluster($cluster_id) {
        $apiService = new APIService();
        $result = $apiService->deleteCluster($cluster_id);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function deleteVenue()
    {
        $venueService = new VenueService();
        $result = $venueService->deleteCluster($_POST['venue_id']);
        return $result;   
    }

    public function updateVenue()
    {
        $venueService = new VenueService();
        $result = $venueService->updateVenue($_POST['venue_id'], $_POST['venue_name'], $_POST['venue_desc']);
        return $result;   
    }  

    public function delAccessPoint()
    {
        $apService = new AccessPointService();
        $result = $apService->deleteAccessPoint($_POST['ap_id']);
        return $result;   
    }  

    public function updateAccessPoint()
    {
        $apService = new AccessPointService();
        $result = $apService->updateAccessPoint($_POST['ap_id'], $_POST['venue_id'], $_POST['ap_name'], $_POST['ap_desc'], $_POST['ap_identifier'], $_POST['ap_serial'], $_POST['ap_tags']);
        return $result;   
    }  

    public function delWifiNetwork() {
        $networkService = new NetworkService();
        $result = $networkService->deleteNetwork($_POST['network_id']);
        return $result; 
    }

    public function editWifiNetwork() {
        $networkService = new NetworkService();
        $result = $networkService->updateNetwork($_POST['network_id'], $_POST['networkData']);
        return $result; 
    }

    public function getTrafficByClients(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getTrafficByClients($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getTrafficByAccessPoints(Request $request) {
        $apiService = new APIService();
        $result = $apiService->getTrafficByAccessPoints($request);
        return response($result)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);  
    }

    public function getTrafficByClientsWeb() {
        $collectionService = new CollectionService();
        $input_data = [];
        $input_data['venue_id'] = $_POST['venue_id'];
        $input_data['ap_id'] = $_POST['ap_id'];
        $input_data['duration'] = $_POST['duration'];
        $input_data['limit'] = $_POST['limit'];

        $result = $collectionService->getTrafficByClients($input_data);
        return $result; 
    }

    public function getTrafficByAccessPointsWeb() {
        $collectionService = new CollectionService();
        $input_data = [];
        $input_data['venue_id'] = $_POST['venue_id'];
        $input_data['ap_id'] = $_POST['ap_id'];
        $input_data['duration'] = $_POST['duration'];
        $input_data['limit'] = $_POST['limit'];

        $result = $collectionService->getTrafficByAccessPoints($input_data);
        return $result;  
    }
}
