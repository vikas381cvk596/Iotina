<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VenueService;
use App\Services\AccessPointService;
use App\Services\NetworkService;
use App\Services\CollectionService;

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
        $result = $apService->createAccessPoint($_POST['venue_id'], $_POST['ap_name'], $_POST['ap_desc'], $_POST['ap_serial'], $_POST['ap_tags']);
        return $result;   
    }  

    public function createNetwork()
    {
        $networkService = new NetworkService();
        $result = $networkService->createNetwork($_POST['networkData']);
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
    
    public function getClientsTrafficGraphData() {
        $collectionService = new CollectionService();
        $result = $collectionService->getClientsTrafficGraphData();
        return $result;    
    }
    
}
