<?php

namespace App\Http\Controllers;

use App\Models\MangoProduction;
use App\Models\PollingStation;
use App\Models\SubCounty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MangoProductionController extends Controller
{
    //create
    public function create()
    {
        $subCounties =  SubCounty::where('county_id', '21')->get();
        $polling_stations = PollingStation::all();
        // $polling_stations = [];
        return view('pages.mango_production.create', compact(['subCounties', 'polling_stations']));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        //validation
        // $request->validate([
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'phone_no' => ['required', 'numeric', 'digits_between:10,15'],
        //     'sub_county' => 'required',
        //     'ward' => 'required',
        //     'kgs' => 'required|numeric|min:0',
        //     'collection_date' => 'required|date',
        // ]);

        try {

            DB::transaction(function () use ($request) {
                //save mango production
                $produce = new MangoProduction();
                $produce->first_name = $request->first_name;
                $produce->last_name = $request->last_name;
                $produce->phone_no = $request->phone_no;
                $produce->kgs = $request->mango_kgs;
                $produce->sub_county_id = $request->sub_county;
                $produce->ward_id = $request->ward;
                $produce->id_no = $request->id_no;
                $produce->lmfcs_no = $request->lmfcs_no;
                $produce->vehicle_no = $request->vehicle_no;
                $produce->payment_mode = $request->payment_mode;
                $produce->bank_name = $request->bank_name;
                $produce->bank_branch = $request->bank_branch;
                $produce->weighing_date = $request->weighing_date;
                $produce->created_by = Auth::user()->id;

                $produce->save();
            });

            return redirect()->route('mango_production.view_all')->with('success', 'Data saved successfully');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'An error occurred while saving the Data. Please try again.');
        }
    }


    public function viewAll()
    {
        $pagename = 'Mango Production';
        $productions = MangoProduction::all();


        return view('pages.mango_production.index', compact('productions', 'pagename'));
    }
}
