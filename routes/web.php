<?php

use Illuminate\Support\Facades\Auth;
use App\Country;

Route::get('/', function(){
    $countries = Country::all();
    return view('welcome',['countries' => $countries]);
});

Auth::routes();


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/ryde-share/{id}/{start_date}/{end_date}', 'HomeController@rydeShare')->name('rydeShare');

Route::get('passGenerate', 'PassGenerateController@passGenerate')->name('passGenerate');

Route::get('reset-password/{token}', 'ResetPasswordController@resetPasswordForm')->name('reset-password');
Route::post('resetPasswordSubmit', 'ResetPasswordController@resetPasswordSubmit')->name('resetPassword.update');
Route::get('verify-email/{token}', 'VerifyEmailController@verifyEmail')->name('verify-email');

Route::get('forget-password', 'ForgetPasswordController@forgetPasswordForm')->name('forget-password');

Route::post('forgotPassword', 'ForgetPasswordController@forgotPassword')->name('forgotPassword');
Route::post('addBecomeADealer', 'HomeController@addBecomeADealer')->name('addBecomeADealer');


Route::group(['namespace' => 'Admin', 'as' => 'admin::', 'prefix' => 'admin'], function(){
    Route::get('login', 'LoginController@index')->name('login');
    Route::post('login', 'LoginController@loginCheck')->name('loginCheck');

    Route::group(['middleware' => ['checkAdmin', 'adminLanguage']], function(){
        Route::get('admin', 'AdminController@index')->name('admin');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('editProfile', 'AdminController@editProfile')->name('editProfile');

        Route::get('password', 'PasswordController@index')->name('password');

        Route::post('changePassword', 'PasswordController@changePassword')->name('changePassword');

        Route::get('changeThemes/{id}', 'AdminController@changeThemes')->name('changeThemes');
        Route::get('changeThemesMode/{local}', 'AdminController@changeThemesMode')->name('changeThemesMode');
        Route::get('getCount', 'AdminController@getCount')->name('getCount');
        Route::get('viewLoad', 'AdminController@viewLoad')->name('viewLoad');
        Route::resource('emailString', 'EmailStringController');


        Route::resource('admins', 'SubAdminController');
        Route::resource('body', 'BodyController');
        Route::resource('brand', 'BrandController');
        Route::resource('door', 'DoorController');
        Route::resource('engine', 'EngineController');
        Route::resource('fuel', 'FuelController');
        Route::resource('gearbox', 'GearboxController');
        Route::resource('insurance', 'InsuranceController');
        Route::resource('Language', 'LanguageController');
        Route::resource('modelYear', 'ModelYearController');
        Route::resource('color', 'ColorController');
        Route::resource('country', 'CountryController');
        Route::resource('category', 'CategoryController');
        Route::resource('feature', 'FeatureController');
        Route::resource('option', 'OptionController');

        Route::resource('languageString', 'LanguageStringController');
        Route::resource('dealer', 'DealerController');
        Route::resource('abilities', 'AbilityController');
        Route::resource('roles', 'RoleController');
        Route::get('addBranch/{id}/{company_id}', 'DealerController@addBranch')->name('addBranch');
        Route::get('editBranch/{dealer_id}/{branch_id}', 'DealerController@editBranch')->name('editBranch');
        Route::get('viewRyde/{company_id}/{branch_id}', 'RydeController@viewBranchRyde')->name('viewRyde');
        Route::get('branchDetails/{id}', 'DealerController@branchDetails')->name('branchDetails');
        Route::get('reportProblem', 'ReportProblemController@index')->name('reportProblem');
        Route::delete('reportProblemDelete/{id}', 'ReportProblemController@destroy')->name('reportProblemDelete');
        Route::get('reportProblemDetails/{id}', 'ReportProblemController@show')->name('reportProblemDetails');
        Route::get('contactUs', 'ContactUsController@index')->name('contactUs');
        Route::delete('contactUsDelete/{id}', 'ContactUsController@destroy')->name('contactUsDelete');
        Route::post('branchAdd', 'DealerController@branchAdd')->name('branchAdd');
        Route::resource('vehicle', 'VehicleController');
        Route::post('getBranch', 'VehicleController@getBranch');
        Route::resource('announcement', 'AnnouncementController');
        Route::resource('page', 'PageController');
        Route::get('setting', 'SettingController@index')->name('setting');
        Route::post('settingUpdate', 'SettingController@store')->name('settingUpdate');

        Route::resource('ryde', 'RydeController');
        Route::post('getModelImage', 'RydeController@getModelImage');
        Route::post('getRyde', 'VehicleController@getRyde');

        Route::get('rydeDetails/{id}', 'RydeController@rydeDetails')->name('rydeDetails');
        Route::get('changeStatus/{id}/{status}', 'VehicleController@changeStatus')->name('changeStatus');


        Route::get('vehicleDetails/{id}', 'VehicleController@vehicleDetails')->name('vehicleDetails');
        Route::get('vehicleNotAvailable/{id}', 'VehicleController@vehicleNotAvailable')->name('vehicleNotAvailable');
        Route::post('updateVehicleNotAvailable', 'VehicleController@updateVehicleNotAvailable')->name('updateVehicleNotAvailable');

        Route::get('vehicleNotAvailableDelete/{id}/{vehicle_id}', 'VehicleController@vehicleNotAvailableDelete')->name('vehicleNotAvailableDelete');

        Route::get('commission', 'CommissionsController@index')->name('commission.index');
        Route::get('getCommission', 'CommissionsController@getCommission')->name('getCommission');
        Route::post('amountTransfer', 'CommissionsController@amountTransfer')->name('amountTransfer');

        Route::get('past-transaction/{id}', 'CommissionsController@pastTransactionView')->name('past-transaction');
        Route::get('getPastTransaction/{id}', 'CommissionsController@getPastTransaction')->name('getPastTransaction');
        Route::post('commissionStatusChange', 'CommissionsController@commissionStatusChange')->name('commissionStatusChange');
        Route::post('getBookingDetails', 'CommissionsController@getBookingDetails')->name('getBookingDetails');


        Route::get('driverRequirement/{id}', 'DriverRequirementController@index')->name('driverRequirement.index');
        Route::get('driverRequirement/{id}/create', 'DriverRequirementController@create')->name('driverRequirement.create');
        Route::post('driverRequirement/store', 'DriverRequirementController@store')->name('driverRequirement.store');
        Route::get('driverRequirement/edit/{id}/{country_id}', 'DriverRequirementController@edit')->name('driverRequirement.edit');
        Route::delete('driverRequirement/delete/{id}/{country_id}', 'DriverRequirementController@destroy')->name('driverRequirement.destroy');

        Route::get('dealerChangeStatus/{id}/{stats}', 'DealerController@dealerChangeStatus')->name('dealerChangeStatus');

        Route::get('categoryRydeDetails/{id}', 'CategoryController@categoryRydeDetails')->name('categoryRydeDetails');

        Route::get('addCategoryVehicle/{id}', 'CategoryController@addCategoryVehicle')->name('addCategoryVehicle');

        Route::post('getVehicle', 'CategoryController@getVehicle');
        Route::post('categoryVehicleAdd', 'CategoryController@categoryVehicleAdd')->name('categoryVehicleAdd');

        Route::get('ryde/create/{company_id}/{branch_id}', 'VehicleController@create')->name('create');
        Route::get('ryde/edit/{branch_id}/{vehicle_id}', 'VehicleController@edit')->name('edit');
        Route::post('getModel', 'VehicleController@getModel')->name('getModel');

        Route::delete('deleteCategoryVehicle/{id}', 'CategoryController@deleteCategoryVehicle')->name('deleteCategoryVehicle');

        Route::resource('booking', 'BookingController');
        Route::get('BookingDetails/{id}', 'BookingController@BookingDetails')->name('BookingDetails');
        Route::post('adjustmentSave', 'BookingController@adjustmentSave')->name('adjustmentSave');

        Route::resource('globalExtra', 'GlobalExtraController');
        Route::resource('users', 'UserController');
        Route::get('userChangeStatus/{id}/{stats}', 'UserController@userChangeStatus')->name('userChangeStatus');

        Route::resource('becomeADealer', 'BecomeADealerController');
        Route::get('becomeAdealerDetails/{id}', 'BecomeADealerController@becomeAdealerDetails')->name('becomeAdealerDetails');
        Route::get('becomeADealerChangeStatus/{id}/{stats}', 'BecomeADealerController@becomeADealerChangeStatus')->name('becomeADealerChangeStatus');
        Route::resource('reportModel', 'ReportModelController');

        Route::post('logout', 'LoginController@logout')->name('logout');
    });
});

Route::group(['namespace' => 'Dealer', 'as' => 'dealer::', 'prefix' => 'dealer'], function(){
    Route::get('changeThemes/{id}', 'AdminController@changeThemes')->name('changeThemes');
    Route::get('changeThemesMode/{local}', 'AdminController@changeThemesMode')->name('changeThemesMode');

    Route::group(['middleware' => ['CheckCompany', 'adminLanguage']], function(){
        Route::get('admin', 'AdminController@index')->name('admin');
        Route::resource('branch', 'BranchController');

        Route::resource('vehicle', 'VehicleController');
        Route::post('getBranch', 'VehicleController@getBranch');
        Route::post('getModel', 'VehicleController@getModel')->name('getModel');
        Route::post('getRyde', 'VehicleController@getRyde')->name('getRyde');

        Route::get('changeStatus/{id}/{stats}', 'VehicleController@changeStatus')->name('changeStatus');


        Route::get('duplicateRecord/{id}', 'VehicleController@duplicateRecord')->name('duplicateRecord');


        Route::get('vehicleDetails/{id}', 'VehicleController@vehicleDetails')->name('vehicleDetails');
        Route::get('vehicleNotAvailable/{id}', 'VehicleController@vehicleNotAvailable')->name('vehicleNotAvailable');
        Route::post('vehicleSold', 'VehicleController@vehicleSold')->name('vehicleSold');

        Route::post('updateVehicleNotAvailable', 'VehicleController@updateVehicleNotAvailable')->name('updateVehicleNotAvailable');
        Route::get('ryde/{id}', 'VehicleController@index')->name('ryde');
        Route::get('ryde/create/{branch_id}/', 'VehicleController@create')->name('create');
        Route::get('ryde/edit/{branch_id}/{id}', 'VehicleController@edit')->name('ryde.edit');
        Route::get('withdraw', 'PaymentController@withdraw')->name('withdraw.request');
        Route::resource('payment', 'PaymentController');
        Route::get('vehicleNotAvailableDelete/{id}/{vehicle_id}', 'VehicleController@vehicleNotAvailableDelete')->name('vehicleNotAvailableDelete');
        Route::get('branchExtra/{id}', 'BranchExtraController@index')->name('branchExtra');
        Route::post('branchExtraSave', 'BranchExtraController@store')->name('branchExtraSave');

        Route::resource('extra', 'ExtraController');
        Route::resource('booking', 'BookingController');
        Route::get('BookingDetails/{id}', 'BookingController@BookingDetails')->name('BookingDetails');
        Route::resource('dealer', 'DealerController');
        Route::post('getVehicleForBooking', 'BookingController@getVehicleForBooking');
        Route::post('getVehicleDetails', 'BookingController@getVehicleDetails');
        Route::post('getFinalAmount', 'BookingController@getFinalAmount');
        Route::post('addBooking', 'BookingController@addBooking');
        
        Route::get('dealerChangePassword', 'PasswordController@index')->name('dealerChangePassword');

        Route::post('changePassword', 'PasswordController@changePassword')->name('changePassword');
        Route::resource('mainRyde', 'MainRydeController');

        Route::post('getExtrasAndSecurityDeposit', 'MainRydeController@getExtrasAndSecurityDeposit');

        Route::post('addReportModel', 'MainRydeController@addReportModel')->name('addReportModel');

    });
    Route::post('logout', 'AdminController@logout')->name('logout');
});

//\App\User::where('id', 6)->update(['password'=>bcrypt(123456789)]);
