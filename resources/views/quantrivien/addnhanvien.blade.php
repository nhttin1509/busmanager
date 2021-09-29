@extends('quantrivien.main')
@section('title')
	@if(isset($ttnhanvien))
		Chỉnh sửa thông tin nhân viên
	@else
		Thêm nhân viên
	@endif
@endsection
@section('content')
    <style>
        .row > *:nth-child(2) {
            text-align: left;
        }
		.header .row > *:nth-child(2) {
            text-align: center;
        }
		#nv_doimatkhau .row * {
			text-align: left;
		}
    </style>
    <div class="content show row" id="addnhanvien">
        <div class="col-lg-3">
        </div>
        <form class="col-lg-6" name="ttnhanvien" action="{{route('addemployee')}}" method="post">
            @csrf
            <fieldset>
                <legend><?php echo isset($ttnhanvien)? 'Sửa Thông Tin Nhân Viên':'Thêm Nhân Viên';?></legend>
                @isset($ttnhanvien)
                    <?php
                    $ttnhanvien = (array)$ttnhanvien;
                    ?>
                    <input type="hidden" name="ID" value="{{$ttnhanvien['Mã']}}">
                @endisset
				<div class="form-group col-lg-6">
					<label>Tên<i class="text text-danger">*</i></label>
					<input type="text" class="form-control" name="name" value="{{isset($ttnhanvien['Họ_Tên'])? $ttnhanvien['Họ_Tên']:''}}" placeholder="Tên đầy đủ" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Ngày sinh<i class="text text-danger">*</i></label>
					<input type="date" class="form-control"  name="brtday" value="{{isset($ttnhanvien['Ngày_sinh'])? $ttnhanvien['Ngày_sinh']:''}}" placeholder="Ngày sinh" required>
                </div>
				<div class="form-group col-lg-12">
					<label>Giới tính<i class="text text-danger">*</i></label>
					<br>
					<input type="radio" class="form-inline" name="gender" value="0" <?php if(!isset($ttnhanvien)||$ttnhanvien['Giới_tính']=='0') echo "checked";?>> Không xác định
					<input type="radio" class="form-inline" name="gender" value="1" <?php if(isset($ttnhanvien)&&$ttnhanvien['Giới_tính']=='1') echo "checked";?>> Nam
					<input type="radio" class="form-inline" name="gender" value="2" <?php if(isset($ttnhanvien)&&$ttnhanvien['Giới_tính']=='2') echo "checked";?>> Nữ
                </div>
				<div class="form-group col-lg-6">
					<label>Username<i class="text text-danger">*</i></label>
					<input type="text" class="form-control"  name="username" value="{{isset($ttnhanvien['Username'])? $ttnhanvien['Username']:''}}" placeholder="Tên đăng nhập" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Password<i class="text text-danger">*</i></label>
					<input type="password" class="form-control"  name="password" value="{{isset($ttnhanvien['Password'])? $ttnhanvien['Password']:''}}" <?php echo isset($ttnhanvien)? "disabled":"";?> placeholder="Mật khẩu" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Email<i class="text text-danger">*</i></label>
					<input type="email" class="form-control"  name="email" value="{{isset($ttnhanvien['Email'])? $ttnhanvien['Email']:''}}" placeholder="Địa chỉ Email" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Số điện thoại<i class="text text-danger">*</i></label>
					<input type="tel" class="form-control"  name="phone" value="{{isset($ttnhanvien['Sđt'])? $ttnhanvien['Sđt']:''}}" placeholder="Số điện thoại" required>
                </div>
				<div class="form-group col-lg-12">
					<label>Địa chỉ<i class="text text-danger">*</i></label>
					<input type="text" class="form-control"  name="address" value="{{isset($ttnhanvien['Địa_chỉ'])? $ttnhanvien['Địa_chỉ']:''}}" placeholder="Địa chỉ" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Loại Nhân viên<i class="text text-danger">*</i></label>
					<select class="form-control" name="typenv" required>
						<option value="QTV" <?php if(isset($ttnhanvien)&&$ttnhanvien['Loại_NV']=='QTV') echo "selected";?>>Quản trị viên</option>
						<option value="QLDV" <?php if(isset($ttnhanvien)&&$ttnhanvien['Loại_NV']=='QLDV') echo "selected";?>>Quản lý đặt vé</option>
						<option value="TX" <?php if(isset($ttnhanvien)&&$ttnhanvien['Loại_NV']=='TX') echo "selected";?>>Tài xế</option>
					</select>
                </div>
				<div class="form-group col-lg-6">
					<label>Bằng lái</label>
					<input type="text" class="form-control" name="banglai" value="{{isset($ttnhanvien['Bằng_lái'])? $ttnhanvien['Bằng_lái']:''}}" placeholder="Số bằng lái">
                </div>
				<div class="form-group col-lg-6">
					<label>Ngày bắt đầu làm việc<i class="text text-danger">*</i></label>
					<input type="date" class="form-control" name="datestart" value="{{isset($ttnhanvien['Date_Starting'])? $ttnhanvien['Date_Starting']:''}}" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Chi nhánh<i class="text text-danger">*</i></label>
					<input type="text" class="form-control" name="chinhanh" value="{{isset($ttnhanvien['Chi_nhánh'])? $ttnhanvien['Chi_nhánh']:''}}" placeholder="Chinh nhánh" required>
                </div>
                <div style="text-align: center; clear: both;">
                    <input type="submit" class="btn btn-success" name="submit" value="<?php echo isset($ttnhanvien)? 'Sửa Thông Tin':'Thêm Nhân Viên';?>">
					@isset($ttnhanvien)
					<span class="btn btn-warning" data-toggle="modal" data-target="#nv_doimatkhau">Đổi Mật khẩu</span>
					@endisset
                    <input type="button" onclick="location.assign('{{url('/admin/nhanvien')}}')" class="btn btn-danger" value="Hủy">
                </div>
            </fieldset>
        </form>
        <div class="col-lg-3"></div>
    </div>
@endsection
@section('excontent')
	@isset($ttnhanvien)
		<div class="modal fade" id="nv_doimatkhau">
			<div class="modal-dialog" style="width: 300px;">
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Đổi mật khẩu</h4>
					</div>
					<div class="modal-body">
						<form name="frm-nvpassword">
							<div class="input-group">
								<label class="input-group-addon">Mật khẩu cũ&nbsp;&nbsp;&nbsp;</label>
								<input type="password" name="oldpassword" class="form-control" placeholder="Mật khẩu cũ">
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Mật khẩu mới</label>
								<input type="password" name="newpassword" class="form-control" placeholder="Mật khẩu mới">
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Xác nhận MK&nbsp;&nbsp;</label>
								<input type="password" name="renewpassword" class="form-control" placeholder="Xác nhận Mật khẩu mới">
							</div>
						</form>
					</div>
					<div class="modal-footer" style="text-align: center;">
						<button class="btn btn-success" onclick="changeNVPassword()">Xác nhận</button>
					</div>
				</div>
			</div>
		</div>
	@endisset
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[6].classList.add('selected');
        option[6].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/partnership-hover.png")}}');
    </script>
	@if(isset($ttnhanvien))
		<script>
			document.forms["ttnhanvien"]["submit"].onclick = function(ev){
				var id = document.forms["ttnhanvien"]["ID"];
				var name = document.forms["ttnhanvien"]["name"];
				var brtday = document.forms["ttnhanvien"]["brtday"];
				var gender = document.forms["ttnhanvien"]["gender"];
				var username = document.forms["ttnhanvien"]["username"];
				var email = document.forms["ttnhanvien"]["email"];
				var phone = document.forms["ttnhanvien"]["phone"];
				var address = document.forms["ttnhanvien"]["address"];
				var typenv = document.forms["ttnhanvien"]["typenv"];
				var banglai = document.forms["ttnhanvien"]["banglai"];
				var datestart = document.forms["ttnhanvien"]["datestart"];
				var chinhanh = document.forms["ttnhanvien"]["chinhanh"];
				var format_username = /^[_a-zA-Z]{1}[_0-9a-zA-Z]{4,29}$/
				var format_phone = /^(0[3578]|09)[0-9]{8}$/;
				var format_name = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
				var format_email = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;
				var str = "";
				name.style.borderColor = "#ccc";
				brtday.style.borderColor = "#ccc";
				username.style.borderColor = "#ccc";
				email.style.borderColor = "#ccc";
				phone.style.borderColor = "#ccc";
				address.style.borderColor = "#ccc";
				banglai.style.borderColor = "#ccc";
				chinhanh.style.borderColor = "#ccc";
				datestart.style.borderColor = "#ccc";
				if(name.value == "")
				{
					name.style.borderColor = "red";
					str += "Tên không được để trống!<br>";
				}
				else
				{
					if(name.value.search(format_name) == -1)
					{
						name.style.borderColor = "red";
						str += "Tên không đúng định dạng!<br>";
					}
				}
				if(brtday.value == "")
				{
					brtday.style.borderColor = "red";
					str += "Ngày sinh không được để trống!<br>";
				}
				if(username.value == "")
				{
					username.style.borderColor = "red";
					str += "Username không được để trống!<br>";
				}
				else
				{
					if(username.value.search(format_username) == -1)
					{
						username.style.borderColor = "red";
						str += "Username không đúng định dạng!<br>";
					}
					else
					{
						$.ajax({
							url: "{{route('admin_checkexist')}}",
							type: "post",
							data: {
								_token: "{{csrf_token()}}",
								typecheck: "employeeusername_change",
								datacheck: username.value,
								idcheck: id.value
							},
							success: function(data){
								if(data.kq == 1)
								{
									username.style.borderColor = "red";
									str += "Username đã tồn tại!<br>";
								}
							},
							async: false
						});
					}
				}
				if(email.value == "")
				{
					email.style.borderColor = "red";
					str += "Email không được để trống!<br>";
				}
				else
				{
					if(email.value.search(format_email) == -1)
					{
						email.style.borderColor = "red";
						str += "Email không đúng định dạng!<br>";
					}
					else
					{
						$.ajax({
							url: "{{route('admin_checkexist')}}",
							type: "post",
							data: {
								_token: "{{csrf_token()}}",
								typecheck: "employeeemail_change",
								datacheck: email.value,
								idcheck: id.value
							},
							success: function(data){
								if(data.kq == 1)
								{
									email.style.borderColor = "red";
									str += "Email đã tồn tại!<br>";
								}
							},
							async: false
						});
					}
				}
				if(phone.value == "")
				{
					phone.style.borderColor = "red";
					str += "Số điện thoại không được để trống!<br>";
				}
				else
				{
					if(phone.value.search(format_phone) == -1)
					{
						phone.style.borderColor = "red";
						str += "Số điện thoại không đúng định dạng!<br>";
					}
					else
					{
						$.ajax({
							url: "{{route('admin_checkexist')}}",
							type: "post",
							data: {
								_token: "{{csrf_token()}}",
								typecheck: "employeephone_change",
								datacheck: phone.value,
								idcheck: id.value
							},
							success: function(data){
								if(data.kq == 1)
								{
									phone.style.borderColor = "red";
									str += "Số điện thoại đã tồn tại!<br>";
								}
							},
							async: false
						});
					}
				}
				if(address.value == "")
				{
					address.style.borderColor = "red";
					str += "Địa chỉ không được để trống!<br>";
				}
				if(typenv.value == "TX")
				{
					if(banglai.value == "")
					{
						banglai.style.borderColor = "red";
						str += "Nhân viên là tài xế không được để trống bằng lái!<br>";
					}
				}
				if(datestart.value == "")
				{
					datestart.style.borderColor = "red";
					str += "Ngày bắt đầu làm việc không được để trống!<br>";
				}
				if(chinhanh.value == "")
				{
					chinhanh.style.borderColor = "red";
					str += "Chi nhánh không được để trống!<br>";
				}
				if(str != "")
				{
					ev.preventDefault();
					$("#alertmessage .modal-body").html(str);
					$("#alertmessage").modal();
				}
			};
			function changeNVPassword()
			{
				var id = document.forms["ttnhanvien"]["ID"];
				var oldpassword = document.forms["frm-nvpassword"]["oldpassword"];
				var newpassword = document.forms["frm-nvpassword"]["newpassword"];
				var renewpassword = document.forms["frm-nvpassword"]["renewpassword"];
				var str = "";
				oldpassword.style.borderColor = "#ccc";
				newpassword.style.borderColor = "#ccc";
				renewpassword.style.borderColor = "#ccc";
				if(oldpassword.value == "")
				{
					oldpassword.style.borderColor = "red";
					str += "Chưa điền Mật khẩu cũ!<br>";
				}
				else
				{
					$.ajax({
						url: "{{route('admin_checkexist')}}",
						type: "post",
						data: {
							_token: "{{csrf_token()}}",
							typecheck: "employeepassword_change",
							datacheck: oldpassword.value,
							idcheck: id.value
						},
						success: function(data){
							if(data.kq == 1)
							{
								oldpassword.style.borderColor = "red";
								str += "Mật khẩu cũ không đúng!<br>";
							}
						},
						async: false
					});
				}
				if(newpassword.value == "")
				{
					newpassword.style.borderColor = "red";
					str += "Chưa điền Mật khẩu mới!<br>";
				}
				else
				{
					if(newpassword.value.length < 6||newpassword.value.length > 30)
					{
						newpassword.style.borderColor = "red";
						str += "Mật khẩu phải từ 6 đến 30 ký tự!<br>";
					}
				}
				if(renewpassword.value == "")
				{
					renewpassword.style.borderColor = "red";
					str += "Chưa điền xác nhận Mật khẩu mới!<br>";
				}
				else
				{
					if(newpassword.value != ""&&newpassword.value != renewpassword.value)
					{
						renewpassword.style.borderColor = "red";
						str += "Xác nhận mật khẩu mới không khớp!<br>";
					}
				}
				if(str != "")
				{
					$("#alertmessage .modal-body").html(str);
					$("#alertmessage").modal();
				}
				else
				{
					$.ajax({
						url: "{{route('admin_changepassword')}}",
						type: "post",
						data: {
							_token: "{{csrf_token()}}",
							typepassword: "employeepassword",
							olddata: oldpassword.value,
							newdata: newpassword.value,
							id: id.value
						},
						success: function(data){
							if(data.kq == 1)
							{
								$("#alertmessage .modal-body").html("Đổi mật khẩu thành công!");
								$("#alertmessage").modal();
								
							}
							else
							{
								$("#alertmessage .modal-body").html("Đổi mật khẩu thất bại!");
								$("#alertmessage").modal();
							}
							oldpassword.value = "";
							newpassword.value = "";
							renewpassword.value = "";
							$("#nv_doimatkhau").modal("hide");
						},
						async: false
					});
				}
			}
			$("#nv_doimatkhau").on("hide.bs.modal", function(){ //Bắt sự kiện modal đặt vé tắt
				//Code Sự kiện tắt modal đặt vé
				var oldpassword = document.forms["frm-nvpassword"]["oldpassword"];
				var newpassword = document.forms["frm-nvpassword"]["newpassword"];
				var renewpassword = document.forms["frm-nvpassword"]["renewpassword"];
				oldpassword.style.borderColor = "#ccc";
				newpassword.style.borderColor = "#ccc";
				renewpassword.style.borderColor = "#ccc";
				oldpassword.value = "";
				newpassword.value = "";
				renewpassword.value = "";
			});
		</script>
	@else
		<script>
			document.forms["ttnhanvien"]["submit"].onclick = function(ev){
				var name = document.forms["ttnhanvien"]["name"];
				var brtday = document.forms["ttnhanvien"]["brtday"];
				var gender = document.forms["ttnhanvien"]["gender"];
				var username = document.forms["ttnhanvien"]["username"];
				var password = document.forms["ttnhanvien"]["password"];
				var email = document.forms["ttnhanvien"]["email"];
				var phone = document.forms["ttnhanvien"]["phone"];
				var address = document.forms["ttnhanvien"]["address"];
				var typenv = document.forms["ttnhanvien"]["typenv"];
				var banglai = document.forms["ttnhanvien"]["banglai"];
				var datestart = document.forms["ttnhanvien"]["datestart"];
				var chinhanh = document.forms["ttnhanvien"]["chinhanh"];
				var format_username = /^[_a-zA-Z]{1}[_0-9a-zA-Z]{4,29}$/
				var format_phone = /^(0[3578]|09)[0-9]{8}$/;
				var format_name = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
				var format_email = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;
				var str = "";
				name.style.borderColor = "#ccc";
				brtday.style.borderColor = "#ccc";
				username.style.borderColor = "#ccc";
				password.style.borderColor = "#ccc";
				email.style.borderColor = "#ccc";
				phone.style.borderColor = "#ccc";
				banglai.style.borderColor = "#ccc";
				chinhanh.style.borderColor = "#ccc";
				datestart.style.borderColor = "#ccc";
				if(name.value == "")
				{
					name.style.borderColor = "red";
					str += "Tên không được để trống!<br>";
				}
				else
				{
					if(name.value.search(format_name) == -1)
					{
						name.style.borderColor = "red";
						str += "Tên không đúng định dạng!<br>";
					}
				}
				if(brtday.value == "")
				{
					brtday.style.borderColor = "red";
					str += "Ngày sinh không được để trống!<br>";
				}
				if(username.value == "")
				{
					username.style.borderColor = "red";
					str += "Username không được để trống!<br>";
				}
				else
				{
					if(username.value.search(format_username) == -1)
					{
						username.style.borderColor = "red";
						str += "Username không đúng định dạng!<br>";
					}
					else
					{
						$.ajax({
							url: "{{route('admin_checkexist')}}",
							type: "post",
							data: {
								_token: "{{csrf_token()}}",
								typecheck: "employeeusername_create",
								datacheck: username.value
							},
							success: function(data){
								if(data.kq == 1)
								{
									username.style.borderColor = "red";
									str += "Username đã tồn tại!<br>";
								}
							},
							async: false
						});
					}
				}
				if(password.value == "")
				{
					password.style.borderColor = "red";
					str += "Password không được để trống!<br>";
				}
				else
				{
					if(password.value.length < 6||password.value.length > 30)
					{
						password.style.borderColor = "red";
						str += "Mật khẩu phải từ 6 đến 30 ký tự!<br>";
					}
				}
				if(email.value == "")
				{
					email.style.borderColor = "red";
					str += "Email không được để trống!<br>";
				}
				else
				{
					if(email.value.search(format_email) == -1)
					{
						email.style.borderColor = "red";
						str += "Email không đúng định dạng!<br>";
					}
					else
					{
						$.ajax({
							url: "{{route('admin_checkexist')}}",
							type: "post",
							data: {
								_token: "{{csrf_token()}}",
								typecheck: "employeeemail_create",
								datacheck: email.value
							},
							success: function(data){
								if(data.kq == 1)
								{
									email.style.borderColor = "red";
									str += "Email đã tồn tại!<br>";
								}
							},
							async: false
						});
					}
				}
				if(phone.value == "")
				{
					phone.style.borderColor = "red";
					str += "Số điện thoại không được để trống!<br>";
				}
				else
				{
					if(phone.value.search(format_phone) == -1)
					{
						phone.style.borderColor = "red";
						str += "Số điện thoại không đúng định dạng!<br>";
					}
					else
					{
						$.ajax({
							url: "{{route('admin_checkexist')}}",
							type: "post",
							data: {
								_token: "{{csrf_token()}}",
								typecheck: "employeephone_create",
								datacheck: phone.value
							},
							success: function(data){
								if(data.kq == 1)
								{
									phone.style.borderColor = "red";
									str += "Số điện thoại đã tồn tại!<br>";
								}
							},
							async: false
						});
					}
				}
				if(address.value == "")
				{
					address.style.borderColor = "red";
					str += "Địa chỉ không được để trống!<br>";
				}
				if(typenv.value == "TX")
				{
					if(banglai.value == "")
					{
						banglai.style.borderColor = "red";
						str += "Nhân viên là tài xế không được để trống bằng lái!<br>";
					}
				}
				if(datestart.value == "")
				{
					datestart.style.borderColor = "red";
					str += "Ngày bắt đầu làm việc không được để trống!<br>";
				}
				if(chinhanh.value == "")
				{
					chinhanh.style.borderColor = "red";
					str += "Chi nhánh không được để trống!<br>";
				}
				if(str != "")
				{
					ev.preventDefault();
					$("#alertmessage .modal-body").html(str);
					$("#alertmessage").modal();
				}
			};
		</script>
	@endif
@endsection
