<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VenueService;

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
}
