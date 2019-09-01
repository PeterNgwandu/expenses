<?php

namespace App\Http\Controllers\Item;

use Alert;
use App\Item\Item;
use App\Budget\Budget;
use App\Accounts\Account;
use Illuminate\Http\Request;
use App\StaffLevel\StaffLevel;
USE Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
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
    public function create($id)
    {
        // $budget = Budget::findOrFail($id);
        // return view('items.create-item')->withBudget($budget);
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
            'budget_id' => 'required',
            'account_id' => 'required',
            'item_no' => 'required',
            'item_name' => 'required',
            'description' => 'required',
            'unit_price' => 'required',
            'unit_measure' => 'required',
            'quantity' => 'required',

        ]);

        $item = new Item();
        $item->budget_id = $request->budget_id;
        $item->account_id = $request->account_id;
        $item->item_no = $request->item_no;
        $item->item_name = $request->item_name;
        $item->description = $request->description;
        $item->unit_price = $request->unit_price;
        $item->unit_measure = $request->unit_measure;
        $item->quantity = $request->quantity;
        $item->total = $request->unit_price * $request->quantity;
        $item->save();

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
        $budget = Budget::findOrFail($id);
        return view('items.create-item')->withBudget($budget);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $accounts = Account::all();
        $item = Item::findOrFail($id);
        return view('items.edit-item')->withItem($item)->withAccounts($accounts);
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

        $stafflevels = StaffLevel::all();

        $hod = $stafflevels[0]->id;
        $ceo = $stafflevels[1]->id;
        $supervisor = $stafflevels[2]->id;
        $normalStaff = $stafflevels[3]->id;
        $financeDirector = $stafflevels[4]->id;

        $item = Item::findOrFail($id);
        $result = Item::where('id', $item->id)->update([
          'budget_id' => $request->budget_id,
          'account_id' => $request->account_id,
          'item_no' => $request->item_no,
          'item_name' => $request->item_name,
          'description' => $request->description,
          'unit_price' => $request->unit_price,
          'unit_measure' => $request->unit_measure,
          'quantity' => $request->quantity,
          'total' => $request->unit_price * $request->quantity,
        ]);

        if(Auth::user()->stafflevel_id == $supervisor)
        {
            Budget::where('id', $item->budget_id)->update([
                'status' => 'Edited',
            ]);
        }elseif(Auth::user()->stafflevel_id == $hod)
        {
            Budget::where('id', $item->budget_id)->update([
                'status' => 'Edited, HOD',
            ]);
        }elseif(Auth::user()->stafflevel_id == $financeDirector)
        {
            Budget::where('id', $item->budget_id)->update([
                'status' => 'Edited, Finance',
            ]);
        }elseif (Auth::user()->stafflevel_id == $ceo) {
            Budget::where('id', $item->budget_id)->update([
                'status' => 'Edited, CEO',
            ]);
        }
        

        alert()->success('Item Updated Successfuly', 'Good Job');
        return redirect(url('budgets/'.$item->budget_id));
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

    public static function get_item_by_id($id) {
        return Item::where('id', $id)->first();
    }

    public static function getLatestItemNo($budget_id)
    {
        return DB::table('items')->join('budgets','items.budget_id','budgets.id')
                                 ->where('budget_id', $budget_id)
                                 ->select('item_no')->orderBy('budgets.created_at', 'desc')->first();
    }

    public static function getLatestItemNoCount($budget_id)
    {
        return DB::table('items')->join('budgets','items.budget_id','budgets.id')
                                 ->where('budget_id', $budget_id)
                                 ->select('item_no')->orderBy('budgets.created_at', 'desc')->distinct()->count();
    }

    public static function generateItemNo($budget_id)
    {
        $item_no = null;
        if (ItemController::getLatestItemNo($budget_id) == null)
        {
            $item_no = (1);
        }elseif(ItemController::getLatestItemNo($budget_id) != null)
        {
            $item_no_count = ItemController::getLatestItemNoCount($budget_id);
            $item_no = ($item_no_count + 1);
        }
        return $item_no;
    }

    public function getNextItemNoByBudgetId($budget_id)
    {
        $data = ItemController::generateItemNo($budget_id);
        return response()->json(['result' => $data]);
    }

    public function getNextItemNoByBudgetIB($budget_id)
    {
        $data = ItemController::generateItemNo($budget_id);
        return response()->json(['result' => $data]);
    }
}
