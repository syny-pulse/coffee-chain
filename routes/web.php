<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Demo\DemoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pos\SupplierController;
use App\Http\Controllers\Pos\CategoryController;
use App\Http\Controllers\Pos\ProductController;
use App\Http\Controllers\Pos\PurchaseController;
use App\Http\Controllers\Pos\StockController;
use App\Http\Controllers\Pos\CustomerController;
use App\Http\Controllers\Pos\UnitController;
use App\Http\Controllers\Pos\InvoiceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Pos\DefaultController;
use App\Http\Controllers\Pos\DashboardController;
use App\Http\Controllers\Pos\ForecastController;



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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::controller(DemoController::class)->group(function ()
 {
    Route::get('/about', 'Index')->name('about.page')->middleware('check');
    Route::get('/contact', 'ContactMethod')->name('cotact.page');
});


Route::middleware('auth')->group(function(){

    //------------------------------------- Admin All Route------------------------------------------
   Route::controller(AdminController::class)->group(function ()
    {
       Route::get('/admin/logout', 'destroy')->name('admin.logout');
       Route::get('/admin/profile', 'Profile')->name('admin.profile');
       Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
       Route::post('/store/profile', 'StoreProfile')->name('store.profile');
       Route::get('/change/password', 'ChangePassword')->name('change.password');
       Route::post('/update/password', 'UpdatePassword')->name('update.password');
   });

   //------------------------------------Supplier routes-----------------------------------------------
   Route::controller(SupplierController::class)->group(function ()
   {
       Route::get('/supplier/all', 'SupplierAll')->name('supplier.all');
       Route::get('/supplier/add', 'SupplierAdd')->name('supplier.add');
       Route::post('/supplier/store', 'SupplierStore')->name('supplier.store');
       Route::get('/supplier/edit/{id}', 'SupplierEdit')->name('supplier.edit');
       Route::post('/supplier/update', 'SupplierUpdate')->name('supplier.update');
       Route::get('/supplier/delete/{id}', 'SupplierDelete')->name('supplier.delete');
   });

   //------------------------------------- Customer All Route ---------------------------------------------
   Route::controller(CustomerController::class)->group(function ()
   {
       Route::get('/customer/all', 'CustomerAll')->name('customer.all');
       Route::get('/customer/add', 'CustomerAdd')->name('customer.add');
       Route::post('/customer/store', 'CustomerStore')->name('customer.store');
       Route::get('/customer/edit/{id}', 'CustomerEdit')->name('customer.edit');
       Route::post('/customer/update', 'CustomerUpdate')->name('customer.update');
       Route::get('/customer/delete/{id}', 'CustomerDelete')->name('customer.delete');
       Route::get('/credit/customer', 'CreditCustomer')->name('credit.customer');
       Route::get('/credit/customer/print/pdf', 'CreditCustomerPrintPdf')->name('credit.customer.print.pdf');
       Route::get('/customer/edit/invoice/{invoice_id}', 'CustomerEditInvoice')->name('customer.edit.invoice');
       Route::post('/customer/update/invoice/{invoice_id}', 'CustomerUpdateInvoice')->name('customer.update.invoice');
       Route::get('/customer/invoice/details/{invoice_id}', 'CustomerInvoiceDetails')->name('customer.invoice.details.pdf');
       Route::get('/paid/customer', 'PaidCustomer')->name('paid.customer');
       Route::get('/unpaid/customer', 'unPaidCustomer')->name('unpaid.customer');
       Route::get('/unpaid/customer/print/pdf', 'unPaidCustomerPrintPdf')->name('paid.customer.print.pdf');
       Route::get('/customer/wise/report', 'CustomerWiseReport')->name('customer.wise.report');
       Route::get('/customer/wise/credit/report', 'CustomerWiseCreditReport')->name('customer.wise.credit.report');
       Route::get('/customer/wise/paid/report', 'CustomerWisePaidReport')->name('customer.wise.paid.report');
   });

   //------------------------- Unit All Route ---------------------------------
   Route::controller(UnitController::class)->group(function ()
   {
       Route::get('/unit/all', 'UnitAll')->name('unit.all');
       Route::get('/unit/add', 'UnitAdd')->name('unit.add');
       Route::post('/unit/store', 'UnitStore')->name('unit.store');
       Route::get('/unit/edit/{id}', 'UnitEdit')->name('unit.edit');
       Route::post('/unit/update', 'UnitUpdate')->name('unit.update');
       Route::get('/unit/delete/{id}', 'UnitDelete')->name('unit.delete');
   });

   //---------------------------- Category All Route----------------------------
   Route::controller(CategoryController::class)->group(function ()
   {
       Route::get('/category/all', 'CategoryAll')->name('category.all');
       Route::get('/category/add', 'CategoryAdd')->name('category.add');
       Route::post('/category/store', 'CategoryStore')->name('category.store');
       Route::get('/category/edit/{id}', 'CategoryEdit')->name('category.edit');
       Route::post('/category/update', 'CategoryUpdate')->name('category.update');
       Route::get('/category/delete/{id}', 'CategoryDelete')->name('category.delete');
   });

   //---------------------------- product All Route----------------------------
   Route::controller(ProductController::class)->group(function ()
   {
       Route::get('/product/all', 'ProductAll')->name('product.all');
       Route::get('/product/add', 'ProductAdd')->name('product.add');
       Route::post('/product/store', 'ProductStore')->name('product.store');
       Route::get('/product/edit/{id}', 'ProductEdit')->name('product.edit');
       Route::post('/product/update', 'ProductUpdate')->name('product.update');
       Route::get('/product/delete/{id}', 'ProductDelete')->name('product.delete');
   });

   //---------------------------- purchase All Route----------------------------
   Route::controller(PurchaseController::class)->group(function ()
   {
       Route::get('/purchase/all', 'PurchaseAll')->name('purchase.all');
       Route::get('/purchase/add', 'PurchaseAdd')->name('purchase.add');
       Route::post('/purchase/store', 'PurchaseStore')->name('purchase.store');
       Route::get('/purchase/delete/{id}', 'PurchaseDelete')->name('purchase.delete');
       Route::get('/purchase/pending', 'PurchasePending')->name('purchase.pending');
       Route::get('/purchase/approve{id}', 'PurchaseApprove')->name('purchase.approve');
       Route::post('/purchase/approve-all', 'PurchaseApproveAll')->name('purchase.approveAll');
       Route::get('/daily/purchase/report', 'DailyPurchaseReport')->name('daily.purchase.report');
       Route::get('/daily/purchase/pdf', 'DailyPurchasePdf')->name('daily.purchase.pdf');
   });

    // Invoice All Route
   Route::controller(InvoiceController::class)->group(function ()
   {
       Route::get('/invoice/all', 'InvoiceAll')->name('invoice.all');
       Route::get('/invoice/details/{id}', 'InvoiceDetails')->name('invoice.details');
       Route::get('/invoice/add', 'invoiceAdd')->name('invoice.add');
       Route::post('/invoice/store', 'InvoiceStore')->name('invoice.store');
       Route::get('/invoice/pending/list', 'PendingList')->name('invoice.pending.list');
       Route::get('/invoice/delete/{id}', 'InvoiceDelete')->name('invoice.delete');
       Route::get('/invoice/approve/{id}', 'InvoiceApprove')->name('invoice.approve');
       Route::post('/approval/store/{id}', 'ApprovalStore')->name('approval.store');
       Route::get('/print/invoice/list', 'PrintInvoiceList')->name('print.invoice.list');
       Route::get('/print/invoice/{id}', 'PrintInvoice')->name('print.invoice');
       Route::get('/scan/invoice/{id}', 'ScanpdfInvoice')->name('invoice.pdf.scan');
       Route::get('/daily/invoice/report', 'DailyInvoiceReport')->name('daily.invoice.report');
       Route::get('/daily/invoice/pdf', 'DailyInvoicePdf')->name('daily.invoice.pdf');


   });

    // ------------------------Stock All Route-----------------------
   Route::controller(StockController::class)->group(function () {
       Route::get('/stock/report', 'StockReport')->name('stock.report');
       Route::get('/stock/report/pdf', 'StockReportPdf')->name('stock.report.pdf');
       Route::get('/stock/supplier/wise', 'StockSupplierWise')->name('stock.supplier.wise');
       Route::get('/supplier/wise/pdf', 'SupplierWisePdf')->name('supplier.wise.pdf');
       Route::get('/product/wise/pdf', 'ProductWisePdf')->name('product.wise.pdf');
   });

     // ------------------------Stock All Route-----------------------
     Route::controller(ForecastController::class)->group(function () {
        Route::get('/forecast/overview', 'forecastOverview')->name('forecast.overview');

        Route::get('/forecast/trend', 'ForecastTrend')->name('forecast.trend');
        Route::post('/forecast/trend/data', 'trendData')->name('forecast.trend.data');


        Route::post('/trend/period/data', 'getYearlyTrendData')->name('forecast.yearly.trend.data');
        Route::post('/trend/period/product', 'getProductTrendData')->name('forecast.product.trend.data');

        Route::get('/forecast/demand', 'ForecastDemand')->name('forecast.demand');
        Route::post('/forecast/demand/data', 'ForecastdemandData')->name('forecast.demand.data');




        Route::get('forecast/daily', 'daily')->name('forecast.daily');
        Route::post('forecast/daily/data','dailyData')->name('forecast.daily.data');

        Route::get('forecast/weekly', 'weekly')->name('forecast.weekly');
        Route::post('forecast/weekly/data','weeklyData')->name('forecast.weekly.data');

        Route::get('forecast/monthly', 'monthly')->name('forecast.monthly');
        Route::post('forecast/monthly/data',  'monthlyData')->name('forecast.monthly.data');

        // Route::get('/demand', [ForecastController::class, 'demand'])->name('forecast.demand');
        // Route::post('/demand/data', [ForecastController::class, 'demandData'])->name('forecast.demand.data');

        // Route::post('/trend/data', [ForecastController::class, 'trendData'])->name('forecast.trend.data');

        // Route::get('/supplier/wise/pdf', 'SupplierWisePdf')->name('supplier.wise.pdf');
        // Route::get('/product/wise/pdf', 'ProductWisePdf')->name('product.wise.pdf');
    });



   }); //end group middleware

   //------------------------------ Default All Route ----------------------------
Route::controller(DefaultController::class)->group(function ()
{
   Route::get('/get-category', 'GetCategory')->name('get-category');
   Route::get('/get-product', 'GetProduct')->name('get-product');
   Route::get('/check-product', 'GetStock')->name('check-product-stock');
   Route::get('/check-unit-price', 'GetUnitPrice')->name('check-unit-price');

});





// Group routes related to the dashboard
// Route::middleware(['auth', 'verified'])->group(function () {
//         // Main dashboard route
//         Route::get('/dashboard', [DashboardController::class, 'Dashboard'])->name('dashboard');
//         Route::get('/get-total-sales', [DashboardController::class, 'getTotalSales'])->name('get-total-sales');
//         Route::get('/get-outstanding-balance', [DashboardController::class, 'getOutstandingBalance'])->name('get-outstanding-balance');
//     });


   Route::controller(DashboardController::class)->group(function () {
                Route::get('/dashboard', 'Dashboard')->name('dashboard');
                Route::get('/get-total-sales', 'getTotalSales')->name('get-total-sales');
                Route::get('/get-outstanding-balance', 'getOutstandingBalance')->name('get-outstanding-balance');
            });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
