<?php

namespace App\Http\Controllers;

use App\Models\Aggregator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MangoProduction;
use App\Models\PollingStation;
use App\Models\RegistrationCentre;
use App\Models\SubCounty;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FarmersDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subCounties =  SubCounty::where('county_id', '21')->get();
        $polling_stations = PollingStation::all();
        $registration_centres = RegistrationCentre::where('status', 1)->get();
        $aggregators = Aggregator::where('status', 1)->get();

        return view('pages.farmers_data.create', compact(['subCounties', 'polling_stations', 'registration_centres', 'aggregators']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {



        try {
            DB::beginTransaction();
        
        // dd($request->all());
                    

            $valueChain = $request->input('value_chain', []);
            $Aggregator = $request->input('aggregator', []);

            // Convert the array data into JSON format
            $valueChainJson = json_encode($valueChain);
            $aggregatorJson = json_encode($Aggregator);

            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'id_no' => $request->input('id_no'),
                'phone_no' => $request->input('phone_no'),
                'role_id' => $request->input('role_id'),
                'sub_county_id' => $request->input('sub_county'),
                'ward_id' => $request->input('ward'),
                'polling_station_id' => $request->input('polling_station'),
                'registration_centre_id' => $request->input('registration_centre_id'),
                'value_chain' => $valueChainJson, // Save as JSON
                'aggregator' => $aggregatorJson, // Save as JSON
                // 'aggregator' => $request->input('aggregator'),
                'user_type' => 'Famers',
                'role_id' => 4,
                'd_0_b' => $request->input('d_0_b'),
                'gender' => $request->input('gender'),
                'password' => Hash::make($request->input('id_no')),
                'status' => true, 
            ]);
        
            DB::commit();
        
            return redirect()->route('farmers_data.view_all')->with('success', 'Data saved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            dd("Database transaction failed: " . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the Data. Please try again.');
            // You can handle the error here, redirect back with an error message, log the error, etc.
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
        $pagename = 'Famers Data';
        // $productions = MangoProduction::all();
        $users = User::where('user_type', 'Famers')->get();


        return view('pages.farmers_data.index', compact('users', 'pagename'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function viewAll()
    {
        $pagename = 'Famers Data';
        // $productions = MangoProduction::all();
        $users = User::where('user_type', 'Famers')->get();


        // return view('pages.farmers_data.index', compact('users', 'pagename'));

        return view('pages.farmers_data.index', compact('users', 'pagename'));
    }
}
