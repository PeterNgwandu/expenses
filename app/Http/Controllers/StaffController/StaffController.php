<?php

namespace App\Http\Controllers\StaffController;

use DB;
use App\User;
use Alert;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
use App\Department\Department;
use App\Accounts\SubAccountType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')->join('departments', 'users.department_id', 'departments.id')
                                   ->join('staff_levels', 'users.stafflevel_id', 'staff_levels.id')
                                   ->select('users.*', 'departments.name as department', 'staff_levels.name as stafflevel')
                                   ->get();
        return view('staffs.show-staffs')->withUsers($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staff_level = StaffLevel::all();
        $departments = Department::where('status', 'Active')->get();
        $accounts = SubAccountType::where('account_subtype_name', 'Staff Advance Accounts')->first();
        return view('staffs.add-staff', compact('staff_level'))->withDepartments($departments)->withAccounts($accounts);
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
            'username' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'phone' => 'required',
        ]);

        $accounts = SubAccountType::where('account_subtype_name', 'Staff Advance Accounts')->first();

        $staff = new User;
        $staff->username = $request->username;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        $staff->phone_alternative = $request->phone_alternative;
        $staff->password = Hash::make($request->password);
        $staff->department_id = $request->department_id;
        $staff->company_id = $request->company_id;
        $staff->sub_acc_type_id = $accounts->id;
        $staff->account_no = $request->account_no;
        $staff->stafflevel_id = $request->stafflevel_id;
        $staff->save();

        alert()->success('Staff Added Successfuly', 'Congratulation');
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
        $staff = User::findOrFail($id);
        $staff_levels = StaffLevel::all();
        $staff_level = User::join('staff_levels','users.stafflevel_id','staff_levels.id')->select('users.stafflevel_id','staff_levels.name as stafflevelname')->where('users.id', $id)->first();
        $staff_dept = User::join('departments','users.department_id','departments.id')->select('users.department_id','departments.name as dept_name')->where('users.id', $id)->first();
        $departments = Department::where('status', 'Active')->get();
        $accounts = SubAccountType::where('account_subtype_name', 'Staff Advance Accounts')->first();
        return view('staffs.edit-staff', compact('staff_dept','staff_level','staff_levels','id'))->withStaff($staff)->withAccounts($accounts)->withDepartments($departments);
    }

    public function deleteUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $result = $user->where('id', $user_id)->delete();
        return response()->json(['result' => $result]);
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

        $user = User::where('id', $id)->update([
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'phone_alternative' => $request->phone_alternative,
            'department_id' => $request->department_id,
            'sub_acc_type_id' => $request->sub_acc_type_id,
            'account_no' => $request->account_no,
            'stafflevel_id' => $request->stafflevel_id,
        ]);

        alert()->success('Staff Updated Successfuly', 'Congratulation');
        return redirect(url('/registered-staffs'));
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

    public function userProfile($user_id)
    {   
        $staff = User::findOrFail($user_id);
        $staff_level = User::join('staff_levels','users.stafflevel_id','staff_levels.id')->select('users.stafflevel_id','staff_levels.name as stafflevelname')->where('users.id', $user_id)->first();
        $staff_dept = User::join('departments','users.department_id','departments.id')->select('users.department_id','departments.name as dept_name')->where('users.id', $user_id)->first();
        return view('staffs.staff-profile', compact('staff','staff_level','staff_dept'));
    }

    public function changePassword(Request $request)
    {
        return view('staffs.change-password');
    }

    public function postChangePassword(Request $request)
    {

        if (Hash::check($request->current_password, Auth::user()->password)) {
            $staff = new User();
            $new_password = Hash::make($request->new_password);
            $staff->password = $new_password;
            $staff->updated([
                'password' => $new_password,
            ]);
            return redirect(url('/'));
        }else{
            return redirect()->back();
        }
    }
}
