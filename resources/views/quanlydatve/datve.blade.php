@extends('quanlydatve.main')
@section('content')
    <div class="col-lg-12">
        <span>
            <ul>
                <a href="{{asset('qldv/giamsat')}}">
                    <li class="option selected">Bản đồ</li>
                </a>
				<a href="{{asset('qldv/datve')}}">
                    <li class="option">Nhập vé</li>
                </a>
            </ul>
        </span>
		<div class="content datve show row">
			<div class="col-lg-4">
				<div class="searchroute">
					<span>Tìm chuyến xe</span>
					<form name="searchchuyenxe">
						<div>
							<div class="input-group" style="position: relative;">
								<span class="input-group-addon">Nơi đi&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<input type="text" name="noidi" class="form-control" list="diadiem" placeholder="Nơi đi">
								<!--ul id="kqsearchtinh"></ul-->
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon">Nơi đến</span>
								<input type="text" name="noiden" class="form-control" list="diadiem" placeholder="Nơi đến">
							</div>
							<br>
							<div class="input-group">
								<span class="input-group-addon">Ngày đi&nbsp;</span>
								<input type="date" name="ngaydi" class="form-control">
							</div>
							<br>
							<div class="selecttype">
								<span>Loại xe</span>
								<label class="radio-inline"><input type="radio" name="loaighe" value="0" checked>Ghế ngồi</label>
								<label class="radio-inline"><input type="radio" name="loaighe" value="1">Giường nằm</label>
							</div>
						</div>
					</form>
					<span class="glyphicon glyphicon-search" id="timchuyenxe"></span>
				</div>
				<div class="searchresult">
					<div class="chuyenxe">
						<div class="loading"></div>
						<ul>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="ttdatve">
					<span>Thông tin đặt vé<i></i></span>
					<form name="frm-ttdatve" class="form-vertical">
						<input type="hidden" name="idchuyenxe">
						<input type="hidden" name="idkhachhang">
						<input type="hidden" name="idnhanvien" value="{{session('qldv.id')}}"> <!--Sẽ thay bằng session mã nhân viên sau-->
						<div class="input-group">
							<label class="input-group-addon">Họ tên&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<input type="text" name="hoten" class="form-control" placeholder="Họ tên">
						</div>	
						<br>
						<div class="input-group">
							<label class="input-group-addon">Di động&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<input type="text" name="sodienthoai" class="form-control" placeholder="Số điện thoại">
						</div>
						<br>
						<div class="input-group">
							<label class="input-group-addon">Giới tính&nbsp;&nbsp;</label>
							<select name="gender" class="form-control">
								<option value="0">Không xác định</option>
								<option value="1">Nam</option>
								<option value="2">Nữ</option>
							</select>
						</div>
						<br>
						<div class="input-group">
							<label class="input-group-addon">Ngày sinh</label>
							<input type="date" name="brtday" class="form-control">
						</div>
						<br>
						<div class="input-group">
							<label class="input-group-addon">Đã chọn&nbsp;&nbsp;&nbsp;</label>
							<input type="text" name="vedachon" class="form-control" placeholder="Các vị trí ghế đã chọn" readonly>
						</div>
						<br>
						<div class="input-group">
							<label class="input-group-addon">Nơi đi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
							<input type="text" name="noidi" class="form-control" placeholder="Nơi đi" readonly>
						</div>
						<br>
						<div class="input-group">
							<label class="input-group-addon">Nơi đến&nbsp;&nbsp;&nbsp;</label>
							<input type="text" name="noiden" class="form-control" placeholder="Nơi đến" readonly>
						</div>
					</form>
					<span onclick="showHistory()">Lịch sử đã đặt</span>
					<span onclick="registerAccount()">Tạo tài khoản</span>
					<br>
					<span onclick="bookTicket()">Đặt vé</span>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="searchcustomer">
					<span>Tìm Khách Hàng</span>
					<form name="timkhachhang">
						<div>
							<input type="text" name="searchkh" class="form-control" placeholder="Search">
							<span id="timkh" class="glyphicon glyphicon-search"></span>
						</div>
						<div>
							<label>Tìm theo:</label>
							<label class="kh-filter"><input type="checkbox" onclick="if(document.getElementById('kh-filter0').checked == true){ document.getElementById('kh-filter1').checked = true; document.getElementById('kh-filter2').checked = true; document.getElementById('kh-filter1').disabled = true; document.getElementById('kh-filter2').disabled = true;} else { document.getElementById('kh-filter1').disabled = false; document.getElementById('kh-filter2').disabled = false;}" id="kh-filter0" value="0">Tất cả</label>
							<label class="kh-filter"><input type="checkbox" id="kh-filter1" value="1" checked>Tên</label>
							<label class="kh-filter"><input type="checkbox" id="kh-filter2" value="2">Sđt</label>
						</div>
					</form>
					<div class="kqtimkh">
						<div class="loading"></div>
						<ul>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('excontent')
    <datalist id="diadiem" style="display: none;">
		@isset($diadiem)
			@foreach($diadiem as $row)
				<option value="{{$row->Tên}}" label="{{$row->Tên_không_dấu}}">
			@endforeach
		@endisset
	</datalist>
    <div id="modaldatve" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Chuyến xe #</h4>
                </div>
                <div class="modal-body row">
					<div class="col-lg-4">
                    <form name="ttchuyenxe">
						<input type="hidden" name="idchuyenxe">
                        <div class="form-group">
                            <label>Nơi đi</label>
                            <input type="text" name="noidi" class="form-control" placeholder="Điểm đi" readonly="">
                        </div>
                        <div class="form-group">
                            <label>Nơi đến</label>
                            <input type="text" name="noiden" class="form-control" placeholder="Điểm đến" readonly="">
                        </div>
                        <div class="form-group">
                            <label>Thời gian đi</label>
                            <input type="text" name="thoigiandi" class="form-control" placeholder="Thời gian đi" readonly="">
                        </div>
                        <div class="form-group">
                            <label>Thời gian đến dự kiến</label>
                            <input type="text" name="thoigianden" class="form-control" placeholder="Thời gian đến dự kiến" readonly="">
                        </div> 
						<div class="form-group">
                            <label>Vé chọn</label>
                            <input type="text" name="vechon" class="form-control" placeholder="Vé đã chọn" readonly="">
                        </div>   
                    </form>
					</div>
                    <div class="col-lg-8">
						<span>Sơ đồ xe</span>
						<div class="sodoxe">
							<div class="loading"></div>
							<div class="table-responsive"></div>
						</div>
					</div>
                </div>
				<div class="modal-footer">
					<button class="btn btn-success" id="chonchuyenxe">Chọn chuyến xe</button>
					<button class="btn btn-danger" id="huychonchuyenxe">Hoàn tác</button>
				</div>
            </div>
        </div>
    </div>
    <div id="modaldadat" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Đã đặt thành công</h4>
                </div>
                <div class="modal-body row">
					<form name="frm-ttdadat">
						<div class="form-group col-lg-6">
							<label>Nơi đi</label>
							<input type="text" class="form-control" name="noidi" placeholder="Điểm đi" readonly>
						</div>
						<div class="form-group col-lg-6">
							<label>Nơi đến</label>
							<input type="text" class="form-control" name="noiden" placeholder="Điểm đến" readonly>
						</div>
						<div class="form-group col-lg-6">
							<label>Thời gian đi</label>
							<input type="text" class="form-control" name="thoigiandi" placeholder="Thời gian đi" readonly>
						</div>
						<div class="form-group col-lg-6">
							<label>Thời gian đến dự kiến</label>
							<input type="text" class="form-control" name="thoigianden" placeholder="Thời gian đến dự kiến" readonly>
						</div>
					</form>
					<h5 style="display: block; margin: .5em; color: #004964;">Vé đã đặt</h5>
                    <ul>
                        <!--li>Thông tin vé # <span class="glyphicon glyphicon-remove" style="color: red;"></span></li-->
                    </ul>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <label>Tổng tiền</label>
                        <input type="text" name="tongtien" class="form-control" placeholder="Tổng tiền" readonly="">
					</div>
                    <button class="btn btn-success" onclick="refreshPage()">OK</button>
                </div>
            </div>
        </div>
    </div>
	<div id="modalttkhachhang" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Thông tin khách hàng</h4>
				</div>
				<div class="modal-body">
					<div class="loading"></div>
					<form name="frm-ttkhachhang">
						<input type="hidden" name="id">
						<div class="form-group">
							<label>Tên</label>
							<input type="text" name="name" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Giới tính</label>
							<input type="text" name="gender" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Ngày sinh</label>
							<input type="text" name="brtday" class="form-control" readonly>
							<input type="hidden" name="hd-brtday">
						</div>
						<div class="form-group">
							<label>Số điện thoại</label>
							<input type="text" name="phone" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input type="text" name="email" class="form-control" readonly>
						</div>
						<div class="form-group">
							<label>Địa chỉ</label>
							<input type="text" name="address" class="form-control" readonly>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button class="btn btn-success" onclick="chooseKH_1()">Lấy Thông Tin</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modaldanhsachdondatve">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Các Đơn đặt vé đã thực hiện</h4>
				</div>
				<div class="modal-body">
					<!--div class="panel-group">
						<div class="panel panel-default">
							<div class="panel-heading">
									<div class="panel-title" data-toggle="collapse" data-target="#collapse1">
										qqqqqqqqqqqq
									</div>
							</div>
							<div class="panel-collapse collapse" id="collapse1">
								<div style="text-align: right;">	
									<span class="btn btn-success">
										<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;Chi tiết
									</span>
									&nbsp;
									<span class="btn btn-danger">
										<i class="glyphicon glyphicon-remove"></i>&nbsp;Hủy
									</span>
								</div>
							</div>
						</div>
					</div-->
				</div>
				<div class="modal-footer">
					<button class="btn btn-danger" data-dismiss="modal">Đóng</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="modalchitietdondatve">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Chi tiết đơn đặt vé</h4>
				</div>
				<div class="modal-body">
					<div class="loading"></div>
					<form name="frm-chitietdondatve" class="row">
						<div class="col-lg-6">
							<div class="input-group">
								<label class="input-group-addon">Chuyến xe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="machuyenxe" placeholder="Mã chuyến xe" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Nơi đi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="noidi" placeholder="Nơi đi" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Nơi đến&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="noiden" placeholder="Nơi đến" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Thời gian đi&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="thoigiandi" placeholder="Thời gian đi" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Thời gian đến</label>
								<input type="text" class="form-control" name="thoigianden" placeholder="Thời gian đến" readonly>
							</div>
							<br>
						</div>
						<div class="col-lg-6">
							<div class="input-group">
								<label class="input-group-addon">Khách hàng&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="makhachhang" placeholder="Mã khách hàng" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Tên khách hàng</label>
								<input type="text" class="form-control" name="name" placeholder="Họ tên khách hàng" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Giới tính&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="gender" placeholder="Giới tính" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Ngày sinh&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="brtday" placeholder="Ngày sinh" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Số điện thoại&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="phone" placeholder="Số điện thoại" readonly>
							</div>
							<br>
						</div>
						<div class="col-lg-3"></div>
						<div class="col-lg-6">
							<br>
							<div class="input-group">
								<label class="input-group-addon">Vị trí đặt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="vitridat" placeholder="Vị trí đặt" readonly>
							</div>
							<br>
							<div class="input-group">
								<label class="input-group-addon">Tổng tiền&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
								<input type="text" class="form-control" name="tongtien" placeholder="Tổng tiền" readonly>
							</div>
						</div>
						<div class="col-lg-3"></div>
					</form>
				</div>
				<div class="modal-footer">
					<button class="btn btn-danger" data-dismiss="modal">Đóng</button>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < 2; i++) {
            option[i].classList.remove('selected');
        }
        option[1].classList.add('selected');
		var date = new Date; 
		document.forms['searchchuyenxe']['ngaydi'].min = date.getFullYear()+'-'+((date.getMonth()+1)<10? ('0'+(date.getMonth()+1)):(date.getMonth()+1))+'-'+(date.getDate()<10? ('0'+date.getDate()):date.getDate());
		document.forms['searchchuyenxe']['ngaydi'].defaultValue = date.getFullYear()+'-'+((date.getMonth()+1)<10? ('0'+(date.getMonth()+1)):(date.getMonth()+1))+'-'+(date.getDate()<10? ('0'+date.getDate()):date.getDate());
		document.forms['searchchuyenxe']['ngaydi'].onblur = function(){
			if(this.value != "")
			{
				if(Date.parse(this.value) < Date.parse(this.min))
				{
					$("#modalalert .modal-body").html("Ngày đi không được nhỏ hơn ngày hiện tại!");
					$("#modalalert").modal("show");
					date = new Date; 
					this.min = date.getFullYear()+'-'+((date.getMonth()+1)<10? ('0'+(date.getMonth()+1)):(date.getMonth()+1))+'-'+(date.getDate()<10? ('0'+date.getDate()):date.getDate());
					this.defaultValue = date.getFullYear()+'-'+((date.getMonth()+1)<10? ('0'+(date.getMonth()+1)):(date.getMonth()+1))+'-'+(date.getDate()<10? ('0'+date.getDate()):date.getDate());
					this.value = this.defaultValue;
				}
			}
		};
		document.getElementById('timchuyenxe').onclick = function(){
			var noidi = document.forms['searchchuyenxe']['noidi'];
			var noiden = document.forms['searchchuyenxe']['noiden'];
			var ngaydi = document.forms['searchchuyenxe']['ngaydi'];
			var loaighe = document.forms['searchchuyenxe']['loaighe'];
			var diadiem = document.getElementById('diadiem').getElementsByTagName('option');
			noidi.style.borderColor = '#ccc';
			noiden.style.borderColor = '#ccc';
			ngaydi.style.borderColor = '#ccc';
			if(noidi.value == ''||noiden.value == ''||ngaydi.value == '')
			{
				if(noidi.value == '')
				{
					noidi.style.borderColor = 'red';
				}
				if(noiden.value == '')
				{
					noiden.style.borderColor = 'red';
				}
				if(ngaydi.value == '')
				{
					ngaydi.style.borderColor = 'red';
				}
				$("#modalalert .modal-body").html("Dữ liệu không đủ!");
				$("#modalalert").modal("show");
			}
			else
			{
				var checknoidi = 0;
				var checknoiden = 0;
				for(var i=0;i<diadiem.length;i++)
				{
					if(noidi.value == diadiem[i].value)
					{
						checknoidi = 1;
					}					
					if(noiden.value == diadiem[i].value)
					{
						checknoiden = 1;
					}
				}
				if(checknoidi == 0&&checknoiden == 0)
				{
					$("#modalalert .modal-body").html("Nơi đi và Nơi đến không đúng!");
					$("#modalalert").modal("show");
					noidi.style.borderColor = 'red';
					noiden.style.borderColor = 'red';
				}
				else if(checknoidi == 0)
				{
					$("#modalalert .modal-body").html("Nơi đi không đúng!");
					$("#modalalert").modal("show");
					noidi.style.borderColor = 'red';
				}
				else if(checknoiden == 0)
				{
					$("#modalalert .modal-body").html("Nơi đến không đúng!");
					$("#modalalert").modal("show");
					noiden.style.borderColor = 'red';
				}
				else if(noidi.value == noiden.value)
				{
					$("#modalalert .modal-body").html("Nơi đi và Nơi đến không được trùng!");
					$("#modalalert").modal("show");
				}
				else
				{
					var searchresult = document.getElementsByClassName('searchresult')[0].getElementsByClassName('loading')[0];
					searchresult.classList.add('show'); 
					$.ajax({
						url: '{{route("qldv-searchroute")}}',
						type: 'post',
						data: {
							_token: '{{csrf_token()}}',
							noidi: noidi.value,
							noiden: noiden.value,
							ngaydi: ngaydi.value,
							loaighe: loaighe.value
						},
						success: function(data){
							if(data.kq == 1&&data.data.length != 0)
							{
								searchresult.nextElementSibling.innerHTML = '';
								var sothutu = 1;
								for(var i=0;i<data.data.length;i++)
								{
									searchresult.nextElementSibling.innerHTML += '<li data-ma=\"'+data.data[i].Mã+'\" data-noidi=\"'+data.data[i].Nơi_đi+'\" data-noiden=\"'+data.data[i].Nơi_đến+'\" data-thoigiandi=\"'+(data.data[i].Ngày_xuất_phát+' '+data.data[i].Giờ_xuất_phát)+'\" data-thoigianden=\"'+(data.data[i].Ngày_đến+' '+data.data[i].Giờ_đến)+'\" onclick=\"showChuyenXe(this)\">#'+data.data[i].Mã+' Chuyến xe '+data.data[i].Giờ_xuất_phát+' - '+data.data[i].Giờ_đến+'<i class="glyphicon glyphicon-ban-circle" style="color: gray;"></i></li>'
									sothutu++;
								}
								searchresult.classList.remove('show'); 
							}
							else if(data.kq == 1&&data.data.length == 0)
							{
								searchresult.nextElementSibling.innerHTML = 'Không tìm thấy chuyến xe nào...';
								searchresult.classList.remove('show'); 
							}
						},
						timeout: 10000,
						error: function(xhr){
							if(xhr.statusText == 'timeout')
							{
								searchresult.nextElementSibling.innerHTML = "Quá lâu để phản hồi...<br><i class='btn btn-danger' onclick='document.getElementById(\"timchuyenxe\").click();'>Thử lại</i>";
								searchresult.classList.remove('show'); 
							}
						}
					});
				}
			}
		};
		function showChuyenXe(ev)
		{
			var noidi = document.forms['ttchuyenxe']['noidi'];
			var noiden = document.forms['ttchuyenxe']['noiden'];
			var thoigiandi = document.forms['ttchuyenxe']['thoigiandi'];
			var thoigianden = document.forms['ttchuyenxe']['thoigianden'];
			var chuyenxe = document.getElementById('modaldatve').getElementsByClassName('modal-title')[0];
			var sodoxe = document.getElementById('modaldatve').getElementsByClassName('sodoxe')[0];
			var loading = document.getElementById('modaldatve').getElementsByClassName('loading')[0];
			noidi.value = ev.getAttribute('data-noidi');
			noiden.value = ev.getAttribute('data-noiden');
			thoigiandi.value = ev.getAttribute('data-thoigiandi');
			thoigianden.value = ev.getAttribute('data-thoigianden');
			chuyenxe.innerHTML = "Chuyến Xe #"+ev.getAttribute('data-ma');
			document.forms['ttchuyenxe']['idchuyenxe'].value = ev.getAttribute('data-ma');
			loading.classList.add('show');
			$.ajax({
				url: '{{route("qldv-routedetails")}}',
				type: 'post',
				data: {
					_token: '{{csrf_token()}}',
					idchuyenxe: ev.getAttribute('data-ma')
				},
				success: function(data){
					if(data.kq == 1)
					{
						idnhanvien = "{{session('qldv.id')}}"; //Sẽ thay thế bằng session mã nhân viên đặt vé
						str = "";
						vedachon = "";
						sohang = data.loaixe[0].Số_hàng;
						socot = data.loaixe[0].Số_cột;
						loaighe = data.loaixe[0].Loại_ghế;
						sodo = data.loaixe[0].Sơ_đồ;
						ve = data.ve;
						demve = 0;
						if(loaighe == 0)
						{
							str+="<table class='sodoghe'>";
							str+="<tr><th>Tài xế</th><th colspan='"+(socot - 1)+"'></th></tr>";
							for(var i=1;i<sohang;i++)
							{
								str+="<tr>"
								for(var j=0;j<socot;j++)
								{
									if(sodo[i*socot + j] == 1)
									{
										if(ve[demve].Trạng_thái == 0)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											// title += "Ngày sinh: "+(ve[demve].Ngày_sinh==null? 'Chưa có':ve[demve].Ngày_sinh)+"<br>";
											// title += "Giới tính: "+(ve[demve].Giới_tính==null? 'Chưa có':(ve[demve].Giới_tính==0? 'Không xác định':(ve[demve].Giới_tính==1? 'Nam':'Nữ')))+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";							
											str+="<td class='vecontrong' onclick='chonve(this)' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 1)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedadat' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng != null)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";		
											str+="<td class='vedanggiu' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i onload='showTime(this)'>"+ve[demve].Thời_gian_còn+"</i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng == null&&ve[demve].Mã_nhân_viên_đặt == idnhanvien)
										{
											vedachon += (ve[demve].Vị_trí_ghế+",");
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";		
											str+="<td class='vecontrong vedangchon' onclick='chonve(this)' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng == null&&ve[demve].Mã_nhân_viên_đặt != idnhanvien)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedadat' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										demve++;
									}
									else if(sodo[i*socot + j] == 0)
									{
										str+="<td></td>";
									}
								}
								str+="</tr>"
							}
							str+="</table>";
						}
						else if(loaighe == 1)
						{
							str+="<table class='sodogiuong tangduoi'>";
							str+="<caption>Tầng Dưới</caption>";
							str+="<tr><th>Tài xế</th><th colspan='"+(socot - 1)+"'></th></tr>";
							for(var i=1;i<(sohang + 1)/2;i++)
							{
								str+="<tr>"
								for(var j=0;j<socot;j++)
								{
									if(sodo[i*socot + j] == 1)
									{
										if(ve[demve].Trạng_thái == 0)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vecontrong' onclick='chonve(this)' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 1)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedadat' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng != null)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedanggiu' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i onload='showTime(this)'>"+ve[demve].Thời_gian_còn+"</i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng == null&&ve[demve].Mã_nhân_viên_đặt == idnhanvien)
										{
											vedachon += (ve[demve].Vị_trí_ghế+",");
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vecontrong vedangchon' onclick='chonve(this)' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng == null&&ve[demve].Mã_nhân_viên_đặt != idnhanvien)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedadat' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										demve++;
									}
									else if(sodo[i*socot + j] == 0)
									{
										str+="<td></td>";
									}
								}
								str+="</tr>"
							}
							str+="</table>";
							str+="<table class='sodogiuong tangtren'>";
							str+="<caption>Tầng Trên</caption>";
							str+="<tr><th>Tài xế</th><th colspan='"+(socot - 1)+"'></th></tr>";
							for(var i=(sohang + 1)/2;i<sohang;i++)
							{
								str+="<tr>"
								for(var j=0;j<socot;j++)
								{
									if(sodo[i*socot + j] == 1)
									{
										if(ve[demve].Trạng_thái == 0)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vecontrong' onclick='chonve(this)' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 1)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedadat' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng != null)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedanggiu' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i onload='showTime(this)'>"+ve[demve].Thời_gian_còn+"</i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng == null&&ve[demve].Mã_nhân_viên_đặt == idnhanvien)
										{
											vedachon += (ve[demve].Vị_trí_ghế+",");
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vecontrong vedangchon' onclick='chonve(this)' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										else if(ve[demve].Trạng_thái == 2&&ve[demve].Mã_khách_hàng == null&&ve[demve].Mã_nhân_viên_đặt != idnhanvien)
										{
											var title = "Vị trí ghế: "+ve[demve].Vị_trí_ghế+"<br>";
											title += "Khách đặt: "+(ve[demve].Tên==null? 'Chưa có':ve[demve].Tên)+"<br>";
											title += "Số điện thoại: "+(ve[demve].Sđt==null? 'Chưa có':ve[demve].Sđt)+"<br>";				
											title += "Nhân viên đặt: "+(ve[demve].Họ_Tên==null? 'Chưa có':ve[demve].Họ_Tên)+"<br>";	
											str+="<td class='vedadat' data-mave='"+ve[demve].Mã+"' data-vitri='"+ve[demve].Vị_trí_ghế+"'>"+ve[demve].Vị_trí_ghế+"<br><i style='display: none;'></i><div class='tooltip-info'>"+title+"</div></td>";
										}
										demve++;
									}
									else if(sodo[i*socot + j] == 0)
									{
										str+="<td></td>";
									}
								}
								str+="</tr>"
							}
							str+="</table>";
						}
						sodoxe.getElementsByTagName('div')[1].innerHTML = str;
						document.forms['ttchuyenxe']['vechon'].value = vedachon;
						$('i[onload]').trigger('onload');
					}
					else if(data.kq == 0)
					{
						$("#modalalert .modal-body").html("Thất bại");
						$("#modalalert").modal("show");
					}
					loading.classList.remove('show');
				},
				timeout: 10000,
				error: function(xhr){
					if(xhr.statusText == 'timeout')
					{
						sodoxe.getElementsByTagName('div')[1].innerHTML = "Lỗi Time Out...";
					}
					loading.classList.remove('show');
				}
			});
			$('#modaldatve').modal({backdrop: "static"});
		}
		document.getElementById('timkh').onclick = function(){ //Bắt sự kiện tìm kiếm khách hàng
			var searchkh = document.forms['timkhachhang']['searchkh'];
			var kqtimkh = document.getElementsByClassName('kqtimkh')[0];
			var loading = kqtimkh.getElementsByClassName('loading')[0];
			searchkh.style.borderColor = "#ccc";
			if(searchkh.value == '')
			{
				$("#modalalert .modal-body").html("Dữ liệu chưa nhập!");
				$("#modalalert").modal("show");
				searchkh.style.borderColor = 'red';
			}
			else
			{
				loading.classList.add('show');
				var filter;
				if(document.getElementById("kh-filter0").checked == true||(document.getElementById("kh-filter1").checked == true&&document.getElementById("kh-filter2").checked == true))
				{
					filter = 0;
				}
				else if(document.getElementById("kh-filter1").checked == true)
				{
					filter = 1;
				}
				else if(document.getElementById("kh-filter2").checked == true)
				{
					filter = 2;
				}
				$.ajax({
					url: '{{route("qldv-searchcustomer")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						filtermode: filter,
						datasearch: searchkh.value
					},
					success: function(data){
						if(data.kq == 1)
						{
							var str = "";
							if(data.data.length == 0)
							{
								str += "Không tìm thấy khách hàng phù hợp..."
							}
							else
							{
								for(var i=0;i<data.data.length;i++)
								{
									str += "<li data-ma='"+data.data[i].Mã+"' onclick='showInfoKH(this)'>"+data.data[i].Tên+" - "+data.data[i].Sđt+" <span onclick='chooseKH(event,this,"+data.data[i].Mã+")' class='glyphicon glyphicon-plus'></span></li>";
								}
							}
							kqtimkh.getElementsByTagName("ul")[0].innerHTML = str;
							loading.classList.remove("show");
						}
					},
					timeout: 10000,
					error: function(xhr){
						
					}
				});
			}
		};
		document.forms['timkhachhang'].onsubmit = function(ev){
			ev.preventDefault();
			document.getElementById('timkh').click();
		};
		function showInfoKH(ev)
		{
			var loading = document.getElementById("modalttkhachhang").getElementsByClassName("loading")[0];
			var idkhachhang = ev.getAttribute("data-ma");
			var id = document.forms["frm-ttkhachhang"]["id"];
			var name = document.forms["frm-ttkhachhang"]["name"];
			var brtday = document.forms["frm-ttkhachhang"]["brtday"];
			var hd_brtday = document.forms["frm-ttkhachhang"]["hd-brtday"];
			var gender = document.forms["frm-ttkhachhang"]["gender"];
			var phone = document.forms["frm-ttkhachhang"]["phone"];
			var email = document.forms["frm-ttkhachhang"]["email"];
			var address = document.forms["frm-ttkhachhang"]["address"];
			loading.classList.add("show");
			$.ajax({
				url: '{{route("qldv-infokh")}}',
				type: 'post',
				data: {
					_token: '{{csrf_token()}}',
					idkhachhang: idkhachhang
				},
				success: function(data){
					if(data.kq == 1)
					{
						id.value = data.data[0].Mã;
						name.value = data.data[0].Tên;
						brtday.value = data.data[0].Ngày_sinh_hiển_thị;
						hd_brtday.value = data.data[0].Ngày_sinh;
						gender.value = data.data[0].Giới_tính==0? "Không xác định":(data.data[0].Giới_tính==1? "Nam":"Nữ");
						phone.value = data.data[0].Sđt;
						address.value = data.data[0].Địa_chỉ!=null&&data.data[0].Địa_chỉ!=""? data.data[0].Địa_chỉ:"Chưa có thông tin";
						email.value = data.data[0].Email!=null&&data.data[0].Email!=""? data.data[0].Email:"Chưa có thông tin";
						loading.classList.remove("show");
					}
				},
				timeout: 10000,
				error: function(xhr){
					
				}
			});
			$("#modalttkhachhang").modal("show");
		}
		function chooseKH(ev,target,id)
		{
			ev.stopPropagation();
			var idkhachhang = document.forms["frm-ttdatve"]["idkhachhang"];
			var hoten = document.forms["frm-ttdatve"]["hoten"];
			var sodienthoai = document.forms["frm-ttdatve"]["sodienthoai"];
			var gioitinh = document.forms["frm-ttdatve"]["gender"];
			var ngaysinh = document.forms["frm-ttdatve"]["brtday"];
			if(target.classList.contains("uncheckkh"))
			{
				idkhachhang.value = "";
				hoten.value = "";
				sodienthoai.value = "";
				gioitinh.value = 0;
				ngaysinh.value = "";
				hoten.removeAttribute("readonly");
				sodienthoai.removeAttribute("readonly");
				gioitinh.removeAttribute("disabled");
				ngaysinh.removeAttribute("readonly");
				target.classList.remove("uncheckkh");
			}
			else
			{
				var children = target.parentNode.parentNode.getElementsByTagName("span");
				for(var i=0;i<children.length;i++)
				{
					children[i].classList.remove("uncheckkh");
				}
				$.ajax({
					url: '{{route("qldv-infokh")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						idkhachhang: id
					},
					success: function(data){
						if(data.kq == 1)
						{
							idkhachhang.value = data.data[0].Mã;
							hoten.value = data.data[0].Tên;
							sodienthoai.value = data.data[0].Sđt;
							gioitinh.value = data.data[0].Giới_tính;
							ngaysinh.value = data.data[0].Ngày_sinh;
							hoten.style.borderColor = "#ccc";
							sodienthoai.style.borderColor = "#ccc";
							gioitinh.style.borderColor = "#ccc";
							ngaysinh.style.borderColor = "#ccc";
							hoten.setAttribute("readonly","");
							sodienthoai.setAttribute("readonly","");
							gioitinh.setAttribute("disabled","");
							ngaysinh.setAttribute("readonly","");
							target.classList.add("uncheckkh");
						}
					},
					timeout: 10000,
					error: function(xhr){
				
					}
				});
			}			
		}
		function chooseKH_1()
		{
			var id = document.forms["frm-ttkhachhang"]["id"];
			var name = document.forms["frm-ttkhachhang"]["name"];
			var brtday = document.forms["frm-ttkhachhang"]["brtday"];
			var hd_brtday = document.forms["frm-ttkhachhang"]["hd-brtday"];
			var gender = document.forms["frm-ttkhachhang"]["gender"];
			var phone = document.forms["frm-ttkhachhang"]["phone"];
			var email = document.forms["frm-ttkhachhang"]["email"];
			var address = document.forms["frm-ttkhachhang"]["address"];
			var idkhachhang = document.forms["frm-ttdatve"]["idkhachhang"];
			var hoten = document.forms["frm-ttdatve"]["hoten"];
			var sodienthoai = document.forms["frm-ttdatve"]["sodienthoai"];
			var gioitinh = document.forms["frm-ttdatve"]["gender"];
			var ngaysinh = document.forms["frm-ttdatve"]["brtday"];
			idkhachhang.value = id.value;
			hoten.value = name.value;
			sodienthoai.value = phone.value;
			gioitinh.value = gender.value=="Không xác định"? 0:(gender.value=="Nam"? 1:2);
			ngaysinh.value = hd_brtday.value;
			hoten.style.borderColor = "#ccc";
			sodienthoai.style.borderColor = "#ccc";
			gioitinh.style.borderColor = "#ccc";
			ngaysinh.style.borderColor = "#ccc";
			hoten.setAttribute("readonly","");
			sodienthoai.setAttribute("readonly","");
			gioitinh.setAttribute("disabled","");
			ngaysinh.setAttribute("readonly","");
			var children = document.getElementsByClassName("kqtimkh")[0].getElementsByTagName("li");
			for(var i=0;i<children.length;i++)
			{
				if(children[i].getAttribute("data-ma") == id.value)
				{
					children[i].getElementsByTagName("span")[0].classList.add("uncheckkh");
					continue;
				}
				children[i].getElementsByTagName("span")[0].classList.remove("uncheckkh");
			}
			$("#modalttkhachhang").modal("hide");
		}
		function chonve(ev)
		{
			if(ev.classList.contains("vedangchon"))
			{
				$.ajax({
					url: '{{route("qldv-huychonve")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						idnhanvien: "{{session('qldv.id')}}", //Sẽ thay thế bằng session mã nhân viên đặt vé
						idve: ev.getAttribute("data-mave")
					},
					success: function(data){
						if(data.kq == 1)
						{
							var title = "Vị trí ghế: "+data.ttghe[0].Vị_trí_ghế+"<br>";
							title += "Khách đặt: "+(data.ttghe[0].Tên==null? 'Chưa có':data.ttghe[0].Tên)+"<br>";
							title += "Số điện thoại: "+(data.ttghe[0].Sđt==null? 'Chưa có':data.ttghe[0].Sđt)+"<br>";				
							title += "Nhân viên đặt: "+(data.ttghe[0].Họ_Tên==null? 'Chưa có':data.ttghe[0].Họ_Tên)+"<br>";	
							ev.getElementsByClassName("tooltip-info")[0].innerHTML = title;
							ev.classList.remove("vedangchon");
							document.forms["ttchuyenxe"]["vechon"].value = document.forms["ttchuyenxe"]["vechon"].value.replace(ev.getAttribute("data-vitri")+",","");
						}
					},
					timeout: 10000,
					error: function(xhr){
					
					}
				});
			}
			else if(ev.classList.contains("vecontrong"))
			{
				ev.classList.add("vedangchon");
				$.ajax({
					url: '{{route("qldv-chonve")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						idnhanvien: "{{session('qldv.id')}}", //Sẽ thay thế bằng session mã nhân viên đặt
						idve: ev.getAttribute("data-mave")
					},
					success: function(data){
						if(data.kq == 0)
						{
							ev.classList.remove("vedangchon");
						}
						else if(data.kq == 1)
						{
							var title = "Vị trí ghế: "+data.ttghe[0].Vị_trí_ghế+"<br>";
							title += "Khách đặt: "+(data.ttghe[0].Tên==null? 'Chưa có':data.ttghe[0].Tên)+"<br>";
							title += "Số điện thoại: "+(data.ttghe[0].Sđt==null? 'Chưa có':data.ttghe[0].Sđt)+"<br>";				
							title += "Nhân viên đặt: "+(data.ttghe[0].Họ_Tên==null? 'Chưa có':data.ttghe[0].Họ_Tên)+"<br>";	
							ev.getElementsByClassName("tooltip-info")[0].innerHTML = title;
							ev.classList.remove("vecontrong");
							ev.classList.remove("vedangchon");
							ev.classList.add("vedadat");
							$("#modalalert .modal-body").html("Vé không sẵn có!");
							$("#modalalert").modal("show");
						}
						else if(data.kq == 2)
						{
							var title = "Vị trí ghế: "+data.ttghe[0].Vị_trí_ghế+"<br>";
							title += "Khách đặt: "+(data.ttghe[0].Tên==null? 'Chưa có':data.ttghe[0].Tên)+"<br>";
							title += "Số điện thoại: "+(data.ttghe[0].Sđt==null? 'Chưa có':data.ttghe[0].Sđt)+"<br>";				
							title += "Nhân viên đặt: "+(data.ttghe[0].Họ_Tên==null? 'Chưa có':data.ttghe[0].Họ_Tên)+"<br>";	
							ev.getElementsByClassName("tooltip-info")[0].innerHTML = title;
							ev.getElementsByTagName("i")[0].innerHTML = data.ttghe[0].Thời_gian_còn;
							ev.getElementsByTagName("i")[0].setAttribute("onload","showTime(this)");
							ev.getElementsByTagName("i")[0].style.display = "block";
							$(ev).find("i").trigger("onload");
							ev.classList.remove("vecontrong");
							ev.classList.remove("vedangchon");
							ev.classList.add("vedanggiu");
							$("#modalalert .modal-body").html("Vé không sẵn có!");
							$("#modalalert").modal("show");
						}
						else if(data.kq == 3)
						{
							var title = "Vị trí ghế: "+data.ttghe[0].Vị_trí_ghế+"<br>";
							title += "Khách đặt: "+(data.ttghe[0].Tên==null? 'Chưa có':data.ttghe[0].Tên)+"<br>";
							title += "Số điện thoại: "+(data.ttghe[0].Sđt==null? 'Chưa có':data.ttghe[0].Sđt)+"<br>";				
							title += "Nhân viên đặt: "+(data.ttghe[0].Họ_Tên==null? 'Chưa có':data.ttghe[0].Họ_Tên)+"<br>";	
							ev.getElementsByClassName("tooltip-info")[0].innerHTML = title;
							ev.classList.remove("vecontrong");
							ev.classList.remove("vedangchon");
							ev.classList.add("vedadat");
							$("#modalalert .modal-body").html("Vé không sẵn có!");
							$("#modalalert").modal("show");
						}
						else if(data.kq == 4)
						{
							var title = "Vị trí ghế: "+data.ttghe[0].Vị_trí_ghế+"<br>";
							title += "Khách đặt: "+(data.ttghe[0].Tên==null? 'Chưa có':data.ttghe[0].Tên)+"<br>";
							title += "Số điện thoại: "+(data.ttghe[0].Sđt==null? 'Chưa có':data.ttghe[0].Sđt)+"<br>";				
							title += "Nhân viên đặt: "+(data.ttghe[0].Họ_Tên==null? 'Chưa có':data.ttghe[0].Họ_Tên)+"<br>";	
							ev.getElementsByClassName("tooltip-info")[0].innerHTML = title;
							document.forms["ttchuyenxe"]["vechon"].value+=(ev.getAttribute("data-vitri")+",");
						}
					},
					timeout: 10000,
					error: function(xhr){
					
					}
				});
			}
		}
		function showTime(ev) //Hàm chạy đồng hồ đếm ngược
		{
			if(ev.innerHTML.valueOf() > 0)
			{
				ev.innerHTML = ev.innerHTML.valueOf() - 1;
				setTimeout(showTime,1000,ev);
			}
			else if(ev.innerHTML.valueOf() == 0)
			{
				ev.style.display = "none";
				ev.parentNode.classList.remove("vedanggiu");
				ev.parentNode.classList.add("vecontrong");
			}
		}
		document.getElementById("chonchuyenxe").onclick = function(ev){
			var idchuyenxe = document.forms["frm-ttdatve"]["idchuyenxe"];
			// var idkhachhang = document.forms["frm-ttdatve"]["idkhachhang"];
			// var idnhanvien = document.forms["frm-ttdatve"]["idnhanvien"];
			// var hoten = document.forms["frm-ttdatve"]["hoten"];
			// var sodienthoai = document.forms["frm-ttdatve"]["sodienthoai"];
			// var gioitinh = document.forms["frm-ttdatve"]["gender"];
			// var ngaysinh = document.forms["frm-ttdatve"]["brtday"];
			var vedachon = document.forms["frm-ttdatve"]["vedachon"];
			var noidi = document.forms["frm-ttdatve"]["noidi"];
			var noiden = document.forms["frm-ttdatve"]["noiden"];
			if(document.forms['ttchuyenxe']['vechon'].value=="")
			{
				$("#modalalert .modal-body").html("Chưa chọn vé!");
				$("#modalalert").modal("show");
			}
			else if(idchuyenxe.value != ""&&idchuyenxe.value != document.forms['ttchuyenxe']['idchuyenxe'].value)
			{
				$("#modalalert .modal-body").html("Đang chọn chuyến xe khác. Hãy hủy chuyến cũ để chọn lại!");
				$("#modalalert").modal("show");
			}
			else
			{
				idchuyenxe.value = document.forms['ttchuyenxe']['idchuyenxe'].value;
				vedachon.value = document.forms['ttchuyenxe']['vechon'].value;
				noidi.value = document.forms['ttchuyenxe']['noidi'].value;
				noiden.value = document.forms['ttchuyenxe']['noiden'].value;
				$("#modaldatve").modal("hide");
				document.forms["frm-ttdatve"].previousElementSibling.getElementsByTagName("i")[0].innerHTML = " - Chuyến xe #"+idchuyenxe.value;
				var results = document.getElementsByClassName("searchresult")[0].getElementsByTagName("li");
				for(var i=0;i<results.length;i++)
				{
					if(results[i].getAttribute("data-ma") == idchuyenxe.value)
					{
						results[i].getElementsByTagName("i")[0].style.color = "green";
						continue;
					}
					results[i].getElementsByTagName("i")[0].style.color = "gray";
				}
			}
		};
		document.getElementById("huychonchuyenxe").onclick = function(ev){
			var idchuyenxe = document.forms['ttchuyenxe']['idchuyenxe'].value;
			var vedachon = document.forms['ttchuyenxe']['vechon'].value!=""? document.forms['ttchuyenxe']['vechon'].value.slice(0,document.forms['ttchuyenxe']['vechon'].value.length - 1).split(","):null;
			var ttdatve_idchuyenxe = document.forms["frm-ttdatve"]["idchuyenxe"];
			var ttdatve_vedachon = document.forms["frm-ttdatve"]["vedachon"];
			var ttdatve_noidi = document.forms["frm-ttdatve"]["noidi"];
			var ttdatve_noiden = document.forms["frm-ttdatve"]["noiden"];
			// alert(vedachon.length+" "+vedachon);
			if(vedachon != null)
			{
				$.ajax({
					url: '{{route("qldv-huychonchuyenxe")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						idnhanvien: "{{session('qldv.id')}}", //Sẽ thay thế bằng session mã nhân viên đặt vé
						idchuyenxe: idchuyenxe,
						vitrive: vedachon
					},
					success: function(data){
						if(data.kq == 1)
						{
							if(ttdatve_idchuyenxe.value == idchuyenxe)
							{
								ttdatve_idchuyenxe.value = "";
								ttdatve_vedachon.value = "";
								ttdatve_noidi.value = "";
								ttdatve_noiden.value = "";
							}
							document.forms["frm-ttdatve"].previousElementSibling.getElementsByTagName("i")[0].innerHTML = "";
							var results = document.getElementsByClassName("searchresult")[0].getElementsByTagName("li");
							for(var i=0;i<results.length;i++)
							{
								if(results[i].getAttribute("data-ma") == idchuyenxe)
								{
									results[i].getElementsByTagName("i")[0].style.color = "gray";
									break;
								}
							}
							$("#modalalert .modal-body").html("Đã hủy thành công!");
							$("#modalalert").modal("show");
							$("#modaldatve").modal("hide");
						}
					},
					timeout: 10000,
					error: function(xhr){
					
					}
				});
			}
			else
			{
				$("#modaldatve").modal("hide");
			}
		};
		function registerAccount()
		{
			var idkhachhang = document.forms["frm-ttdatve"]["idkhachhang"];
			var idnhanvien = document.forms["frm-ttdatve"]["idnhanvien"];
			var hoten = document.forms["frm-ttdatve"]["hoten"];
			var sodienthoai = document.forms["frm-ttdatve"]["sodienthoai"];
			var gioitinh = document.forms["frm-ttdatve"]["gender"];
			var ngaysinh = document.forms["frm-ttdatve"]["brtday"];
			if(idkhachhang.value != "")
			{
				$("#modalalert .modal-body").html("Đã có tài khoản được chọn!");
				$("#modalalert").modal("show");
			}
			else if(idkhachhang.value == "")
			{
				hoten.style.borderColor = "#ccc";
				sodienthoai.style.borderColor = "#ccc";
				gioitinh.style.borderColor = "#ccc";
				ngaysinh.style.borderColor = "#ccc";
				if(hoten.value == ""||sodienthoai.value == ""||gioitinh.value == 0||ngaysinh.value == "")
				{
					hoten.style.borderColor = hoten.value==""? "red":hoten.style.borderColor;
					sodienthoai.style.borderColor = sodienthoai.value==""? "red":sodienthoai.style.borderColor;
					gioitinh.style.borderColor = gioitinh.value==0? "red":gioitinh.style.borderColor;
					ngaysinh.style.borderColor = ngaysinh.value==""? "red":ngaysinh.style.borderColor;
					$("#modalalert .modal-body").html("Dữ liệu để đăng ký không đầy đủ!");
					$("#modalalert").modal("show");
				}
				else
				{
					var format_phone = /^(0[3578]|09)[0-9]{8}$/;
					var format_name = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
					if(sodienthoai.value.search(format_phone) == -1&&hoten.value.search(format_name) == -1)
					{
						hoten.style.borderColor = "red";
						sodienthoai.style.borderColor = "red";
						$("#modalalert .modal-body").html("Dữ liệu Di động và Họ tên không đúng định dạng!");
						$("#modalalert").modal("show");
					}
					else if(sodienthoai.value.search(format_phone) == -1)
					{
						sodienthoai.style.borderColor = "red";
						$("#modalalert .modal-body").html("Dữ liệu Di động không đúng định dạng!");
						$("#modalalert").modal("show");
					}
					else if(hoten.value.search(format_name) == -1)
					{
						hoten.style.borderColor = "red";
						$("#modalalert .modal-body").html("Dữ liệu Họ tên không đúng định dạng!");
						$("#modalalert").modal("show");
					}
					else
					{
						$.ajax({
							url: '{{route("qldv-dangky")}}',
							type: 'POST',
							data: {
								_token: '{{csrf_token()}}',
								SDT: sodienthoai.value,
								MK: sodienthoai.value,
								NGAYSINH: ngaysinh.value,
								GT: gioitinh.value,
								NAME: hoten.value
							},
							success: function (data) {
								if(data.kq==0)
								{
									sodienthoai.style.borderColor = "red";
									$("#modalalert .modal-body").html("Dữ liệu Di động đã tồn tại. Hãy dùng dữ liệu khác!");
									$("#modalalert").modal("show");
								}
								else if(data.kq==1)
								{
									idkhachhang.value = data.id;
									hoten.setAttribute("readonly","");
									sodienthoai.setAttribute("readonly","");
									gioitinh.setAttribute("disabled","");
									ngaysinh.setAttribute("readonly","");
									$("#modalalert .modal-body").html("Đã đăng ký thành công với mật khẩu tạm thời là số điện thoại!");
									$("#modalalert").modal("show");
								}                       
							}
						});
					}
				}
			}
		}
		function bookTicket()
		{
			var idchuyenxe = document.forms["frm-ttdatve"]["idchuyenxe"];
			var idkhachhang = document.forms["frm-ttdatve"]["idkhachhang"];
			var idnhanvien = document.forms["frm-ttdatve"]["idnhanvien"];
			var hoten = document.forms["frm-ttdatve"]["hoten"];
			var sodienthoai = document.forms["frm-ttdatve"]["sodienthoai"];
			var gioitinh = document.forms["frm-ttdatve"]["gender"];
			var ngaysinh = document.forms["frm-ttdatve"]["brtday"];
			var vedachon = document.forms["frm-ttdatve"]["vedachon"];
			var noidi = document.forms["frm-ttdadat"]["noidi"];
			var noiden = document.forms["frm-ttdadat"]["noiden"];
			var thoigiandi = document.forms["frm-ttdadat"]["thoigiandi"];
			var thoigianden = document.forms["frm-ttdadat"]["thoigianden"];
			if(idchuyenxe.value == ""||idkhachhang.value == "")
			{
				$("#modalalert .modal-body").html("Chưa chọn chuyến xe hoặc khách hàng!");
				$("#modalalert").modal("show");
			}
			else
			{
				$.ajax({
					url: '{{route("qldv-datve")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						idnhanvien: idnhanvien.value,
						idkhachhang: idkhachhang.value,
						idchuyenxe: idchuyenxe.value,
						vedachon: vedachon.value!=""? vedachon.value.slice(0,vedachon.value.length - 1).split(","):null
					},
					success: function(data){
						if(data.kq == 1)
						{
							var display = document.getElementById("modaldadat").getElementsByClassName("modal-body")[0].getElementsByTagName("ul")[0];
							var str = "";
							for(var i=0;i<data.data.length;i++)
							{
								str += " <li data-ma='"+data.data[i].Mã+"'>Vé #"+data.data[i].Mã+" - "+data.data[i].Vị_trí_ghế+"/ "+data.data[i].Tiền_vé+"đ</li>";
							}
							display.innerHTML = str;
							noidi.value = data.ttchuyenxe[0].Nơi_đi;
							noiden.value = data.ttchuyenxe[0].Nơi_đến;
							thoigiandi.value = data.ttchuyenxe[0].Ngày_đi;
							thoigianden.value = data.ttchuyenxe[0].Ngày_đến;
							document.getElementById("modaldadat").getElementsByClassName("modal-footer")[0].getElementsByTagName("input")[0].style.textAlign = "right";
							document.getElementById("modaldadat").getElementsByClassName("modal-footer")[0].getElementsByTagName("input")[0].value = data.tongtien + "đ";
							document.forms["frm-ttdatve"].reset();
							idchuyenxe.value = "";
							idkhachhang.value = "";
							hoten.removeAttribute("readonly");
							sodienthoai.removeAttribute("readonly");
							gioitinh.removeAttribute("disabled");
							ngaysinh.removeAttribute("readonly");
							document.forms["frm-ttdatve"].previousElementSibling.getElementsByTagName("i")[0].innerHTML = "";
							$("#modaldadat").modal({backdrop: "static"});
						}
						else
						{
							$("#modalalert .modal-body").html("Đặt vé thất bại!");
							$("#modalalert").modal("show");
						}
					}
				});
			}
		}
		function showHistory()
		{
			var idnhanvien = '{{session("qldv.id")}}';
			$.ajax({
				url: '{{route("qldv_showhistory")}}',
				type: 'post',
				data: {
					_token: '{{csrf_token()}}',
					data: idnhanvien
				},
				success: function(data){
					if(data.kq == 1)
					{
						var str = '';
						var isfirst = true;
						for(var i=0;i<data.data.length;i++)
						{
							if(data.data[i].Trạng_thái == 0||data.data[i].Trạng_thái == 1 )
							{
								str += '<div class="panel-group">';
								str += '<div class="panel panel-default">';
								str += '<div class="panel-heading">';
								str += '<div class="panel-title" data-toggle="collapse" data-target="#collapse'+i+'">';
								str += '#'+(i+1)+' Đơn đặt vé <i>#'+data.data[i].Mã+'</i> Ngày <i>'+data.data[i].Ngày+'</i> Lúc <i>'+data.data[i].Giờ+'</i>';
								str += '</div>';
								str += '</div>';
								str += '<div class="panel-collapse collapse" id="collapse'+i+'">';
								str += '<div style="text-align: right; padding: .25em;">';	
								str += '<span class="btn btn-success" onclick="chitietDondatve('+data.data[i].Mã+')">';
								str += '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;Chi tiết</span>&nbsp;';
								str += '<span class="btn btn-danger" onclick="huyDondatve('+data.data[i].Mã+')"><i class="glyphicon glyphicon-remove"></i>&nbsp;Hủy</span>';
								str += '</div>';
								str += '</div>';
								str += '</div>';
								str += '</div>';
							}
							else if(data.data[i].Trạng_thái == 2)
							{
								if(isfirst)
								{
									str += "<hr>";
									isfirst = false;
								}
								str += '<div class="panel-group">';
								str += '<div class="panel panel-default">';
								str += '<div class="panel-heading">';
								str += '<div class="panel-title" data-toggle="collapse" data-target="#collapse'+i+'">';
								str += '#'+(i+1)+' Đơn đặt vé <i>#'+data.data[i].Mã+'</i> Ngày <i>'+data.data[i].Ngày+'</i> Lúc <i>'+data.data[i].Giờ+'</i>';
								str += '<span class="badge">Đã Hủy</span></div>';
								str += '</div>';
								str += '<div class="panel-collapse collapse" id="collapse'+i+'">';
								str += '<div style="text-align: right; padding: .25em;">';	
								str += '<span class="btn btn-success" onclick="chitietDondatve('+data.data[i].Mã+')">';
								str += '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;Chi tiết</span>&nbsp;';
								str += '</div>';
								str += '</div>';
								str += '</div>';
								str += '</div>';
							}							
						}
						if(str == "")
						{
							str = "Chưa có đơn đặt vé nào!";
						}
						$("#modaldanhsachdondatve .modal-body").html(str);
						$("#modaldanhsachdondatve").modal("show");
					}
				},
				timeout: 10000,
				error: function(xhr){
					if(xhr.statusText == "timeout")
					{
						$("#modalalert .modal-body").html("Vui lòng kiểm tra kết nối!");
						$("#modalalert").modal("show");
					}
				}
			});
		}
		function chitietDondatve(id)
		{
			var machuyenxe = document.forms["frm-chitietdondatve"]["machuyenxe"];
			var noidi = document.forms["frm-chitietdondatve"]["noidi"];
			var noiden = document.forms["frm-chitietdondatve"]["noiden"];
			var thoigiandi = document.forms["frm-chitietdondatve"]["thoigiandi"];
			var thoigianden = document.forms["frm-chitietdondatve"]["thoigianden"];
			var vitridat = document.forms["frm-chitietdondatve"]["vitridat"];
			var tongtien = document.forms["frm-chitietdondatve"]["tongtien"];
			var makhachhang = document.forms["frm-chitietdondatve"]["makhachhang"];
			var name = document.forms["frm-chitietdondatve"]["name"];
			var brtday = document.forms["frm-chitietdondatve"]["brtday"];
			var gender = document.forms["frm-chitietdondatve"]["gender"];
			var phone = document.forms["frm-chitietdondatve"]["phone"];
			var loading = document.getElementById("modalchitietdondatve").getElementsByClassName("loading")[0];
			$("#modalchitietdondatve").modal();
			loading.classList.add("show");
			$.ajax({
				url: '{{route("qldv_showdetails")}}',
				type: 'post',
				data: {
					_token: '{{csrf_token()}}',
					data: id
				},
				success: function(data){
					if(data.kq == 1)
					{
						machuyenxe.value = data.data.Mã_chuyến_xe;
						noidi.value = data.data.Nơi_đi;
						noiden.value = data.data.Nơi_đến;
						thoigiandi.value = data.data.Ngày_đi;
						thoigianden.value = data.data.Ngày_đến;
						makhachhang.value = data.data.Mã_khách_hàng;
						name.value = data.data.Tên;
						brtday.value = data.data.Ngày_sinh;
						gender.value = data.data.Giới_tính==0? "Không xác định":(data.data.Giới_tính==1? "Nam":"Nữ");
						phone.value = data.data.Sđt;
						vitridat.value = data.data.Vị_trí_đặt;
						tongtien.value = data.data.Tổng_tiền;
						loading.classList.remove("show");
					}
					else
					{
						$("#modalchitietdondatve").modal("hide");
						$("#modalalert .modal-body").html("Có lỗi xảy ra!");
						$("#modalalert").modal("show");
					}
				},
				timeout: 10000,
				error: function(xhr){
					if(xhr.statusText == "timeout")
					{
						$("#modalchitietdondatve").modal("hide");
						$("#modalalert .modal-body").html("Vui lòng kiểm tra kết nối!");
						$("#modalalert").modal("show");
					}
				}
			});
		}
		function huyDondatve(id)
		{
			if(confirm("Bạn chắc chắc muốn hủy đơn đặt vé này?"))
			{
				$.ajax({
					url: '{{route("qldv_huydondatve")}}',
					type: 'post',
					data: {
						_token: '{{csrf_token()}}',
						data: id
					},
					success: function(data){
						if(data.kq == 1)
						{
							showHistory();
							$("#modalalert .modal-body").html("Hủy đơn đặt vé thành công!");
							$("#modalalert").modal("show");
						}
						else
						{
							$("#modalalert .modal-body").html("Hủy đơn đặt vé thất bại!");
							$("#modalalert").modal("show");
						}
					},
					timeout: 10000,
					error: function(xhr){
						if(xhr.statusText == "timeout")
						{
							$("#modalalert .modal-body").html("Vui lòng kiểm tra kết nối!");
							$("#modalalert").modal("show");
						}
					}
				});
			}
		}
		function refreshPage()
		{
			location.href = location.href;
		}
		$("#modaldadat").on("hide.bs.modal", function(){ //Bắt sự kiện modal đặt vé tắt
			//Code Sự kiện tắt modal đặt vé
		});
		$("#modaldatve").on("hide.bs.modal", function(){ //Bắt sự kiện modal đặt vé tắt
			//Code Sự kiện tắt modal đặt vé
		});
    </script>
@endsection
