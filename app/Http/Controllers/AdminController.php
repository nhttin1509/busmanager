<?php

namespace App\Http\Controllers;

use App\Chuyenxe;
use core_question\bank\view;
use gradereport_singleview\local\screen\select;
use http\Env\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Khachhang;
use App\Nhanvien;
use App\Loaixe;
use App\Xe;
use App\Tramdung;
use App\Lotrinh;
use App\Tinh;
use App\Ve;
use phpDocumentor\Reflection\Types\Integer;

class AdminController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth:admin');
//    }

    public function test()
	{
        echo "Thành công!";
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    /*public function login(Request $request){
        echo "Kết quả:";
        echo $request->username;
        echo bcrypt($request->password);
    }*/

    public function checkLogin(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if($username == null||$password == null)
        {
            return redirect()->back()->with(['alert' => 'Tên tài khoản hoặc mật khẩu không được để trống','username' => $username]);
            // return response()->json(var_dump($errors);
        }
        $account = DB::table('employee')->where([['Username','=',$username],['Password','=',$password],['Loại_NV','=','QTV']])->get();
        if(!empty($account[0]))
        {
            session(['admin.islogin' => 1]);
            session(['admin.id' => $account[0]->Mã]);
            session(['admin.name' => $account[0]->Họ_Tên]);
            return redirect('/admin');
        }
        else
		{
            return redirect()->back()->with(['alert' => 'Tài khoản hoặc mật khẩu không đúng','username' => $username]);
        }
    }

    public  function logout()
	{
        session()->forget('admin');
        return redirect()->back();
    }

	//Phần thống kê
	public function thongke()
	{
		$slkhachhang = count(DB::table("customer")->select("Mã")->get());
		$slchuyenxe = count(DB::table("chuyen_xe")->select("Mã")->get());
		$slchuyenxedadi = count(DB::table("chuyen_xe")->where('Trạng_thái','=',2)->select("Mã")->get());
		$slchuyenxedangcho = count(DB::table("chuyen_xe")->where('Trạng_thái','=',0)->select("Mã")->get());
		$thongke = DB::table("thong_ke")->where('Năm','=',date("Y"))->orderBy("Tháng","asc")->select("Chi_phí","Doanh_thu","Số_chuyến_xe")->get();
		$thongke1 = DB::table("thong_ke")->where('Năm','=',(date("Y") - 1))->orderBy("Tháng","asc")->select("Chi_phí","Doanh_thu","Số_chuyến_xe")->get();
		$chuyenxe = [];
		$dem = 0;
		$tmp1 = [];
		$tmp2 = [];
		$tongdt = 0;
		for($i=0;$i<count($thongke1);$i++)
		{
			$tmp1[$i] = $thongke1[$i]->Số_chuyến_xe;
			$tmp2[$i] = $thongke1[$i]->Doanh_thu/1000000;
		}
		$chuyenxe[$dem]=['Năm' => (date("Y") - 1),'Chuyến_xe' => $tmp1,'Doanh_thu' => $tmp2];
		$tmp1 = [];
		$tmp2 = [];
		$dem += 1;
		for($i=0;$i<count($thongke);$i++)
		{
			$tmp1[$i] = $thongke[$i]->Số_chuyến_xe;
			$tmp2[$i] = $thongke[$i]->Doanh_thu/1000000;
			$tongdt += $thongke[$i]->Doanh_thu;
		}
		$chuyenxe[$dem]=['Năm' => date("Y"),'Chuyến_xe' => $tmp1,'Doanh_thu' => $tmp2];
		return view("quantrivien.thongke",compact("slkhachhang","slchuyenxe","slchuyenxedadi","slchuyenxedangcho","chuyenxe","tongdt"));
	}

    //Phần khách hàng
    public function khachhang()
	{
        $customer = Khachhang::all();
        return view('quantrivien.khachhang', compact('customer'));
    }

    public function addkhachhang($index="")
	{
        if($index=="")
		{
            return view("quantrivien.addkhachhang");
        }
        try
		{
            $ttkhachhangs = DB::select("SELECT * FROM customer WHERE Mã = ?",[$index]);
            foreach ($ttkhachhangs as $row)
			{
                $ttkhachhang = $row;
            }
            return view("quantrivien.addkhachhang",["ttkhachhang" => $ttkhachhang]);
        }
		catch (\Exception $e)
		{
            die("Lỗi: ".$e);
        }
    }

    public  function addcustomer(Request $request)
	{
        $name = $request->name;
		$tenkhongdau = FunctionBase::convertAlias($name);
        $brtday = $request->brtday;
        $gender = $request->gender;
        $address = $request->address;
        $password = md5($request->password);
        $email = $request->email;
        $phone = $request->phone;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if(isset($request->ID))
		{
            if(DB::update("UPDATE `customer` SET `Tên`= ?,`Tên_không_dấu`= ?,`Ngày_sinh`= ?,`Giới tính`= ?,`Địa chỉ`= ?,`Password`= ?,`Email`= ?,`Sđt`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$name,$tenkhongdau,$brtday,$gender,$address,$password,$email,$phone,$updated_at,$request->ID]))
				{
					return redirect()->back()->with('alert','Sửa thành công!');
				}
            else
			{
				return redirect()->back()->with('alert','Sửa thất bại!');
			}
        }
        else
		{
            if(!DB::select('select * from customer where Email = ? or Sđt = ? or Tên = ?',[$request->email,$request->phone,$request->name]))
            {
                DB::insert("INSERT INTO `customer`(`Tên`, `Tên_không_dấu`, `Ngày_sinh`, `Giới tính`, `Địa chỉ`, `Password`, `Email`, `Sđt`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?,?,?)",
                    [$name,$tenkhongdau,$brtday,$gender,$address,$password,$email,$phone,$created_at,$updated_at]);
                return redirect()->back()->with('alert','Thêm thành công!');
            }
            else
			{
				return redirect()->back()->with('alert','Thêm thất bại!');
			}
        }
    }

    public function delcustomer($id)
	{
        try
		{
            DB::delete('DELETE FROM customer WHERE Mã = ?',[$id]);
        }
		catch (\Exception $e)
		{
            die("Lỗi xóa dữ liệu :".$e);
        }
        return redirect()->back();
    }
    public function viewkhachhang($id = "")
	{
        if ($id == "")
		{
			return redirect(url('admin/khachhang'));
		}
        $ttkhachhangs = DB::table('customer')->where('Mã','=',$id)->get();
        foreach ($ttkhachhangs as $row)
		{
            $ttkhachhang = (array)$row;
        }
        $ttchuyendi = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')
            ->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')
            ->where('ve.Mã_khách_hàng','=',$id)
            ->select('lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','ve.Vị_trí_ghế','chuyen_xe.Tiền_vé')
            ->get();
        return view('quantrivien.viewkhachhang',compact('ttkhachhang','ttchuyendi'));
    }

    //Phần nhân viên
    public function nhanvien(){
        $nhanvien = Nhanvien::all();
        return view("quantrivien.nhanvien",["employee"=>$nhanvien]);
    }
    public function addnhanvien($index = ""){
        if($index == ""){
            return view("quantrivien.addnhanvien");
        }
        try {
            $ttnhanviens = DB::select("SELECT * FROM employee WHERE Mã = ?",[$index]);
            foreach ($ttnhanviens as $row){
                $ttnhanvien = $row;
            }
            return view("quantrivien.addnhanvien",["ttnhanvien" => $ttnhanvien]);
        } catch (\Exception $e) {
            die("Lỗi: ".$e);
        }
    }
    public  function addemployee(Request $request){
        $name = $request->name;
        $brtday = $request->brtday;
        $gender = $request->gender;
        $address = $request->address;
        $username = $request->username;
        $password = md5($request->password);
        $email = $request->email;
        $phone = $request->phone;
        $type = $request->typenv;
        $banglai = $request->banglai;
        $chinhanh = $request->chinhanh;
        $datestart = $request->datestart;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if(isset($request->ID)){
            if(DB::update("UPDATE `employee` SET `Họ_Tên`= ?,`Ngày_sinh`= ?,`Giới_tính`= ?,`Địa_chỉ`= ?,`Username`= ?,`Password`= ?,`Email`= ?,`Sđt`= ?,`Loại_NV`= ?,`Bằng_lái`= ?,`Chi_nhánh`= ?,`Date_Starting`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$name,$brtday,$gender,$address,$username,$password,$email,$phone,$type,$banglai,$chinhanh,$datestart,$updated_at,$request->ID]))
                return redirect()->back()->with('alert','Sửa thành công!');
            else
                return redirect()->back()->with('alert','Sửa thất bại!');
        }
        else {
            if(!DB::select('select * from employee where Email = ? or Sđt = ? or Họ_Tên = ?',[$request->email,$request->phone,$request->name]))
            {
                DB::insert("INSERT INTO `employee`(`Họ_Tên`, `Ngày_sinh`, `Giới_tính`, `Địa_chỉ`, `Username`, `Password`, `Email`, `Sđt`, `Loại_NV`,`Bằng_lái`,`Chi_nhánh`,`Date_Starting`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
                    [$name,$brtday,$gender,$address,$username,$password,$email,$phone,$type,$banglai,$chinhanh,$datestart,$created_at,$updated_at]);
                return redirect()->back()->with('alert','Thêm thành công!');
            }
            else
                return redirect()->back()->with('alert','Thêm thất bại!');
        }
    }
    public function delemployee($id){
        try {
            DB::delete('DELETE FROM employee WHERE Mã = ?',[$id]);
        } catch (\Exception $e) {
            die("Lỗi xóa dữ liệu :".$e);
        }
        return redirect()->back();
    }
	public function userinfo(Request $request)
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

    //Phần loại xe
    public function loaixe(){
        $busmodel = Loaixe::all();
        return view('quantrivien.loaixe',compact('busmodel'));
    }
    public function addbusmodel(Request $request){
        $name = $request->name;
        $row = $request->row;
        $col = $request->col;
        $employeeid = $request->employeeID;
        $soghe = $request->soghe;
        $sodo = $request->noidung;
        $loaighe =$request->loaighe;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if(!empty($request->ID)) {
            if (DB::update("UPDATE `bus_model` SET `Tên_Loại`= ?,`Loại_ghế`= ?,`Số_ghế`= ?,`Số_hàng`= ?,`Số_cột`= ?,`Sơ_đồ`= ?,`Mã_nhân_viên_chỉnh_sửa`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$name, $loaighe, $soghe, $row, $col, $sodo, $employeeid, $updated_at, $request->ID]))
            {
                return redirect()->back()->with('alert', 'Sửa thành công!');
            }
            else
                return redirect()->back()->with('alert','Sửa thất bại!');
        }
        else {
            if( DB::insert("INSERT INTO `bus_model`(`Tên_Loại`, `Loại_ghế`, `Số_ghế`, `Số_hàng`, `Số_cột`, `Sơ_đồ`, `Mã_nhân_viên_tạo`, `Mã_nhân_viên_chỉnh_sửa`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?,?,?)",
                [$name,$loaighe,$soghe,$row,$col,$sodo,$employeeid,$employeeid,$created_at,$updated_at]))
            {
                return redirect()->back()->with('alert','Thêm thành công!');
            }
            else
                return redirect()->back()->with('alert','Thêm thất bại!');
        }
    }
    public function delbusmodel($id){
        try {
            DB::delete('DELETE FROM bus_model WHERE Mã = ?',[$id]);
        } catch (\Exception $e) {
            die("Lỗi xóa dữ liệu :".$e);
        }
        return redirect()->back();
    }

    //Phần xe
    public function xe(){
        $bus = DB::table('xe')->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')
            ->select('xe.Mã','xe.Biển_số','xe.Mã_loại_xe','bus_model.Loại_ghế','xe.Ngày_bảo_trì_gần_nhất','xe.Ngày_bảo_trì_tiếp_theo','xe.location')->get();
        $typebuses = Loaixe::all();
        $typebus = [];
        foreach ($typebuses as $row){
            $typebus["{$row['Mã']}"]=$row['Tên_Loại'];
        }
        return view('quantrivien.xe',compact('bus','typebus'));
    }
    public function addxe($index = ""){
        $bustypes = DB::select("SELECT Mã,Tên_Loại,Loại_ghế FROM bus_model");
        if($index == ""){
            return view("quantrivien.addxe",["bustypes"=>$bustypes]);
        }
        try {
            $ttxes = DB::table('xe')
                ->where('xe.Mã','=',$index)
                ->select('xe.Mã','xe.Biển_số','xe.Mã_loại_xe','xe.Ngày_bảo_trì_gần_nhất','xe.Ngày_bảo_trì_tiếp_theo')->get();
            foreach ($ttxes as $row){
                $ttxe = $row;
            }
            return view("quantrivien.addxe",["ttxe" => $ttxe,"bustypes"=>$bustypes]);
        } catch (\Exception $e) {
            die("Lỗi: ".$e);
        }
    }
    public  function addbus(Request $request){
        $bienso = $request->bienso;
        $idtypebus = $request->idtypebus;
        $gannhat = $request->baotrigannhat;
        $tieptheo = $request->baotritieptheo;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if(isset($request->ID)){
            if(DB::update("UPDATE `xe` SET `Biển_số`= ?,`Mã_loại_xe`= ?,`Ngày_bảo_trì_gần_nhất`= ?,`Ngày_bảo_trì_tiếp_theo`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$bienso,$idtypebus,$gannhat,$tieptheo,$updated_at,$request->ID]))
                return redirect()->back()->with('alert','Sửa thành công!');
            else
                return redirect()->back()->with('alert','Sửa thất bại!');
        }
        else {
            if( DB::insert("INSERT INTO `xe`(`Biển_số`, `Mã_loại_xe`, `Ngày_bảo_trì_gần_nhất`, `Ngày_bảo_trì_tiếp_theo`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?)",
                [$bienso,$idtypebus,$gannhat,$tieptheo,$created_at,$updated_at]))
            {
                return redirect()->back()->with('alert','Thêm thành công!');
            }
            else
                return redirect()->back()->with('alert','Thêm thất bại!');
        }
    }
    public function delbus($id){
        try {
            DB::delete('DELETE FROM xe WHERE Mã = ?',[$id]);
        } catch (\Exception $e) {
            die("Lỗi xóa dữ liệu :".$e);
        }
        return redirect()->back();
    }

    //Phần trạm dừng
    public function tramdung(){
        $busstop = DB::table('tram_dung')->join('employee','tram_dung.Mã_nhân_viên_tạo','=','employee.Mã')
            ->join('employee as employee1','tram_dung.Mã_nhân_viên_chỉnh_sửa','=','employee1.Mã')
            ->select('tram_dung.Mã','tram_dung.Tên','tram_dung.Tọa_độ','tram_dung.Mã_nhân_viên_tạo','tram_dung.Mã_nhân_viên_chỉnh_sửa','employee.Họ_Tên as Nhân_viên_tạo','employee1.Họ_Tên as Nhân_viên_chỉnh_sửa')->get();
        return view("quantrivien.tramdung",compact('busstop'));
    }
    public function addtramdung($index = ""){
        if($index == ""){
            return view("quantrivien.addtramdung");
        }
        try {
            $tttramdungs = DB::select("SELECT * FROM tram_dung WHERE Mã = ?",[$index]);
            foreach ($tttramdungs as $row){
                $tttramdung = $row;
            }
            return view("quantrivien.addtramdung",["tttramdung" => $tttramdung]);
        } catch (\Exception $e) {
            die("Lỗi: ".$e);
        }
    }
    public  function addbusstop(Request $request){
        $name = $request->name;
        $employeeid = $request->employeeID;
        $toado = $request->toado;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if(isset($request->ID)){
            if(DB::update("UPDATE `tram_dung` SET `Tên`= ?,`Tọa_độ`= ?,`Mã_nhân_viên_chỉnh_sửa`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$name,$toado,$employeeid,$updated_at,$request->ID]))
                return redirect()->back()->with('alert','Sửa thành công!');
            else
                return redirect()->back()->with('alert','Sửa thất bại!');
        }
        else {
            if( DB::insert("INSERT INTO `tram_dung`(`Tên`, `Tọa_độ`, `Mã_nhân_viên_tạo`, `Mã_nhân_viên_chỉnh_sửa`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?)",
                [$name,$toado,$employeeid,$employeeid,$created_at,$updated_at]))
            {
                return redirect()->back()->with('alert','Thêm thành công!');
            }
            else
                return redirect()->back()->with('alert','Thêm thất bại!');
        }
    }
    public function delbusstop($id){
        try {
            DB::delete('DELETE FROM tram_dung WHERE Mã = ?',[$id]);
        } catch (\Exception $e) {
            die("Lỗi xóa dữ liệu :".$e);
        }
        return redirect()->back();
    }

    //Phần lộ trình
    public function lotrinh($cm =""){
        $busstops = Tramdung::all();
        if($cm == "")
        {
            $busroute = Lotrinh::all();
            $province = Tinh::all();
            return view("quantrivien.lotrinh",compact('busroute','busstops','province'));
        }
        elseif($cm == "1"){
            $busroute = Lotrinh::all();
            return \response()->json(['msg'=>$busroute]);
        }
        elseif($cm == "2"){
            $province = Tinh::all();
            return \response()->json(['msg'=>$province]);
        }
    }
    public function addbusroute(Request $request) {
        $noidi = $request->noidi;
        $noiden = $request->noiden;
        $employeeid = $request->employeeID;
        $thoigiandi = $request->thoigiandi;
        $busstops = $request->busstops;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if($request->ID != ""){
            if(DB::update("UPDATE `lo_trinh` SET `Nơi_đi`= ?,`Nơi_đến`= ?,`Thời_gian_đi_dự_kiến`= ?,`Các_trạm_dừng_chân`= ?,`Mã_nhân_viên_chỉnh_sửa`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$noidi,$noiden,$thoigiandi,$busstops,$employeeid,$updated_at,$request->ID]))
                return \response()->json(['result'=>'1']);
            else
                return \response()->json(['result'=>'0']);
        }
        else {
            if( DB::insert("INSERT INTO `lo_trinh`(`Nơi_đi`, `Nơi_đến`, `Thời_gian_đi_dự_kiến`, `Các_trạm_dừng_chân`, `Mã_nhân_viên_tạo`, `Mã_nhân_viên_chỉnh_sửa`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?,?)",
                [$noidi,$noiden,$thoigiandi,$busstops,$employeeid,$employeeid,$created_at,$updated_at]))
            {
                return \response()->json(['result'=>'1']);
            }
            else
                return \response()->json(['result'=>'0']);
        }
    }
    public function delbusroute(Request $request) {
        if(isset($request->ID)) {
            try {
                DB::delete('DELETE FROM lo_trinh WHERE Mã = ?',[$request->ID]);
            } catch (\Exception $e) {
                return \response()->json(['result'=>'0']);
            }
            return \response()->json(['result'=>'1']);
        }
    }
	public function admin_getlocations(Request $request)
	{
		$locations = $request->locations;
		$toado = [];
		for($i=0;$i<count($locations);$i++)
		{
			$name = DB::table("tram_dung")->where("Mã","=",$locations[$i])->select("Tên")->get()[0]->Tên;
			$location = explode(",",DB::table("tram_dung")->where("Mã","=",$locations[$i])->select("Tọa_độ")->get()[0]->Tọa_độ);
			$location[2] = $name;
			$toado[$i] = $location;
		}
		return response()->json(['kq' => 1,'data' => $toado]);
	}

    //Phần tỉnh
    public function addprovince(Request $request) {
        $name = $request->name;
		$namekhongdau = FunctionBase::convertAlias($request->name);
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if($request->ID != ""){
            if(DB::update("UPDATE `tinh` SET `Tên`= ?, `Tên_không_dấu`= ?, `updated_at`= ? WHERE `Mã`= ?",
                [$name,$namekhongdau,$updated_at,$request->ID]))
                return \response()->json(['result'=>'1']);
            else
                return \response()->json(['result'=>'0']);
        }
        else {
            if( DB::insert("INSERT INTO `tinh`(`Tên`, `Tên_không_dấu`, `created_at`, `updated_at`) VALUES (?,?,?,?)",
                [$name,$namekhongdau,$created_at,$updated_at]))
            {
                return \response()->json(['result'=>'1']);
            }
            else
                return \response()->json(['result'=>'0']);
        }
    }
    public function delprovince(Request $request) {
        if(isset($request->ID)) {
            try {
                DB::delete('DELETE FROM tinh WHERE Mã = ?',[$request->ID]);
            } catch (\Exception $e) {
                return \response()->json(['result'=>'0']);
            }
            return \response()->json(['result'=>'1']);
        }
    }

    //Phần chuyến xe
    public function chuyenxe() {
        $chuyenxe = DB::table('chuyen_xe')->join('employee','chuyen_xe.Mã_nhân_viên_tạo','=','employee.Mã')
            ->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')
            ->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')
            ->join('employee as employee1','chuyen_xe.Mã_tài_xế','=','employee1.Mã')
            ->where('chuyen_xe.is_del','=','0')
            ->select('chuyen_xe.Mã','employee.Họ_Tên as Nhân_viên_tạo','employee1.Họ_Tên as Tài_xế','lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','xe.Biển_số','chuyen_xe.Tiền_vé','bus_model.Loại_ghế','chuyen_xe.Ngày_xuất_phát','chuyen_xe.Giờ_xuất_phát','chuyen_xe.Trạng_thái')
            ->orderBy('chuyen_xe.Mã','desc')
			->get();
        return view("quantrivien.chuyenxe",compact('chuyenxe'));
    }
    public function addchuyenxe($id = "") {
        $lotrinhs = Lotrinh::all();
        $taixes = DB::table('employee')->where('Loại_NV','=','TX')->select('Mã','Họ_Tên')->get();
        $xes = Xe::all();
        if($id == "") {
            return view("quantrivien.addchuyenxe",compact('lotrinhs','taixes','xes'));
        }
        else {
            $ttchuyenxes = DB::table('chuyen_xe')->join('employee','chuyen_xe.Mã_nhân_viên_tạo','=','employee.Mã')
                ->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')
                ->join('employee as employee1','chuyen_xe.Mã_tài_xế','=','employee1.Mã')
                ->where('chuyen_xe.Mã','=',$id)
                ->select('chuyen_xe.Mã','chuyen_xe.Mã_nhân_viên_tạo','employee.Họ_Tên as Nhân_viên_tạo','chuyen_xe.Mã_tài_xế','employee1.Họ_Tên as Tài_xế','chuyen_xe.Mã_lộ_trình','lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','chuyen_xe.Mã_xe','xe.Biển_số','chuyen_xe.Ngày_xuất_phát','chuyen_xe.Giờ_xuất_phát','chuyen_xe.Tiền_vé','chuyen_xe.Trạng_thái')
                ->get();
            $ticket = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')
                ->join('xe','xe.Mã','=','chuyen_xe.Mã_xe')
                ->join('bus_model','bus_model.Mã','=','xe.Mã_loại_xe')
                ->select('ve.Mã','ve.Mã_chuyến_xe','ve.Mã_nhân_viên_đặt','ve.Mã_khách_hàng','ve.Mã_đặt_vé','ve.Vị_trí_ghế','ve.Trạng_thái','chuyen_xe.Tiền_vé','bus_model.Loại_ghế')
                ->where('Mã_chuyến_xe','=',$id)->get();
            foreach ($ttchuyenxes as $row){
                $ttchuyenxe = $row;
            }
            return view("quantrivien.addchuyenxe",compact('ttchuyenxe','lotrinhs','taixes','xes','ticket'));
        }
    }
    public function addchuyenxexl(Request $request) {
        $idlotrinh = $request->lotrinh;
        $idtaixe = $request->taixe;
        $idxe = $request->xe;
        $employeeid = $request->employeeID;
        $startdate = $request->startdate;
        $starttime = $request->starttime;
        $giave = $request->tien;
        $status = $request->status;
        $created_at = date('Y-m-d h-i-s');
        $updated_at = date('Y-m-d h-i-s');
        if(isset($request->ID)){
            try {
                if(!DB::select("SELECT * FROM chuyen_xe WHERE Mã = ? AND Mã_xe = ?",[$request->ID,$idxe])) {
                    DB::delete('DELETE FROM ve WHERE Mã_chuyến_xe = ?', [$request->ID]);
                    $loaixes = DB::table('xe')->join('bus_model', 'xe.Mã_loại_xe', '=', 'bus_model.Mã')
                        ->where('xe.Mã', '=', $idxe)->select('bus_model.Số_hàng', 'bus_model.Số_cột', 'bus_model.Sơ_đồ','bus_model.Loại_ghế')
                        ->get();
                    foreach ($loaixes as $row) {
                        $loaixe = (array)$row;
                    }
                    $sodo = $loaixe["Sơ_đồ"];
                    $row = intval($loaixe["Số_hàng"]);
                    $col = intval($loaixe["Số_cột"]);
                    $loaighe = $loaixe["Loại_ghế"];
                    $trangthai = 0;
                    $k = 1;
                    if($loaighe==1)
                    {
                        $row = ($row - 1)/2 + 1;
                    }
                    for ($i = 1; $i < $row; $i++) {
//                        $k = 1;
                        for ($j = 0; $j < $col; $j++) {
                            /*if ($i * $col + $j == 0)
                                continue;*/
                            if ($sodo[$i * $col + $j] == 1) {
                                $vitri = 'A-' . ($k);
                                DB::insert("INSERT INTO `ve`(`Mã_chuyến_xe`, `Vị_trí_ghế`, `Trạng_thái`, `created_at`, `updated_at`) VALUES (?,?,?,?,?)",
                                    [$request->ID, $vitri, $trangthai, $created_at, $updated_at]);
                                $k++;
                            }
                        }
                    }
                    if($loaighe==1){
                        $k = 1;
                        for ($i = $row; $i < intval($loaixe["Số_hàng"]); $i++) {
//                        $k = 1;
                            for ($j = 0; $j < $col; $j++) {
                                /*if ($i * $col + $j == 0)
                                    continue;*/
                                if ($sodo[$i * $col + $j] == 1) {
                                    $vitri = 'B-' . ($k);
                                    DB::insert("INSERT INTO `ve`(`Mã_chuyến_xe`, `Vị_trí_ghế`, `Trạng_thái`, `created_at`, `updated_at`) VALUES (?,?,?,?,?)",
                                        [$request->ID, $vitri, $trangthai, $created_at, $updated_at]);
                                    $k++;
                                }
                            }
                        }
                    }
                    DB::update("UPDATE `chuyen_xe` SET `Mã_lộ_trình`= ?,`Mã_tài_xế`= ?,`Mã_xe`= ?,`Tiền_vé`= ?,`Ngày_xuất_phát`= ?,`Giờ_xuất_phát`= ?,`Trạng_thái`= ?,`updated_at`= ? WHERE `Mã`= ?",
                        [$idlotrinh, $idtaixe, $idxe, $giave, $startdate, $starttime, $status, $updated_at, $request->ID]);
                }
                else{
                    DB::update("UPDATE `chuyen_xe` SET `Mã_lộ_trình`= ?,`Mã_tài_xế`= ?,`Mã_xe`= ?,`Tiền_vé`= ?,`Ngày_xuất_phát`= ?,`Giờ_xuất_phát`= ?,`Trạng_thái`= ?,`updated_at`= ? WHERE `Mã`= ?",
                        [$idlotrinh, $idtaixe, $idxe, $giave, $startdate, $starttime, $status, $updated_at, $request->ID]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('alert','Sửa thất bại!');
            }
            return redirect()->back()->with('alert','Sửa thành công!');
        }
        else {
            try {
                /*DB::insert("INSERT INTO `chuyen_xe`(`Mã_nhân_viên_tạo`, `Mã_lộ_trình`, `Mã_tài_xế`, `Mã_xe`, `Thời_gian_xuất_phát`, `created_at`, `updated_at`) VALUES (?,?,?,?,?,?,?)",
                    [$employeeid,$idlotrinh,$idtaixe,$idxe,$starttime,$created_at,$updated_at]);*/
                $id = DB::table("chuyen_xe")->insertGetId([
                    'Mã_nhân_viên_tạo' => $employeeid,
                    'Mã_lộ_trình' => $idlotrinh,
                    'Mã_tài_xế' => $idtaixe,
                    'Mã_xe' => $idxe,
                    'Tiền_vé' => $giave,
                    'Giờ_xuất_phát' => $starttime,
                    'Ngày_xuất_phát' => $startdate,
                    'Trạng_thái' => $status,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at
                ]);
                $loaixes = DB::table('xe')->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')
                    ->where('xe.Mã','=',$idxe)->select('bus_model.Số_hàng','bus_model.Số_cột','bus_model.Sơ_đồ','bus_model.Loại_ghế')
                    ->get();
                foreach ($loaixes as $row){
                    $loaixe = (array)$row;
                }
                $sodo = $loaixe["Sơ_đồ"];
                $row = intval($loaixe["Số_hàng"]);
                $col  = intval($loaixe["Số_cột"]);
                $loaighe = $loaixe["Loại_ghế"];
                $trangthai = 0;
                $k = 1;
                if($loaighe==1)
                {
                    $row = ($row - 1)/2 + 1;
                }
                for ($i = 1;$i<$row;$i++){
//                    $k = 1;
                    for ($j = 0;$j<$col;$j++){
                        /*if($i*$col+$j == 0)
                            continue;*/
                        if($sodo[$i*$col+$j]==1){
                            $vitri = 'A-'.($k);
                            DB::insert("INSERT INTO `ve`(`Mã_chuyến_xe`, `Vị_trí_ghế`, `Trạng_thái`, `created_at`, `updated_at`) VALUES (?,?,?,?,?)",
                                [$id,$vitri,$trangthai,$created_at,$updated_at]);
                            $k++;
                        }
                    }
                }
                if($loaighe==1){
                    $k = 1;
                    for ($i = $row; $i < intval($loaixe["Số_hàng"]); $i++) {
//                        $k = 1;
                        for ($j = 0; $j < $col; $j++) {
                            /*if ($i * $col + $j == 0)
                                continue;*/
                            if ($sodo[$i * $col + $j] == 1) {
                                $vitri = 'B-' . ($k);
                                DB::insert("INSERT INTO `ve`(`Mã_chuyến_xe`, `Vị_trí_ghế`, `Trạng_thái`, `created_at`, `updated_at`) VALUES (?,?,?,?,?)",
                                    [$id, $vitri, $trangthai, $created_at, $updated_at]);
                                $k++;
                            }
                        }
                    }
                }
                return redirect()->back()->with('alert','Thêm thành công!');
            }  catch (\Exception $e) {
                /*return \response()->json(['result'=>'0']);*/
                /*return redirect()->back()->with('alert','Thêm thất bại!');*/
                die($e);
            }
        }
    }
    public function delchuyenxe($id = "") {
        $updated_at = date('Y-m-d h-i-s');
        if($id == "")
            return redirect()->back();
        else {
            try {
                DB::update("UPDATE `chuyen_xe` SET `is_del`= ?,`updated_at`= ? WHERE `Mã`= ?",
                    [1,$updated_at,$id]);
            } catch (\Exception $e) {
                return redirect()->back();
            }
            return redirect()->back();
        }
    }

    //Phần vé
    public function ve () {
        $ticket = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')
            ->join('xe','xe.Mã','=','chuyen_xe.Mã_xe')
            ->join('bus_model','bus_model.Mã','=','xe.Mã_loại_xe')
            ->select('ve.Mã','ve.Mã_chuyến_xe','ve.Mã_nhân_viên_đặt','ve.Mã_khách_hàng','ve.Mã_đặt_vé','ve.Vị_trí_ghế','ve.Trạng_thái','chuyen_xe.Tiền_vé','bus_model.Loại_ghế')
            ->get();
        return view('quantrivien.ve',['ticket'=>$ticket]);
    }
    public function ticket($index,$id = '') {
        if ($index==1&&$id==''){
            $chuyenxe = DB::table('chuyen_xe')->join('employee','chuyen_xe.Mã_nhân_viên_tạo','=','employee.Mã')
                ->join('lo_trinh','chuyen_xe.Mã_lộ_trình','=','lo_trinh.Mã')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')
                ->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')
                ->join('employee as employee1','chuyen_xe.Mã_tài_xế','=','employee1.Mã')
                ->where('chuyen_xe.is_del','=','0')
                ->select('chuyen_xe.Mã','employee.Họ_Tên as Nhân_viên_tạo','employee1.Họ_Tên as Tài_xế','lo_trinh.Nơi_đi','lo_trinh.Nơi_đến','xe.Biển_số','chuyen_xe.Tiền_vé','bus_model.Loại_ghế','chuyen_xe.Ngày_xuất_phát','chuyen_xe.Giờ_xuất_phát','chuyen_xe.Trạng_thái')
                ->orderBy('chuyen_xe.Mã','desc')
				->get();
            return \response()->json(['msg'=>$chuyenxe]);
        }
        elseif ($index==2&&$id==''){
            $ticket = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')
                ->join('xe','xe.Mã','=','chuyen_xe.Mã_xe')
                ->join('bus_model','bus_model.Mã','=','xe.Mã_loại_xe')
                ->select('ve.Mã','ve.Mã_chuyến_xe','ve.Mã_nhân_viên_đặt','ve.Mã_khách_hàng','ve.Mã_đặt_vé','ve.Vị_trí_ghế','ve.Trạng_thái','chuyen_xe.Tiền_vé','bus_model.Loại_ghế')
                ->get();
            return \response()->json(['msg'=>$ticket]);
        }
        elseif ($index==2&&$id!=''){
            $ticket = DB::table('ve')->join('chuyen_xe','ve.Mã_chuyến_xe','=','chuyen_xe.Mã')
                ->join('xe','xe.Mã','=','chuyen_xe.Mã_xe')
                ->join('bus_model','bus_model.Mã','=','xe.Mã_loại_xe')
                ->where('ve.Mã_chuyến_xe','=',$id)
                ->select('ve.Mã','ve.Mã_chuyến_xe','ve.Mã_nhân_viên_đặt','ve.Mã_khách_hàng','ve.Mã_đặt_vé','ve.Vị_trí_ghế','ve.Trạng_thái','chuyen_xe.Tiền_vé','bus_model.Loại_ghế')
                ->get();
            return \response()->json(['msg'=>$ticket]);
        }
    }
    public function editticket(Request $request) {
        $id = $request->ID;
        $trangthai = $request->trangthai;
        $updated_at = date('Y-m-d h-i-s');
        try {
            DB::update("UPDATE `ve` SET `Trạng_thái`= ?,`updated_at`= ? WHERE `Mã`= ?",
                [$trangthai,$updated_at,$id]);
        } catch (\Exception $e) {
            return \response()->json(['result'=>0]);
        }
        return \response()->json(['result'=>1]);
    }

	//Phần tin tức
	public function tintuc()
	{
		try {
			$tintuc = DB::table('news')->join('employee as em1','news.id_admin_created','=','em1.Mã')->join('employee as em2','news.id_admin_changed','=','em2.Mã')->select('news.news_id','news.title','news.image','news.introduce','news.content','news.check_slide','news.is_disabled','em1.Họ_Tên as admin_created','em2.Họ_Tên as admin_changed')->orderBy('news.news_id','desc')->get();
			return view('quantrivien.tintuc',compact('tintuc'));
		} catch (\Exception $e) {
			return $e;
		}
	}
	public function addtintuc(Request $request){
		$idadmin = session("admin.id");
        $tieude = $request->tieude;
        $mota = $request->mota;
        $noidung=$request->noidung;
        $checkslide = $request->isslide;
		$kichhoat = $request->kichhoat;
        $time = date("Y-m-d H:i:s");
		$hinhanh = $request->hinhanh;
		$namehinhanh = strtotime($time)."-".$hinhanh->getClientOriginalName();
		$hinhanh->move('upload',$namehinhanh);
        try {
			DB::table("news")->insert(["title" => $tieude, "image" => $namehinhanh, "introduce" => $mota, "content" => $noidung, "check_slide" => $checkslide, "is_disabled" => $kichhoat, "id_admin_created" => $idadmin, "id_admin_changed" => $idadmin, "created_at" => $time, "updated_at" => $time]);
			return redirect("/admin/tintuc")->with(['alert' => "Thêm thành công!"]);
		} catch (\Exception $e) {
			return $e;
		}
    }
	public function edittintuc(Request $request)
	{
		$idadmin = session("admin.id");
		$idnews = $request->newsid;
		$tieude = $request->tieude;
        $mota = $request->mota;
        $noidung=$request->noidung;
		$checkslide = $request->isslide;
		$kichhoat = $request->kichhoat;
        $time = date("Y-m-d H:i:s");
		if($request->hinhanh != null)
		{
			$hinhanh = $request->hinhanh;
			$namehinhanh = strtotime($time)."-".$hinhanh->getClientOriginalName();
			$hinhanh->move('upload',$namehinhanh);
		}
        try {
			if($request->hinhanh != null)
			{
				DB::update("UPDATE `news` SET `title` = ?, `image` = ?, `introduce` = ?, `content` = ?, `check_slide` = ?, `is_disabled` = ?, `id_admin_changed` = ?, `updated_at` = ? WHERE `news_id` = ?",[$tieude,$namehinhanh,$mota,$noidung,$checkslide,$kichhoat,$idadmin,$time,$idnews]);
			}
			else
			{
				DB::update("UPDATE `news` SET `title` = ?, `introduce` = ?, `content` = ?, `check_slide` = ?, `is_disabled` = ?, `id_admin_changed` = ?, `updated_at` = ? WHERE `news_id` = ?",[$tieude,$mota,$noidung,$checkslide,$kichhoat,$idadmin,$time,$idnews]);
			}
			return redirect("/admin/tintuc")->with(['alert' => "Sửa thành công!"]);
		} catch(\Exception $e) {
			return $e;
		}
	}
	public function deltintuc($id = "")
	{
		if($id == "")
		{
			return redirect('/admin/tintuc')->with(["alert" => "Đường dẫn không tồn tại!"]);
		}
		else
		{
			try {
                DB::delete('DELETE FROM news WHERE news_id = ?',[$id]);
				return redirect('/admin/tintuc')->with(["alert" => "Xóa thành công!"]);
            } catch (\Exception $e) {
                return $e;
            }
		}
	}

	//Phần giới thiệu
	public function editgioithieu(Request $request)
	{
		$idadmin = session("admin.id");
		$noidung = $request->noidunggt;
		$time = date("Y-m-d H:i:s");
		$gt = DB::table("gioi_thieu")->select("gt_id")->get();
		if(empty($gt))
		{
			try {
				DB::table("gioi_thieu")->insert(["noidung" => $noidung, "id_admin_created" => $idadmin, "id_admin_changed" => $idadmin, "created_at" => $time, "updated_at" => $time]);
				return redirect()->back()->with(['alert' => 'Sửa thông tin giới thiệu thành công!']);
			} catch (\Exception $e) {
				return $e;
			}
		}
		else
		{
			$id = $gt[0]->gt_id;
			try {
				DB::update("UPDATE `gioi_thieu` SET `noidung` = ?, `id_admin_changed` = ?, `updated_at` = ? WHERE gt_id = ?",[$noidung,$idadmin,$time,$id]);
				return redirect()->back()->with(['alert' => 'Sửa thông tin giới thiệu thành công!']);
			} catch (\Exception $e) {
				return $e;
			}
		}
	}

	//Phần gửi thông báo liên hệ
	public function admin_sendmessage()
	{ //Test OK

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() {
            while (true) {
				$lienhe = DB::table('lienhe')->leftJoin('admin_lienhe','lienhe.lh_id','=','admin_lienhe.lh_id')->select('lienhe.*','admin_lienhe.is_checked as is_new')->get();
				$solienhemoi = count(DB::table('lienhe')->select('lh_id')->orderBy('lh_id','desc')->get()) - count(DB::table('admin_lienhe')->where('admin_id','=',session('admin.id'))->select('*')->get());
                echo 'data: ' . json_encode(['lienhe' => $lienhe,'sllhnew' => $solienhemoi]) . "\n\n";
                ob_flush();
                flush();
                sleep(60);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;
    }
	public function admin_showmessage(Request $request)
	{
		$id = $request->data;
		if(count(DB::table("admin_lienhe")->where([['lh_id','=',$id],['admin_id','=',session("admin.id")]])->select("*")->get()) == 0)
		{
			try {
				$data = DB::table("lienhe")->leftJoin("admin_lienhe","lienhe.lh_id","=","admin_lienhe.lh_id")->where("lienhe.lh_id","=",$id)->select("lienhe.*","admin_lienhe.is_checked as is_new")->get();
				DB::insert("INSERT INTO `admin_lienhe`(`lh_id`, `admin_id`, `is_checked`) VALUES (?,?,'1')",[$id,session("admin.id")]);
				return response()->json(['kq' => 1,'data' => $data]);
			} catch (\Exception $e) {
				return $e;
			}
		}
		else
		{
			try {
				$data = DB::table("lienhe")->leftJoin("admin_lienhe","lienhe.lh_id","=","admin_lienhe.lh_id")->where("lienhe.lh_id","=",$id)->select("lienhe.*","admin_lienhe.is_checked as is_new")->get();
				return response()->json(['kq' => 1,'data' => $data]);
			} catch (\Exception $e) {
				return $e;
			}
		}
	}

	//Phần hàm dùng chung
	public function admin_checkexist(Request $request)
	{
		$typecheck = $request->typecheck;
		$datacheck = $request->datacheck;
		switch($typecheck)
		{
			case "useremail_create":
				if(count(DB::select("SELECT * FROM customer WHERE Email = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "userphone_create":
				if(count(DB::select("SELECT * FROM customer WHERE Sđt = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "useremail_change":
				if(count(DB::select("SELECT * FROM customer WHERE Email = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM customer WHERE Email = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM customer WHERE Email = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			case "userphone_change":
				if(count(DB::select("SELECT * FROM customer WHERE Sđt = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM customer WHERE Sđt = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM customer WHERE Sđt = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			case "employeeusername_create":
				if(count(DB::select("SELECT * FROM employee WHERE Username = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "employeeemail_create":
				if(count(DB::select("SELECT * FROM employee WHERE Email = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "employeephone_create":
				if(count(DB::select("SELECT * FROM employee WHERE Sđt = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "employeeusername_change":
				if(count(DB::select("SELECT * FROM employee WHERE Username = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM employee WHERE Username = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM employee WHERE Username = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			case "employeeemail_change":
				if(count(DB::select("SELECT * FROM employee WHERE Email = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM employee WHERE Email = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM employee WHERE Email = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			case "employeephone_change":
				if(count(DB::select("SELECT * FROM employee WHERE Sđt = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM employee WHERE Sđt = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM employee WHERE Sđt = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			case "employeepassword_change":
				if(count(DB::select("SELECT * FROM employee WHERE Password = ? AND Mã = ?",[md5($datacheck),$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else
				{
					return response()->json(['kq' => 1]);
				}
				break;
			case "bienso_create":
				if(count(DB::select("SELECT * FROM xe WHERE Biển_số = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "bienso_change":
				if(count(DB::select("SELECT * FROM xe WHERE Biển_số = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM xe WHERE Biển_số = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM xe WHERE Biển_số = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			case "tentramdung_create": //Kiểm tra tên trạm dừng khi tạo mới
				if(count(DB::select("SELECT * FROM tram_dung WHERE Tên = ?",[$datacheck])) != 0)
				{
					return response()->json(['kq' => 1]);
				}
				else
				{
					return response()->json(['kq' => 0]);
				}
				break;
			case "tentramdung_change": //Kiểm tra tên trạm dừng khi thay đổi
				if(count(DB::select("SELECT * FROM tram_dung WHERE Tên = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 1)
				{
					return response()->json(['kq' => 0]);
				}
				else if(count(DB::select("SELECT * FROM tram_dung WHERE Tên = ? AND Mã = ?",[$datacheck,$request->idcheck])) == 0)
				{
					if(count(DB::select("SELECT * FROM tram_dung WHERE Tên = ?",[$datacheck])) != 0)
					{
						return response()->json(['kq' => 1]);
					}
					else
					{
						return response()->json(['kq' => 0]);
					}
				}
				break;
			default:
		}
	}
	public function admin_retrievedata(Request $request)
	{
		switch($request->typedata)
		{
			case 'khachhang':
				try {
					$customer = Khachhang::all();
					return response()->json(['kq' => 1,'data' => $customer]);
				} catch (\Exception $e) {
					return response()->json(['kq' => 0]);
				}
				break;
			case 'nhanvien':
				try {
					$employee = Nhanvien::all();
					return response()->json(['kq' => 1,'data' => $employee]);
				} catch (\Exception $e) {
					return response()->json(['kq' => 0]);
				}
				break;
			case 'xe':
				try {
					$bus = DB::table('xe')->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')
					->select('xe.Mã','xe.Biển_số','xe.Mã_loại_xe','bus_model.Loại_ghế','xe.Ngày_bảo_trì_gần_nhất','xe.Ngày_bảo_trì_tiếp_theo','xe.location')->get();
					return response()->json(['kq' => 1,'data' => $bus]);
				} catch (\Exception $e) {
					return response()->json(['kq' => 0]);
				}
				break;
			case 'tramdung':
				try {
					$busstop = DB::table('tram_dung')->join('employee','tram_dung.Mã_nhân_viên_tạo','=','employee.Mã')
					->join('employee as employee1','tram_dung.Mã_nhân_viên_chỉnh_sửa','=','employee1.Mã')
					->select('tram_dung.Mã','tram_dung.Tên','tram_dung.Tọa_độ','tram_dung.Mã_nhân_viên_tạo','tram_dung.Mã_nhân_viên_chỉnh_sửa','employee.Họ_Tên as Nhân_viên_tạo','employee1.Họ_Tên as Nhân_viên_chỉnh_sửa')->get();
					return response()->json(['kq' => 1,'data' => $busstop]);
				} catch (\Exception $e) {
					return response()->json(['kq' => 0]);
				}
				break;
			case 'tintuc':
				try {
					$tintuc = DB::table('news')->join('employee as em1','news.id_admin_created','=','em1.Mã')->join('employee as em2','news.id_admin_changed','=','em2.Mã')->select('news.news_id','news.title','news.image','news.introduce','news.content','news.check_slide','news.is_disabled','em1.Họ_Tên as admin_created','em2.Họ_Tên as admin_changed')->orderBy('news.news_id','desc')->get();
					return response()->json(['kq' => 1,'data' => $tintuc]);
				} catch(\Exception $e) {
					return response()->json(['kq' => 0]);
				}
				break;
			case 'gioithieu':
				try {
					$gioithieu = DB::table('gioi_thieu')->select('noidung')->get();
					return response()->json(['kq' => 1,'data' => $gioithieu]);
				} catch(\Exception $e) {
					return response()->json(['kq' => 0]);
				}
				break;
			default:
		}
	}
	public function admin_changepassword(Request $request) //Hàm thay đổi mật khẩu
	{
		$id = $request->id;
		$olddata = $request->olddata;
		$newdata = $request->newdata;
		$type = $request->typepassword;
		if($type == "employeepassword")
		{
			if(count(DB::select("SELECT * FROM employee WHERE Password = ? AND Mã = ?",[md5($olddata),$id])) == 1)
			{
				try {
					DB::update("UPDATE `employee` SET Password = ? WHERE Mã = ?",[md5($newdata),$id]);
					return response()->json(['kq' => 1]);
				} catch (\Exception $e) {
					return $e;
				}
			}
			else
			{
				return response()->json(['kq' => 0]);
			}
		}
	}
}
