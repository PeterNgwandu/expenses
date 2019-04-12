<?php

namespace App\Http\Controllers\Comments;

use Alert;
use App\Comments\Comment;
use Illuminate\Http\Request;
use App\Retirement\Retirement;
use App\Requisition\Requisition;
use App\Comments\RetirementComment;
use App\Http\Controllers\Controller;
use App\Comments\ExpenseRetirementComment;

class CommentsController extends Controller
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
            'req_no' => 'required',
            'user_id' => 'required',
            'body' => 'required',
        ]);

        $comment = new Comment();
        $comment->user_id = $request->user_id;
        $comment->req_no = $request->req_no;
        $comment->body = $request->body;
        $comment->save();

        alert()->success('Comments added successfuly', 'Good Job');
        session()->flash('message', 'Comment has being added');
        return redirect(url('pending-requisitions'));
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

    public function retirementComment(Request $request)
    {
        $this->validate(request(), [
            'ret_no' => 'required',
            'user_id' => 'required',
            'body' => 'required',
        ]);

        $comment = new RetirementComment();
        $comment->user_id = $request->user_id;
        $comment->ret_no = $request->ret_no;
        $comment->body = $request->body;
        $comment->save();

        Alert::success('Comments added successfuly', 'Good Job');
        session()->flash('message', 'Comment has being added');
        return redirect(url('all-retirements/'.$request->ret_no));
    }

    public function expenseRetirementComment(Request $request)
    {
        $this->validate(request(), [
            'ret_no' => 'required',
            'user_id' => 'required',
            'body' => 'required',
        ]);

        $comment = new ExpenseRetirementComment();
        $comment->user_id = $request->user_id;
        $comment->ret_no = $request->ret_no;
        $comment->body = $request->body;
        $comment->save();

        alert()->success('Comments added successfuly', 'Good Job');
        session()->flash('message', 'Comment has being added');
        return redirect(url('/expense_retirements/'.$request->ret_no));
    }
}
