@extends('quantrivien.main')
@section('title')
	Quản lý Tin tức
@endsection
@section('content')
    <div class="content row show" style="overflow: hidden; position: relative; padding: 3em 1em 1em;">
        <h4 style="padding: .5em; position: absolute; top: 0; left: 0; width: 100%;">Bảng Tin Tức</h4>
        <div id="tintuc">
        </div>
        <div class="nutthaotac">
            <a href="javascript:void(0)" onclick="addnews()" title="Thêm Chuyến Xe">
                <i class="glyphicon glyphicon-plus"></i>Thêm
            </a>
            <a href="javascript:void(0)" onclick="refreshTT()" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>Reset
            </a>
            <a href="javascript:void(0)" onclick="showFull(this,'tintuc',obj,objlen)">
                <i class="glyphicon glyphicon-resize-full"></i>
            </a>
        </div>
    </div>
@endsection
@section('excontent')
    <div id ="btnnotice">
        <div id="comment">
            Khi tạo chuyến xe, Vé sẽ được tự động tạo tương ứng với số ghế của loại xe dùng để chuyên chở trong chuyến xe!
        </div>
    </div>
	<!--Modal chỉnh sửa tin tuc -->
	<div id="edittintuc" class="modal fade" role="dialog">
		<div class="modal-dialog" style="width: 1000px;">
		<form name="formedittintuc" action="{{route('edittintuc')}}" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<input type="hidden" name="newsid">
		<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close txtclose" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Chỉnh sửa Tin Tức</h4>
				</div>
				<div class="modal-body">
					<div class="hienloi">
						<div class="alert alert-danger" id="loiedit"></div>
					</div>	
					<div class="input-group col-lg-3">
						<label class="input-group-addon">Kích hoạt</label>
						<select name="kichhoat" class="form-control">
							<option value="0" selected>Có</option>
							<option value="1">Không</option>
						</select>
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Tiêu đề&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class="form-control txttieude" name="tieude"  placeholder="tiêu đề tin tức">
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Hình ảnh&nbsp;</label>
						<input type="file" class="form-control txthinhanh" name="hinhanh"  >
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Mô tả&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<textarea class="form-control txtmota" rows="3" name="mota"></textarea>
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Nội dung&nbsp;</label>
						<textarea class="form-control txtnoidung" rows="5" id="noidung" name="noidung"></textarea>
						<script type="text/javascript"> CKEDITOR.replace('noidung');</script>
					</div>
					<br>
					<div class="form-group">
						<label>Đặt làm slide?</label>
						<br>&nbsp;&nbsp;&nbsp;
						<input type="radio" class="form-inline" name="isslide" value="1">&nbsp;Có
						<input type="radio" class="form-inline" name="isslide" value="0" checked>&nbsp;Không
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" name="submit" class=" btn btn-success txtcapnhat" value="Sửa">
					<button type="button" class="btn btn-default txtclose" data-dismiss="modal">Close</button>
				</div>
			</div>
			</form>
		</div>
	</div>
	<!--Modal thêm tin tức-->
	<div id="addtintuc" class="modal fade" role="dialog">
		<div class="modal-dialog" style="width: 1000px;">
		<form name="formaddtintuc" action="{{route('addtintuc')}}" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
		<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close txtclose" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Thêm Tin Tức</h4>
				</div>
				<div class="modal-body">
					<div class="hienloi">
						<div class="alert alert-danger" id="loiadd" style="display: none;"></div>
					</div>	
					<div class="input-group col-lg-3">
						<label class="input-group-addon">Kích hoạt</label>
						<select name="kichhoat" class="form-control">
							<option value="0" selected>Có</option>
							<option value="1">Không</option>
						</select>
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Tiêu đề&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<input type="text" class="form-control txttieude" name="tieude"  placeholder="tiêu đề tin tức">
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Hình ảnh&nbsp;</label>
						<input type="file" class="form-control txthinhanh" name="hinhanh"  >
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Mô tả&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
						<textarea class="form-control txtmota" rows="3" name="mota"></textarea>
					</div>
					<br>
					<div class="input-group">
						<label class="input-group-addon">Nội dung&nbsp;</label>
						<textarea class="form-control txtnoidung" rows="5" id="noidung1" name="noidung"></textarea>
						<script type="text/javascript"> CKEDITOR.replace('noidung1');</script>
					</div>
					<br>
					<div class="form-group">
						<label>Đặt làm slide?</label>
						<br>&nbsp;&nbsp;&nbsp;
						<input type="radio" class="form-inline" name="isslide" value="1">&nbsp;Có
						<input type="radio" class="form-inline" name="isslide" value="0" checked>&nbsp;Không
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" name="submit" class=" btn btn-success txtcapnhat" value="Thêm">
					<button type="button" class="btn btn-default txtclose" data-dismiss="modal">Close</button>
				</div>
			</div>
			</form>
		</div>
	</div>
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[9].classList.add('selected');
        option[9].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/newspaper-hover.png")}}');
        var obj = {
            width: '100%',
            height: '100%',
            showTop: false,
            showBottom: false,
            collapsible: false,
            showHeader: true,
            filterModel: {on: true, mode: "AND", header: true},
            /* scrollModel: {horizontal: true,autoFit: true}, */
            resizable: false,
            roundCorners: false,
            rowBorders: true,
            columnBorders: false,
            postRenderInterval: -1,
            selectionModel: { type: 'row', mode: 'single' },
            hoverMode: 'row',
            numberCell: { show: true, title: 'STT', width: 50, align: 'center'},
            stripeRows: true,
            freezeCols: 1,
            cellDblClick: function (event,ui) {
                window.open( "{{asset("/showtintuc")}}"+ "/" + ui.rowData["news_id"]);
            }
        };
        obj.colModel = [
            {
                title: "Thao tác",
                width: 100,
                editor: false,
                dataIndx: "View",
                align: 'center',
                render: function (ui) {
                    var str = '';
                    str += '<a title="Edit" id="idEditTinTuc" ><i class="glyphicon glyphicon-edit  text-success" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    str += '<a title="Delete" id="idDelTintuc" ><i class="glyphicon glyphicon-remove  text-danger" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    return str;
                },
                postRender: function (ui) {
                    var rowData = ui.rowData,
                        $cell = this.getCell(ui);
                    //add button
                    $cell.find("a#idEditTinTuc")
                        .unbind("click")
                        .bind("click", function (evt) {
							var newsid = document.forms["formedittintuc"]["newsid"];
							var kichhoat = document.forms["formedittintuc"]["kichhoat"];
							var tieude = document.forms["formedittintuc"]["tieude"];
							var hinhanh = document.forms["formedittintuc"]["hinhanh"];
							var mota = document.forms["formedittintuc"]["mota"];
							var noidung = document.forms["formedittintuc"]["noidung"];
							var isslide = document.forms["formedittintuc"]["isslide"];
							newsid.value = rowData["news_id"];
							kichhoat.value = rowData["is_disabled"];
							tieude.value = rowData["title"];
							mota.value = rowData["introduce"];
							CKEDITOR.instances["noidung"].setData(rowData["content"]);
							isslide.value = rowData["check_slide"];
							tieude.style.borderColor = "#ccc";
							hinhanh.style.borderColor = "#ccc";
							mota.style.borderColor = "#ccc";
							document.getElementById("cke_noidung").style.borderColor = "#ccc";
							document.getElementById("loiedit").innerHTML = "";
							document.getElementById("loiedit").style.display = "none";
                            $('#edittintuc').modal();
                        });
                    $cell.find("a#idDelTintuc")
                        .unbind("click")
                        .bind("click", function (evt) {
                            if(confirm("Bạn chắc chắn muốn xóa?")){
                                location.assign('{{url("/admin/deltintuc")}}/'+rowData['news_id']);
                            }
                        });
                }
            },
            {
                title: "Trạng thái",
                width: 100,
                dataIndx: "is_disabled",
                dataType: "string",
                editor: false,
                align: 'center',
                render: function(ui){
                    switch(ui.rowData["is_disabled"]){
                        case 0:
                            return '<small style="font-size: .8em;" class="btn btn-success">Active</small>';
                            break;
                        case 1:
                            return '<small style="font-size: .8em;" class="btn btn-danger">Disable</small>';
                            break;
                    }
                },
                filter: {
                    type: 'select',
                    condition: 'equal',
                    options: [
                        {'':'[All]'},
                        {'0':'Active'},
                        {'1':'Disable'}
                    ],
                    listeners: ['change']
                }
            },
			{
                title: "là Slide",
                width: 100,
                dataIndx: "check_slide",
                dataType: "string",
                editor: false,
                align: "center",
                render: function(ui){
                    if(ui.rowData["check_slide"]==0)
                        return '<small style="font-size: .8em;" class="btn btn-danger">notSlide</small>';
                    else if(ui.rowData["check_slide"]==1)
                        return '<small style="font-size: .8em;" class="btn btn-success">Slide</small>';
                },
                filter: {
                    type: 'select',
                    condition: 'equal',
                    listeners: ['change'],
                    options: [
                        {'':'[All]'},
                        {'0':'notSlide'},
                        {'1':'Slide'}
                    ]
                }
            },
            {
                title: "Tiêu đề",
                width: 150,
                dataIndx: "title",
                dataType: "string",
                editor: false,
                align: 'left',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Tiêu đề"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Ảnh minh họa",
                width: 150,
                dataIndx: "image",
                dataType: "string",
                editor: false,
                align: 'center',
                render: function(ui){
					return "<a href='"+"{{asset("/upload")}}"+"/"+ui.rowData['image']+"' target='_blank'><i class='glyphicon glyphicon-eye-open' style='color: green;'></i></a>";
				}
            },
            {
                title: "Giới thiệu",
                width: 250,
                dataIndx: "introduce",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo thông tin Giới thiệu"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
			{
                title: "Người tạo",
                width: 200,
                dataIndx: "admin_created",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Người tạo"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
			{
                title: "Người chỉnh sửa",
                width: 200,
                dataIndx: "admin_changed",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Người chỉnh sửa"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            }
        ];
        var objlen = 0;
        for(var i =0; i<obj.colModel.length;i++){
            objlen+=obj.colModel[i].width;
        }
        $(function () {
            obj.dataModel = {
                data: {!! json_encode($tintuc) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
            var $grid = $("#tintuc").pqGrid(obj);
            if(objlen <= document.getElementById('tintuc').offsetWidth){
                $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
            }
            else{
                $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
            }
        });
        function refreshTT() {
            $.ajax({
                type:'POST',
                url:'{{asset("/admin/retrievedata")}}',
				data: {
					_token: '{{csrf_token()}}',
					typedata: 'tintuc'
				},
                success:function(data){
                    if (data.kq == 1) {
                        obj.dataModel = {
                            data: data.data,
                            location: "local",
                            sorting: "local",
                            sortDir: "down"
                        };
                        obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
                        var $grid = $("#tintuc").pqGrid(obj);
                        if(objlen <= document.getElementById('tintuc').offsetWidth){
                            $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                        }
                        else{
                            $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                        }
                        $("#tintuc").pqGrid("reset",{filter : true});
                    }                   
                },
				timeout: 10000,
				error: function(xhr){
					if(xhr.statusText == "timeout")
					{
						$("#alertmessage .modal-body").html("Vui lòng kiểm tra kết nối!");
						$("#alertmessage").modal("show");
					}
				}
            });
        }
        function showFull(ev,id,obj,s){
            if(ev.getElementsByTagName("i")[0].classList.contains("glyphicon-resize-full")){
                ev.getElementsByTagName("i")[0].classList.remove("glyphicon-resize-full");
                ev.getElementsByTagName("i")[0].classList.add("glyphicon-resize-small");
                document.getElementById(id).style.position = "fixed";
                document.getElementById(id).style.width = "100%";
                document.getElementById(id).style.height = "100%";
                document.getElementById(id).style.top = "0";
                document.getElementById(id).style.left = "0";
                document.getElementById(id).style.paddingTop = "3.45em";
                document.getElementsByClassName("nutthaotac")[0].style.top = "calc(100% - 3em)";
                var $grid = $("#"+id).pqGrid(obj);
                if(s <= document.getElementById(id).offsetWidth){
                    $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                }
                else{
                    $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                }
            }
            else{
                ev.getElementsByTagName("i")[0].classList.remove("glyphicon-resize-small");
                ev.getElementsByTagName("i")[0].classList.add("glyphicon-resize-full");
                document.getElementById(id).style.position = "relative";
                document.getElementById(id).style.width = "100%";
                document.getElementById(id).style.height = "auto";
                document.getElementById(id).style.paddingTop = "0";
                document.getElementsByClassName("nutthaotac")[0].style.position = "absolute";
                document.getElementsByClassName("nutthaotac")[0].style.top = ".4em";
                var $grid = $("#"+id).pqGrid(obj);
                if(s <= document.getElementById(id).offsetWidth){
                    $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                }
                else{
                    $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                }
            }
        }
		function addnews()
		{
			var kichhoat = document.forms["formaddtintuc"]["kichhoat"];
			var tieude = document.forms["formaddtintuc"]["tieude"];
			var hinhanh = document.forms["formaddtintuc"]["hinhanh"];
			var mota = document.forms["formaddtintuc"]["mota"];
			var noidung = document.forms["formaddtintuc"]["noidung"];
			var isslide = document.forms["formaddtintuc"]["isslide"];
			kichhoat.value = "0";
			tieude.value = "";
			hinhanh.value = "";
			mota.value = "";
			CKEDITOR.instances["noidung1"].setData("");
			isslide.value = "0";
			tieude.style.borderColor = "#ccc";
			hinhanh.style.borderColor = "#ccc";
			mota.style.borderColor = "#ccc";
			document.getElementById("cke_noidung1").style.borderColor = "#ccc";
			document.getElementById("loiadd").innerHTML = "";
			document.getElementById("loiadd").style.display = "none";
			$("#addtintuc").modal();
		}
		document.forms["formaddtintuc"]["submit"].onclick = function(ev){
			var kichhoat = document.forms["formaddtintuc"]["kichhoat"];
			var tieude = document.forms["formaddtintuc"]["tieude"];
			var hinhanh = document.forms["formaddtintuc"]["hinhanh"];
			var mota = document.forms["formaddtintuc"]["mota"];
			var noidung = document.forms["formaddtintuc"]["noidung"];
			var isslide = document.forms["formaddtintuc"]["isslide"];
			var str = "";
			tieude.style.borderColor = "#ccc";
			hinhanh.style.borderColor = "#ccc";
			mota.style.borderColor = "#ccc";
			document.getElementById("cke_noidung1").style.borderColor = "#ccc";
			if(tieude.value == "")
			{
				tieude.style.borderColor = "red";
				str += "Tiêu đề không được để trống!<br>";
			}
			if(hinhanh.value == "")
			{
				hinhanh.style.borderColor = "red";
				str += "Chưa chọn hình minh họa!<br>";
			}
			if(mota.value == "")
			{
				mota.style.borderColor = "red";
				str += "Mô tả không được để trống!<br>";
			}
			if(CKEDITOR.instances["noidung1"].getData() == "")
			{
				document.getElementById("cke_noidung1").style.borderColor = "red";
				str += "Nội dung tin tức không được để trống!<br>";
			}
			if(str != "")
			{
				ev.preventDefault();
				document.getElementById("loiadd").innerHTML = str;
				document.getElementById("loiadd").style.display = "block";
			}
			
		};
		document.forms["formedittintuc"]["submit"].onclick = function(ev){
			var kichhoat = document.forms["formedittintuc"]["kichhoat"];
			var tieude = document.forms["formedittintuc"]["tieude"];
			var hinhanh = document.forms["formedittintuc"]["hinhanh"];
			var mota = document.forms["formedittintuc"]["mota"];
			var noidung = document.forms["formedittintuc"]["noidung"];
			var isslide = document.forms["formedittintuc"]["isslide"];
			var str = "";
			tieude.style.borderColor = "#ccc";
			mota.style.borderColor = "#ccc";
			document.getElementById("cke_noidung").style.borderColor = "#ccc";
			if(tieude.value == "")
			{
				tieude.style.borderColor = "red";
				str += "Tiêu đề không được để trống!<br>";
			}
			if(mota.value == "")
			{
				mota.style.borderColor = "red";
				str += "Mô tả không được để trống!<br>";
			}
			if(CKEDITOR.instances["noidung"].getData() == "")
			{
				document.getElementById("cke_noidung").style.borderColor = "red";
				str += "Nội dung tin tức không được để trống!<br>";
			}
			if(str != "")
			{
				ev.preventDefault();
				document.getElementById("loiedit").innerHTML = str;
				document.getElementById("loiedit").style.display = "block";
			}
			
		};
    </script>
@endsection
