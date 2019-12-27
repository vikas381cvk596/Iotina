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
        $apService = new AccessPointService();
        $result = $apService->getAllAccessPoints();
        return $result;
    }      

    public function getAllWifiNetworks() {
        $networkService = new NetworkService();
        $result = $networkService->getAllWifiNetworks();
        return $result;
    }

    public function getCollectionsData() {
        $collectionService = new CollectionService();
        $result = $collectionService->getCollectionsData();
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
        $result = $collectionService->getClientsTrafficGraphData();
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
    
    
}
