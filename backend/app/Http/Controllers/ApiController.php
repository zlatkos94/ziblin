<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Region;
use App\Repositories\RoadmapRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getRegions(Request $request)
    {
        $regions = Region::all(['id','name']);
        return response()->json($regions);
    }

    public function getClients(Request $request)
    {
        $query = $request->get('query');
        $data = Client::where('company_name','like','%'.$query.'%')->get();
        return response()->json($data);
    }
}
