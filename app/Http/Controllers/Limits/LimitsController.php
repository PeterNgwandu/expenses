<?php

namespace App\Http\Controllers\Limits;

use DB;
use Alert;
use App\Limits\Limit;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use App\Http\Controllers\Controller;

class LimitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $limits = Limit::join('staff_levels','limits.stafflevel_id','staff_levels.id')

                       ->select('limits.*','staff_levels.name as stafflevel')
                       ->orderBy('id','asc')
                       ->get();
        $stafflevels = StaffLevel::where('name', '!=', 'Normal Staff')->get();
        return view('limits.set-limits', compact('stafflevels','limits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'stafflevel_id' => 'required',
            'max_amount' => 'required',
        ]);

        $limit = new Limit();
        $limit->stafflevel_id = $request->stafflevel_id;
        $limit->max_amount = $request->max_amount;
        $limit->save();

        return redirect()->back();

    }

    public function adjustLimit($data_id, $max_amount)
    {
        $result = Limit::where('id', $data_id)->update(['max_amount' => $max_amount]);
        return response()->json(['result' => $result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $limit = Limit::findOrFail($id);
        $stafflevels = StaffLevel::where('name', '!=', 'Normal Staff')->get();
        return view('limits.adjust-limit', compact('limit','stafflevels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $limit = Limit::findOrFail($id);
        $limit->stafflevel_id = $request->stafflevel_id;
        $limit->max_amount = $request->max_amount;
        $limit->save();

        alert()->success('You have adjusted the limit successfully', 'Done');
        return redirect(url('/limits/create'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getMaxValue($stafflevel_id)
    {
        $result = Limit::where('stafflevel_id', $stafflevel_id)->first();
        if (isset($result))
        {
            return response()->json(['result' ,$result]);
        }
    }
}
