<!DOCTYPE html>
<html>
<head>
    <title>Quản lý đặt vé</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style-qldv.css')}}">
</head>
<body>
<div class="container-fluid">
    <div class="header">
        <div class="row">
            <h3 class="col-lg-4"><a href="{{asset('qldv/')}}">AWE QL Đặt vé</a></h3>
            <h5 class="col-lg-4"><a href="{{url('/')}}" title="Chuyển về trang khách hàng"><img src="{{asset('/images/icons/luggage.png')}}" height="30" alt="icon">AwesomeTravel</a></h5>
            <div class="col-lg-4 userzone">
                <span onclick="showMenu(this)"><img src="{{asset('images/icons/user.png')}}" alt="icon">{{session('qldv.name','QLDVTest')}}&nbsp;<i class="glyphicon glyphicon-menu-down" ></i>
                    <ul>
                        <li onclick="showUserInfo({{session('qldv.id')}})"><i class="glyphicon glyphicon-info-sign"></i>Thông tin</li>
                        <a href="{{route('qldv_logout')}}">
                            <li><i class="glyphicon glyphicon-off"></i>Thoát</li>
                        </a>
                    </ul>
                </span>
            </div>
        </div>
    </div>
    <div class="noidung row">
		@yield('content')
    </div>
</div>
<div class="modal fade" id="userinfo">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<div class="modal-title">Thông tin QL Đặt vé</div>
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
				<a href="#" class="btn btn-success"><i class="glyphicon glyphicon-edit"></i>&nbsp;Thay Đổi Thông Tin</a>
			</div>
		</div>
	</div>
</div>
@yield('excontent')
<div id="modalalert" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Thông báo</h4>
			</div>
			<div class="modal-body">
				Nội dung thông báo...
			</div>
		</div>
	</div>
</div>
<script>
    document.getElementsByClassName("container-fluid")[0].style.paddingTop = document.getElementsByClassName("header")[0].clientHeight + 45 + "px";
    document.getElementsByClassName("container-fluid")[0].style.paddingBottom = "15px";
	function showMenu(ev){
		if(ev.getElementsByTagName("i")[0].classList.contains("glyphicon-menu-down")){
			ev.getElementsByTagName("ul")[0].style.display = "block";
			ev.style.border = "1px solid white";
			ev.style.borderBottom = "none";
			ev.getElementsByTagName("i")[0].classList.remove("glyphicon-menu-down");
			ev.getElementsByTagName("i")[0].classList.add("glyphicon-menu-up");
		}
		else{
			ev.getElementsByTagName("i")[0].classList.remove("glyphicon-menu-up");
			ev.getElementsByTagName("i")[0].classList.add("glyphicon-menu-down");
			ev.getElementsByTagName("ul")[0].style.display = "none";
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
			url: '{{route("qldv_userinfo")}}',
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
					alert('Tải thông tin thất bại!');
				}
			},
			timeout: 10000,
			error: function(xhr){
				if(xhr.statusText=="timeout")
				{
					alert('Vui lòng kiểm tra kết nối!');
				}
			}
		});
	}
</script>
@yield('script')
</body>
</html>
