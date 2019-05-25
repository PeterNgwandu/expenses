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
Route::get('submitted-requisitions/{req_no}', 'Requisitions\RequisitionsController@submittedRequisitions')->name('submitted-requisition');
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

Route::get('edit-staff', 'StaffController\StaffController@edit');
Route::get('delete-user/{user_id}', 'StaffController\StaffController@deleteUser');

Route::get('edit-item/{item_id}', 'Item\ItemController@edit');
Route::put('edit-item/{item_id}', 'Item\ItemController@update');

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
Route::get('/edit-requisition-line/{req_no}', 'Requisitions\RequisitionsController@edit_requisition');
Route::get('/edit-retirement-line/{ret_no}', 'Retirements\RetirementController@editRetirement');
Route::get('/send-retirement-line/{ret_no}', 'Retirements\RetirementController@sendRetirement');
Route::get('/truncate-retirement-edited-lines/{user_id}/{ret_no}', 'Retirements\RetirementController@truncateRetirementLines');
Route::post('submit-new-single-requisition-row', 'Requisitions\RequisitionsController@newPermanentRequisitionSubmission');
Route::get('permanent-requisition/{req_no}', 'Requisitions\RequisitionsController@permanentRequisitionSubmission');
// Route::get('edit-requisition/{req_id}', 'Requisitions\RequisitionsController@edit');
Route::get('edit-requisitions/{req_no}', 'Requisitions\RequisitionsController@editRequisitionByReqNo');
Route::post('update-requisition/{req_id}', 'Requisitions\RequisitionsController@update');
Route::get('unretired-requisitions', 'Requisitions\RequisitionsController@unretiredRequisition')->name('unretired-requisitions');

Route::get('approved-requisitions', 'Requisitions\RequisitionsController@approvedRequisitions')->name('approved-requisitions');
Route::get('paid-requisitions', 'Requisitions\RequisitionsController@paidRequisitions')->name('paid-requisitions');
Route::get('retired-requisitions', 'Requisitions\RequisitionsController@retiredRequisitions')->name('retired-requisitions');

Route::get('pending-requisitions', 'Requisitions\RequisitionsController@pendingRequsitionsHandling')->name('pending-requisitions');

Route::get('delete-record', 'Requisitions\RequisitionsController@deleteRecord');

Route::get('process-payment/{req_no}', 'Requisitions\RequisitionsController@processPayment');

Route::get('delete-requisition/{req_id}', 'Requisitions\RequisitionsController@deleteRequsitionLine');
Route::get('/deleting-requisition/{req_no}/{id}', 'Requisitions\RequisitionsController@deletingRequisition'); // Delete requisition when Editing i.e on Edit Page of the requisition

Route::get('delete-retirement/{ret_id}', 'Retirements\RetirementController@deleteRetirementLine');

Route::get('delete-requsition-by-id/{req_id}', 'Requisitions\RequisitionsController@deleteRequisitionById');

Route::get('requisition/report/{req_no}', 'Requisitions\RequisitionsController@printRequisition');

Route::get('budget-restrict/{budget_id}', 'Requisitions\RequisitionsController@budgetRestrict');
Route::get('/expense-retirement-budget-restrict/{budget_id}', 'ExpenseRetirements\ExpenseRetirementController@budgetRestrict');

Route::get('/get-total-on-edit/{req_no}', 'Requisitions\RequisitionsController@getTotalOnEdit');

Route::get('/refresh', function() {
	return redirect()->back();
});

Route::get('/truncate-requisitions-line/{user_id}', 'Requisitions\RequisitionsController@truncateRequisitionOnRefresh');
Route::get('/truncate-edited-lines/{user_id}', 'Requisitions\RequisitionsController@truncateEditedLinesOnReset');
Route::get('/bring-edited-line-to-permanent-table/{user_id}/{data_no}', 'Requisitions\RequisitionsController@bringEditedLineToPermanentTable');

/*

	Expese Retirements
*/

Route::get('expense_retirements/create', 'ExpenseRetirements\ExpenseRetirementController@create')->name('expense-retirements.create');
Route::get('expense_retirements/manage', 'ExpenseRetirements\ExpenseRetirementController@index')->name('expense-retirements.index');
Route::get('expense_retirements/pending', 'ExpenseRetirements\ExpenseRetirementController@pendingExpenseRetirement')->name('expense-retirements.pending');
Route::get('expense_retirements/confirmed', 'ExpenseRetirements\ExpenseRetirementController@confirmedExpenseRetirement')->name('expense-retirements.confirmed');
Route::get('expense_retirements/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@show')->name('expense_retirements.show');
Route::get('get-items-list/{id}', 'ExpenseRetirements\ExpenseRetirementController@getItemsList');
Route::post('/submit-single-expense-retire-row', 'ExpenseRetirements\ExpenseRetirementController@submit_expense_retire_row');
Route::post('/submit-single-edit-expense-retire-row/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@submit_edit_expense_retire_row');
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
Route::post('/add-retirement-row', 'Retirements\RetirementController@add_retirement_row');
Route::post('/submit-single-edit-retire-row', 'Retirements\RetirementController@submit_edit_retire_row');
Route::get('permanent-retire/{retire_no}', 'Retirements\RetirementController@permanentRetirementSubmission');

Route::get('paid-requisitions/{req_no}', 'Requisitions\RequisitionsController@getAllPaidRequisition')->name('paid-requisition');

// Route::get('paid-requisitions/{req_no}', 'Requisitions\RequisitionsController@getAllPaidRequisition')->name('paid-requisition');

Route::get('retire/{req_no}', 'Retirements\RetirementController@getRetirementForm')->name('retire');
Route::get('add-retirement/{ret_no}/{req_no}', 'Retirements\RetirementController@addRetirement');
Route::get('all-retirements/{ret_no}', 'Retirements\RetirementController@getAllRetirement')->name('view-retirements');

Route::get('submitted-retirements', 'Retirements\RetirementController@submittedRetirements')->name('retirements.submitted');
Route::get('confirmed-retirements', 'Retirements\RetirementController@confirmedRetirements')->name('retirements.confirmed');

Route::post('filter_by_date', 'Requisitions\RequisitionsController@filterByDate')->name('filter_by_date');
Route::post('pending_filter_by_date', 'Requisitions\RequisitionsController@filterByDatePending')->name('pending_filter_by_date');
Route::post('submitted_filter_by_date', 'DataFilter\DataFilterController@filterByDateSubmitted')->name('submitted_filter_by_date');
Route::post('approved_filter_by_date', 'DataFilter\DataFilterController@filterByDateApproved')->name('approved_filter_by_date');
Route::post('retirement_submitted_filter_by_date', 'DataFilter\DataFilterController@filterByDateSubmittedRetirement')->name('retirement_submitted_filter_by_date');

/*
	Journals
*/
Route::resource('journals', 'Journal\JournalController');
Route::get('journal/report', 'Journal\JournalController@printJournal');
Route::post('journal/report', 'Journal\JournalController@postJournal');
Route::get('journal/view', 'Journal\JournalController@viewJournals')->name('journals.view');
Route::get('/journal/{journal_no}', 'Journal\JournalController@viewJournalEntry');


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
Route::get('/get-activity-name', 'Requisitions\RequisitionsController@getActivityName');

Route::get('/add-new-form', 'Requisitions\RequisitionsController@addRequisitionForm');

Route::get('get_description_by_item_id/{item_id}', 'Requisitions\RequisitionsController@getDescription');

Route::get('/getMax/{$stafflevel_id}', 'Limits\LimitsController@getMaxValue');

Route::get('/get_next_item_no_by_budget_id/{budget_id}', 'Item\ItemController@getNextItemNoByBudgetId');
Route::get('/get_next_item_no_by_budget_id_two/{budget_id}', 'Item\ItemController@getNextItemNoByBudgetID');

Route::get('database-backup', 'Backup\BackupController@databaseBackup');

Route::get('download-backup', 'Backup\BackupController@downloadBackup');

Route::get('get/file', function(){
    return Storage::download("Storage::disk('local')->url('mysql-expense.sql')");
});

Route::get('/approve-budget/{budget_id}', 'Budgets\BudgetsController@approveBudget');
Route::get('/reject-budget/{budget_id}', 'Budgets\BudgetsController@rejectBudget');
Route::get('/delete-budget/{budget_id}', 'Budgets\BudgetsController@deleteBudget');
// Route::get('/adjust-limit/{data_id}/{da}', 'Limits\LimitsController@adjustLimit');

// Updating requisitions on temporary table

Route::get('/update-item-name/{data_id}/{item_name}', 'Requisitions\RequisitionsController@updateItemName');
Route::get('/update-unit-measure/{data_id}/{unit_measure}', 'Requisitions\RequisitionsController@updateUnitMeasures');
Route::get('/update-quantity/{data_id}/{quantity}', 'Requisitions\RequisitionsController@updateQuantity');
Route::get('/update-unit-price/{data_id}/{unit_price}', 'Requisitions\RequisitionsController@updateUnitPrice');
Route::get('/update-vat/{data_id}/{vat}', 'Requisitions\RequisitionsController@updateVat');
Route::get('/update-description/{data_id}/{description}', 'Requisitions\RequisitionsController@updateDescription');
Route::get('/update-budget-line/{data_id}/{budget_line}', 'Requisitions\RequisitionsController@updateBudgetLine');
Route::get('/update-account/{data_id}/{account}', 'Requisitions\RequisitionsController@updateAccount');

// Updating requisitions on permanent table
Route::get('/update-requisition-item-name/{data_id}/{item_name}', 'Requisitions\RequisitionsController@updateRequisitionItemName');
Route::get('/update-requisition-unit-measure/{data_id}/{unit_measure}', 'Requisitions\RequisitionsController@updateRequisitionUnitMeasure');
Route::get('/update-requisition-quantity/{data_id}/{quantity}', 'Requisitions\RequisitionsController@updateRequsitionsQuantity');
Route::get('/update-requisition-unit_price/{data_id}/{unit_price}', 'Requisitions\RequisitionsController@updateRequisitionUnitPrice');
Route::get('/update-requisition-description/{data_id}/{description}', 'Requisitions\RequisitionsController@updateRequisitionDescription');
Route::get('/update-requisition-item_id/{data_id}/{budget_line}', 'Requisitions\RequisitionsController@updateRequisitionLine');
Route::get('/update-requisition-vat/{data_id}/{vat}', 'Requisitions\RequisitionsController@updateRequsitionVat');
Route::get('/update-requisition-account/{data_id}/{account}', 'Requisitions\RequisitionsController@updateRequisitionAccount');

Route::get('/update-requisition-with-no-budget-item-name/{data_id}/{item_name}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionItemName');
Route::get('/update-requisition-with-no-budget-unit_measure/{data_id}/{unit_measure}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionUnitMeasure');
Route::get('/update-requisition-with-no-budget-quantity/{data_id}/{quantity}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionQuantity');
Route::get('/update-requisition-with-no-budget-unit_price/{data_id}/{unit_price}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionUnitPrice');
Route::get('/update-requisition-with-no-budget-description/{data_id}/{description}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionDescription');
Route::get('/update-requisition-vat/{data_id}/{vat}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionVat');
Route::get('/update-requisition-account/{data_id}/{account}', 'Requisitions\RequisitionsController@updateNoBudgetRequisitionAccount');

Route::get('/update-retirement-supplier/{supplier_id}/{data_id}', 'Retirements\RetirementController@updateSupplier');
Route::get('/update-retirement-ref_no/{ref_no}/{data_id}', 'Retirements\RetirementController@updateRefNo');
Route::get('/update-retirement-item_name/{item_name}/{data_id}', 'Retirements\RetirementController@updateItemName');
Route::get('/update-retirement-unit_measure/{unit_measure}/{data_id}', 'Retirements\RetirementController@updateUnitMeasure');
Route::get('/update-retirement-quantity/{quantity}/{data_id}', 'Retirements\RetirementController@updateQuantity');
Route::get('/update-retirement-unit_price/{unit_price}/{data_id}', 'Retirements\RetirementController@updateUnitPrice');
Route::get('/update-retirement-description/{description}/{data_id}', 'Retirements\RetirementController@updateDescription');
Route::get('/update-retirement/{user_id}/{ret_no}', 'Retirements\RetirementController@updateRetirement');

Route::get('/reset-expense-retirement/{user_id}/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@resetExpenseRetirement');
Route::get('/delete-expense-retirement-line/{ret_no}/{data_id}', 'ExpenseRetirements\ExpenseRetirementController@deleteExpenseRetirementLine');
/*
	Handling Retirement AJAX Requests
*/

Route::get('get-supplier/{req_id}/{budget}/{item}/{account}/{supplier}', 'Retirements\RetirementController@getSupplier');
Route::get('get_ref_no/{req_id}/{ref_no}', 'Retirements\Retirements\RetirementController@getRefNo');

Route::get('/edit-expense-retirement-line/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@editExpenseRetirement');
Route::get('/send-expense-retirement-line/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@sendExpenseRetirement');

Route::get('/update-expense-retirement-supplier/{data_id}/{supplier_id}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementSupplier');
Route::get('/update-expense-retirement-ref_no/{data_id}/{ref_no}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementRefNo');
Route::get('/update-expense-retirement-purchase_date/{data_id}/{purchase_date}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementPurchaseDate');
Route::get('/update-expense-retirement-item_name/{data_id}/{item_name}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementItemName');
Route::get('/update-expense-retirement-unit_measure/{data_id}/{unit_measure}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementUnitMeasure');
Route::get('/update-expense-retirement-quantity/{data_id}/{quantity}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementQuantity');
Route::get('/update-expense-retirement-unit_price/{data_id}/{unit_price}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementUnitPrice');
Route::get('/update-expense-retirement-vat/{data_id}/{vat}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementVat');
Route::get('/update-expense-retirement-account/{data_id}/{account}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementAccount');
Route::get('/update-expense-retirement-description/{data_id}/{description}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirementDescription');
Route::get('/bring-expense-retirement-to-permanent-table/{user_id}/{ret_no}', 'ExpenseRetirements\ExpenseRetirementController@updateExpenseRetirement');


/*
 	END AJAX Routes
*/
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
