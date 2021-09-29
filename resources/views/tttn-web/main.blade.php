<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/style-lienhe.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet prefetch" href="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('ckfinder/ckfinder.js')}}"></script>

</head>
<body>
    <!-- phần header -->
        <div class="header">
            <!-- Logo -->
                <img  class="headerlogo" src="{{asset('images/logo1.png')}}" onclick="location.href='{{url("/")}}';" height="40">
            <!-- Phần Chữ chạy  -->
                <div style="width: 70%;color: #FFF; float: left;"><div style="width: 70%; margin-left:40%; margin-top: 10px;"><marquee direction="left">CHÀO MỪNG BẠN ĐẾN VỚI WEBSITE CỦA CHÚNG TÔI, CHÚC BẠN CÓ CHUYẾN ĐI VUI VẺ !</marquee>
                </div></div>
            <?php $makh=Session::get('makh'); ?>
            @if(Session::has('makh'))
                <!-- Button đăng ký, đăng nhập -->
                    <ul>
                        <li><a  href="{{route('logout')}}" style="line-height: 40px;color: #FFF;">( Đăng xuất )</a></li>
                        <li style="color: #FFF;line-height: 40px;"><i class="fa fa-address-book-o" style="font-size:20px; margin-right: 3px;"></i>  <a href="{{asset("thongtin/{$makh}")}}" style="color: #CCC; cursor: pointer;">{{Session::get('sdt')}}</a></li>
                    </ul>
            @else
                <!-- Hiện số điện thoại, đăng xuất -->
                    <ul>
                        <li><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#register">Đăng ký</button></li>
                        <li><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#login">Đăng nhập</button></li>
                    </ul>

            @endif
        </div>
    <!-- Kết phần header -->
    <!-- Phần Menu  -->
        <div class="menu">
            <ul>
                <li><a href="{{asset('/')}}"><i class="glyphicon glyphicon-home" aria-hidden="true"></i>
                        <span>TRANG CHỦ</span>
                    </a>
                </li>
                <li>
                    <a href="{{asset('/datve')}}">
                        <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i>
                        <span>ĐẶT VÉ</span>
                    </a>
                </li>
                <li>
                    <a href="{{asset('/tintuc')}}">
                        <i class="glyphicon glyphicon-text-size" aria-hidden="true"></i>
                        <span>TIN TỨC</span>
                    </a>
                </li>
                <li>
                    <a href="{{asset('/gioithieu')}}">
                        <i class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i>
                        <span>GIỚI THIỆU</span>
                    </a>
                </li>
                <li>
                    <a href="{{asset('/lienhe')}}">
                        <i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>
                        <span>LIÊN HỆ</span>
                    </a>
                </li>
            </ul>
        </div>
        <div style="clear: both;"></div>
    <!-- Kết Phần Menu -->
@yield('content')
        <div style="clear: both;"></div>
    <!-- Phần footer -->
        <main class="main">
            <div class="footer">
                <ul>
                    <li>
                        <h3>Liên Hệ</h3>
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <i>Đại Học Sư phạm Thành phố Hồ Chí Minh</i><br>
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        <i>0989671651</i><br>
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                        <i>nhttin1509@gmail.com</i><br>
                        <i><a href="https://www.facebook.com/SPT.Transport280/" target="_blank"><span><i class="fa fa-facebook">SPT Transport</i></span></a></i>
                    </li>
                    <li>

                        <h4>&#9679; Thiết Kế Bởi: </h4>
                        <strong>Nguyễn Hồ Trọng Tín - 44.01.103.032</strong><br>
                        <strong>Hà Tử Hào - 44.01.103.018</strong><br>
                        <strong>Đỗ Văn Khoaa - 44.01.103.021</strong><br>
                        <strong>Nguyễn Kiều Trung Tính - 44.01.103.03</strong><br>
                    </li>
                </ul>
            </div>
        </main>
    <!-- Kết phần footer -->
@yield('excontent')
    <script type="text/javascript">
        $(document).ready(function(){
            /* Xử lý đăng ký */
                    $(".dangky").click(function(){
                        var sdt=$(".dienthoai").val();
                        var bieuthuc = /^(0[3578]|09)[0-9]{8}$/;
                        var bieuthuc2 = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
                        var truyen = true;
                        var gioitinh = document.getElementsByName("txtgioitinh2");
                         for (var i = 0; i < gioitinh.length; i++){
                            if (gioitinh[i].checked == true){
                               gt = gioitinh[i].value;
                            }
                        }
                        var ngaysinh = $(".txtngaysinh").val();
                        /*Kiểm tra điện thoại*/
                            if(sdt!=""){
                               if(sdt.search(bieuthuc)==-1){
                                 $(".loi").html("<div class='alert alert-danger'><strong>Số điện thoại bạn nhập không hợp lệ !</strong></div>");
                                 truyen = false;
                               }
                               else{
                                $(".loi").html("");
                               }
                            }
                            else{
                                $(".loi").html("<div class='alert alert-danger'><strong>Số điện thoại không được để trống !</strong></div>");
                                truyen = false;
                            }
                        /*Kiểm tra tên*/
                            var name = $(".txtname").val();
                            if(name.search(bieuthuc2)==-1){
                                 $(".loi6").html("<div class='alert alert-danger'><strong>Tên bạn nhập không hợp lệ !</strong></div>");
                                 truyen = false;
                             }
                              else{
                                $(".loi6").html("");
                               }
                        /*Kiểm tra mật khẩu */
                            var mk = $(".matkhau").val();
                            var rmk = $(".rematkhau").val();
                            var xemmk =true;
                            if(mk==""){
                                $(".loi2").html("<div class='alert alert-danger'><strong>Mật khẩu không được để trống !</strong></div>");
                                truyen = false;
                                xemk = false;
                            }
                            else if(mk.length> 30 || mk.length <6){
                                $(".loi2").html("<div class='alert alert-danger'><strong>Độ dài mật khẩu ít nhất 6 ký tự và  không quá 30 ký tự !</strong></div>");
                                truyen = false;
                                xemmk = false;
                            }
                            else{
                                $(".loi2").html("");
                            }
                        /*Kiểm tra repassword*/
                            if(rmk==""){
                                $(".loi3").html("<div class='alert alert-danger'><strong>Nhập lại mật khẩu không được để trống !</strong></div>");
                                truyen = false;
                                 xemmk = false;
                            }
                            else if(rmk.length > 30 || rmk.length < 6){
                                $(".loi3").html("<div class='alert alert-danger'><strong>Nhập lại mật khẩu có độ dài không đúng!</strong></div>");
                                truyen = false;
                                 xemmk = false;
                            }
                            else{
                                $(".loi3").html("");
                            }
                        /*Kiểm tra nhập lại mật khẩu đúng không*/
                            if(xemmk==true && mk!=rmk){
                                     $(".loi4").html("<div class='alert alert-danger'><strong>Nhập lại mật khẩu không giống mật khẩu!</strong></div>");
                                        truyen = false;
                            }
                            else{
                                $(".loi4").html("");
                            }
                        /*Kiểm tra xem đúng chưa để mở ajax truyền dữ liệu đi*/
                            if(truyen == true){
                                 $.ajax({
                                    url: '{{route("dangky")}}',
                                    type: 'POST',
                                    data: {
                                    _token: '{{csrf_token()}}',
                                    SDT: sdt,
                                    MK: mk,
                                    NGAYSINH: ngaysinh,
                                    GT:gt,
                                    NAME: name
                                    },
                                success: function (data) {
                                   if(data.kq==0){
                                    $(".loi5").html("<div class='alert alert-danger'><strong>Xin lổi - Số điện thoại này đã đăng ký tài khoản trước đó !</strong></div>");
                                   }
                                   else if(data.kq==1){
                                    $(".dangkytc").html("<div class='alert alert-success'><strong>Bạn đã đăng ký thành công, bấm <a href='javascript:void(0);' data-toggle='modal' data-target='#login' data-dismiss='modal' class='btn btn-link'>Đăng nhập</a> để tiếp tục</trong></div>");
                                   }

                                 }
                                });
                            }
                        });
            /* Kết thúc xử lý đăng ký*/
            /* Xử lý đóng đăng ký*/
                $(".dongdangky").click(function(){
                    $(".loi").html("");
                    $(".loi2").html("");
                    $(".loi3").html("");
                    $(".loi4").html("");
                    $(".loi5").html("");
                    $(".dangkytc").html("");
                });
            /* Kết thưc xử lý đóng đăng ký*/
            /* Xử lý đăng nhập*/
                $(".dangnhap").click(function(){
                    var dndt = $(".dndienthoai").val();
                    var dnmk = $(".dnmatkhau").val();
                    var bieuthuc = /^(0[3578]|09)[0-9]{8}$/;
                    var dntruyen = true;
                    /* Kiểm tra điện thoại*/
                        if(dndt!=""){
                            if(dndt.search(bieuthuc)==-1){
                                 $(".dnloi").html("<div class='alert alert-danger'><strong>Số điện thoại bạn nhập không hợp lệ !</trong></div>");
                                 dntruyen = false;
                                }
                            else{
                                $(".dnloi").html("");
                            }
                        }
                        else{
                            $(".dnloi").html("<div class='alert alert-danger'><strong>Số điện thoại không được để trống !</strong></div>");
                            dntruyen = false;
                        }
                    /* Kiểm tra mật khẩu*/
                        if(dnmk==""){
                            $(".dnloi2").html("<div class='alert alert-danger'><strong>Mật khẩu không được để trống !</strong></div>");
                            dntruyen = false;
                        }
                        else if(dnmk.length> 30 || dnmk.length <6){
                            $(".dnloi2").html("<div class='alert alert-danger'><strong>Độ dài mật khẩu ít nhất 6 ký tự và  không quá 30 ký tự !</strong></div>");
                            dntruyen = false;
                        }
                        else{
                            $(".dnloi2").html("");
                        }
                    /* Không lổi thì gửi dữ liệu đi bằng ajax */
                        if(dntruyen==true){
                              $.ajax({
                                url: '{{route("dangnhap")}}',
                                type: 'POST',
                                data: {
                                _token: '{{csrf_token()}}',
                                DNDT: dndt,
                                DNMK: dnmk
                                },
                            success: function (data) {
                               if(data.kq == 0){
                                $(".dnloi3").html("<div class='alert alert-danger'><strong>Số điện thoại hoặc mật khẩu không đúng !</strong></div");
                               }
                               else{
                                var sdt = data.sdt;
                                var ma = data.ma;
                                $("#login").modal("hide");
                                $(".header").html("<img src='{{asset("images/logo.png")}}' height='40'><ul><li><a  href='{{route("logout")}}' style='line-height: 40px;color: #FFF;'>( Đăng xuất )</a></li><li style='color: #FFF;line-height: 40px;'><i class='fa fa-address-book-o' style='font-size:20px; margin-right: 3px;'></i>  <a href='thongtin/"+ma+"' style='color: #CCC; cursor: pointer;'>"+sdt+"</a></li></ul>");
                                    location.reload();
                               }

                             }
                            });
                        }
                });
            /* Kết thức xử lý đăng nhập*/
             $(".dongdangnhap").click(function(){
                $(".dnloi").html("");
                $(".dnloi2").html("");
                $("dnloi3").html("");
            });
        });
    </script>
@extends('tttn-web.login')
@extends('tttn-web.register')
@yield('script')
</body>

