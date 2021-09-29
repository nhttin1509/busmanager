<?php
/* Bắt đầu phần Route cho trang quản trị hệ thống */

Route::get('admin/login', function(){
    if(session()->has('admin')){
        return redirect('admin/');
    }
    else{
        return view('quantrivien.login');
    }
});

Route::post('admin/login', 'AdminController@checkLogin')->name('adminlogin');

Route::middleware('admincheck')->group(function (){
    Route::post('admin/logout', 'AdminController@logout')->name('adminlogout');
    Route::get('admin/', 'AdminController@thongke');

    Route::get('admin/thongke', 'AdminController@thongke');

//Phần khách hàng

    Route::get('admin/khachhang', 'AdminController@khachhang');

    Route:: get('admin/addkhachhang/{index?}', 'AdminController@addkhachhang');

    Route::post('admin/addcustomer', 'AdminController@addcustomer')->name('addcustomer');

    Route::get('admin/delkhachhang/{id}', 'AdminController@delcustomer');

    Route::get('admin/viewkhachhang/{id?}', 'AdminController@viewkhachhang');

//Phần chuyến xe

    Route::get('admin/chuyenxe', 'AdminController@chuyenxe');

    Route::get('admin/addchuyenxe/{id?}', 'AdminController@addchuyenxe');

    Route::post('admin/addchuyenxexl', 'AdminController@addchuyenxexl')->name('addchuyenxexl');

    Route::get('admin/delchuyenxe/{id?}', 'AdminController@delchuyenxe');

//Phần vé và reset chuyến xe

    Route::get('admin/ve', 'AdminController@ve');

    Route::get('admin/ticket/{index}/{id?}', 'AdminController@ticket');

    Route::post('admin/editticket', 'AdminController@editticket')->name('editticket');

//Phần loại xe

    Route::get('admin/loaixe', 'AdminController@loaixe');

    Route::post('admin/addbusmodel', 'AdminController@addbusmodel')->name('addbusmodel');

    Route::get('admin/delloaixe/{id}', 'AdminController@delbusmodel');

//Phần nhân viên

    Route::get('admin/nhanvien', 'AdminController@nhanvien');

    Route:: get('admin/addnhanvien/{index?}', 'AdminController@addnhanvien');

    Route::post('admin/addemployee','AdminController@addemployee')->name('addemployee');

    Route::get('admin/delnhanvien/{id}','AdminController@delemployee');

	Route::post('admin/userinfo','AdminController@userinfo')->name('userinfo');

//Phần xe

    Route::get('admin/xe', 'AdminController@xe');

    Route:: get('admin/addxe/{index?}', 'AdminController@addxe');

    Route::post('admin/addbus','AdminController@addbus')->name('addbus');

    Route::get('admin/delxe/{id}','AdminController@delbus');

//Phần trạm dừng

    Route::get('admin/tramdung', 'AdminController@tramdung');

    Route:: get('admin/addtramdung/{index?}', 'AdminController@addtramdung');

    Route::post('admin/addbusstop','AdminController@addbusstop')->name('addbusstop');

    Route::get('admin/deltramdung/{id}','AdminController@delbusstop');

//Phần lộ trình

    Route::get('admin/lotrinh/{cm?}', 'AdminController@lotrinh');

    Route::post('admin/addbusroute', 'AdminController@addbusroute')->name('addbusroute');

    Route::post('admin/delbusroute', 'AdminController@delbusroute')->name('delbusroute');

	Route::post('admin/getlocations', 'AdminController@admin_getlocations')->name("admin_getlocations");

//Phần tỉnh

    Route::post('admin/addprovince', 'AdminController@addprovince')->name('addprovince');

    Route::post('admin/delprovince', 'AdminController@delprovince')->name('delprovince');

//Phần tin tức

	Route::get('admin/tintuc', 'AdminController@tintuc');

	Route::post('admin/addtintuc', 'AdminController@addtintuc')->name('addtintuc');

	Route::post('admin/edittintuc', 'AdminController@edittintuc')->name('edittintuc');

	Route::get('admin/deltintuc/{id?}', 'AdminController@deltintuc');

//Phần giới thiệu

	Route::post('admin/editgioithieu', 'AdminController@editgioithieu')->name('editgioithieu');

//Phần liên hệ

	Route::post('admin/sendmessage','AdminController@admin_sendmessage')->name('admin_sendmessage');

	Route::post('admin/showmessage', 'AdminController@admin_showmessage')->name('admin_showmessage');

//Phần hàm dùng chung

	Route::post('admin/checkexist', 'AdminController@admin_checkexist')->name('admin_checkexist');

	Route::post('admin/retrievedata', 'AdminController@admin_retrievedata');

	Route::post('admin/changepassword', 'AdminController@admin_changepassword')->name('admin_changepassword');

});

/* Kết thúc phần Route cho trang quản trị hệ thống */
/*Route::get('ticket', function (){
    $matrix = [
        'user1'=>[3,2,5,5,'?',6],
        'user2'=>[3,'?',1,5,4,6],
        'user3'=>[3,2,1,5,'?',6],
        'user4'=>[3,7,'?',5,1,6],
        'user5'=>[3,2,8,5,'?',6]
    ];
    App\Http\Controllers\TicketSuggestion::ticketSuggestion($matrix);
    App\Http\Controllers\TicketSuggestion::makeMatrix();
});*/

Route::get("ticket", "TicketSuggestion@makeMatrix");

Route::post("ticket", "TicketSuggestion@makeMatrix")->name('ticketsuggestion');

/* Route::get('test11', function(){
	return response()->json(0);
}); */
