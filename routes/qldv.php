<?php
/* Bắt đầu phần Route cho trang quản lý đặt vé */

Route::get('qldv/login', function(){
    if(session()->has('isadmin')){
        return redirect('qldv/');
    }
    else{
        return view('quanlydatve.login');
    }
});

Route::post('qldv/login', 'TicketBookingManager@qldv_checkLogin')->name('qldv_login');

Route::middleware('qldvcheck')->group(function(){
	Route::get('qldv/logout', 'TicketBookingManager@qldv_logout')->name('qldv_logout');
	Route::get('/qldv', function() {
		return redirect('qldv/datve');
	});

	Route::get('/qldv/giamsat/{id?}', 'TicketBookingManager@qldv_giamsat');

	Route::get('/qldv/datve','TicketBookingManager@trangdatve');

	Route::post('/qldv/searchroute','TicketBookingManager@searchroute')->name('qldv-searchroute');

	Route::post('/qldv/routedetails','TicketBookingManager@routedetails')->name('qldv-routedetails');

	Route::post('/qldv/chonve','TicketBookingManager@qldv_chonve')->name('qldv-chonve');

	Route::post('/qldv/huychonve','TicketBookingManager@qldv_huychonve')->name('qldv-huychonve');

	Route::post('/qldv/huychonchuyenxe','TicketBookingManager@qldv_huychonchuyenxe')->name('qldv-huychonchuyenxe');

	Route::post('/qldv/searchcustomer','TicketBookingManager@qldv_searchcustomer')->name('qldv-searchcustomer');

	Route::post('/qldv/infokhachhang','TicketBookingManager@qldv_infokhachhang')->name('qldv-infokh');

	Route::post('/qldv/xldatve','TicketBookingManager@qldv_xldatve')->name('qldv-datve');

	Route::post('/qldv/xldangky','TicketBookingManager@qldv_xldangky')->name('qldv-dangky');
	
	Route::post('qldv/userinfo','TicketBookingManager@qldv_userinfo')->name('qldv_userinfo');
	
	Route::get('qldv/sendgps','TicketBookingManager@qldv_sendgps')->name('qldv_sendgps');
	
	Route::post('qldv/showhistory','TicketBookingManager@qldv_showhistory')->name('qldv_showhistory');
	
	Route::post('qldv/showdetails','TicketBookingManager@qldv_showdetails')->name('qldv_showdetails');
	
	Route::post('qldv/huydondatve','TicketBookingManager@qldv_huydondatve')->name('qldv_huydondatve');
	
	// Route::get('qldv/testsse','TicketBookingManager@qldv_testSSE')->name('qldv_testSSE');
});

// Route::get('/qldv/ticket/{idve}','TicketBookingManager@ticketinfo');

/* Route::post('/qldv/searchtinh', 'TicketBookingManager@searchtinh')->name('searchtinh'); */
/* Kết thúc phần Route cho trang quản lý đặt vé */