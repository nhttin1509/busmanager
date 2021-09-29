@extends('quantrivien.main')
@section('title')
	@if(isset($ttxe))
		Chỉnh sửa thông tin xe
	@else
		Thêm xe
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
    <div class="content show row" id="addxe">
        <div class="col-lg-3">
        </div>
        <form name="ttxe" class="col-lg-6" action="{{route('addbus')}}" method="post">
            @csrf
            <fieldset>
                <legend><?php echo isset($ttxe)? 'Sửa Thông Tin Xe':'Thêm Xe';?></legend>
                @isset($ttxe)
                    <?php
                    $ttxe = (array)$ttxe;
                    ?>
                    <input type="hidden" name="ID" value="{{$ttxe['Mã']}}">
                @endisset
                <label>Biển số<i class="text text-danger">*</i></label>
                <input type="text" class="form-control" name="bienso" value="{{isset($ttxe['Biển_số'])? $ttxe['Biển_số']:''}}" placeholder="Biển số xe" required>
                <br>
                <label>Loại xe<i class="text text-danger">*</i></label>
                <select class="form-control" name="idtypebus" required>
                    @foreach($bustypes as $bustype)
                        <?php $bustype = (array)$bustype;?>
                        <option value="{{$bustype['Mã']}}" {{isset($ttxe['Mã_loại_xe'])? ($bustype['Mã']==$ttxe['Mã_loại_xe']? 'selected':''):''}}>{{$bustype['Tên_Loại']}}-{{$bustype['Loại_ghế']==0? 'Ghế_ngồi':'Giường_nằm'}}</option>
                    @endforeach
                </select>
                <br>
                <label>Lần bảo trì gần nhất<i class="text text-danger">*</i></label>
                <br>
                <input type="date" class="form-control" name="baotrigannhat" value="{{isset($ttxe['Ngày_bảo_trì_gần_nhất'])? $ttxe['Ngày_bảo_trì_gần_nhất']:''}}" required>
                <br>
                <label>Lần bảo trì tiếp theo<i class="text text-danger">*</i></label>
                <br>
                <input type="date" class="form-control" name="baotritieptheo" value="{{isset($ttxe['Ngày_bảo_trì_tiếp_theo'])? $ttxe['Ngày_bảo_trì_tiếp_theo']:''}}" required>
                <br>
                <div style="text-align: center">
                    <input type="submit" class="btn btn-success" name="submit" value="<?php echo isset($ttxe)? 'Sửa Thông Tin':'Thêm Xe';?>">
                    <input type="button" onclick="location.assign('{{url('/admin/xe')}}')" class="btn btn-danger" value="Hủy">
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
        option[7].classList.add('selected');
        option[7].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/bus-hover.png")}}');
    </script>
	@if(isset($ttxe))
		<script>
			document.forms["ttxe"]["submit"].onclick = function(ev){
				var id = document.forms["ttxe"]["ID"];
				var bienso = document.forms["ttxe"]["bienso"];
				var idtypebus = document.forms["ttxe"]["idtypebus"];
				var baotrigannhat = document.forms["ttxe"]["baotrigannhat"];
				var baotritieptheo = document.forms["ttxe"]["baotritieptheo"];
				var str = "";
				bienso.style.borderColor = "#ccc";
				baotrigannhat.style.borderColor = "#ccc";
				baotritieptheo.style.borderColor = "#ccc";
				if(bienso.value == "")
				{
					bienso.style.borderColor = "red";
					str += "Biển số xe không được để trống!<br>";
				}
				else
				{
					$.ajax({
						url: "{{route('admin_checkexist')}}",
						type: "post",
						data: {
							_token: "{{csrf_token()}}",
							typecheck: "bienso_change",
							datacheck: bienso.value,
							idcheck: id.value
						},
						success: function(data){
							if(data.kq == 1)
							{
								bienso.style.borderColor = "red";
								str += "Biển số xe đã tồn tại!<br>";
							}
						},
						async: false
					});
				}
				if(baotrigannhat.value == "")
				{
					baotrigannhat.style.borderColor = "red";
					str += "Lần bảo trì gần nhất không được để trống!<br>";
				}
				if(baotritieptheo.value == "")
				{
					baotritieptheo.style.borderColor = "red";
					str += "Lần bảo trì tiếp theo không được để trống!<br>";
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
			document.forms["ttxe"]["submit"].onclick = function(ev){
				var bienso = document.forms["ttxe"]["bienso"];
				var idtypebus = document.forms["ttxe"]["idtypebus"];
				var baotrigannhat = document.forms["ttxe"]["baotrigannhat"];
				var baotritieptheo = document.forms["ttxe"]["baotritieptheo"];
				var str = "";
				bienso.style.borderColor = "#ccc";
				baotrigannhat.style.borderColor = "#ccc";
				baotritieptheo.style.borderColor = "#ccc";
				if(bienso.value == "")
				{
					bienso.style.borderColor = "red";
					str += "Biển số xe không được để trống!<br>";
				}
				else
				{
					$.ajax({
						url: "{{route('admin_checkexist')}}",
						type: "post",
						data: {
							_token: "{{csrf_token()}}",
							typecheck: "bienso_create",
							datacheck: bienso.value
						},
						success: function(data){
							if(data.kq == 1)
							{
								bienso.style.borderColor = "red";
								str += "Biển số xe đã tồn tại!<br>";
							}
						},
						async: false
					});
				}
				if(baotrigannhat.value == "")
				{
					baotrigannhat.style.borderColor = "red";
					str += "Lần bảo trì gần nhất không được để trống!<br>";
				}
				if(baotritieptheo.value == "")
				{
					baotritieptheo.style.borderColor = "red";
					str += "Lần bảo trì tiếp theo không được để trống!<br>";
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
