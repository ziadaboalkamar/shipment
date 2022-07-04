<?php

use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\cityController;
use App\Http\Controllers\Dashboard\ProfileController;
use Illuminate\Support\Facades\Artisan;

Auth::routes(['register' => false]);
Route::group(['middleware' => 'auth'], function () {
    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::post('/settings', 'SettingController@store')->name('settings.store');
});

Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    return "cache clear";
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/definations/company', 'setting\definationsController@company')->name('company');
Route::post('/definations/storeCompany', 'setting\definationsController@storeCompany')->name('storeCompany');


Route::get('/definations/city', 'setting\definationsController@addCity')->name('addCity');
Route::get('/definations/branch', 'setting\definationsController@addBranch')->name('addBranch');
Route::post('/definations/branch', 'setting\definationsController@storeBranch')->name('storeBranch');
Route::get('/getManateqByMa7afza', 'generalController@getManateqByMa7afza')
    ->name('getManateqByMa7afza');
Route::get('/getCommertialnameBy3amil', 'generalController@getCommertialnameBy3amil')
    ->name('getCommertialnameBy3amil');
Route::get('/getTawsilByManteka', 'generalController@getTawsilByManteka')
    ->name('getTawsilByManteka');


    Route::get('/tas3ir/3amil-5as', 'tas3irController@tas3ir_3amil_5as')->name('tas3ir.3amil_5as');
Route::post('/tas3ir/save-3amel', 'tas3irController@save_3amel')->name('save_tas3ir_3amel');
Route::post('/tas3ir/save-ta7wel', 'tas3irController@save_ta7wel')->name('save_tas3ir_ta7wel');
Route::post('/tas3ir/save-3amel-5as', 'tas3irController@save_3amel_5as')->name('save_3amel_5as');

Route::get('/tas3ir/mandouben', 'tas3irController@tas3ir_mandouben')->name('tas3ir.mandouben');
Route::get('/tas3ir/getNameByType/', 'tas3irController@getNameByType')->name('tas3ir.mandouben.getNameByType');
Route::post('/tas3ir/save-mandouben', 'tas3irController@saveMandobe')->name('save_tas3ir_mandouben');
Route::get('/getManateqAndTas3irMandobByMa7afza', 'tas3irController@getManateqAndTas3irMandobByMa7afza')->name('getManateqAndTas3irMandobByMa7afza');



Route::get('/getManateqAndTas3ir5asByMa7afza', 'tas3irController@getManateqAndTas3ir5asByMa7afza')->name('getManateqAndTas3ir5asByMa7afza');

//shipments

Route::get('shiments', 'shipmentsController@HomePage')->name('home-page');
Route::get('shiments/{type}', 'shipmentsController@shipments')->name('shiments');
Route::get('shiment/create', 'shipmentsController@create')->name('shiments.create');
Route::post('shiment/store', 'shipmentsController@store')->name('shiments.store');
Route::get('shiment/edit/{code}', 'shipmentsController@edit')->name('shiments.edit');
Route::post('shiment/update', 'shipmentsController@update')->name('shiments.update');
Route::get('shiment/search', 'shipmentsController@shipmentsSearch')->name('sipments.search');
Route::get('shiment/deleteShipment/{code}', 'shipmentsController@deleteShipment')->name('shiments.deleteShipment');


Route::get('shiment/editview', 'shipmentsController@editview')->name('shiments.editview');
Route::get('shiment/status', 'shipmentsController@status')->name('shiments.status');
Route::get('shiment/print', 'shipmentsController@print')->name('shiments.print');
Route::get('shiment/estlamGet', 'shipmentsController@estlamGet')->name('shiments.estlamGet');
Route::get('shiment/changeToArchive', 'shipmentsController@changeToArchive')->name('shiments.changeToArchive');
Route::get('shiment/isCodeUsed', 'shipmentsController@isCodeUsed')->name('shiments.isCodeUsed');
Route::post('shiment/t7weel_manual', 'shipmentsController@t7weel_manual')->name('shiments.t7weel_manual');


Route::get('getShipmentsByCode', 'shipmentsController@getShipmentsByCode')->name('getShipmentsByCode');
Route::get('shipment/t7wel_qr', 'shipmentsController@t7wel_qr')->name('shipment.t7wel_qr');
Route::any('shipment/t7wel_qr_save', 'shipmentsController@t7wel_qr_save')->name('shipment.t7wel_qr_save');
Route::get('shipment/taslim_qr', 'shipmentsController@taslim_qr')->name('shipment.taslim_qr');
Route::any('shipment/taslim_qr_save', 'shipmentsController@taslim_qr_save')->name('shipment.taslim_qr_save');


Route::get('shipment/shipment_search', 'shipmentsController@shipment_bar_search')->name('shipment_bar_search');

//end shipments

//frou3
Route::get('/frou3/export', 'frou3Controller@export')->name('frou3.export');
Route::get('/frou3/import', 'frou3Controller@import')->name('frou3.import');

 //t7wel sho7nat
Route::get('/frou3_t7wel_sho7nat_qr', 'frou3Controller@frou3_t7wel_sho7nat_qr')->name('frou3_t7wel_sho7nat_qr');
Route::get('/frou3_t7wel_sho7nat_qr', 'frou3Controller@frou3_t7wel_sho7nat_qr')->name('frou3_t7wel_sho7nat_qr');
Route::any('/frou3_t7wel_sho7nat_qr_save', 'frou3Controller@frou3_t7wel_sho7nat_qr_save')->name('frou3_t7wel_sho7nat_qr_save');
Route::get('/accept_frou3_t7wel', 'frou3Controller@accept_frou3_t7wel')->name('accept_frou3_t7wel');
Route::post('/accept_frou3_t7wel', 'frou3Controller@accept_frou3_t7wel_save')->name('accept_frou3_t7wel_save');
Route::get('/accept_t7wel_get', 'frou3Controller@accept_t7wel_get')->name('accept_t7wel_get');
Route::post('/accept_frou3_t7wel_qr_save', 'frou3Controller@accept_frou3_t7wel_qr_save')->name('accept_frou3_t7wel_qr_save');


Route::get('/frou3_t7wel_sho7nat_manual', 'frou3Controller@frou3_t7wel_sho7nat_manual')->name('frou3_t7wel_sho7nat_manual');
Route::post('/frou3_t7wel_sho7nat_manual', 'frou3Controller@frou3_t7wel_sho7nat_manual_save')->name('frou3_t7wel_sho7nat_manual_save');


 //end t7wel sho7nat


//t7wel rag3
Route::get('/frou3_t7wel_rag3_qr', 'frou3Controller@frou3_t7wel_rag3_qr')->name('frou3_t7wel_rag3_qr');
Route::any('/frou3_t7wel_rag3_qr_save', 'frou3Controller@frou3_t7wel_rag3_qr_save')->name('frou3_t7wel_rag3_qr_save');

//endt7wel rag3
Route::get('/accept_frou3_rag3', 'frou3Controller@accept_frou3_rag3')->name('accept_frou3_rag3');
Route::post('/accept_frou3_rag3', 'frou3Controller@accept_frou3_rag3_save')->name('accept_frou3_rag3_save');
Route::get('/accept_rag3_get', 'frou3Controller@accept_rag3_get')->name('accept_rag3_get');
Route::post('/accept_frou3_rag3_qr_save', 'frou3Controller@accept_frou3_rag3_qr_save')->name('accept_frou3_rag3_qr_save');

Route::get('/frou3_t7wel_rag3_manual', 'frou3Controller@frou3_t7wel_rag3_manual')->name('frou3_t7wel_rag3_manual');
Route::post('/frou3_t7wel_rag3_manual', 'frou3Controller@frou3_t7wel_rag3_manual_save')->name('frou3_t7wel_rag3_manual_save');



//accounting

Route::get('/frou3/accounting/mosadad', 'frou3Controller@AccountingMosadad')->name('accounting.mosadad');
Route::post('/frou3/accounting/cancelTasdid', 'frou3Controller@cancelTasdid')->name('frou3.accounting.cancelTasdid');

Route::get('/frou3/accounting/notmosadad', 'frou3Controller@AccountingNotMosadad')->name('accounting.notmosadad');
Route::post('/frou3/accounting/tasdid', 'frou3Controller@tasdid')->name('frou3.accounting.tasdid');
//end accounting



///
//end frou3




// accounting
    //3amil
Route::get('/accounting/3amil/mosadad', 'accountingController@amilMosadad')->name('accounting.3amil.mosadad');
Route::post('/accounting/3amil/canselTasdid', 'accountingController@amilcanselTasdid')->name('accounting.3amil.canceltasdid');

Route::get('/accounting/3amil/notmosadad', 'accountingController@amilNotMosadad')->name('accounting.3amil.notmosadad');
Route::post('/accounting/3amil/tasdid', 'accountingController@amilTasdid')->name('accounting.3amil.tasdid');
    //end 3amil
    //mandoub
Route::get('/accounting/mandoubtaslim/mosadad', 'accountingController@mandoubtaslimMosadad')->name('accounting.mandoubtaslim.mosadad');
Route::post('/accounting/mandoubtaslim/canselTasdid', 'accountingController@mandoubtaslimCanselTasdid')->name('accounting.mandoubtaslim.canceltasdid');

Route::get('/accounting/mandoubtaslim/notmosadad', 'accountingController@mandoubTaslimNotMosadad')->name('accounting.mandoubtaslim.notmosadad');
Route::post('/accounting/mandoubtaslim/tasdid', 'accountingController@mandoubTaslimTasdid')->name('accounting.mandoubtaslim.tasdid');
            ///
Route::get('/accounting/mandoubestlam/mosadad', 'accountingController@mandoubestlamMosadad')->name('accounting.mandoubestlam.mosadad');
Route::post('/accounting/mandoubestlam/canselTasdid', 'accountingController@mandoubestlamcanselTasdid')->name('accounting.mandoubestlam.canceltasdid');

Route::get('/accounting/mandoubestlam/notmosadad', 'accountingController@mandoubestlamNotMosadad')->name('accounting.mandoubestlam.notmosadad');
Route::post('/accounting/mandoubestlam/tasdid', 'accountingController@mandoubestlamTasdid')->name('accounting.mandoubestlam.tasdid');
    //mandoub

    //loadmore
Route::get('/accounting/loadMore', 'accountingController@loadMore')->name('accounting.loadMore');


//end Accounting




//users Definations
Route::get('/users/add-client', 'setting\userdefinationsController@addClient')->name('addClient');
Route::post('/users/add-client', 'setting\userdefinationsController@storeClient')->name('storeClient');
Route::get('/users/editclient/{code}', 'setting\userdefinationsController@editclient')->name('editclient');
Route::post('/users/updateClient/{code}', 'setting\userdefinationsController@updateClient')->name('updateClient');


Route::get('/users/add-mandoub', 'setting\userdefinationsController@addMandoub')->name('addMandoub');
Route::post('/users/add-mandoub', 'setting\userdefinationsController@storeMandoub')->name('storeMandoub');
Route::get('/users/edit-mandoub/{code}', 'setting\userdefinationsController@editMandoub')->name('editMandoub');
Route::post('/users/updateMandoub/{code}', 'setting\userdefinationsController@updateMandoub')->name('updateMandoub');


Route::get('/users/add-user', 'setting\userdefinationsController@adduser')->name('addUser');
Route::post('/users/add-user', 'setting\userdefinationsController@storeUser')->name('storeUser');
Route::get('/users/edit-user/{code}', 'setting\userdefinationsController@editUser')->name('editUser');

Route::post('/users/updateUser/{code}', 'setting\userdefinationsController@updateUser')->name('updateUser');


Route::get('/users/registrationRequest', 'setting\userdefinationsController@registrationRequest')->name('registrationRequest');
Route::post('/users/registrationRequest', 'setting\userdefinationsController@registrationRequestSave')->name('registrationRequestSave');
Route::get('/users/commercialNames', 'setting\userdefinationsController@commercialNames')->name('commercialNames');



//end Definations
//Statrt Khazna
Route::get('/settings/Khazna', 'KhaznaController@create')->name('Khazna.create');
Route::post('/settings/Khazna/store', 'KhaznaController@store')->name('Khazna.store');

Route::get('/settings/addUserTo5azma', 'KhaznaController@addUserTo5azma')->name('Khazna.adduser');



Route::get('/permissions', 'permissionController@index')->name('permissions');
Route::post('/permissions/store', 'permissionController@store')->name('permissions.store');


Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['guest']], function () {
    Route::get('/main', function () {
        return view('auth.login');
    });


});

Route::get('/clear', function () {
    Artisan ::call('cache:clear');
    Artisan::call('config:cache');
});
Route::group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {


        Route::get('/dashboard','HomeController@index')->name('dashboard');
        Route::get('/dashboard/home','HomeController@index')->name('dashboard.home');
        // Route::prefix('dashboard')->namespace('Dashboard')->middleware(['auth'])->name('dashboard.')->group(function () {
        //     // Route::resource('roles', 'RoleController');
        //     // Route::resource('users', 'UserController');
        //     //doctors
        //     Route::delete('/doctors/bulk_delete', 'DoctorController@bulkDelete')->name('doctors.bulk_delete');
        //     Route::resource('doctors', 'DoctorController')->except('show');
        //     Route::get('/doctors/data','DoctorController@data')->name('doctors.data');
        //     Route::get('/doctors/forclogout/{id}','DoctorController@forcLogout')->middleware('permission:update-logoutdoctors')->name('doctors.forclogout');
        //     //patients
        //     Route::delete('/patients/bulk_delete', 'PatientController@bulkDelete')->name('patients.bulk_delete');
        //     Route::resource('patients', 'PatientController')->except('show');
        //     Route::get('/patients/data','PatientController@data')->name('patients.data');
        //     Route::get('/patients/forclogout/{id}','PatientController@forcLogout')->middleware('permission:update-logoutpatients')->name('patients.forclogout');

        //     //first aids
        //     Route::delete('/firstaids/bulk_delete', 'FirstAidController@bulkDelete')->name('firstaids.bulk_delete');
        //     Route::resource('firstaids', 'FirstAidController')->except('show');;
        //     Route::get('/firstaids/data','FirstAidController@data')->name('firstaids.data');
        //     //first aids children
        //     Route::delete('/firstaidchildren/bulk_delete', 'FirstAidChildController@bulkDelete')->name('firstaidchildren.bulk_delete');
        //     Route::resource('firstaidchildren', 'FirstAidChildController')->except('show');;
        //     Route::get('/firstaidchildren/data','FirstAidChildController@data')->name('firstaidchildren.data');
        //     //emergenc
        //     Route::delete('/emergencs/bulk_delete', 'EmergencController@bulkDelete')->name('emergencs.bulk_delete');
        //     Route::resource('emergencs', 'EmergencController')->except('show');;
        //     Route::get('/emergencs/data','EmergencController@data')->name('emergencs.data');
        //     //emergenc
        //     Route::delete('/emergencchildren/bulk_delete', 'EmergencChildController@bulkDelete')->name('emergencchildren.bulk_delete');
        //     Route::resource('emergencchildren', 'EmergencChildController')->except('show');
        //     Route::get('/emergencchildren/data','EmergencChildController@data')->name('emergencchildren.data');
        //     //profile
        //     Route::resource('profiles','ProfileController');


        // });

    });

Route::get('/home', 'HomeController@index')->name('home');
