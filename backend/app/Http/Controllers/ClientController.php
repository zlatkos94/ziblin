<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Region;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    protected $model;

    public function __construct(Client $client)
    {
        // set the model
        $this->model = new ClientRepository($client);
        $this->middleware('auth:api');

    }
//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('auth:api');
//    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fk_user = Auth::id();

        $clients = $this->model->all($fk_user);

        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $regions = DB::table('regions')->select('regions.name as label', 'regions.id as value')->get();

//        $regions = $this->model->create();

        $region = new Region();

        $regions = $region->DropDownRegion();

        return response()->json( $regions );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_name'     => 'required|min:1|max:64',
            'client_name'      => 'required|max:1024',
            'address'      => 'required',
            'city'      => 'required',
            'phone'      => 'required',
            'facebook'      => 'required',
            'instagram'      => 'required',
            'mail'      => 'required',
            'lat'      => 'required',
            'long'      => 'required',
            'description'      => 'required',
            'fk_region'      => 'required',
        ]);
        $fk_user = Auth::id();
        $request->request->add(['fk_user' => $fk_user]);
        return $this->model->store($request->all());
//
//        $client = new Client();
//        $client->company_name = $request->input('company_name');
//        $client->client_name = $request->input('client_name');
//        $client->address = $request->input('address');
//        $client->city = $request->input('city');
//        $client->phone = $request->input('phone');
//        $client->facebook = $request->input('facebook');
//        $client->instagram = $request->input('instagram');
//        $client->mail = $request->input('mail');
//        $client->lat = $request->input('lat');
//        $client->long = $request->input('long');
//        $client->description = $request->input('description');
////        $client->fk_user = $request->input('fk_user');
//        $client->fk_user = $fk_user;
//        $client->fk_region = $request->input('fk_region');
//        $client->save();
//        return response()->json( ['status' => 'success'] );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        $client = DB::table('clients')->where('id', '=', $id)->first();
//        $regions = DB::table('regions')->select('regions.name as label', 'regions.id as value')->get();
//        $users = DB::table('users')->select('users.name as label', 'users.id as value')->get();

        $response = $this->model->edit($id);
        $regions = $response['regions'];
        $client = $response['client'];
        $users = $response['users'];

        return response()->json( [ 'regions' => $regions, 'client' => $client, 'users' => $users, ] );

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'company_name'     => 'required|min:1|max:64',
            'client_name'      => 'required|max:1024',
            'description'      => 'required|max:1024',
            'fk_region'      => 'required',
            'fk_user'      => 'required',
        ]);

        return $this->model->update($request->all(), $id);

//        $client = Client::find($id);
//
//        $client->company_name = $request->input('company_name');
//        $client->client_name = $request->input('client_name');
//        $client->description = $request->input('description');
//        $client->fk_region = $request->input('fk_region');
//        $client->fk_user = $request->input('fk_user');
//        $client->save();
//        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $client = Client::find($id);
//        if($client){
//            $client->delete();
//        }
//        return response()->json( ['status' => 'success'] );

        return $this->model->delete($id);
    }
}
