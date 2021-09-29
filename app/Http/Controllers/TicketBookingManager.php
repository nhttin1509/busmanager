<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Khachhang;

class TicketBookingManager extends Controller
{
    //
	public function qldv_checkLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if($username == null||$password == null)
        {
            return redirect()->back()->with(['alert' => 'Tên tài khoản hoặc mật khẩu không được để trống','username' => $username]);
            // return response()->json(var_dump($errors);
        }
        $account = DB::table('employee')->where([['Username','=',$username],['Password','=',md5($password)],['Loại_NV','!=','TX']])->get();
        if(!empty($account[0]))
        {
            session(['qldv.islogin' => 1]);
            session(['qldv.id' => $account[0]->Mã]);
            session(['qldv.name' => $account[0]->Họ_Tên]);
            return redirect('/qldv');
        }
        else
		{
            return redirect()->back()->with(['alert' => 'Tài khoản hoặc mật khẩu không đúng','username' => $username]);
        }
    }

    public  function qldv_logout()
	{
        session()->forget('qldv');
        return redirect()->back();
    }
	
	/* public function searchtinh(Request $request)
	{
		$txtsearch = strtolower($request->txtsearch);
		$txtsearch = FunctionBase::convertAlias($txtsearch);
		if($txtsearch != null)
		{
			$results = DB::select(DB::raw("SELECT Tên FROM tinh WHERE BINARY LOWER(Tên_không_dấu) LIKE '%{$txtsearch}%'"));
			return response()->json(['kq' => 1,'data' => $results]);
		}
		return response()->json(['data' => 0]);
	} */
	//Phần giám sát
	public function qldv_giamsat($id = "")
	{
		if($id == "")
		{
			$id = DB::table("chuyen_xe")->where("Trạng_thái","=",0)->orderBy("Mã","desc")->select("Mã")->get()[0]->Mã;
			if(!session('qldv.idgiamsat'))
			{
				session(['qldv.idgiamsat' => $id]);
			}
		}
		else
		{
			session(['qldv.idgiamsat' => $id]);
		}		
		return view('quanlydatve.giamsat');
	}
	
	//Phần trang đặt vé
	public function trangdatve()
	{
		$diadiem = DB::table('tinh')->select('Tên','Tên_không_dấu')->get();
		return view('quanlydatve.datve',['diadiem' => $diadiem]);
	}
	
	public function searchroute(Request $request)
	{
		$noidi = $request->noidi;
		$noiden = $request->noiden;
		$ngaydi = $request->ngaydi;
		$loaighe = $request->loaighe;
		$chuyenxe = DB::table('chuyen_xe')->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')->where('lo_trinh.Nơi_đi','=',$noidi)->where('lo_trinh.Nơi_đến','=',$noiden)->where('chuyen_xe.Ngày_xuất_phát','=',$ngaydi)->where('bus_model.Loại_ghế','=',$loaighe)->select('chuyen_xe.Mã','lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','chuyen_xe.Ngày_xuất_phát','chuyen_xe.Giờ_xuất_phát','lo_trinh.Thời_gian_đi_dự_kiến','bus_model.Loại_ghế','bus_model.Số_hàng','bus_model.Số_cột','bus_model.Sơ_đồ')->get();
		foreach($chuyenxe as $key => $value)
		{
			$thoigiandi = strtotime($chuyenxe[$key]->Ngày_xuất_phát.' '.$chuyenxe[$key]->Giờ_xuất_phát);
			$ngayden = date('d-m-Y',$thoigiandi + $chuyenxe[$key]->Thời_gian_đi_dự_kiến);
			$gioden = date('H:i:s',$thoigiandi + $chuyenxe[$key]->Thời_gian_đi_dự_kiến);
			$chuyenxe[$key]->Ngày_xuất_phát = date('d-m-Y',strtotime($chuyenxe[$key]->Ngày_xuất_phát));
			$chuyenxe[$key]->Ngày_đến = $ngayden;
			$chuyenxe[$key]->Giờ_đến = $gioden;
		}
		return response()->json(['kq' => 1,'data' => $chuyenxe]);
	}
	
	public function routedetails(Request $request)
	{
		$machuyenxe = $request->idchuyenxe;
		sleep(0);
		try
		{
			$loaixe = DB::table('chuyen_xe')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')->join('bus_model','xe.Mã_loại_xe','bus_model.Mã')->where('chuyen_xe.Mã','=',$machuyenxe)->select('bus_model.Loại_ghế','bus_model.Sơ_đồ','bus_model.Số_cột','bus_model.Số_hàng')->get();
			$ve = DB::table('ve')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->leftJoin('employee','ve.Mã_nhân_viên_đặt','=','employee.Mã')->where('ve.Mã_chuyến_xe','=',$machuyenxe)->select('ve.Mã','ve.Mã_khách_hàng','ve.Mã_chuyến_xe','ve.Trạng_thái','ve.Thời_điểm_chọn','ve.Vị_trí_ghế','customer.Tên','customer.Ngày_sinh','customer.Giới tính as Giới_tính','customer.Sđt','customer.Địa chỉ','ve.Mã_nhân_viên_đặt','employee.Họ_Tên')->orderBy('ve.Mã','asc')->get();
			foreach($ve as $key => $value)
			{
				if($ve[$key]->Thời_điểm_chọn != null)
				{
					$thoigiancon = 600 - intval(strtotime(date("Y-m-d H:i:s"))) + intval(strtotime($ve[$key]->Thời_điểm_chọn));
					$ve[$key]->Thời_gian_còn = $thoigiancon;
				}
				else
				{
					$ve[$key]->Thời_gian_còn = null;
				}
			}
			return response()->json(['kq' => 1,'loaixe' => $loaixe,'ve' => $ve]);
		} catch(\Exception $e)
		{
			return response()->json(['kq' => 0,'error' => $e]);
		}
	}
	
	public function qldv_chonve(Request $request) //Chọn vé
	{
		$mave = $request->idve;
		$manhanvien = $request->idnhanvien;
		if(!empty(DB::select("SELECT * FROM ve WHERE Mã = ? AND Trạng_thái = 1",[$mave])))
		{
			$ve = DB::table('ve')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->leftJoin('employee','ve.Mã_nhân_viên_đặt','=','employee.Mã')->where('ve.Mã','=',$mave)->select('ve.Vị_trí_ghế','customer.Tên','customer.Sđt','employee.Họ_Tên')->get();
			return response()->json(['kq' => 1,'ttghe' => $ve]);
		}
		else if(!empty(DB::select("SELECT * FROM ve WHERE Mã = ? AND Trạng_thái = 2 AND Mã_khách_hàng IS NOT NULL",[$mave])))
		{
			$ve = DB::table('ve')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->leftJoin('employee','ve.Mã_nhân_viên_đặt','=','employee.Mã')->where('ve.Mã','=',$mave)->select('ve.Vị_trí_ghế','ve.Thời_điểm_chọn','customer.Tên','customer.Sđt','employee.Họ_Tên')->get();
			$thoigiancon = 600 - intval(strtotime(date("Y-m-d H:i:s"))) + intval(strtotime($ve[0]->Thời_điểm_chọn));
			$ve[0]->Thời_gian_còn = $thoigiancon;
			return response()->json(['kq' => 2,'ttghe' => $ve]);
		}
		else if(!empty(DB::select("SELECT * FROM ve WHERE Mã = ? AND Trạng_thái = 2 AND Mã_khách_hàng IS NULL",[$mave])))
		{
			$ve = DB::table('ve')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->leftJoin('employee','ve.Mã_nhân_viên_đặt','=','employee.Mã')->where('ve.Mã','=',$mave)->select('ve.Vị_trí_ghế','customer.Tên','customer.Sđt','employee.Họ_Tên')->get();
			return response()->json(['kq' => 3,'ttghe' => $ve]);
		}
		else if(DB::select("SELECT * FROM ve WHERE Mã = ? AND Trạng_thái = 0",[$mave]))
		{
			try
			{
				$updated_at = date('Y-m-d H:i:s');
				DB::update("UPDATE `ve` SET `Trạng_thái` = 2, `Mã_nhân_viên_đặt` = ?, `updated_at` = ? WHERE `Mã` = ? AND `Trạng_thái` = 0",[$manhanvien,$updated_at,$mave]);
				// sleep(.1);
				$ve = DB::table('ve')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->leftJoin('employee','ve.Mã_nhân_viên_đặt','=','employee.Mã')->where('ve.Mã','=',$mave)->select('ve.Vị_trí_ghế','customer.Tên','customer.Sđt','employee.Họ_Tên')->get();
				return response()->json(['kq' => 4,'ttghe' => $ve]);
			} catch(\Exception $e)
			{
				return response()->json(['kq' => 0]);
			}
		}
		return response()->json(['kq' => 0]);
	}
	
	public function qldv_huychonve(Request $request) //Hủy chọn vé
	{
		$mave = $request->idve;
		$updated_at = date('Y-m-d H:i:s');
		try
		{
			DB::update("UPDATE `ve` SET `Trạng_thái` = 0, `Mã_nhân_viên_đặt` = NULL, `updated_at` = ? WHERE `Mã` = ? AND `Trạng_thái` = 2",[$updated_at,$mave]);
			// sleep(.1);
			$ve = DB::table('ve')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->leftJoin('employee','ve.Mã_nhân_viên_đặt','=','employee.Mã')->where('ve.Mã','=',$mave)->select('ve.Vị_trí_ghế','customer.Tên','customer.Sđt','employee.Họ_Tên')->get();
			return response()->json(['kq' => 1,'ttghe' => $ve]);
		} catch(\Exception $e)
		{
			return response()->json(['kq' => 0]);
		}
	}
	
	public function qldv_huychonchuyenxe(Request $request) //Hủy chọn chuyến xe
	{
		$vitrive = $request->vitrive;
		$machuyenxe = $request->idchuyenxe;
		$updated_at = date('Y-m-d H:i:s');
		for($i=0;$i<count($vitrive);$i++)
		{
			try
			{
				DB::update("UPDATE `ve` SET `Trạng_thái` = 0, `Mã_nhân_viên_đặt` = NULL, `updated_at` = ? WHERE `Mã_chuyến_xe` = ? AND `Trạng_thái` = 2 AND `Vị_trí_ghế` = ?",[$updated_at,$machuyenxe,$vitrive[$i]]);
				// sleep(.1);
			} catch(\Exception $e)
			{
				return response()->json(['kq' => 0]);
			}
		}
		return response()->json(['kq' => 1]);
	}
	
	public function qldv_searchcustomer(Request $request) //Tìm khách hàng
	{
		$datasearch = $request->datasearch;
		$filtermode = $request->filtermode;
		$khongdau = strtolower(FunctionBase::convertAlias($datasearch));
		if($filtermode == 0)
		{
			$kq = DB::select(DB::raw("SELECT Mã,Tên,Sđt FROM customer WHERE BINARY LOWER(Tên_không_dấu) LIKE '%{$khongdau}%' OR Sđt LIKE BINARY '%{$khongdau}%'"));
			return response()->json(['kq' => 1,'data' => $kq]);
		}
		else if($filtermode == 1)
		{
			$kq = DB::select(DB::raw("SELECT Mã,Tên,Sđt FROM customer WHERE BINARY LOWER(Tên_không_dấu) LIKE '%{$khongdau}%'"));
			return response()->json(['kq' => 1,'data' => $kq]);
		}
		else if($filtermode == 2)
		{
			$kq = DB::select(DB::raw("SELECT Mã,Tên,Sđt FROM customer WHERE Sđt LIKE BINARY '%{$khongdau}%'"));
			return response()->json(['kq' => 1,'data' => $kq]);
		}
		return response()->json(['kq' => 0]);
	}
	
	public function qldv_infokhachhang(Request $request) //Xuất ra thông tin khách hàng đã đăng ký
	{
		$idkhachhang = $request->idkhachhang;
		try
		{
			$kq = DB::table('customer')->where('Mã','=',$idkhachhang)->select("Mã","Tên","Ngày_sinh","Giới tính as Giới_tính","Sđt","Email","Địa chỉ as Địa_chỉ")->get();
			if(!empty($kq))
			{
				$kq[0]->Ngày_sinh_hiển_thị = date("d-m-Y",strtotime($kq[0]->Ngày_sinh));
			}
			return response()->json(['kq' => 1,'data' => $kq]);
		}
		catch (\Exception $e)
		{
			return response()->json(['kq' => 0]);
		}
	}
	
	public function qldv_xldatve(Request $request)
	{
		$manhanvien = $request->idnhanvien;
		$makhachhang = $request->idkhachhang;
		$machuyenxe = $request->idchuyenxe;
		$vedachon = $request->vedachon;
		$madatve = $makhachhang.strtotime(date("Y-m-d H:i:s"));
		try
		{
			$data = [];
			$updated_at = date('Y-m-d H:i:s');
			DB::insert("INSERT INTO `dondatve`(`Mã`,`Mã_nhân_viên_đặt`,`Mã_khách_hàng`,`Mã_chuyến_xe`,`Vị_trí_đặt`,`Trạng_thái`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?,?)",[$madatve,$manhanvien,$makhachhang,$machuyenxe,implode(",",$vedachon),0,$updated_at,$updated_at]);
			$ttchuyenxe = DB::table("chuyen_xe")->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")->where("chuyen_xe.Mã","=",$machuyenxe)->select("lo_trinh.Nơi_đi","lo_trinh.Nơi_đến","chuyen_xe.Ngày_xuất_phát","chuyen_xe.Giờ_xuất_phát","lo_trinh.Thời_gian_đi_dự_kiến")->get();
			$ttchuyenxe[0]->Ngày_đi = date("d-m-Y H:i:s",strtotime($ttchuyenxe[0]->Ngày_xuất_phát." ".$ttchuyenxe[0]->Giờ_xuất_phát));
			$ttchuyenxe[0]->Ngày_đến = date("d-m-Y H:i:s",strtotime($ttchuyenxe[0]->Ngày_đi)+$ttchuyenxe[0]->Thời_gian_đi_dự_kiến);
			for($i=0;$i<count($vedachon);$i++)
			{
				$updated_at = date('Y-m-d H:i:s');
				if(DB::update("UPDATE `ve` SET `Mã_khách_hàng` = ?, `Mã_đặt_vé` = ?, `Trạng_thái` = 1, `updated_at` = ? WHERE `Mã_chuyến_xe` = ? AND `Mã_nhân_viên_đặt` = ? AND `Vị_trí_ghế` = ? AND `Trạng_thái` = 2",[$makhachhang,$madatve,$updated_at,$machuyenxe,$manhanvien,$vedachon[$i]]))
				{
					$data[$i] = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')->where('ve.Mã_chuyến_xe','=',$machuyenxe)->where('ve.Vị_trí_ghế','=',$vedachon[$i])->select('ve.Mã','ve.Vị_trí_ghế','chuyen_xe.Tiền_vé')->get()[0];
				}
			}
			$tongtien = count($vedachon)*$data[0]->Tiền_vé;
			return response()->json(['kq' => 1,'data' => $data,'tongtien' => $tongtien,'ttchuyenxe' => $ttchuyenxe]);
		}
		catch(\Exception $e)
		{
			return response()->json(['kq' => $e]);
		}
	}
	
	public function qldv_xldangky(Request $request){
        $sdt = $request->SDT;
        $mk = $request->MK;
        $ngaysinh = $request->NGAYSINH;
        $gt = $request->GT;
        $name = $request->NAME;
        $namekd = FunctionBase::convertAlias($name);
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
			$id = $account->Mã;
            return \response()->json(['kq' => 1,'id' => $id]);
           
        }
        else{
            return \response()->json(['kq' => 0]);
        }
    }
	
	public function qldv_userinfo(Request $request)
	{
		$id = $request->id;
		$ttnhanvien = DB::table('employee')->where('Mã','=',$id)->get();
		if($ttnhanvien)
		{
			sleep(1);
			return response()->json(['kq' => 1,'userinfo' => $ttnhanvien]);
		}
		return response()->json(['kq' => 0]);
	}
	public function qldv_sendgps() { //Test OK

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() {
            while (true) {
				$hientai = date('Y-m-d H:i:s');
                $ngaytruoc = date('Y-m-d',strtotime($hientai) - 24*3600);
				$ngaysau = date('Y-m-d',strtotime($hientai) + 24*3600);
				// $xe = DB::table('chuyen_xe')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')->where('chuyen_xe.Ngày_xuất_phát','BETWEEN',$ngaytruoc,'AND',$ngaysau)->select('chuyen_xe.Mã','xe.location')->get();
				$xe = DB::table('chuyen_xe')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')->orderBy("chuyen_xe.Mã","desc")->select('chuyen_xe.Mã','xe.location')->get();
                echo 'data: ' . json_encode($xe) . "\n\n";
                ob_flush();
                flush();
                sleep(3);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;
    }
	public function qldv_showhistory(Request $request)
	{
		$id = $request->data;
		try {
			$data = DB::table("dondatve")->where("Mã_nhân_viên_đặt","=",$id)->select("Mã","created_at","Trạng_thái")->orderBy("Trạng_thái","asc")->orderBy("created_at","desc")->get();
			for($i=0;$i<count($data);$i++)
			{
				$data[$i]->Ngày = date("d-m-Y",strtotime($data[$i]->created_at));
				$data[$i]->Giờ = date("H:i:s",strtotime($data[$i]->created_at));
			}
			return response()->json(['kq' => 1,'data' => $data]);
		} catch (\Exception $e) {
			return response()->json(['kq' => 0,'data' => $e]);
		}
	}
	public function qldv_showdetails(Request $request)
	{
		$id = $request->data;
		try {
			$details = DB::table("dondatve")->join("chuyen_xe","dondatve.Mã_chuyến_xe","=","chuyen_xe.Mã")
			->join("customer","dondatve.Mã_khách_hàng","=","customer.Mã")
			->join("lo_trinh","chuyen_xe.Mã_lộ_trình","=","lo_trinh.Mã")
			->where("dondatve.Mã","=",$id)
			->select("dondatve.Mã","dondatve.Mã_chuyến_xe","dondatve.Mã_khách_hàng","dondatve.Vị_trí_đặt","lo_trinh.Nơi_đi","lo_trinh.Nơi_đến","chuyen_xe.Giờ_xuất_phát","chuyen_xe.Ngày_xuất_phát","lo_trinh.Thời_gian_đi_dự_kiến","chuyen_xe.Tiền_vé","customer.Tên","customer.Ngày_sinh","customer.Giới tính as Giới_tính","customer.Sđt")->get();
			$detail = $details[0];
			$detail->Ngày_đi = date("d-m-Y H:i:s",strtotime($detail->Ngày_xuất_phát." ".$detail->Giờ_xuất_phát));
			$detail->Ngày_đến = date("d-m-Y H:i:s",strtotime($detail->Ngày_đi)+$detail->Thời_gian_đi_dự_kiến);
			$detail->Tổng_tiền = count(explode(",",$detail->Vị_trí_đặt))*intval($detail->Tiền_vé); 
			return response()->json(['kq' => 1,'data' => $detail]);
		} catch (\Exception $e) {
			return response()->json(['kq' => 0,'error' => $e]);
		}
	}
	public function qldv_huydondatve(Request $request)
	{
		$id = $request->data;
		try {
			$updated_at = date('Y-m-d H:i:s');
			DB::update("UPDATE `ve` SET `Mã_nhân_viên_đặt` = NULL, `Mã_khách_hàng` = NULL, `Trạng_thái` = 0, `Mã_đặt_vé` = NULL, `updated_at` = ? WHERE `Mã_đặt_vé` = ?",[$updated_at,$id]);
			DB::update("UPDATE `dondatve` SET `Trạng_thái` = 2, `updated_at` = ? WHERE `Mã` = ?",[$updated_at,$id]);
			return response()->json(['kq' => 1]);
		} catch (\Exception $e) {
			return response()->json(['kq' => 0,'error' => $e]);
		}
	}
	// public function qldv_testSSE() { //Test OK

        // $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() {
            // while (true) {
                // $ngay = date('d-m-Y H-i-s');
                // echo 'data: ' . json_encode($ngay) . "\n\n";
                // ob_flush();
                // flush();
                // sleep(3);
            // }
        // });

        // $response->headers->set('Content-Type', 'text/event-stream');
        // return $response;
    // }
	// public function ticketinfo(/*Request $request*/$idve)
	// {
		// $idve = $request->idve; //Gửi lên idve
		// $ttve = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')->leftJoin('customer','ve.Mã_khách_hàng','=','customer.Mã')->where('ve.Mã','=',$idve)->select('ve.Mã','customer.Tên','customer.Email','customer.Sđt','lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','chuyen_xe.Giờ_xuất_phát','chuyen_xe.Ngày_xuất_phát','ve.Vị_trí_ghế','chuyen_xe.Tiền_vé')->get();
		// if(count($ttve) == 0)
		// {
			// return response()->json(['kq' => 0]);
		// }
		// else
		// {
			// $ttve = $ttve[0];
			// return response()->json(['kq' => 1,'data' => $ttve]);
		// }
	// } 
	// public function checkphone(Request $request) //Hàm kiểm tra Sđt điện thoại đã đăng ký chưa, nếu đã đăng ký thì đăng ký vé luôn còn không thì chuyển qua form đăng ký mới
	// {
		// $phone = $request->phone; //Gửi lên phone
		// $idve = $request->idve; //Gửi lên idve
		// $ttkh = DB::table('customer')->where('Sđt','=',$phone)->get();
		// if(count($ttkh) != 0)
		// {
			// $updated_at = date('Y-m-d h-i-s');
			// if(DB::update("UPDATE `ve` SET `Mã_khách_hàng` = ?, `Trạng_thái` = ?, `updated_at` = ? WHERE `Mã` = ? AND `Trạng_thái` = ?",[$ttkh[0]->Mã,1,$updated_at,$idve,0]))
			// {
				// return response()->json(['kq' => 1]); //Đăng ký vé thành công
			// }
			// else
			// {
				// return response()->json(['kq' => 0]); //Đăng ký vé thất bại
			// }
		// }
		// else
		// {
			// return response()->json(['kq' => 2]); //Chưa có tài khoản chuyển qua form nhập thông tin để đăng ký
		// }
	// }
	// public function dangkyve(Request $request) //Hàm đăng ký mới Sđt và đăng ký vé luôn
	// {
		// $phone = $request->phone; //Gửi lên phone
		// $name = $request->name; //Gửi lên name
		// $gioitinh = $request->gender; //Gửi lên gender
		// $ngaysinh = $request->ngaysinh; //Gửi lên ngaysinh
		// $idve = $request->idve; //Gửi lên idve
		// $ttkh = DB::table('customer')->where('Sđt','=',$phone)->get();
		// if(count($ttkh) == 0)
		// {
			// $created_at = date('Y-m-d h-i-s');
			// $updated_at = date('Y-m-d h-i-s');
			// if(DB::insert("INSERT INTO `customer`(`Tên`, `Ngày_sinh`, `Giới tính`, `Sđt`, `Password`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?)",[$name,$ngaysinh,$gioitinh,$phone,md5($phone),$created_at,$updated_at]))
			// {
				// $tmp = DB::table('customer')->where('Sđt','=',$phone)->get();
				// $idkh = $tmp[0]->Mã;
				// if(DB::update("UPDATE `ve` SET `Mã_khách_hàng` = ?, `Trạng_thái` = ? WHERE `Mã` = ? AND `Trạng_thái` = ?",[$idkh,1,$idve,0]))
				// {
					// return response()->json(['kq' => 1]); //Đăng ký vé thành công
				// }
				// else
				// {
					// return response()->json(['kq' => 0]); //Đăng ký vé thất bại
				// }
			// }
			// else
			// {
				// return response()->json(['kq' => 0]); //Đăng ký vé thất bại
			// }
		// }
		// else
		// {
			// return response()->json(['kq' => 2]); //Đã có tài khoản chuyển qua form nhập Sđt ban đầu
		// }
	// }
}
