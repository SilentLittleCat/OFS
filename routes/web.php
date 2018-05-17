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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('attachment/{md5}', [
    'as' => 'attachment.download',
    'uses' => 'Web\AttachmentController@download',
]);
Route::get('image/{md5}', [
    'as' => 'attachment.image',
    'uses' => 'Web\AttachmentController@image',
]);

// Route::get('/test',function() {

//     $password = bcrypt('admin');
//     dd($password);
//     dd(dict()->get('user_info','work_type'));
//     $password = bcrypt('admin');
//     dd($password);
//     $obj = new \App\Services\User\Info();
//     $obj = $obj->find(1);
//     var_dump($obj);exit;
// });

Route::get('login', 'Web\Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Web\Auth\LoginController@login');
Route::post('logout', 'Web\Auth\LoginController@logout')->name('logout');

Route::get('register', 'Web\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Web\Auth\RegisterController@register');

Route::get('/test', 'TestController@index');
Route::post('/test/submit', 'TestController@submit')->name('test.submit');

Route::any('/wechat', 'WeChatController@serve');

Route::get('/', 'Web\IndexController@index')->name('index');
Route::get('/food-introduction', 'Web\IndexController@introduce')->name('food.introduce');
Route::get('/activity', 'Web\IndexController@activity')->name('activity');

Route::group(['middleware' => 'auth'], function() {
	Route::post('/webupload', 'Admin\Base\AttachmentController@webupload')->name('webupload');

	Route::post('/orders/pay', 'Web\OrderController@generate')->name('orders.generate');

	// Route::get('/money/index', 'Web\MoneyController@index')->name('money.index');
	Route::get('/money/payIndex', 'Web\MoneyController@payIndex')->name('money.pay.index');
	Route::get('/money/payCode', 'Web\MoneyController@payCode')->name('money.pay.code');
	Route::post('/money/payConfirm', 'Web\MoneyController@payConfirm')->name('money.pay.confirm');
	Route::get('/money/backIndex', 'Web\MoneyController@backIndex')->name('money.back.index');
	Route::post('/money/back', 'Web\MoneyController@back')->name('money.back');
	Route::post('/money/pay', 'Web\MoneyController@pay')->name('money.pay');

	Route::get('/users', 'Web\UserController@index')->name('users.index');
	Route::get('/users/info', 'Web\UserController@info')->name('users.info');
	Route::get('/users/weight', 'Web\UserController@weight')->name('users.weight');
	Route::get('/users/edit', 'Web\UserController@edit')->name('users.edit');
	Route::post('/users/update', 'Web\UserController@update')->name('users.update');
	Route::get('/users/address', 'Web\UserController@address')->name('users.address');
	Route::get('/users/address/add', 'Web\UserController@addAddress')->name('users.address.add');
	Route::get('/users/remain-money', 'Web\UserController@remainMoney')->name('users.remain.money');
	Route::post('/users/address/store', 'Web\UserController@storeAddress')->name('users.address.store');
	Route::get('/users/score', 'Web\UserController@score')->name('users.score');
	Route::get('/users/score-record', 'Web\UserController@showScoreRecord')->name('users.score.record');
	Route::post('/users/score-change', 'Web\UserController@scoreChange')->name('users.score.change');
	Route::get('/users/show-change', 'Web\UserController@showScoreChange')->name('users.score.show-change');
	Route::get('/users/recommend', 'Web\UserController@recommend')->name('users.recommend');
	Route::get('/users/coupon', 'Web\UserController@coupon')->name('users.coupon');
	Route::get('/users/contact-us', 'Web\UserController@contact')->name('users.contact');	

	Route::get('/orders', 'Web\OrderController@index')->name('orders.index');
	Route::get('/orders/after-sale', 'Web\OrderController@afterSale')->name('orders.after.sale');
	Route::get('/orders/after-sale/{id}', 'Web\OrderController@showAfterSale')->name('orders.after.sale.show');
	Route::post('/orders/create-after-sale', 'Web\OrderController@createAfterSale')->name('orders.create.after.sale');
	Route::get('/orders/apply-after-sale', 'Web\OrderController@applyAfterSale')->name('orders.apply.after.sale');
	Route::post('/orders/ajax-after-sale', 'Web\OrderController@ajaxAfterSale')->name('orders.ajax.after.sale');
	Route::get('/orders/send', 'Web\OrderController@send')->name('orders.send');
	Route::post('/orders/changeTime', 'Web\OrderController@changeTime')->name('orders.change.time');
	Route::get('/orders/changeAdd', 'Web\OrderController@changeAdd')->name('orders.change.add');
	Route::post('/orders/changeAddress', 'Web\OrderController@changeAddress')->name('orders.change.address');
	Route::get('/orders/share', 'Web\OrderController@share')->name('orders.share');
	Route::get('/orders/share-code', 'Web\OrderController@shareCode')->name('orders.share.code');
	Route::post('/orders/confirm', 'Web\OrderController@confirm')->name('orders.confirm');
	Route::get('/orders/{order_id}', 'Web\OrderController@show')->name('orders.show');
	Route::get('/orders/award/index', 'Web\OrderController@award')->name('orders.award');
	Route::get('/orders/award/money', 'Web\OrderController@money')->name('orders.money');
	Route::get('/orders/award/{order_id}', 'Web\OrderController@showAward')->name('orders.award.show');
});