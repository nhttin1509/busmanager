<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


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
class AndroidController extends Controller
{
    public function Index(){
        $tinh = DB::select("SELECT Tên FROM tinh");
        return view('tttn-web.index',["tinh" => $tinh]);
    }
    public function android_Chuyenxe1(Request $request){
        $Noidi = $request->Noidi;
        $Noiden = $request->Noiden;
        $Thoigian =  date('Y-m-d',strtotime($request->Ngaydi));
		$loaighe = $request->Loaighe;
        $Chuyenxe = DB::table("chuyen_xe")->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
		->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")
		->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
		->where("Nơi_đi","=", $Noidi)->where("Nơi_đến","=",$Noiden)
		->where("Trạng_thái","=","0")
		->where("is_del","=",0)
		->where("Ngày_xuất_phát","=",$Thoigian)
        ->where("bus_model.Loại_ghế","=",$loaighe)
		->select("Nơi_đi","Nơi_đến","Ngày_xuất_phát","Giờ_xuất_phát","lo_trinh.Thời_gian_đi_dự_kiến",
            "chuyen_xe.Mã","Tiền_vé","Loại_ghế","Biển_số")->get();
         foreach ($Chuyenxe as $key => $value ) {
            $time = $Chuyenxe[$key]->Ngày_xuất_phát." ".$Chuyenxe[$key]->Giờ_xuất_phát;
            $tmp = strtotime($time) + $Chuyenxe[$key]->Thời_gian_đi_dự_kiến;
            $Chuyenxe[$key]->Thời_gian_đến_dự_kiến = date('Y-m-d H:i:s',$tmp);
        }
        return response()->json(['kq'=>1,'chuyenxe'=>$Chuyenxe,'from'=>$Noidi,'to'=>$Noiden,'thoigian'=>$Thoigian,'tt'=>$request->Ngaydi]);
    } 

    public function android_Chonve(Request $request){
		$id = $request->ID;
        $chonve = DB::table("chuyen_xe")->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")->where("chuyen_xe.Mã","=",$id)->select("Ngày_xuất_phát","Giờ_xuất_phát","Nơi_đến","Nơi_đi","Loại_ghế","Tiền_vé")->get();
        $sodo = DB::table("chuyen_xe")->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")->where("chuyen_xe.Mã","=",$id)->select("Sơ_đồ")->get();
        $ve = DB::table("chuyen_xe")->join("ve","chuyen_xe.Mã","=","ve.Mã_chuyến_xe")->select("Vị_trí_ghế","ve.Trạng_thái","ve.Mã")->where("chuyen_xe.Mã","=",$id)->get();
        return response()->json(['chonve'=> $chonve,'ve'=> $ve,'sodo'=> $sodo]);
    }

	public function android_Lichsu(Request $request){
		$id = $request->ID;
        $ve = DB::table("ve")->join("chuyen_xe","chuyen_xe.Mã","=","ve.Mã_chuyến_xe")
		->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
		->join("xe","chuyen_xe.Mã_xe","=","xe.Mã")->join("bus_model","xe.Mã_loại_xe","=","bus_model.Mã")
		->where("ve.Mã_khách_hàng","=",$id)
		->where("ve.Trạng_thái","=",1)
		->select("Vị_trí_ghế","ve.Trạng_thái","ve.Mã","Nơi_đi","Nơi_đến","Ngày_xuất_phát","Giờ_xuất_phát","Tiền_vé","Loại_ghế","ve.Mã_chuyến_xe",'lo_trinh.Thời_gian_đi_dự_kiến')
        ->get();
        $chuyenxe = [];
        $daduyet = [];
        $dem = 0;
        try {
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
        } catch (\Exception $e){
            return response()->json(['ve' => 'lỗi']);
        }
        return response()->json(['ve'=> $chuyenxe]);
    }
  
    /***/
	public function android_gettinh(){
		$tinh=DB::table('tinh')->select('Tên')->get();
		return \response()->json(['kq'=>$tinh]);
	}
	
	public function android_chondatve(Request $request){
        $id = $request-> ID;
        $mang = $request-> MANG;
        $makh = $request -> MAKH;
        $dodai = $request -> DODAI;
		$array = explode(',', $mang);
        $m = 0;
        for($i = 0;$i < $dodai; $i++){
            if($array[$i] != null){
                // $int = (int)$array[$i];
               $kt = DB::table("ve")->where("Mã","=",$array[$i])->select("Trạng_thái","Mã_khách_hàng")->get();
               if($kt[0]->Trạng_thái == 1 ){
                    $m = 1;
                    break;
               }
                if($kt[0]->Trạng_thái == 2 && $kt[0]->Mã_khách_hàng != $makh){
                    $m = 1;
                    break;
               }
            }
        }
        if ($m == 1) {
            # code...
            return  \response()->json(['kq'=>'wrong']);
        }
        else {
              for($i=0;$i<$dodai;$i++){
                 if($array[$i] != null){
                     DB::update("UPDATE ve SET `Trạng_thái`= ?,`Mã_khách_hàng`= ? WHERE `Mã`= ?",
                    [1,$makh,$array[$i]]);
                 }
              }
        }
        return \response()->json(['kq'=>'success','id'=>$id,'makh'=>$makh,'mang'=>$array]);
    }
	
	/*dangky*/
    public function android_dangky(Request $request){
        $sdt = $request->SDT;
        $mk = $request->MK;
		$gt = $request->GT;
		$name = $request->NAME;
		$namekd =  $request->NAMEKD;
		$ngaysinh = $request->NGAYSINH;
        $kt = DB::select("SELECT * FROM customer WHERE Sđt = ? ",[$sdt]);
        if(!$kt){
            $account = new Khachhang;
            $account["Sđt"] = $sdt;
            $account["Password"] = md5($mk);
			$account["Tên"] = $name;
            $account["Tên_không_dấu"] =$namekd;
            $account["Ngày_sinh"] = $ngaysinh;
            $account["Giới tính"] = $gt;
            $account->save(); 
            return \response()->json('1');
        
        }
        else{
            return \response()->json('0');
        }
    }
    /*dangnhap*/
    public function android_dangnhap(Request $request){
        $dndt = $request->DNDT;
        $dnmk = md5($request->DNMK);
        $dnkt = DB::select("SELECT * FROM customer WHERE Sđt = ? AND Password = ?",[$dndt,$dnmk]);
        if($dnkt){
            $makh = $dnkt[0]->Mã;
            $sdt = $dnkt[0]->Sđt;
            return \response()->json(['kq'=>'success','tt'=>$dnkt]);
        }
        else{
            return \response()->json(['kq'=>'wrong']);
        }
    }

/*cap nhat thong tin*/
    public function android_capnhat(Request $request){
        $ma = $request->MA;
        $name = $request->NAME;
		$namekd = $request->NAMEKD;
        $ngay = $request->NGAY;
        $thang = $request->THANG;
        $nam = $request->NAM;
        $gt = $request->GT;
        $diachi = $request->DIACHI;
        $email = $request->EMAIL;
        DB::update("UPDATE customer SET `Tên`= ?, `Tên_không_dấu` = ?, `Giới tính`= ?, `Ngày_sinh`= ?, `Địa chỉ`= ?, `Email`= ? WHERE `Mã`= ?",
                    [$name,$namekd,$gt,$nam."-".$thang."-".$ngay,$diachi,$email,$ma]); 
        return \response()->json(['kq'=>1]);
    }
     /*thong tin khach*/
    // public function thongtin($makh){
    //     $thongtinkhach = DB::table("customer")->where("Mã","=","$makh")->select("Mã","Tên","Ngày_sinh","Giới tính","Địa chỉ","Email","Sđt")->get();
    //     return view('tttn-web.thongtinkhach',["thongtinkhach"=>$thongtinkhach]);
    // }
    /***/

    /*doi mat khau*/
    public function android_doimatkhau(Request $request){
        $ma = $request->MA;
        $mkcu = md5($request->MKCU);
        $mkmoi= md5($request->MKMOI);
        $dnkt = DB::select("SELECT * FROM customer WHERE Mã = ? AND Password = ?",[$ma,$mkcu]);
        if($dnkt){
             DB::update("UPDATE customer SET `Password`= ? WHERE `Mã`= ?",[$mkmoi,$ma]);
             return \response()->json(['kq'=>1]);
        }
        else{
            return \response()->json(['kq'=>0]);
        }
    }

    //đăng nhập cho driver
     public function android_dangnhapDriver(Request $request){
        $dndt = $request->DNDT;
        $dnmk = md5($request->DNMK);
        $dnkt = DB::select("SELECT * FROM employee WHERE Email = ? AND Password = ?",[$dndt,$dnmk]);
        if($dnkt){
            // $makh = $dnkt[0]->Mã;
            // $SDT = $dnkt[0]->Sđt;
            return \response()->json(['kq'=>'success','tt'=>$dnkt]);
        }
        else{
            return \response()->json(['kq'=>'wrong']);
        }
    }

    //đăng ký cho driver
     public function android_dangkyDriverAndroid(Request $request){
        $email = $request->EMAIL;
        $mk = $request->MK;
        $name = $request->NAME;
        $dob = $request->DOB;
        $gender = $request->GENDER;
        $sdt = $request->SDT;
        $address = $request->ADDRESS;

        $kt = DB::select("SELECT * FROM employee WHERE Email = ? ",[$email]);
        if(!$kt){
            $account = new Nhanvien;
            $account["Email"] = $email;
            $account["Password"] = md5($mk);
            $account["Ngày_sinh"] = $dob;
            $account["Giới_tính"] = $gender;
            $account["Họ_Tên"] = $name;
            $account["Địa_chỉ"] = $address;
            $account["Sđt"] = $sdt;
            $account["Username"] = $email;
			$account["Loại_NV"] = 'TX';
            $account["Bằng_lái"] = $sdt;
            $account["Chi_nhánh"] = $address;
            $account["Date_Starting"] = $dob;

            $account->save(); 
            return \response()->json('1');
        
        }
        else{
            return \response()->json('0');
        }
    }
    
    public function android_ticketinfo(Request $request)
    {
        $idve = $request->idve; //Gửi lên idve
        $ttve = DB::table('ve')
        // ->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')
        // ->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')
        ->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')
        ->where('ve.Mã','=',$idve)
        ->select('ve.Mã','customer.Tên','customer.Email','customer.Sđt','customer.Giới tính'
            ,'customer.Ngày_sinh', 'customer.Địa chỉ'
            // ,'lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','chuyen_xe.Giờ_xuất_phát','chuyen_xe.Ngày_xuất_phát','ve.Vị_trí_ghế','chuyen_xe.Tiền_vé'
        )->get();
        if(count($ttve) == 0)
        {
            return response()->json(['kq' => 0]);
        }
        else
        {
            $ttve = $ttve[0];
            return response()->json(['kq' => 1,'data' => $ttve]);
        }
    }

    public function android_checkphone(Request $request) //Hàm kiểm tra Sđt điện thoại đã đăng ký chưa, nếu đã đăng ký thì đăng ký vé luôn còn không thì chuyển qua form đăng ký mới
    {
        $phone = $request->phone; //Gửi lên phone
        $idve = $request->idve; //Gửi lên idve
        $ttkh = DB::table('customer')->where('Sđt','=',$phone)->get();
        if(count($ttkh) != 0)
        {
            $updated_at = date('Y-m-d h-i-s');
            if(DB::update("UPDATE ve SET Mã_khách_hàng = ?, Trạng_thái = ?, updated_at = ? WHERE Mã = ? AND Trạng_thái = ?",[$ttkh[0]->Mã,1,$updated_at,$idve,0]))
            {
                return response()->json(['kq' => 1]); //Đăng ký vé thành công
            }
            else
            {
                return response()->json(['kq' => 0]); //Đăng ký vé thất bại
            }
        }
        else
        {
            return response()->json(['kq' => 2]); //Chưa có tài khoản chuyển qua form nhập thông tin để đăng ký
        }
    }
    public function android_dangkyve(Request $request) //Hàm đăng ký mới Sđt và đăng ký vé luôn
    {
        $phone = $request->phone; //Gửi lên phone
        $name = $request->name; //Gửi lên name
		$namekhongdau = $request->namekd;
        $gioitinh = $request->gender; //Gửi lên gender
        $ngaysinh = date('Y-m-d',strtotime($request->ngaysinh)); //Gửi lên ngaysinh
        $idve = $request->idve; //Gửi lên idve
        $ttkh = DB::table('customer')->where('Sđt','=',$phone)->select('Mã')->get();
        if(count($ttkh) == 0)
        {
            $created_at = date('Y-m-d h-i-s');
            $updated_at = date('Y-m-d h-i-s');
            try{
                $account = new Khachhang;
				$account["Sđt"] = $phone;
				$account["Password"] = md5($phone);
				$account["Tên"] = $name;
				$account["Tên_không_dấu"] =$namekhongdau;
				$account["Ngày_sinh"] = $ngaysinh;
				$account["Giới tính"] = $gioitinh;
				$account->save();
				
                $idkh = $account->Mã;
                if(DB::update("UPDATE `ve` SET `Mã_khách_hàng` = ?, `Trạng_thái` = ? WHERE `Mã` = ? AND `Trạng_thái` = ?",[$idkh,1,$idve,0]))
                {
                    return response()->json(['kq' => 1]); //Đăng ký vé thành công
                }
                else
                {
                    return response()->json(['kq' => 0]); //Đăng ký vé thất bại
                }
            }
            catch (\Exception $e){
                return response()->json(['kq' => 0]); //Đăng ký vé thất bại
            }
        }
    }

    public function android_xulydatve(Request $request){
            $ma = $request -> MA;
            $makh = $request -> MAKH;
            $kt = DB::table("ve")->where("Mã","=",$ma)->select("Trạng_thái")->get();
            if($kt[0]->Trạng_thái == 0){
                $time = date("Y-m-d H:i:s");
                DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
                        [2,$makh,$time,$ma]);
                // sleep(300);
                // $kt2 = DB::table("ve")->where("Mã","=",$ma)->select("Trạng_thái","Mã_khách_hàng","Mã","Thời_điểm_chọn")->get();
                // // && $kt2[0]->Mã_khách_hàng == $makh
                // if($kt2[0]->Trạng_thái == 2 && $kt2[0]->Thời_điểm_chọn == $time){
                //     DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`=? WHERE `Mã`= ?",
                //         [0,null,null,$ma]);
                // }
                return \response()->json(['kq'=>0]);
            }
            // vé vưa có ngươi đặt
            else if($kt[0]->Trạng_thái == 1){
                 return \response()->json(['kq'=>1]);
            }// vé vữa có ngươi` giữ chỗ
            else if($kt[0]->Trạng_thái == 2){
                 return \response()->json(['kq'=>2]);
            }
        }

    //     public function android_xulydatve(Request $request){
    //         $ma = $request -> MA;
    //         $makh = $request -> MAKH;
    //         $kt = DB::table("ve")->where("Mã","=",$ma)->select("Trạng_thái")->get();
    //         if($kt[0]->Trạng_thái == 0){
    //             $time = date("Y-m-d H:i:s");
    //             DB::update("UPDATE ve SET Trạng_thái`= ?,Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
    //                     [2,$makh,$time,$ma]);                
    //         }
    //         // vé vưa có ngươi đặt
    //         else if($kt[0]->Trạng_thái == 1){
    //              return \response()->json(['kq'=>1]);
    //         }// vé vữa có ngươi` giữ chỗ
    //         else if($kt[0]->Trạng_thái == 2){
    //              return \response()->json(['kq'=>2]);
    //         }
    //         sleep(60);
    //         $kt2 = DB::table("ve")->where("Mã","=",$ma)->select("Trạng_thái","Mã_khách_hàng","Mã","Thời_điểm_chọn")->get();
    //         // && $kt2[0]->Mã_khách_hàng == $makh
    //         if($kt2[0]->Trạng_thái == 2 && $kt2[0]->Thời_điểm_chọn == $time){
    //             DB::update("UPDATE ve SET Trạng_thái`= ?,Mã_khách_hàng`= ?, `Thời_điểm_chọn`=? WHERE `Mã`= ?",[0,null,null,$ma]);
    //         }
    //         return \response()->json(['kq'=>0]);
    // }

         public function xulydatve2(Request $request){
    
            $ma = $request -> MA;

         $kt = DB::table("ve")->where("Mã","=",$ma)->select("Trạng_thái")->get();
           if($kt[0]->Trạng_thái == 2){
            DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`Mã_khách_hàng`= ?, `Thời_điểm_chọn`= ? WHERE `Mã`= ?",
                    [0,Null,Null,$ma]);
            return \response()->json(['kq'=>1]);
        }   
        //trương` hợp ngươi dung` tự tắt giữ chỗ va handle vẫn tiếp tục đếm
        else if($kt[0]->Trạng_thái == 0){
                 return \response()->json(['kq'=>0]);
            }

    }

     public function android_capnhatDriver(Request $request){
        $ma = $request->MA;
        $name = $request->NAME;
        $ngay = $request->NGAY;
        $thang = $request->THANG;
        $nam = $request->NAM;
        $gt = $request->GT;
        $diachi = $request->DIACHI;
        // $email = $request->EMAIL;
        $branch = $request->branch;
        $licence = $request->licence;
        $phone = $request->phone;
        $username = $request->username;
        $email = $request->email;
        $datestarting = $request->datestarting;
        DB::update("UPDATE employee SET `Họ_Tên`= ?, `Giới_tính`= ?, `Ngày_sinh`= ?, `Địa_chỉ`= ?,  `Date_Starting`= ?, `Chi_nhánh`= ?, `Bằng_lái`= ?, `Sđt`= ?, `Username`= ?, `Email`= ?,
            `Loại_NV`= ?
         WHERE `Mã`= ?",
                    [$name,$gt,$nam."-".$thang."-".$ngay,$diachi,$datestarting,
                    $branch,$licence,$phone,$username,$email,"TX",$ma]); 
        return \response()->json(['kq'=>1]);
    }
     /*thong tin khach*/
    // public function thongtin($makh){
    //     $thongtinkhach = DB::table("customer")->where("Mã","=","$makh")->select("Mã","Tên","Ngày_sinh","Giới tính","Địa chỉ","Email","Sđt")->get();
    //     return view('tttn-web.thongtinkhach',["thongtinkhach"=>$thongtinkhach]);
    // }
    /***/

    /*doi mat khau*/
    public function android_doimatkhauDriver(Request $request){
        $ma = $request->MA;
        $mkcu = md5($request->MKCU);
        $mkmoi= md5($request->MKMOI);
        $dnkt = DB::select("SELECT * FROM employee WHERE Mã = ? AND Password = ?",[$ma,$mkcu]);
        if($dnkt){
             DB::update("UPDATE employee SET `Password`= ? WHERE `Mã`= ?",[$mkmoi,$ma]);
             return \response()->json(['kq'=>1]);
        }
        else{
            return \response()->json(['kq'=>0]);
        }
    }
}
