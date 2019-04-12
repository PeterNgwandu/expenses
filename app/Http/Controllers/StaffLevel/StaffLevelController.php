<?php

namespace App\Http\Controllers\StaffLevel;

use App\User;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StaffLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = StaffLevel::all();
        return view('levels.show', compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            'name',
        ]);

        $staff_level = new StaffLevel;
        $staff_level->name = $request->name;
        $staff_level->save();

        return redirect()->back();
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
        //
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
        //
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

    public function disableLevel($level_id)
    {
        $level_id = StaffLevel::findOrFail($level_id);
        $data = DB::table('staff_levels')->where('id', $level_id->id)->update([
            'status' => 'Disabled',
        ]);
        alert()->success('Staff Level Disabled', 'Success');
        return redirect()->back();
    }

    public function enableLevel($level_id)
    {
        $level_id = StaffLevel::findOrFail($level_id);
        $data = DB::table('staff_levels')->where('id', $level_id->id)->update([
            'status' => 'Active',
        ]);
        alert()->success('Staff Level Enabled', 'Success');
        return redirect()->back();
    }

    public function deleteLevel($level_id)
    {
        $level_id = StaffLevel::findOrFail($level_id);
        $data = DB::table('staff_levels')->where('id', $level_id->id)->delete();
        alert()->success('Staff Level Deleted', 'Success');
        return redirect()->back();
    }
}
