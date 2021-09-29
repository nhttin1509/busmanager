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
    // return view('welcome');
// });
/* Bắt đầu phần Route cho trang khách hàng */
    /*Trang chủ*/
    Route::get('/','Controller@Index')->name("home");
    /*Trang tìm vé*/
    Route::get('/datve','Controller@Datve');
    /*Trang liên hệ*/
    Route::get('/lienhe', function () {
        return view('tttn-web.lienhe');
    });
    /* Tìm chuyến xe ở trang chủ*/
    Route::post('/chuyenxe', 'Controller@Chuyenxe1')->name('chuyenxe');
    /*Tìm chuyến xe ở trang đặt vé*/
    Route::post('/chuyenxe1', 'Controller@Chuyenxe2')->name('chuyenxe1');
    /*Chọn vé*/
    Route::get('/chonve/{id}','Controller@Chonve')->middleware('checkdangnhap')->name('chonve');
    /*Xử lý chọn vé*/
    Route::post('/xulydatve','Controller@xulydatve')->name('xulydatve');
    Route::post('/xulydatve2','Controller@xulydatve2')->name('xulydatve2');
    /*Chọn đặt vé*/
    Route::post('/chondatve','Controller@chondatve')->name('chondatve');
    /*Thông tin vé*/
    Route::get('/thongtinve/{id}/{makh}','Controller@thongtinve')->middleware('checkdangnhap')->name('thongtinve');
    /*Đăng ký*/
    Route::post('/dangky','Controller@dangky')->name('dangky');
    /*Đăng nhập*/
    Route::post('/dangnhap','Controller@dangnhap')->name('dangnhap');
    /*Đăng xuất*/
    Route::get('logout', function(){
        Request::session()->flush();
        $tinh = DB::select("SELECT Tên FROM tinh");
        return redirect(route("home"));
    })->name('logout');
    /*Thông tin khách*/
    Route::get('thongtin/{makh}','Controller@thongtin')->middleware('checkdangnhap')->name('thongtin');
    /*Giới thiệu*/
    Route::get('/gioithieu','Controller@gioithieu')->name('gioithieu');
    /*Cập nhật thông tin*/
    Route::post('/capnhattt','Controller@capnhattt')->middleware('checkdangnhap')->name('capnhattt');
    /*Đổi mật khẩu*/
    Route::post('/doimatkhau','Controller@doimatkhau')->middleware('checkdangnhap')->name('doimatkhau');
    /*Tin tức*/
    Route::get('/tintuc','Controller@tintuc')->name('tintuc');
    /*Form tin tức*/
    Route::get('/formtintuc', function(){
        return view('tttn-web.formtintuc');
    } );
    /*Thêm giới thiệu*/
    Route::post('/addgioithieu','Controller@addgioithieu')->name('addgioithieu');
    /*Thêm tin tức*/
    Route::post('/addtintuc','Controller@addtintuc')->name('addtintuc');
    /*Show tin tức*/
    Route::get('/showtintuc/{id}','Controller@showtintuc')->name('showtintuc');
    /*Xử lý liên hệ*/
    Route::post('/xulylienhe','Controller@xulylienhe')->name('xulylienhe');
    /*Xử lý đề xuất*/
     Route::post('/xulydx','Controller@xulydx')->name('xulydx');
     /*Hủy chọn vé đề xuất*/
     Route::post('/huygiudx','Controller@huygiudx')->name('huygiudx');
/* Kết thúc phần Route cho trang khách hàng */
/* Bắt đầu phần Route để test chức năng */

Route::get('/checkconnection', 'Controller@checkConnection');

//Testing
Route::get('/admintest', 'AdminController@test');
Route::get('/admintest/test', function() {
    return view('test');
});
Route::post('/admintest/login', 'AdminController@login')->name('login');

/* Kết thúc phần Route để test chức năng */
