<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketSuggestion extends Controller
{
    //

    /**
     * @param $matrix
     */
    public static function ticketSuggestion($matrix,$machuyenxe,$makhachhang){
        $tbarr = [];
        $normmatrix = [];
        $similarmatrix = [];
        $sodem = 0;
        $sodem1 = 0;
        $keyarr = [];
        foreach ($matrix as $row){
            $sohang = count($row);
            $num = $sohang;
            $s = 0;
            for ($i=0;$i<$sohang;$i++){
                if (!is_numeric($row[$i])){
                    $num-=1;
                    $s+=0;
                }
                else{
                    $s+=intval($row[$i]);
                }
            }
            $tbarr[$sodem]=round($s/$num,2);
            $sodem+=1;
        }
        /* print_r($tbarr); */
        $tmp = $matrix;
        foreach ($tmp as $key => $value)
        {
            $sohang = count($value);
            for ($i=0;$i<$sohang;$i++)
            {
                if(is_numeric($value[$i]))
                {
                    $tmp[$key][$i]= intval($value[$i]) - $tbarr[$sodem1];
                }
            }
            $sodem1+=1;
        }
        foreach ($tmp as $key => $value)
        {
            $sohang = count($value);
            $hople = 0;
            for ($i=0;$i<$sohang;$i++)
            {
                if(is_numeric($value[$i])&&$value[$i] != 0)
                {
                    $hople++;
                }
                if($hople >= 2)
                {
                    $normmatrix[$key] = $value;
                    break;
                }
            }
        }
        $sodem = 0;
        foreach ($normmatrix as $key => $value){
            $keyarr[$sodem] = $key;
            $sodem++;
        }
        foreach ($normmatrix as $key => $value){
            $similarmatrix[$key] = [];
        }
//        return view('ticket', ['normmatrix' => $normmatrix,'matrix' => $matrix]);
//		die(var_dump($normmatrix));
        foreach ($similarmatrix as $key => $value){
            $num = 0;
            foreach ($normmatrix as $row){
                $sohang = count($row);
                $s = 0;
                $s1 = 0;
                $s2 = 0;
                for ($i=0;$i<$sohang;$i++){
                    if(is_numeric($normmatrix[$key][$i])&&is_numeric($row[$i])){
                        $s += $normmatrix[$key][$i] * $row[$i];
                    }
                    if (is_numeric($normmatrix[$key][$i])){
                        $s1 += pow($normmatrix[$key][$i],2);
//                        echo $s1.'<br>';
                    }
                    if (is_numeric($row[$i])){
                        $s2 += pow($row[$i],2);
//                        echo $s2.'<br>';
                    }
                }
                $similarmatrix[$key][$num] = round($s/(sqrt($s1)*sqrt($s2)),2);
//                echo $s/(sqrt($s1)*sqrt($s2)).'<br>';
                $num++;
            }
        }
//		return view('ticket', ['similarmatrix' => $similarmatrix,'matrix' => $matrix]);
//        die(var_dump($similarmatrix));
        $normmatrixFull = $normmatrix;
        foreach ($normmatrix as $key => $value){
            $sohang = count($value);
            for ($i=0;$i<$sohang;$i++){
                if(!is_numeric($value[$i])){
                    $max1 = -1;
                    $max2 = -1;
                    $s1 = 0;
                    $s2 = 0;
                    for ($j=0;$j<count($keyarr);$j++){
                        if($keyarr[$j]==$key)
                            continue;
                        if($similarmatrix[$key][$j]>$max1&&is_numeric($normmatrix[$keyarr[$j]][$i])){
                            $max1 = floatval($similarmatrix[$key][$j]);
                            $s1 = $normmatrix[$keyarr[$j]][$i]*$max1;
                        }
                        if($max1>$max2){
                            $max2 = $max1+$max2;
                            $max1= $max2-$max1;
                            $max2= $max2-$max1;
                            $s2= $s1+$s2;
                            $s1= $s2-$s1;
                            $s2= $s2-$s1;
                        }
                    }
                    $normmatrixFull[$key][$i] = round(($s1+$s2)/(abs($max1)+abs($max2)),2);
                }
            }
        }
        $matrixFinal = $normmatrixFull;
        $sodem2 = 0;
        foreach ($matrixFinal as $key => $value){
            for ($i=0;$i<count($value);$i++){
                $matrixFinal[$key][$i] = round($matrixFinal[$key][$i] + $tbarr[$sodem2],2);
            }
            $sodem2++;
        }
		if(empty($matrixFinal))
		{
			return response()->json(['kq' => 0]);
		}
//        return view('ticket', ['similarmatrix' => $similarmatrix,'matrix' => $matrix,'normmatrix' => $normmatrix,'normmatrixFull' => $normmatrixFull,'matrixFinal' => $matrixFinal]);
        $vedadat = DB::table('ve')->where('Mã_chuyến_xe','=',$machuyenxe)->select("Mã","Vị_trí_ghế","Trạng_thái")->get();
        $sove = count($vedadat);
        $bangdenghi = [];
        if($makhachhang != null&&isset($matrixFinal["{$makhachhang}"]))
        {
            $bangdenghi = $matrixFinal["{$makhachhang}"];
        }
        else
        {
            for ($i=0;$i<$sove;$i++)
            {
                $bangdenghi[$i] = 0;
            }
            foreach ($matrixFinal as $row)
            {
                for ($i=0;$i<$sove;$i++)
                {
                    if($row[$i] > $bangdenghi[$i])
                    {
                        $bangdenghi[$i] = $row[$i];
                    }
                }
            }
        }
        for ($i=0;$i<$sove;$i++)
        {
            $vedadat[$i]->tanso = $bangdenghi[$i];
        }
        for ($i=0;$i<$sove-1;$i++)
        {
            for($j=$i+1;$j<$sove;$j++)
            {
                if($vedadat[$i]->tanso < $vedadat[$j]->tanso)
                {
//                    self::hoandoi($bangdenghi[$i],$bangdenghi[$j]);
                    self::hoandoi($vedadat[$i]->Mã,$vedadat[$j]->Mã);
                    self::hoandoi($vedadat[$i]->Trạng_thái,$vedadat[$j]->Trạng_thái);
                    self::hoandoi($vedadat[$i]->Vị_trí_ghế,$vedadat[$j]->Vị_trí_ghế);
                    self::hoandoi($vedadat[$i]->tanso,$vedadat[$j]->tanso);
                }
            }
        }
        $kq = [];
        $demve = 0;
        foreach ($vedadat as $row)
        {
            if($row->Trạng_thái == 0)
            {
                $kq[$demve] = $row;
                $demve++;
            }
        }
        return response()->json(['kq' => $kq]);
//        echo var_dump($tbarr).'<br>';
//        echo var_dump($normmatrix).'<br>';
//        echo var_dump($similarmatrix).'<br>';
//        echo var_dump($normmatrixFull).'<br>';
//        echo var_dump($matrixFinal).'<br>';
    }
    public static function makeMatrix(Request $request){
        $matrix = [];
//        $machuyenxe = 10;
//        $makhachhang = 28;
//		$makhachhang = 10;
//		$machuyenxe = 23;
        $machuyenxe = $request->idchuyenxe;
        $makhachhang = isset($request->idkhachhang)? $request->idkhachhang:null;
		$namcuoi = isset($request->tuoimin)? date('Y-m-d',strtotime('12/31/'.(intval(date('Y'))-intval($request->tuoimin)))):null;
		$namdau = isset($request->tuoimax)? date('Y-m-d',strtotime('01/01/'.(intval(date('Y'))-intval($request->tuoimax)))):null;
		$gioitinh = isset($request->gioitinh)? $request->gioitinh:null;
		if($makhachhang!=null)
		{
			$ttkh = DB::table('customer')->where('Mã','=',$makhachhang)->select('Giới tính','Ngày_sinh')->get();
			$gioitinh = $ttkh[0]->{'Giới tính'};
			$tuoi = intval(date('Y')) - intval(date('Y',strtotime($ttkh[0]->{'Ngày_sinh'})));
			if($tuoi >= 14&&$tuoi <= 36)
			{
				$namdau = date('Y-m-d',strtotime('01/01/1982'));
				$namcuoi = date('Y-m-d',strtotime('12/31/2004'));
			}
			elseif($tuoi > 36)
			{
				$namdau = date('Y-m-d',strtotime('01/01/1970'));
				$namcuoi = date('Y-m-d',strtotime('12/31/1981'));
			}
			elseif($tuoi < 14)
			{
				$namdau = date('Y-m-d',strtotime('01/01/2005'));
				$namcuoi = date('Y-m-d');
			}
			// return response()->json($tuoi);
		}
		// return response()->json($namcuoi);
        $ttxe = DB::table('chuyen_xe')->join('xe','chuyen_xe.Mã_xe','=','xe.Mã')
            ->join('bus_model','xe.Mã_loại_xe','=','bus_model.Mã')
            ->where('chuyen_xe.Mã','=',$machuyenxe)
            ->select('chuyen_xe.Mã_lộ_trình','xe.Mã_loại_xe','bus_model.Loại_ghế')->get();
        $malotrinh = $ttxe[0]->Mã_lộ_trình;
        $maloaixe = $ttxe[0]->Mã_loại_xe;
        $mtxve = DB::table('ve')->where('Mã_chuyến_xe','=',$machuyenxe)->select('Vị_trí_ghế')->get();
        $mtxkhach = DB::select(DB::raw("SELECT Mã_khách_hàng,COUNT(*) FROM ve,chuyen_xe,xe,customer WHERE ve.Mã_chuyến_xe = chuyen_xe.Mã AND chuyen_xe.Mã_lộ_trình = {$malotrinh} AND chuyen_xe.Mã_xe = xe.Mã AND xe.Mã_loại_xe = {$maloaixe} AND ve.Mã_khách_hàng IS NOT NULL AND ve.Mã_khách_hàng = customer.Mã AND customer.`Giới tính` = '{$gioitinh}' AND ve.Trạng_thái = 1 AND customer.Ngày_sinh BETWEEN '{$namdau}' AND '{$namcuoi}' GROUP BY Mã_khách_hàng ORDER BY COUNT(*) DESC"));
        // return response()->json(var_dump($mtxkhach));
		$customermax = 30;
		if($makhachhang!=null)
		{
			$vekh0 = DB::select(DB::raw("SELECT Vị_trí_ghế,COUNT(*) FROM ve,chuyen_xe,xe WHERE ve.Mã_chuyến_xe = chuyen_xe.Mã AND chuyen_xe.Mã_lộ_trình = {$malotrinh} AND chuyen_xe.Mã_xe = xe.Mã AND xe.Mã_loại_xe = {$maloaixe}  AND ve.Mã_khách_hàng = {$makhachhang} GROUP BY Vị_trí_ghế"));
			$dem = 0;
			$dem1 = 0;
			$tmp = [];
			/* print_r($mtxkhach); */
			foreach ($mtxve as $ve)
			{
				$lengthve = count($vekh0);
				if($lengthve == 0)
				{
					break;
				}
				$tmp[$dem1] = '?';
				if($dem == $lengthve)
				{
					$dem1++;
					continue;
				}
				if($ve->Vị_trí_ghế == $vekh0[$dem]->Vị_trí_ghế)
				{
					$tmp[$dem1] = $vekh0[$dem]->{'COUNT(*)'};
					$dem++;
				}
				$dem1++;
			}
			if(count($vekh0) != 0)
			{
				$matrix["{$makhachhang}"] = $tmp;
				$customermax = 29;
			}
		}
        foreach ($mtxkhach as $row)
        {
            $makh = $row->Mã_khách_hàng;
            if($makh == $makhachhang)
            {
                continue;
            }
            if($customermax <= 0 || $row->{'COUNT(*)'} == 0)
            {
                break;
            }
            $mtxkh = DB::select(DB::raw("SELECT Vị_trí_ghế,COUNT(*) FROM ve,chuyen_xe,xe WHERE ve.Mã_chuyến_xe = chuyen_xe.Mã AND chuyen_xe.Mã_lộ_trình = {$malotrinh} AND chuyen_xe.Mã_xe = xe.Mã AND xe.Mã_loại_xe = {$maloaixe}  AND ve.Mã_khách_hàng = {$makh} GROUP BY Vị_trí_ghế"));
            $dem = 0;
            $dem1 = 0;
            $tmp = [];
            foreach ($mtxve as $ve)
            {
                $tmp[$dem1] = '?';
                $lengthve = count($mtxkh);
                if($dem == $lengthve)
                {
                    $dem1++;
                    continue;
                }
                if($ve->Vị_trí_ghế == $mtxkh[$dem]->Vị_trí_ghế)
                {
                    $tmp[$dem1] = $mtxkh[$dem]->{'COUNT(*)'};
                    $dem++;
                }
                $dem1++;
            }
            $matrix["{$makh}"] = $tmp;
            $customermax--;
        }
//        $matrix = DB::select(DB::raw("SELECT Vị_trí_ghế,COUNT(*) FROM ve,chuyen_xe,xe WHERE ve.Mã_chuyến_xe = chuyen_xe.Mã AND chuyen_xe.Mã_lộ_trình = {$malotrinh} AND chuyen_xe.Mã_xe = xe.Mã AND xe.Mã_loại_xe = {$maloaixe}  AND ve.Mã_khách_hàng = {$makhachhang} GROUP BY Vị_trí_ghế"));
//        print_r($matrix);
        return self::ticketSuggestion($matrix,$machuyenxe,$makhachhang);
    }
    public static function hoandoi(&$a,&$b)
    {
        $tmp = $a;
        $a = $b;
        $b = $tmp;
    }
}
