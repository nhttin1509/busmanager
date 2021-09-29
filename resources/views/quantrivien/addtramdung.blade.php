@extends('quantrivien.main')
@section('title')
	@if(isset($tttramdung))
		Chỉnh sửa thông tin trạm dừng
	@else
		Thêm trạm dừng
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
    <div class="content show row" id="addtramdung">
        <div class="col-lg-3">
        </div>
        <form name="tramdung" class="col-lg-6" action="{{route('addbusstop')}}" method="post">
            @csrf
            <fieldset>
                <legend><?php echo isset($tttramdung)? 'Sửa Thông Tin Trạm Dừng':'Thêm Trạm Dừng';?></legend>
                @if(isset($tttramdung))
                    <?php
                    $tttramdung = (array)$tttramdung;
                    ?>
                    <input type="hidden" name="ID" value="{{$tttramdung['Mã']}}">
                @endif
                <input type="hidden" name="employeeID" value="{{session('admin.id')}}">
                <label>Tên trạm dừng<i class="text text-danger">*</i></label>
                <input type="text" class="form-control" name="name" value="{{isset($tttramdung['Tên'])? $tttramdung['Tên']:''}}" placeholder="Tên trạm dừng" required>
                <br>
                <label>Tọa độ<i class="text text-danger">*</i></label>
                <input type="text" class="form-control"  name="toado" value="{{isset($tttramdung['Tọa_độ'])? $tttramdung['Tọa_độ']:''}}" placeholder="Tọa độ" readonly>
                <br>
                <div id="viewmap" style="width: 100%; height: 500px;"></div>
                <br>
                <div style="text-align: center">
                    <input type="submit" class="btn btn-success" name="submit" value="<?php echo isset($tttramdung)? 'Sửa Thông Tin':'Thêm Trạm Dừng';?>">
                    <input type="button" onclick="location.assign('{{url('/admin/tramdung')}}')" class="btn btn-danger" value="Hủy">
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
        option[8].classList.add('selected');
        option[8].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/parking-hover.png")}}');
        function openmap(){
            var toado = document.forms["tramdung"]["toado"].value;
            var x,y;
            if(toado!=""){
                var arr = toado.split(",");
                x = arr[0];
                y = arr[1];
            }
            else{
                x = 10.865720;
                y = 106.759710;
            }
            var mapOptions = {
                center: new google.maps.LatLng(x, y),
                zoom: 16,
                mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(x, y)
            });
            var map = new google.maps.Map(document.getElementById("viewmap"),mapOptions);
            marker.setMap(map);
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            });
            function placeMarker(location) {
                marker['position'] = location;
                marker.setMap(map);
                document.forms["tramdung"]["toado"].value = location.lat()+","+location.lng();
            }
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78&callback=openmap"></script>
	@if(isset($tttramdung))
		<script>
			document.forms["tramdung"]["submit"].onclick = function(ev){
				var id = document.forms["tramdung"]["ID"];
				var name = document.forms["tramdung"]["name"];
				var toado = document.forms["tramdung"]["toado"];
				var str = "";
				name.style.borderColor = "#ccc";
				toado.style.borderColor = "#ccc";
				if(name.value == "")
				{
					name.style.borderColor = "red";
					str += "Tên trạm dừng không được để trống!<br>";
				}
				else
				{
					$.ajax({
						url: "{{route('admin_checkexist')}}",
						type: "post",
						data: {
							_token: "{{csrf_token()}}",
							typecheck: "tentramdung_change",
							datacheck: name.value,
							idcheck: id.value
						},
						success: function(data){
							if(data.kq == 1)
							{
								name.style.borderColor = "red";
								str += "Tên trạm dừng đã tồn tại!<br>";
							}
						},
						async: false
					});
				}
				if(toado.value == "")
				{
					toado.style.borderColor = "red";
					str += "Chưa chọn tọa độ!<br>";
				}
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
			document.forms["tramdung"]["submit"].onclick = function(ev){
				var name = document.forms["tramdung"]["name"];
				var toado = document.forms["tramdung"]["toado"];
				var str = "";
				name.style.borderColor = "#ccc";
				toado.style.borderColor = "#ccc";
				if(name.value == "")
				{
					name.style.borderColor = "red";
					str += "Tên trạm dừng không được để trống!<br>";
				}
				else
				{
					$.ajax({
						url: "{{route('admin_checkexist')}}",
						type: "post",
						data: {
							_token: "{{csrf_token()}}",
							typecheck: "tentramdung_create",
							datacheck: name.value
						},
						success: function(data){
							if(data.kq == 1)
							{
								name.style.borderColor = "red";
								str += "Tên trạm dừng đã tồn tại!<br>";
							}
						},
						async: false
					});
				}
				if(toado.value == "")
				{
					toado.style.borderColor = "red";
					str += "Chưa chọn tọa độ!<br>";
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
