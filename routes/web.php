<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::middleware(['auth'])->group(function () {
	Route::get('/', function () {
    return view('layout.index');
})->middleware('auth');

Route::resource('items', 'Item\ItemController');
Route::resource('limits', 'Limits\LimitsController');
Route::resource('budgets', 'Budgets\BudgetsController');
Route::resource('accounts', 'Accounts\AccountController');
Route::resource('staffs', 'StaffController\StaffController');
Route::resource('departments', 'Department\DepartmentController');
Route::resource('staff-levels', 'StaffLevel\StaffLevelController');
Route::resource('requisitions', 'Requisitions\RequisitionsController');
Route::resource('comment', 'Comments\CommentsController');
Route::get('submitted-requisitions', 'Requisitions\RequisitionsController@submittedRequisition')->name('submitted-requisitions');
Route::get('submitted-requisitions/{user_id}', 'Requisitions\RequisitionsController@submittedRequisitions')->name('submitted-requisition');
Route::get('approve-requisition/{req_no}', 'Requisitions\RequisitionsController@approveRequisition');
Route::get('reject-requisition/{req_no}', 'Requisitions\RequisitionsController@rejectRequisition');
// Route::post('items.store/{id}', 'Item\ItemController@store');

Route::get('/view-departments', 'Department\DepartmentController@viewAllDepts');
Route::get('/enable-deparment/{dept_id}', 'Department\DepartmentController@enableDept');
Route::get('/disable-deparment/{dept_id}', 'Department\DepartmentController@disableDept');
Route::get('/delete-department/{dept_id}', 'Department\DepartmentController@deleteDept');

Route::get('/enable-level/{level_id}', 'StaffLevel\StaffLevelController@enableLevel');
Route::get('/disable-level/{level_id}', 'StaffLevel\StaffLevelController@disableLevel');
Route::get('/delete-level/{level_id}', 'StaffLevel\StaffLevelController@deleteLevel');

Route::get('registered-staffs', 'StaffController\StaffController@index')->name('staffs');
Route::get('/staffs', function () {
    return view('levels.index');
})->name('register-levels');
});



Route::get('/create-requisition', 'Requisitions\RequisitionsController@renderRequisitionForm')->name('create-requisition');

/*
	New Way to handle requisitions
*/

Route::get('/create-requisition', 'Requisitions\RequisitionsController@createRequisitionForm')->name('requisitions.create');
Route::post('/requisition-create', 'Requisitions\RequisitionsController@createRequisition')->name('requisition.create');

Route::get('requisition-summary/{id}', 'Requisitions\RequisitionsController@getRequisitionSummary')->name('requisition-summary');

Route::post('requisition/edit/{req_id}', 'Requisitions\RequisitionsController@editRequisition');
Route::resource('finance-supportive-detail', 'Accounts\FinanceSupportiveDetailsController');

Route::post('submit-single-requisition-row', 'Requisitions\RequisitionsController@submit_requisition');
Route::get('permanent-requisition/{req_no}', 'Requisitions\RequisitionsController@permanentRequisitionSubmission');
Route::get('edit-requisition/{req_id}', 'Requisitions\RequisitionsController@edit');
Route::post('update-requisition/{req_id}', 'Requisitions\RequisitionsController@update');
Route::get('unretired-requisitions', 'Requisitions\RequisitionsController@unretiredRequisition')->name('unretired-requisitions');

Route::get('approved-requisitions', 'Requisitions\RequisitionsController@approvedRequisitions')->name('approved-requisitions');
Route::get('paid-requisitions', 'Requisitions\RequisitionsController@paidRequisitions')->name('paid-requisitions');
Route::get('retired-requisitions', 'Requisitions\RequisitionsController@retiredRequisitions')->name('retired-requisitions');

Route::get('pending-requisitions', 'Requisitions\RequisitionsController@pendingRequsitionsHandling')->name('pending-requisitions');

Route::get('delete-record', 'Requisitions\RequisitionsController@deleteRecord');

Route::get('process-payment/{req_no}', 'Requisitions\RequisitionsController@processPayment');

Route::get('delete-requisition/{req_no}/{req_id}', 'Requisitions\RequisitionsController@deleteRequsitionLine');


/*

	Expese Retirements
*/

Route::get('expense_retirements/create', 'ExpenseRetirements\ExpenseRetirementController@create')->name('expense-retirements.create');
Route::get('expense_retirements/manage', 'ExpenseRetirements\ExpenseRetirementController@index')->name('expense-retirements.index');
Route::get('expense_retirements/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@show')->name('expense_retirements.show');
Route::get('get-items-list/{id}', 'ExpenseRetirements\ExpenseRetirementController@getItemsList');
Route::post('/submit-single-expense-retire-row', 'ExpenseRetirements\ExpenseRetirementController@submit_expense_retire_row');
Route::get('expense-permanent-retire/{exp_retire_no}', 'ExpenseRetirements\ExpenseRetirementController@permanentExpenseRetirementSubmission');
Route::get('approve-expense-retirement/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@approveExpenseRetirement')->name('approve-expense-retirement');
Route::get('reject-expense-retirement/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@rejectExpenseRetirement')->name('reject-expense-retirement');
Route::post('expense_retirement/comment', 'Comments\CommentsController@expenseRetirementComment')->name('expense-retirements.comments');


/*
	Retirements
*/

Route::resource('retirements', 'Retirements\RetirementController');
// Route::get('paid-requisitions/{req_no}', 'Retirements\RetirementController@getAllPaidRequisition');
Route::get('approve-retirement/{ret_no}/{user_id}', 'Retirements\RetirementController@approveRetirement');
Route::get('reject-retirement/{ret_no}/{user_id}', 'Retirements\RetirementController@rejectRetirement');
Route::post('retirement/comment', 'Comments\CommentsController@retirementComment')->name('retirements.comments');
Route::post('retirements-store', 'Retirements\RetirementController@store');
Route::post('/submit-single-retire-row', 'Retirements\RetirementController@submit_retire_row');
Route::get('permanent-retire/{retire_no}', 'Retirements\RetirementController@permanentRetirementSubmission');

Route::get('paid-requisitions/{req_no}', 'Requisitions\RequisitionsController@getAllPaidRequisition')->name('paid-requisition');

// Route::get('paid-requisitions/{req_no}', 'Requisitions\RequisitionsController@getAllPaidRequisition')->name('paid-requisition');

Route::get('retire/{req_no}', 'Retirements\RetirementController@getRetirementForm')->name('retire');
Route::get('all-retirements/{ret_no}', 'Retirements\RetirementController@getAllRetirement')->name('view-retirements');

Route::get('submitted-retirements', 'Retirements\RetirementController@submittedRetirements')->name('retirements.submitted');


/*
	Journals
*/
Route::resource('journals', 'Journal\JournalController');
Route::get('journal/report', 'Journal\JournalController@printJournal');
Route::post('journal/report', 'Journal\JournalController@postJournal');


/*
	AJAX Routes
*/
Route::get('delete-row/{rowID}', 'Requisitions\RequisitionsController@deleteRowByBudgetID');
Route::get('get-items-list/{id}', 'Requisitions\RequisitionsController@getItemsList');
Route::get('/add-new-row', 'Requisitions\RequisitionsController@add_new_row');
Route::get('/submit-single-row/{budget}/{item}/{accounts}', 'Requisitions\RequisitionsController@submit_single_row');
Route::get('/update-item-unit-measure/{rowID}/{value}', 'Requisitions\RequisitionsController@updateUnitMeasure');
Route::get('/update-item-description/{rowID}/{value}', 'Requisitions\RequisitionsController@updateItemDescription');
Route::get('/update-item-unit-price/{rowID}/{value}', 'Requisitions\RequisitionsController@updateItemUnitPrice');
Route::get('/update-item-quantity/{rowID}/{value}', 'Requisitions\RequisitionsController@updateItemQuantity');
Route::get('/getTotal/{rowID}', 'Requisitions\RequisitionsController@getItemBudgetTotal');
Route::get('/submit-requisition/{user_id}', 'Requisitions\RequisitionsController@submitRequisition');
Route::get('/submit-requisition-form/{user_id}', 'Requisitions\RequisitionsController@submitRequisition');

Route::get('/get-item-description/{item_id}', 'Requisitions\RequisitionsController@getItemDescription');

Route::get('/add-new-form', 'Requisitions\RequisitionsController@addRequisitionForm');

Route::get('get_description_by_item_id/{item_id}', 'Requisitions\RequisitionsController@getDescription');

Route::get('/getMax/{$stafflevel_id}', 'Limits\LimitsController@getMaxValue');

Route::get('/get_next_item_no_by_budget_id/{budget_id}', 'Item\ItemController@getNextItemNoByBudgetId');
Route::get('/get_next_item_no_by_budget_id_two/{budget_id}', 'Item\ItemController@getNextItemNoByBudgetID');

/*
	Handling Retirement AJAX Requests
*/

Route::get('get-supplier/{req_id}/{budget}/{item}/{account}/{supplier}', 'Retirements\RetirementController@getSupplier');
Route::get('get_ref_no/{req_id}/{ref_no}', 'Retirements\Retirements\RetirementController@getRefNo');


/*
 	END AJAX Routes
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
