@extends('quantrivien.main')
@section('title')
	@if(isset($ttkhachhang))
		Chỉnh sửa khách hàng
	@else
		Thêm khách hàng
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
    </style>
    <div class="content show row" id="addkhachhang">
        <div class="col-lg-3">
        </div>
        <form id="frm-khachhang" name="ttkhachhang" class="col-lg-6" action="{{route('addcustomer')}}" method="post">
            @csrf
            <fieldset>
                <legend><?php echo isset($ttkhachhang)? 'Sửa Thông Tin Người Dùng':'Thêm Người Dùng';?></legend>
                @isset($ttkhachhang)
                    <?php
                    $ttkhachhang = (array)$ttkhachhang;
                    ?>
                    <input type="hidden" name="ID" value="{{$ttkhachhang['Mã']}}">
                @endisset
				<div class="form-group col-lg-6">
					<label>Tên<i class="text text-danger">*</i></label>
					<input type="text" class="form-control" name="name" value="{{isset($ttkhachhang['Tên'])? $ttkhachhang['Tên']:''}}" placeholder="Tên đầy đủ" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Ngày sinh<i class="text text-danger">*</i></label>
					<input type="date" class="form-control"  name="brtday" value="{{isset($ttkhachhang['Ngày_sinh'])? $ttkhachhang['Ngày_sinh']:''}}" placeholder="Ngày sinh" required>
                </div>
				<div class="form-group col-lg-12">
					<label>Giới tính<i class="text text-danger">*</i></label>
					<br>
					<input type="radio" class="form-inline" name="gender" value="0" <?php if(!isset($ttkhachhang)||$ttkhachhang['Giới tính']=='0') echo "checked";?>> Không xác định
					<input type="radio" class="form-inline" name="gender" value="1" <?php if(isset($ttkhachhang)&&$ttkhachhang['Giới tính']=='1') echo "checked";?>> Nam
					<input type="radio" class="form-inline" name="gender" value="2" <?php if(isset($ttkhachhang)&&$ttkhachhang['Giới tính']=='2') echo "checked";?>> Nữ
                </div>
				<div class="form-group col-lg-6">
					<label>Email</label>
					<input type="email" class="form-control"  name="email" value="{{isset($ttkhachhang['Email'])? $ttkhachhang['Email']:''}}" placeholder="Địa chỉ Email">
                </div>
				<div class="form-group col-lg-6">
					<label>Số điện thoại<i class="text text-danger">*</i></label>
					<input type="tel" class="form-control"  name="phone" value="{{isset($ttkhachhang['Sđt'])? $ttkhachhang['Sđt']:''}}" placeholder="Số điện thoại" required>
                </div>
				<div class="form-group col-lg-6">
					<label>Password<i class="text text-danger">*</i></label>
					<input type="password" class="form-control"  name="password" value="{{isset($ttkhachhang['Password'])? $ttkhachhang['Password']:''}}" <?php echo isset($ttkhachhang)? "disabled":"";?> placeholder="Mật khẩu" required>
                </div>
				<div class="form-group col-lg-12">
					<label>Địa chỉ</label>
					<input type="text" class="form-control"  name="address" value="{{isset($ttkhachhang['Địa chỉ'])? $ttkhachhang['Địa chỉ']:''}}" placeholder="Địa chỉ">
                </div>
                <div style="text-align: center; clear: both;">
                    <input type="submit" name="submit" class="btn btn-success" value="<?php echo isset($ttkhachhang)? 'Sửa Thông Tin':'Thêm Người Dùng';?>">
                    <input type="button" onclick="location.assign('{{url('/admin/khachhang')}}')" class="btn btn-danger" value="Hủy">
                </div>
            </fieldset>
        </form>
        <div class="col-lg-3"></div>
    </div>
@endsection
@section('excontent')
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[1].classList.add('selected');
        option[1].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/customer-hover.png")}}');
	</script>
	@if(isset($ttkhachhang))
	<script>
        document.forms["ttkhachhang"]["submit"].onclick = function (ev) {
            var name = document.forms["ttkhachhang"]["name"];
			var brtday = document.forms["ttkhachhang"]["brtday"];
			var gender = document.forms["ttkhachhang"]["gender"];
            var email = document.forms["ttkhachhang"]["email"];
            var phone = document.forms["ttkhachhang"]["phone"];
            var password = document.forms["ttkhachhang"]["password"];
            var address = document.forms["ttkhachhang"]["address"];
			var idkhachhang = '{{$ttkhachhang["Mã"]}}';
			var str = "";
			var format_phone = /^(0[3578]|09)[0-9]{8}$/;
			var format_name = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
			var format_email = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;
			name.style.borderColor = "#ccc";
			brtday.style.borderColor = "#ccc";
			email.style.borderColor = "#ccc";
			phone.style.borderColor = "#ccc";
			password.style.borderColor = "#ccc";
			if(name.value == "")
			{
				name.style.borderColor = "red";
				str += "Tên không được để trống!<br>";
			}
			else if(name.value != "")
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
			if(gender.value == 0)
			{
				str += "Giới tính không được để không xác định!<br>";
			}			
			if(email.value != "")
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
							typecheck: "useremail_change",
							datacheck: email.value,
							idcheck: idkhachhang
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
			else if(phone.value != "")
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
							typecheck: "userphone_change",
							datacheck: phone.value,
							idcheck: idkhachhang
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
			// if(password.value == "")
			// {
				// password.style.borderColor = "red";
				// str += "Password không được để trống!<br>";
			// }
			// else if(password.value != "")
			// {
				// if(password.value.length < 6 || password.value.length > 30)
				// {
					// password.style.borderColor = "red";
					// str += "Password không được ít hơn 6 ký tự và vượt quá 30 ký tự!<br>";
				// }
			// }
			if(str != "")
			{
				ev.preventDefault();
				$('#alertmessage .modal-body').html(str);
				$('#alertmessage').modal('show');
			}
        };
    </script>
	@else
	<script>
        document.forms["ttkhachhang"]["submit"].onclick = function (ev) {
            var name = document.forms["ttkhachhang"]["name"];
			var brtday = document.forms["ttkhachhang"]["brtday"];
			var gender = document.forms["ttkhachhang"]["gender"];
            var email = document.forms["ttkhachhang"]["email"];
            var phone = document.forms["ttkhachhang"]["phone"];
            var password = document.forms["ttkhachhang"]["password"];
            var address = document.forms["ttkhachhang"]["address"];
			var str = "";
			var format_phone = /^(0[3578]|09)[0-9]{8}$/;
			var format_name = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
			var format_email = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;
			name.style.borderColor = "#ccc";
			brtday.style.borderColor = "#ccc";
			email.style.borderColor = "#ccc";
			phone.style.borderColor = "#ccc";
			password.style.borderColor = "#ccc";
			if(name.value == "")
			{
				name.style.borderColor = "red";
				str += "Tên không được để trống!<br>";
			}
			else if(name.value != "")
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
			if(gender.value == 0)
			{
				str += "Giới tính không được để không xác định!<br>";
			}			
			if(email.value != "")
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
							typecheck: "useremail_create",
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
			else if(phone.value != "")
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
							typecheck: "userphone_create",
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
			if(password.value == "")
			{
				password.style.borderColor = "red";
				str += "Password không được để trống!<br>";
			}
			else if(password.value != "")
			{
				if(password.value.length < 6 || password.value.length > 30)
				{
					password.style.borderColor = "red";
					str += "Password không được ít hơn 6 ký tự và vượt quá 30 ký tự!<br>";
				}
			}
			if(str != "")
			{
				ev.preventDefault();
				$('#alertmessage .modal-body').html(str);
				$('#alertmessage').modal('show');
			}
        };
    </script>
	@endif
@endsection