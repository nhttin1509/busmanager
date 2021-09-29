@extends('tttn-web.main')
@section('title')
    Trang chủ
@endsection
@section('content')
<div class="mainformtintuc" style="min-height: 400px; width:60%; margin:auto;">
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#addtintuc">Thêm</button>
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#addgioithieu">Thêm GT</button>
</div>
@endsection
@section('excontent')
	<div id="addgioithieu" class="modal fade" role="dialog">
  <div class="modal-dialog">
	 <form id="formgioithieu" action="{{route('addgioithieu')}}" method="POST" enctype="multipart/form-data">
	   	 <input type="hidden" name="_token" value="{{csrf_token()}}">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thêm Giới Thiệu</h4>
      </div>
      <div class="modal-body">
        	<div class="input-group">
			    <span class="input-group-addon">Nội dung</span>
			    <textarea class="form-control txtnoidunggt" id="noidungtg" rows="5" name="noidunggt"></textarea>
			    <script type="text/javascript"> CKEDITOR.replace('noidunggt');</script>
		  	</div>
      </div>
      <div class="modal-footer">
      	<input type="submit" class=" btn btn-success txtcapnhatgt" value="Thêm">
        <button type="button" class="btn btn-default txtclosegt" data-dismiss="modal">Close</button>
      </div>
    </div>
	</form>
  </div>
</div>
	<!-- tin tuc -->
	<div id="addtintuc" class="modal fade" role="dialog">
  <div class="modal-dialog">
	   <form id="formtintuc" action="{{route('addtintuc')}}" method="POST" enctype="multipart/form-data">
	   	 <input type="hidden" name="_token" value="{{csrf_token()}}">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close txtclose" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Thêm Tin Tức</h4>
      </div>
      <div class="modal-body">
      	<div class="hienloi">
        	<div class="ttloi"></div>
			<div class="ttloi2"></div>
			<div class="ttloi3"></div>
			<div class="ttloi4"></div>
		</div>	
			<div class="input-group">
			    <span class="input-group-addon">Tiêu đề</span>
			    <input type="text" class="form-control txttieude" name="tieude"  placeholder="tiêu đề tin tức">
		  	</div>
		  	<br>
		  	<div class="input-group">
			    <span class="input-group-addon">Hình ảnh</span>
			    <input type="file" class="form-control txthinhanh" name="hinhanh"  >
		  	</div>
		  	<br>
		  	<div class="input-group">
			    <span class="input-group-addon">Mô tả</span>
			    <textarea class="form-control txtmota" rows="3" name="mota"></textarea>
		  	</div>
		  	<br>
		  	<div class="input-group">
			    <span class="input-group-addon">Nội dung</span>
			    <textarea class="form-control txtnoidung" id="noidung" rows="5" name="noidung"></textarea>
			    <script type="text/javascript"> CKEDITOR.replace('noidung');</script>
		  	</div>
		  	
      </div>
      <div class="modal-footer">
      	 <input type="submit" class=" btn btn-success txtcapnhat" value="Thêm">
        <button type="button" class="btn btn-default txtclose" data-dismiss="modal">Close</button>
      </div>
    </div>
	</form>
  </div>
</div>
@endsection
