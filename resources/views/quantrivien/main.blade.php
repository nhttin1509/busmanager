<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.theme.min.css')}}" rel="stylesheet">
	<link href="{{asset('plugins/paramquery-3.3.4/pqgrid.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/pqgrid.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/pqgrid.ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/themes/custom/pqgrid.css')}}" rel="stylesheet">
    {{--<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">--}}
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/Chart.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
    <script src="{{asset('plugins/paramquery-3.3.4/pqgrid.min.js')}}"></script>
    <script src="{{asset('plugins/paramquery-3.3.4/jsZip-2.5.0/jszip.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('ckfinder/ckfinder.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style-qtv.css')}}">
</head>
<body>
@if(session('alert'))
	<script>
		$(document).ready(function(){
			$('#alertmessage .modal-body').html('{{session('alert')}}');
			$('#alertmessage').modal('show');
		});
	</script>
@endif
<div class="container-fluid">
    <div class="header">
        <div class="row">
            <h3 class="col-lg-4"><a href="{{asset('admin/')}}">AWE Admin</a></h3>
            <h5 class="col-lg-4"><a href="{{url('/')}}" title="Chuyển về trang khách hàng"><img src="{{asset('/images/icons/luggage.png')}}" height="30" alt="icon">AwesomeTravel</a></h5>
            <div class="col-lg-4 userzone">
				<span onclick="showMessage(this)" class="glyphicon glyphicon-bell">
					<ul></ul>
				</span>
                <span onclick="showMenu(this)"><img src="{{asset('images/icons/user.png')}}" alt="icon">{{session('admin.name','AdminTest')}}&nbsp;<i class="glyphicon glyphicon-menu-down" ></i>
                    <ul>
                        <li onclick="showUserInfo({{session('admin.id')}})"><i class="glyphicon glyphicon-info-sign"></i>Thông tin</li>
						<li onclick="showWebInfo()"><i class="glyphicon glyphicon-globe"></i>WebInfo</li>
                        <a href="{{route('adminlogout')}}">
                            <li><i class="glyphicon glyphicon-off"></i>Thoát</li>
                        </a>
                    </ul>
                </span>
                <!--span>Thoát</span-->
            </div>
        </div>
        {{--<hr />--}}
    </div>
    <div class="noidung row">
        <div class="sidebar">
            <div class="menu">
                <ul>
                    <a href="{{url('/admin/thongke')}}">
                        <li class="option selected"><img src="{{asset('images/icons/report.png')}}" alt="icon">&nbsp;&nbsp;Thống kê</li>
                    </a>
                    <a href="{{url('/admin/khachhang')}}">
                        <li class="option"><img src="{{asset('images/icons/customer.png')}}" alt="icon">&nbsp;&nbsp;Khách hàng</li>
                    </a>
                    <a href="{{url('/admin/chuyenxe')}}">
                        <li class="option"><img src="{{asset('images/icons/chuyenxe.png')}}" alt="icon">&nbsp;&nbsp;Chuyến xe</li>
                    </a>
					<a href="{{url('/admin/ve')}}">
                        <li class="option"><img src="{{asset('images/icons/bus-ticket.png')}}" alt="icon">&nbsp;&nbsp;Vé xe</li>
                    </a>
                    <a href="{{url('/admin/loaixe')}}">
                        <li class="option"><img src="{{asset('images/icons/bus-type.png')}}" alt="icon">&nbsp;&nbsp;Loại xe</li>
                    </a>
                    <a href="{{url('/admin/lotrinh')}}">
                        <li class="option"><img src="{{asset('images/icons/route.png')}}" alt="icon">&nbsp;&nbsp;Lộ trình</li>
                    </a>
                    <a href="{{url('/admin/nhanvien')}}">
                        <li class="option"><img src="{{asset('images/icons/partnership.png')}}" alt="icon">&nbsp;&nbsp;Nhân viên</li>
                    </a>
                    <a href="{{url('/admin/xe')}}">
                        <li class="option"><img src="{{asset('images/icons/bus.png')}}" alt="icon">&nbsp;&nbsp;Xe</li>
                    </a>
                    <a href="{{url('/admin/tramdung')}}">
                        <li class="option"><img src="{{asset('images/icons/parking.png')}}" alt="icon">&nbsp;Trạm dừng</li>
                    </a>
					<a href="{{url('/admin/tintuc')}}">
                        <li class="option"><img src="{{asset('images/icons/newspaper.png')}}" alt="icon">&nbsp;Tin tức</li>
                    </a>
                </ul>
            </div>
        </div>
        <div class="">
            <!-- <span>
                <ul>
                    <li class="option selected" onclick="change(this)">Bản đồ</li>
                    <li class="option" onclick="change(this)">Nhập vé</li>
                </ul>
            </span> -->
           {{-- <span><a href="{{url('/')}}">Trang chủ</a></span>--}}
            @yield('content')
        </div>
    </div>
</div>
@yield('excontent')
<div class="modal fade" id="userinfo">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<div class="modal-title">Thông tin Admin</div>
			</div>
			<div class="modal-body">
				<div class="loading"></div>
				<form name="frm-userinfo" class="row">
					<div class="form-group col-lg-6">
						<label>Tên:</label>
						<input type="text" name="name" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Tên đăng nhập:</label>
						<input type="text" name="username" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Giới tính:</label>
						<input type="text" name="gender" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Mật khẩu:</label>
						<input type="password" name="password" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Ngày sinh:</label>
						<input type="date" name="brtday" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Ngày bắt đầu làm việc:</label>
						<input type="date" name="starttime" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Email:</label>
						<input type="email" name="email" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Số điện thoại:</label>
						<input type="text" name="phone" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Chi nhánh:</label>
						<input type="text" name="chinhanh" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
					<div class="form-group col-lg-6">
						<label>Địa chỉ:</label>
						<input type="text" name="address" class="form-control" placeholder="Chưa có thông tin" readonly>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<a href="{{asset('admin/addnhanvien/').'/'.session('admin.id')}}" class="btn btn-success"><i class="glyphicon glyphicon-edit"></i>&nbsp;Thay Đổi Thông Tin</a>
			</div>
		</div>
	</div>
</div>
<div id="editgioithieu" class="modal fade">
	<div class="modal-dialog">
	<form id="formeditgioithieu" action="{{route('editgioithieu')}}" method="POST" enctype="multipart/form-data">
	   	<input type="hidden" name="_token" value="{{csrf_token()}}">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Giới Thiệu</h4>
			</div>
			<div class="modal-body">
				<div class="hienloi">
					<div class="alert alert-danger" id="loigt"></div>
				</div>
				<div class="input-group">
					<span class="input-group-addon">Nội dung</span>
					<textarea class="form-control txtnoidunggt" id="noidunggt" rows="5" name="noidunggt"></textarea>
					<script type="text/javascript"> CKEDITOR.replace('noidunggt');</script>
				</div>
			</div>
			<div class="modal-footer">
				<input type="submit" class=" btn btn-success txtcapnhatgt" name="submit" value="Sửa">
				<a href="{{route("gioithieu")}}" target="_blank" class="btn btn-warning"><i class="glyphicon glyphicon-eye-open" style="color: white;"></i>&nbsp;Xem</a>
				<button type="button" class="btn btn-default txtclosegt" data-dismiss="modal">Close</button>
			</div>
		</div>
	</form>
	</div>
</div>
<div class="modal fade" id="messagedetails">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" style="word-wrap: break-word;">Nội dung liên hệ</h4>
			</div>
			<div class="modal-body">
				<div class="loading"></div>
				<form name="frm-messagedetails" class="row">
					<div class="col-lg-12">
						<div class="form-group col-lg-6" style="text-align: left;">
							<label>Người gửi</label>
							<input type="text" name="name" class="form-control" readonly>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-6" style="text-align: left;">
							<label>Email</label>
							<input type="text" name="email" class="form-control" readonly>
						</div>
						<div class="form-group col-lg-6" style="text-align: left;">
							<label>Số đt</label>
							<input type="text" name="phone" class="form-control" readonly>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-6" style="text-align: left;">
							<label>Thời gian gửi</label>
							<input type="text" name="posttime" class="form-control" readonly>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="form-group col-lg-12" style="text-align: left;">
							<label>Nội dung</label>
							<textarea class="form-control" name="noidung" rows="10" style="resize: none; overflow: auto;" readonly></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!--Modal thông báo-->
<div class="modal fade" id="alertmessage">
	<div class="modal-dialog" style="width: 400px; margin-top: 200px;">
		<div class="modal-content" style="text-align: center;">
			<div class="modal-header">
				<h4 class="modal-title">Thông báo</h4>
			</div>
			<div class="modal-body alert alert-warning" style="text-align: center; margin-bottom: 0;">
			</div>
			<div class="modal-footer" style="text-align: center;">
				<span class="btn btn-success" data-dismiss="modal">OK</span>
			</div>
		</div>
	</div>
</div>
<script>
    document.getElementsByClassName("container-fluid")[0].style.paddingTop=document.getElementsByClassName("header")[0].clientHeight+15+"px";
    document.getElementsByClassName("container-fluid")[0].style.paddingBottom= "15px";
    function pqDatePicker(ui)
	{
        var $this = $(this);
        $this
        //.css({ zIndex: 3, position: "relative" })
            .datepicker({
                yearRange: "-25:+0", //25 years prior to present.
                changeYear: true,
                changeMonth: true,
                //showButtonPanel: true,
                onClose: function (evt, ui) {
                    $(this).focus();
                }
            });
        //default From date
        $this.filter(".pq-from").datepicker("option", "defaultDate", new Date("01/01/1996"));
        //default To date
        $this.filter(".pq-to").datepicker("option", "defaultDate", new Date("12/31/1998"));
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	function showMenu(ev)
	{
		if(ev.getElementsByTagName("i")[0].classList.contains("glyphicon-menu-down"))
		{
			ev.getElementsByTagName("ul")[0].classList.add("show");
			ev.style.border = "1px solid white";
			ev.style.borderBottom = "none";
			ev.getElementsByTagName("i")[0].classList.remove("glyphicon-menu-down");
			ev.getElementsByTagName("i")[0].classList.add("glyphicon-menu-up");
		}
		else
		{
			ev.getElementsByTagName("i")[0].classList.remove("glyphicon-menu-up");
			ev.getElementsByTagName("i")[0].classList.add("glyphicon-menu-down");
			ev.getElementsByTagName("ul")[0].classList.remove("show");
			ev.style.border = "1px solid transparent";
			ev.style.borderBottom = "none";
		}
	}
	function showUserInfo(id)
	{
		var name = document.forms['frm-userinfo']['name'];
		var username = document.forms['frm-userinfo']['username'];
		var gender = document.forms['frm-userinfo']['gender'];
		var password = document.forms['frm-userinfo']['password'];
		var brtday = document.forms['frm-userinfo']['brtday'];
		var phone = document.forms['frm-userinfo']['phone'];
		var chinhanh = document.forms['frm-userinfo']['chinhanh'];
		var address = document.forms['frm-userinfo']['address'];
		var starttime = document.forms['frm-userinfo']['starttime'];
		var email = document.forms['frm-userinfo']['email'];
		$('#userinfo').modal('show');
		$('#userinfo .loading').addClass('show');
		$.ajax({
			url: '{{route('userinfo')}}',
			type: 'post',
			data: {
				_token: '{{csrf_token()}}',
				id: id
			},
			success: function(data){
				if(data.kq==1)
				{
					name.value = data.userinfo[0].Họ_Tên;
					name.parentNode.title = name.value==null? '':name.value;
					username.value = data.userinfo[0].Username;
					username.parentNode.title = username.value;
					gender.value = data.userinfo[0].Giới_tính==0? 'Không xác định':(data.userinfo[0].Giới_tính==1? 'Nam':'Nữ');
					gender.parentNode.title = gender.value;
					password.value = data.userinfo[0].Password;
					brtday.value = data.userinfo[0].Ngày_sinh;
					brtday.parentNode.title = brtday.value==null? '':brtday.value;
					phone.value = data.userinfo[0].Sđt;
					phone.parentNode.title = phone.value==null? '':phone.value;
					chinhanh.value = data.userinfo[0].Chi_nhánh;
					chinhanh.parentNode.title = chinhanh.value==null? '':chinhanh.value;
					address.value = data.userinfo[0].Địa_chỉ;
					address.parentNode.title = address.value==null? '':address.value;
					starttime.value = data.userinfo[0].Date_Starting;
					starttime.parentNode.title = starttime.value==null? '':starttime.value;
					email.value = data.userinfo[0].Email;
					email.parentNode.title = email.value==null? '':email.value;
					$('#userinfo .loading').removeClass('show');
				}
				else
				{
					$('#alertmessage .modal-body').html('Tải thông tin thất bại!');
					$('#alertmessage').modal('show');
				}
			},
			timeout: 10000,
			error: function(xhr){
				if(xhr.statusText=="timeout")
				{
					$('#alertmessage .modal-body').html('Vui lòng kiểm tra kết nối!');
					$('#alertmessage').modal('show');
				}
			}
		});
	}
	function showWebInfo()
	{
		document.getElementById("loigt").innerHTML = "";
		document.getElementById("loigt").style.display = "none";
		document.getElementById("cke_noidunggt").style.borderColor = "#ccc";
		$.ajax({
            type:'POST',
            url:'{{asset("/admin/retrievedata")}}',
			data: {
				_token: '{{csrf_token()}}',
				typedata: 'gioithieu'
			},
            success:function(data){
                if (data.kq == 1) {
                    CKEDITOR.instances["noidunggt"].setData(data.data[0].noidung);
					$("#editgioithieu").modal();
                }
            }
        });
	}
	document.forms["formeditgioithieu"]["submit"].onclick = function(ev){
		document.getElementById("cke_noidunggt").style.borderColor = "#ccc";
		var str = "";
		if(CKEDITOR.instances["noidunggt"].getData() == "")
		{
			document.getElementById("cke_noidunggt").style.borderColor = "red";
			str += "Nội dung không được để trống!";
		}
		if(str != "")
		{
			ev.preventDefault();
			document.getElementById("loigt").innerHTML = str;
			document.getElementById("loigt").style.display = "block";
		}
	};
	function showMessage(ev)
	{
		ev.getElementsByTagName("ul")[0].classList.toggle("show");
	}
	window.onclick = function(ev){
		if(ev.target != document.getElementsByClassName("userzone")[0].getElementsByTagName("span")[0])
		{
			document.getElementsByClassName("userzone")[0].getElementsByTagName("span")[0].getElementsByTagName("ul")[0].classList.remove("show");
		}
	};
	function checkmessage(ev)
	{
		var id = ev.getAttribute("data-id");
		var loadingscreen = document.getElementById("messagedetails").getElementsByClassName("loading")[0];
		var title = document.getElementById("messagedetails").getElementsByClassName("modal-title")[0];
		var name = document.forms["frm-messagedetails"]["name"];
		var email = document.forms["frm-messagedetails"]["email"];
		var phone = document.forms["frm-messagedetails"]["phone"];
		var posttime = document.forms["frm-messagedetails"]["posttime"];
		var content = document.forms["frm-messagedetails"]["noidung"];
		loadingscreen.classList.add("show");
		$("#messagedetails").modal();
		$.ajax({
			url: '{{route("admin_showmessage")}}',
			type: 'post',
			data: {
				_token: '{{csrf_token()}}',
				data: id
			},
			success: function(data){
				if(data.kq == 1)
				{
					title.innerHTML = data.data[0].tieu_de;
					name.value = data.data[0].ho_ten;
					email.value = data.data[0].email;
					phone.value = data.data[0].dien_thoai;
					posttime.value = data.data[0].ngay_dang;
					content.value = data.data[0].noi_dung;
					if(data.data[0].is_new == null)
					{
						document.getElementsByClassName("userzone")[0].getElementsByTagName("span")[0].getElementsByTagName("i")[0].innerHTML -= 1;
						$(".userzone span:nth-child(1) li[data-id='"+id+"'] span").css({display: "none"});
					}
					loadingscreen.classList.remove("show");
				}
			},
			timeout: 10000,
			error: function(xhr){
				if(xhr.statusText == "timeout")
				{

				}
			}
		});
	}
	if(window.EventSource !== undefined){
		// supports eventsource object go a head...
		var es = new EventSource("{{route('admin_sendmessage')}}");
        es.addEventListener("message", function(e) {
            var arr = JSON.parse(e.data);
			var str = "<i class='badge'>"+arr.sllhnew+"</i>";
			str += "<ul>";
			for(var i=0;i<arr.lienhe.length;i++)
			{
				str += "<li onclick='checkmessage(this)' class='media border' data-id='"+arr.lienhe[i].lh_id+"' title='"+arr.lienhe[i].tieu_de+"'><div class='media-body'><h6>"+arr.lienhe[i].ho_ten+"<br><small><i>Gửi lúc "+arr.lienhe[i].ngay_dang+"</i></small></h6><p>"+arr.lienhe[i].tieu_de+"</p></div>"+(arr.lienhe[i].is_new == null? "<span class='badge badge-secondary'>new</span>":"");
			}
			str += "</ul>";
			document.getElementsByClassName("userzone")[0].getElementsByTagName("span")[0].innerHTML = str;
        }, false);
	} else {
		// EventSource not supported,
		// apply ajax long poll fallback
    }
</script>
@yield('script')
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78&callback=showMap"></script> -->
</body>
</html>
