<?php
/* Bắt đầu phần route cho App Android */

//Code route cho app android trong này
//app customer
Route::post('/datveAndroid', 'AndroidController@android_chondatve');
Route::get('/gettinh', 'AndroidController@android_gettinh');
Route::post('/chuyenxeAndroid', 'AndroidController@android_Chuyenxe1');
Route::post('/chonveAndroid', 'AndroidController@android_Chonve');
Route::post('/lichsuAndroid', 'AndroidController@android_Lichsu');
Route::post('/dangkyAndroid', 'AndroidController@android_dangky');
Route::post('/dangnhapAndroid', 'AndroidController@android_dangnhap');
Route::post('/updateUserAndroid', 'AndroidController@android_capnhat');
Route::post('/changePassAndroid', 'AndroidController@android_doimatkhau');
Route::post('/xulydatveAndroid', 'AndroidController@android_xulydatve');
Route::post('/destroydatveAndroid', 'AndroidController@android_xulydatve2');
Route::post("/ticketAndroid", "TicketSuggestion@makeMatrix");

//app driver
Route::post('/dangnhapDriverAndroid', 'AndroidController@android_dangnhapDriver');
Route::post('/dangkyDriverAndroid', 'AndroidController@android_dangkyDriverAndroid');
Route::post('/ticketInfoAndroid', 'AndroidController@android_ticketinfo');
Route::post('/checkphoneAndroid', 'AndroidController@android_checkphone');
Route::post('/dangkyveAndroid', 'AndroidController@android_dangkyve');
Route::post('/capnhatDriver', 'AndroidController@android_capnhatDriver');
Route::post('/doimatkhauDriver', 'AndroidController@android_doimatkhauDriver');

/* Kết thúc phần route cho App Android */
