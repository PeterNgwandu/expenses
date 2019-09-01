<?php

namespace App\Http\Controllers\Department;

use Alert;
use App\User;
use App\Company\Company;
use Illuminate\Http\Request;
use App\Department\Department;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::first();
        $departments = Department::all();
        return view('departments.index')->withDepartments($departments)->withCompany($company);
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
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $department = new Department;
        $department->name = $request->name;
        $department->company_id = $request->company_id;
        $department->save();

        alert()->success('Department registered successfuly', 'Done');
        return redirect(url('/view-departments'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::first();
        $department = Department::findOrFail($id);
        return view('departments.edit-department')->withDepartment($department)->withCompany($company);
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
        $department = Department::findOrFail($id);
        $result = $department->update([
            'name' => $request->name,
        ]);

        return redirect(url('view-departments'));
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

    public function viewAllDepts()
    {
        $departments = Department::all();
        return view('departments.show')->withDepartments($departments);
    }

    public function disableDept($dept_id)
    {
        $dept_id = Department::findOrFail($dept_id);
        $data = DB::table('departments')->where('id', $dept_id->id)->update([
            'status' => 'Disabled',
        ]);
        alert()->success('Department Disabled', 'Success');
        return redirect()->back();
    }

    public function enableDept($dept_id)
    {
        $dept_id = Department::findOrFail($dept_id);
        $data = DB::table('departments')->where('id', $dept_id->id)->update([
            'status' => 'Active',
        ]);
        alert()->success('Department Enabled', 'Success');
        return redirect()->back();
    }

    public function deleteDept($dept_id)
    {
        $dept_id = Department::findOrFail($dept_id);
        $data = DB::table('departments')->where('id', $dept_id->id)->delete();
        alert()->success('Department Deleted', 'Success');
        return redirect()->back();
    }

    public static function checkIfDeptHasUser()
    {
        $users_with_depts = User::select('users.department_id')->get();
        $departments = Department::select('id')->get();
        foreach ($departments as $department) {
            if ($users_with_depts->contains($department->id)) {
                return true;
            }
        }
    }

    public static function checkDepatmentExistance()
    {
        $dept = Department::select('id')->get(); 
        foreach($dept as $d){                                                                                                                                               
          $user_dept = Department::join('users','departments.id','users.department_id')
                        ->whereIn('users.department_id', [$d])
                        ->select('users.username','users.department_id')
                        ->get();

          if(!$user_dept->isEmpty()){
              return true;
          }else{
              return false;
          }           
        }
    }

}
