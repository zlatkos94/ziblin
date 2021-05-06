<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Roadmap;
use App\Repositories\RoadmapRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoadmapController extends Controller
{
    protected $model;

    public function __construct(Roadmap $roadmap)
    {
        // set the model
        $this->model = new RoadmapRepository($roadmap);
        $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roadmaps = $this->model->all();

        return response()->json($roadmaps);

//        $roadmaps = DB::table('roadmap')
//            ->join('users', 'users.id', '=', 'roadmap.fk_user')
//            ->join('promo_material', 'promo_material.id', '=', 'roadmap.fk_promo_material')
//            ->join('clients', 'clients.id', '=', 'roadmap.fk_client')
//            ->select('roadmap.*', 'users.name as user_name', 'promo_material.name as material_name',
//                'clients.company_name as client_company_name','clients.client_name as client_client_name')
//            ->get();
//
//        return response()->json($roadmaps);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $clients = (new Client())->AllClients();

//        $clients = DB::table('clients')->select('clients.company_name as label', 'clients.id as value')->get();

        $promoMaterials = DB::table('promo_material')->select('promo_material.name as label', 'promo_material.id as value')->get();

        return response()->json(['clients' => $clients, 'promoMaterials' => $promoMaterials]);

    }

//    public function getClients(Request $request)
//    {
//        $query = $request->get('query');
//        $data = Client::where('company_name','like','%'.$query.'%')->get();
//
//
//        return response()->json($data);
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'fk_client'      => 'required',
            'fk_promo_material'      => 'required',
            'description'       => 'required|min:1|max:64',
            'applies_to_date'   => 'required|date_format:Y-m-d',
        ]);
        $fk_user = Auth::id();

        $roadmap = new Roadmap();
        $roadmap->fk_client = $request->input('fk_client');
        $roadmap->fk_promo_material = $request->input('fk_promo_material');
        $roadmap->fk_user = $fk_user;
        $roadmap->description = $request->input('description');
        $roadmap->applies_to_date = $request->input('applies_to_date');
        $roadmap->save();
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Roadmap  $roadmap
     * @return \Illuminate\Http\Response
     */
    public function show(Roadmap $roadmap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Roadmap  $roadmap
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roadmap = DB::table('roadmap')->where('id', '=', $id)->first();

        $clients = DB::table('clients')->select('clients.company_name as label' , 'clients.id as value')->get();

        $promoMaterials = DB::table('promo_material')->select('promo_material.name as label', 'promo_material.id as value')->get();

        $users = DB::table('users')->select('users.name as label', 'users.id as value')->get();

        return response()->json(['roadmap' => $roadmap,'clients' => $clients, 'promoMaterials' => $promoMaterials,'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Roadmap  $roadmap
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'applies_to_date'      => 'required',
            'description'      => 'required|max:1024',
            'fk_user'      => 'required',
            'fk_client'      => 'required',
            'fk_promo_material'      => 'required',
        ]);

        return $this->model->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Roadmap  $roadmap
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $roadmap = Roadmap::find($id);
        if($roadmap){
            $roadmap->delete();
        }
        return response()->json( ['status' => 'success'] );
    }
}
