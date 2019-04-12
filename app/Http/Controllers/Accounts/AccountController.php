<?php

namespace App\Http\Controllers\Accounts;

use DB;
use Alert;
use Illuminate\Http\Request;
use App\Accounts\AccountType;
use App\Accounts\Account;
use App\Accounts\SubAccountType;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts_types = AccountType::all();
        $sub_types = SubAccountType::all();
        
        return view('accounts.accounts-index', compact('accounts_types','sub_types','account'));
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
        $this->validate($request, array(
            'account_no' => 'required',
            'account_name' => 'required',
            'sub_accounts_types' => 'required',
            'description' => 'required',
        ));

        $uid = Auth::user()->id; 

        $account = new Account;
        $account->account_no = $request->account_no;
        $account->account_name = $request->account_name;
        $account->description = $request->description;
        $account->sub_account_type = $request->sub_accounts_types;
        $account->user_id = $uid;

        $account->save();

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

    public static function get_sub_account_type_by_account_type_id($id) {
        return DB::table('sub_account_types')->where('account_type_id', $id)->get();
    }

    public static function get_accounts_by_account_subtype_id($id) {
        return DB::table('accounts')->where('sub_account_type', $id)->get();
    }
}
