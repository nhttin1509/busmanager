<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Chuyenxe;
use App\Duongdi;
use App\Khachhang;
use App\Loaixe;
use App\Lotrinh;
use App\Nhanvien;
use App\Tinh;
use App\Tramdung;
use App\Ve;
use App\Xe;
use App\Tintuc;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

//    public  function __construct()
//    {
//        $this->middleware('auth');
//    }
    /*Hiển thị trang chủ*/
        public function Index(){
            /*lấy thông tin các tỉnh*/
                $tinh = DB::select("SELECT * FROM tinh");
            /*lấy 3 tin tức mới nhất*/
                $tintuc = DB::table("news")
                    ->orderBy('news_id', 'desc')
                    ->limit(3)
                    ->select("*")
                    ->get();
            /* lấy ảnh slide*/
                $slide = DB::table("news")
                    ->where("check_slide","=","1")
                    ->select("image","title","news_id")
                    ->get();
            /*Trả về trang chủ*/
                return view('tttn-web.index',["tinh" => $tinh,"tintuc"=>$tintuc, "slide"=>$slide]);
        }
    /* Tìm chuyến xe ở trang chủ*/
        public function Chuyenxe1(Request $request){
            $Noidi = $request->noidi;
            $Noiden = $request->noiden;
            /*chuyển đổi thời gian đúng định dạng Y-m-d*/
                $Thoigian =  date('Y-m-d',strtotime($request->Ngaydi));
            /*lấy thông tin chuyến xe tìm được */
                $Chuyenxe = DB::table("chuyen_xe")
                    ->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
                    ->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")
                    ->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
                    ->where("Nơi_đi","=", $Noidi)
                    ->where("Nơi_đến","=",$Noiden)
                    ->where("Trạng_thái","=","0")
                    ->where("is_del","=",0)
                    ->where('Ngày_xuất_phát','=',$Thoigian)
                    ->select("Nơi_đi","Nơi_đến","Ngày_xuất_phát","Giờ_xuất_phát","lo_trinh.Thời_gian_đi_dự_kiến","chuyen_xe.Mã","Tiền_vé","Loại_ghế","Biển_số")
                    ->get();
            /*Thêm thời gian đến dự kiến*/
                foreach ($Chuyenxe as $key => $value ) {
                    $time = $Chuyenxe[$key]->Ngày_xuất_phát." ".$Chuyenxe[$key]->Giờ_xuất_phát;
                    $tmp = strtotime($time) + $Chuyenxe[$key]->Thời_gian_đi_dự_kiến;
                    $Chuyenxe[$key]->Thời_gian_đến_dự_kiến = date('Y-m-d H:i:s',$tmp);
                }
            /*trả về trang chuyến xe*/
            return view('tttn-web.chuyenxe',["Chuyenxe" => $Chuyenxe]);
        }
    /*Tìm chuyến xe ở trang đặt vé*/
        public function Chuyenxe2(Request $request){
            $Noidi = $request->noidi;
            $Noiden = $request->noiden;
            $Loaixe = $request->loaixe;
            /*chuyển đổi thời giann đúng định dạng Y-m-d*/
                $Ngaydi =  date('Y-m-d',strtotime($request->Ngaydi));
            /*Thông tin chuyến xe tìm được*/
                $Chuyenxe = DB::table("chuyen_xe")
                    ->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
                    ->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")
                    ->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
                    ->where("Nơi_đi","=", $Noidi)
                    ->where("Nơi_đến","=",$Noiden)
                    ->where("Trạng_thái","=","0")
                    ->where("is_del","=",0)
                    ->where('Ngày_xuất_phát','=',$Ngaydi)
                    ->where('bus_model.Loại_ghế','=',$Loaixe)
                    ->select("Nơi_đi","Nơi_đến","Ngày_xuất_phát","Giờ_xuất_phát","lo_trinh.Thời_gian_đi_dự_kiến","chuyen_xe.Mã","Tiền_vé","Loại_ghế","Biển_số")
                    ->get();
                   
            /*Thêm thời gian đến dự kiến*/
                foreach ($Chuyenxe as $key => $value ) {
                $time = $Chuyenxe[$key]->Ngày_xuất_phát." ".$Chuyenxe[$key]->Giờ_xuất_phát;
                $tmp = strtotime($time) + $Chuyenxe[$key]->Thời_gian_đi_dự_kiến;
                $Chuyenxe[$key]->Thời_gian_đến_dự_kiến = date('Y-m-d H:i:s',$tmp);
                }
            /* Trả về trang chuyến xe*/
             return view('tttn-web.chuyenxe',["Chuyenxe" => $Chuyenxe]);
            
        }
    /*Hiển thị trang đặt vé*/
        public function Datve() {
            /*lấy thông tin các tỉnh*/
                $tinh = DB::select("SELECT * FROM tinh");
            /* trả về trang đặt vé*/
                return view('tttn-web.datve',["tinh" => $tinh]);
        }
    /* Hiển thị trang chọn vé*/
        public function Chonve($id){
            /* lấy thông tin chuyến xe*/
                $chonve = DB::table("chuyen_xe")
                    ->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
                    ->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")
                    ->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
                    ->where("chuyen_xe.Mã","=",$id)
                    ->select("chuyen_xe.Mã","Ngày_xuất_phát","Giờ_xuất_phát","Nơi_đến","Nơi_đi","Loại_ghế","Tiền_vé")
                    ->get();
            /*lấy sơ đồ xe*/
                $sodo = DB::table("chuyen_xe")
                    ->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")
                    ->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
                    ->where("chuyen_xe.Mã","=",$id)
                    ->select("Sơ_đồ","Loại_ghế")
                    ->get();
            /* lấy thông tin vé*/
                $ve = DB::table("chuyen_xe")
                    ->join("ve","chuyen_xe.Mã","=","ve.Mã_chuyến_xe")
                    ->where("chuyen_xe.Mã","=",$id)
                    ->select("Vị_trí_ghế","ve.Trạng_thái","ve.Mã","ve.Mã_khách_hàng","ve.Thời_điểm_chọn")
                    ->get();
            /* Thêm thời gian */
                $tmp = date("Y-m-d H:i:s");
                $tmp = strtotime($tmp);
                foreach ($ve as $key => $value ) {
                    $time = $ve[$key]->Thời_điểm_chọn;
                    $time = strtotime($time);
                    $time = $tmp - $time;
                    if($time < 600){
                        $time = 600 - $time;
                     }
                     else{
                        $time = null;
                     }
                    $ve[$key]->TG = $time;
                }
            /* trả về trang chọn vé*/
                return view('tttn-web.chonve',['chonve'=> $chonve,'ve'=>$ve,'sodo'=>$sodo,'id'=>$id]);
        }
    /* Xử lý chọn giữ vé*/
        public function xulydatve(Request $request){
                $ma = $request -> MA;
                $makh = $request -> MAKH;
                /* Kiểm tra trạng thái vé*/
                    $kt = DB::table("ve")
                        ->where("Mã","=",$ma)
                        ->select("Trạng_thái","Thời_điểm_chọn")
                        ->get();
                    if($kt[0]->Trạng_thái == 0){
                        $time = date("Y-m-d H:i:s");
                        /* update trạng thái vé*/
                        DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
                            [2,$makh,$time,$ma]);
                        /*hàm sleep đếm ngược*/
                        sleep(600);
                        /*kiểm tra trạng thái và mã khách hàng*/
                            $kt2 = DB::table("ve")
                                ->where("Mã","=",$ma)
                                ->select("Trạng_thái","Mã_khách_hàng","Mã","Thời_điểm_chọn")
                                ->get();
                            if($kt2[0]->Trạng_thái == 2  && $kt2[0]->Thời_điểm_chọn == $time){
                                /*update trạng thái vé*/
                                DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`=? WHERE `Mã`= ?",
                                    [0,null,null,$ma]);
                    }
                    /*trả về kq ajax*/
                        return \response()->json(['kq'=>0]);
                }
                else if($kt[0]->Trạng_thái == 1){
                    /*trả về kq ajax*/
                        return \response()->json(['kq'=>1]);
                }
                else if($kt[0]->Trạng_thái == 2){
                    $tmp = date("Y-m-d H:i:s");
                    $tmp = strtotime($tmp);
                    $time = $kt[0]->Thời_điểm_chọn;
                    $time = strtotime($time);
                    $time = $tmp - $time;
                    if($time < 600){
                        $time = 600 - $time;
                    }
                    else{
                        $time = null;
                    }
                    /*trả về kq ajax*/
                        return \response()->json(['kq'=>2,'TGC'=>$time]);
                }
            }
    /*Xử lý đề xuất*/
        public function xulydx(Request $request){
                $ma = $request -> MA;
                $makh = $request -> MAKH;
                /* Kiểm tra trạng thái vé*/
                    $kt = DB::table("ve")
                        ->where("Mã","=",$ma)
                        ->select("Trạng_thái","Thời_điểm_chọn")
                        ->get();
                    if($kt[0]->Trạng_thái == 0){
                        $time = date("Y-m-d H:i:s");
                        /* update trạng thái vé*/
                        DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
                            [2,$makh,$time,$ma]);
                        /*hàm sleep đếm ngược*/
                        sleep(600);
                        /*kiểm tra trạng thái và mã khách hàng*/
                            $kt2 = DB::table("ve")
                                ->where("Mã","=",$ma)
                                ->select("Trạng_thái","Mã_khách_hàng","Mã","Thời_điểm_chọn")
                                ->get();
                            if($kt2[0]->Trạng_thái == 2  && $kt2[0]->Thời_điểm_chọn == $time){
                                /*update trạng thái vé*/
                                DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`=? WHERE `Mã`= ?",
                                    [0,null,null,$ma]);
                    }
                    /*trả về kq ajax*/
                        return \response()->json(['kq'=>0]);
                }
                else if($kt[0]->Trạng_thái == 1){
                    /*trả về kq ajax*/
                        return \response()->json(['kq'=>1]);
                }
                else if($kt[0]->Trạng_thái == 2){
                    /*trả về kq ajax*/
                        return \response()->json(['kq'=>2]);
                }
            }
    /*Hủy vé đề xuất*/
        public function huygiudx(Request $request){
                $ma = $request -> MA;
                /*update trạng thái vé*/
                    DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
                        [0,Null,Null,$ma]);
                /*trả kết quả về ajax*/
                    return \response()->json(['kq'=>1]);
        }
    /* Xử lý hủy giữ vé*/
        public function xulydatve2(Request $request){
                $ma = $request -> MA;
                /*update trạng thái vé*/
                    DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
                        [0,Null,Null,$ma]);
                /*trả kết quả về ajax*/
                    return \response()->json(['kq'=>1]);
        }
    /* CHọn đặt vé*/
        public function chondatve(Request $request){
            $id = $request-> ID;
            $mang = $request-> MANG;
            $makh = $request -> MAKH;
            $dodai = $request -> DODAI;
            for($i=0;$i<$dodai;$i++){
                if($mang[$i] != null){
                    /*update trạng thái vé*/
                        DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ? WHERE `Mã`= ?",
                            [1,$makh,$mang[$i]]); 
                }
            }
            /* update trạng thái vé*/
                DB::table("ve")
                    ->where("Mã_khách_hàng","=","$makh")
                    ->where("Trạng_thái","=","2")
                    ->update(["Trạng_thái"=>1]);
            /* trả kết quả về ajax*/
                return \response()->json(['id'=>$id,'makh'=>$makh]);
        }
    /* Thông tin vé*/
        public function thongtinve($id,$makh){
            /* lấy thông tin vé*/
                $chonve = DB::table("chuyen_xe")
                    ->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
                    ->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")
                    ->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
                    ->where("chuyen_xe.Mã","=",$id)
                    ->select("Ngày_xuất_phát","Giờ_xuất_phát","Nơi_đến","Nơi_đi","Loại_ghế","Tiền_vé")
                    ->get();
            /* lấy thông tin vé đã đặt*/
                $vedadat = DB::table("ve")
                    ->where("Mã_chuyến_xe","=",$id)
                    ->where("Mã_khách_hàng","=",$makh)
                    ->select("Vị_trí_ghế")
                    ->get();
            /* gửi thông tin về trang thông tin vé*/
                return view('tttn-web.thongtinve',["chonve" => $chonve,"vedadat"=>$vedadat]);
        }
    /* Đăng ký */
        public function dangky(Request $request){
            $sdt = $request->SDT;
            $mk = $request->MK;
            $ngaysinh = $request->NGAYSINH;
            $gt = $request->GT;
            $name = $request->NAME;
            $namekd = FunctionBase::convertAlias($name); //đổi tên về không dấu
            /* kiểm tra số điện thoại*/
                $kt = DB::select("SELECT * FROM customer WHERE Sđt = ? ",[$sdt]);
                if(!$kt){
                    /* thêm mới khách hàng*/
                    $account = new Khachhang;
                    $account["Sđt"] = $sdt;
                    $account["Password"] = md5($mk);
                    $account["Tên"] = $name;
                    $account["Tên_không_dấu"] =$namekd;
                    $account["Ngày_sinh"] = $ngaysinh;
                    $account["Giới tính"] = $gt;
                    $account->save();
                    /* trả kết quả về ajax*/
                        return \response()->json(['kq'=>1]);
                   
                }
                else{
                    /* trả kết quả về ajax*/
                        return \response()->json(['kq'=>0]);
                    }
                }
    /*Đăng nhập */
        public function dangnhap(Request $request){
            $dndt = $request->DNDT;
            $dnmk = md5($request->DNMK); // mã hóa mật khẩu
            /* kiểm tra  số điện thoại và mật khẩu*/
                $dnkt = DB::select("SELECT * FROM customer WHERE Sđt = ? AND Password = ?",[$dndt,$dnmk]);
                if($dnkt){
                    $makh = $dnkt[0]->Mã;
                    $sdt = $dnkt[0]->Sđt;
                    $request->session()->put("makh", $makh); // tạo session tên makh
                    $request->session()->put("sdt", $sdt); // tạo session tên sdt
                    /* trả về ajax*/
                        return \response()->json(['kq'=>1,'sdt'=>$sdt,'ma'=>$makh]);
                }
                else{
                    /* trả về ajax*/
                        return \response()->json(['kq'=>0]);
                }

        }
    /*Cập nhật thông tin*/
        public function capnhattt(Request $request){
            $ma = $request->MA;
            $name = $request->NAME;
            $namekd = FunctionBase::convertAlias($name); // đổi tên không dấu
            $ngaysinh = $request->NGAYSINH;
            $gt = $request->GT;
            $diachi = $request->DIACHI;
            $email = $request->EMAIL;
            /* kiểm tra email*/
                $kt = DB::select("SELECT * FROM customer WHERE EMAIL = ? AND Mã != ?",[$email,$ma]);
                if(!$kt){
                    /*update thông tin*/
                         DB::update("UPDATE `customer` SET `Tên`= ?, `Tên_không_dấu`= ?, `Giới tính`= ?, `Ngày_sinh`= ?, `Địa chỉ`= ?, `Email`= ? WHERE `Mã`= ?",[$name,$namekd,$gt,$ngaysinh,$diachi,$email,$ma]); 
                    /*trả về ajax*/
                        return \response()->json(['kq'=>1]);
                }
                else{
                    /*trả về ajax*/
                        return \response()->json(['kq'=>0]);
                }
        }
    /*Hiển thị thông tin khách */
        public function thongtin($makh){
            /* Lấy thông tin khách*/
                $thongtinkhach = DB::table("customer")
                    ->where("Mã","=","$makh")
                    ->select("Mã","Tên","Ngày_sinh","Giới tính","Địa chỉ","Email","Sđt")
                    ->get();
            /* lấy thông tin vé đã đi*/
                $ve = DB::table("ve")->join("chuyen_xe","chuyen_xe.Mã","=","ve.Mã_chuyến_xe")
                    ->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
                    ->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
                    ->where("ve.Mã_khách_hàng","=",$makh)
                    ->where("ve.Trạng_thái","=",1)
                    ->select("Vị_trí_ghế","ve.Trạng_thái","ve.Mã","Nơi_đi","Nơi_đến","Ngày_xuất_phát","Giờ_xuất_phát","Tiền_vé","Loại_ghế","ve.Mã_chuyến_xe",'lo_trinh.Thời_gian_đi_dự_kiến')
                    ->get();
                $chuyenxe = [];
                $daduyet = [];
                $dem = 0;
                foreach ($ve as $key => $value ) {
                    $check = 0;
                    if(count($daduyet)!=0){
                        for($i=0;$i<count($daduyet);$i++){
                            if($value->Mã_chuyến_xe == $daduyet[$i]){
                                $check = 1;
                                break;
                            }
                        }
                    }
                    if($check == 1){
                        continue;
                    }
                    $daduyet[$dem] = $value->Mã_chuyến_xe;
                    $chuyenxe[$dem] = $value;
                    $time = $chuyenxe[$dem]->Ngày_xuất_phát." ".$chuyenxe[$dem]->Giờ_xuất_phát;
                    $tmp = strtotime($time) + $chuyenxe[$dem]->Thời_gian_đi_dự_kiến;
                    $chuyenxe[$dem]->Thời_gian_đến_dự_kiến = date('Y-m-d H:i:s',$tmp);
                    foreach ($ve as $key1 => $value1) {
                        if($key1 != $key&&$value1->Mã_chuyến_xe == $chuyenxe[$dem]->Mã_chuyến_xe){
                            $chuyenxe[$dem]->Vị_trí_ghế .= (",".$ve[$key1]->Vị_trí_ghế);
                       }
                    }
                    $dem++;
                }
            /* trả về trang thông tin khách*/
            return view('tttn-web.thongtinkhach',["thongtinkhach"=>$thongtinkhach,"lichsudi"=>$chuyenxe]);
        }
    /* Đổi mật khẩu*/
        public function doimatkhau(Request $request){
            $ma = $request->MA;
            $mkcu = md5($request->MKCU); // mã hóa mật khẩu cũ
            $mkmoi= md5($request->MKMOI); // mã hóa mật khẩu mới
            /* kiểm tra  mật khẩu*/
                $dnkt = DB::select("SELECT * FROM customer WHERE Mã = ? AND Password = ?",[$ma,$mkcu]);
                if($dnkt){
                    /*update mật khẩu*/
                     DB::update("UPDATE `customer` SET `Password`= ? WHERE `Mã`= ?",[$mkmoi,$ma]);
                    /* trả về ajax*/
                        return \response()->json(['kq'=>1]);
                }
                else{
                    /*trả về ajax*/
                        return \response()->json(['kq'=>0]);
                }
        }
    /* Hiển thị trang tin tức*/
        public function tintuc(){
            /* lấy thông tin tin tức*/
                 $tintuc = DB::table("news")
					 ->where('is_disabled','=','0')
                     ->orderBy('news_id', 'desc')
                     ->paginate(9);
            /* trả về trang tin tức*/
                return view('tttn-web.tintuc',["tintuc"=>$tintuc]);
        }
    /* Thêm tin tức*/
        public function addtintuc(Request $request){
            $tieude = $request->tieude;
            $hinhanh = $request->hinhanh;
            $namehinhanh = $hinhanh->getClientOriginalName();
            $hinhanh->move('upload',$hinhanh->getClientOriginalName());
            $mota = $request->mota;
            $noidung=$request->noidung;
            $time = date("Y-m-d H:i:s");

            DB::table("news")->insert(
        ["title" => $tieude, "image" => $namehinhanh, "introduce" => $mota, "content" => $noidung, "check_slide" => 0 ,"id_admin_created"=>1, "id_admin_changed" =>1, "created_at" => $time, "updated_at" => $time]
             );
             return view('tttn-web.formtintuc');
        }
    /* Show chi tiết tin tức*/
        public function showtintuc($id){
             $tintuc = DB::table("news")
                 ->where("news_id","=",$id)
                 ->select("title","introduce","content","created_at")
                 ->get();
             $tintuckhac = DB::table("news")
                 ->where("news_id","!=",$id)
                 ->orderBy('news_id', 'desc')
                 ->limit(3)
                 ->select("*")
                 ->get();
            
            return view('tttn-web.viewtintuc',["tintuc"=>$tintuc,"tintuckhac"=>$tintuckhac]);
        }
    /* Thêm giới thiệu*/
        public function addgioithieu(Request $request){
               $noidung = $request->noidunggt; 
               $time = date("Y-m-d H:i:s");
                 DB::table("gioi_thieu")->insert(
        ["noidung" => $noidung, "id_admin_created"=>1, "id_admin_changed" =>1, "created_at" => $time, "updated_at" => $time]
            );
             return view('tttn-web.formtintuc');
        }
    /* Show giới thiệu*/
        public function gioithieu(){
            $gioithieu = DB::table("gioi_thieu")->orderBy('gt_id', 'desc')->limit(1)->select("*")->get();
            return view('tttn-web.gioithieu',['gioithieu'=>$gioithieu]);
        }
    /*Xử lý liên hệ*/
        public function xulylienhe(Request $request){
            $ten = $request->TEN;
            $mail = $request->MAIL;
            $dt = $request->DT;
            $tieude = $request->TIEUDE;
            $noidung = $request->NOIDUNG;
            $time = date("Y-m-d H:i:s");
            DB::table("lienhe")->insert(
        ["ho_ten" => $ten, "email"=>$mail, "dien_thoai" => $dt, "tieu_de" => $tieude, "noi_dung"=>$noidung, "ngay_dang"=>$time]);
            return \response()->json(['kq'=>1]);
        }



    /***/
    public function checkConnection(){
        try {

            DB::connection()->getPdo();
            echo 'Successfully!';
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration. error:" . $e );
        }
    }

    public function register(Request $request){
        try {
            $account = new Khachhang;
            $account["Sđt"] = $request->query("phone");
            $account["Password"] = md5($request->query("password"));
            $account->save();
        } catch (\Exception $e) {
            die("Không thể đăng ký được tài khoản: ".$e);
        }
    }
    public function addBusModel(Request $request){
        try {
            $busmodel = new Loaixe;
            $busmodel["Tên_Loại"] = $request->query("busname");
            $busmodel["Số_ghế"] = $request->query("soghe");
            $busmodel["Sơ_đồ"] = $request->query("sodo");
            $busmodel["Mã_nhân_viên_tạo"] = $request->query("nhanvientao");
            $busmodel["Mã_nhân_viên_chỉnh_sửa"] = $request->query("nhanviensua");
            $busmodel->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
    public function addChuyenxe(Request $request){
        try {
            $chuyenxe = new Chuyenxe;
            $chuyenxe["Mã_nhân_viên_tạo"] = $request->query("nhanvientao");
            $chuyenxe["Mã_lộ_trình"] = $request->query("lotrinh");
            $chuyenxe["Mã_tài_xế"] = $request->query("taixe");
            $chuyenxe["Mã_xe"] = $request->query("xe");
            $chuyenxe["Thời_gian_xuất_phát"] = $request->query("starttime");
            $chuyenxe->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
    public function addDuongdi(Request $request){
        try {
            $duongdi = new Duongdi;
            $duongdi["Mã_Trạm_Start"] = $request->query("tramkhoihanh");
            $duongdi["Mã_Trạm_End"] = $request->query("tramcuoi");
            $duongdi["Tọa_độ_detail"] = $request->query("toadochitiet");
            $duongdi->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
    public function addLotrinh(Request $request){
        try {
            $lotrinh = new Lotrinh;
            $lotrinh["Mã_nhân_viên_tạo"] = $request->query("nhanvientao");
            $lotrinh["Mã_nhân_viên_chỉnh_sửa"] = $request->query("nhanviensua");
            $lotrinh["Nơi_đi"] = $request->query("noidi");
            $lotrinh["Nơi_đến"] = $request->query("noiden");
            $lotrinh["Các_trạm_dừng_chân"] = $request->query("cactramdung");
            $lotrinh->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
    public function addProvince(Request $request){
        try {
            $province = new Tinh;
            $province["Tên"] = $request->query("province");
            $province["Mã_vùng"] = $request->query("localcode");
            $province->save();
        } catch (\Exception $e) {
            die("Không thể thêm tỉnh/thành phố: ".$e);
        }
    }
    public function addTramdung(Request $request){
        try {
            $tramdung = new Tramdung;
            $tramdung["Tên"] = $request->query("tentramdung");
            $tramdung["Tọa_độ"] = $request->query("toado");
            $tramdung["Mã_nhân_viên_tạo"] = $request->query("nhanvientao");
            $tramdung["Mã_nhân_viên_chỉnh_sửa"] = $request->query("nhanviensua");
            $tramdung->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
    public function addVe(Request $request){
        try {
            $ve = new Ve;
            $ve["Mã_chuyến_xe"] = $request->query("chuyenxe");
            $ve["Vị_trí_ghế"] = $request->query("gheso");
            $ve["Giá"] = $request->query("gia");
            $ve["Trạng_thái"] = $request->query("trangthai");
            $ve->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
    public function addXe(Request $request){
        try {
            $xe = new Xe;
            $xe["Biển_số"] = $request->query("bienso");
            $ve["Mã_loại_xe"] = $request->query("loaixe");
            $ve["Ngày_bảo_trì_gần_nhất"] = $request->query("baotrigannhat");
            $ve["Ngày_bảo_trì_tiếp_theo"] = $request->query("baotritieptheo");
            $ve->save();
        } catch (\Exception $e) {
            die("Có lỗi xảy ra: ".$e);
        }
    }
}
