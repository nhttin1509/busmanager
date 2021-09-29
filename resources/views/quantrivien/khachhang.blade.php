@extends('quantrivien.main')
@section('title')
	Quản lý Khách hàng
@endsection
@section('content')
    <div class="content khachhang row show" style="overflow: hidden; position: relative; padding: 3em 1em 1em;">
        <h4 style="padding: .5em; position: absolute; top: 0; left: 0; width: 100%;">Bảng Khách Hàng</h4>
        <div id="customer"></div>
        <div class="nutthaotac" style="padding-right: 0;">
            <a href="javascript:void(0)" onclick="window.open('{{url("admin/addkhachhang")}}')" title="Thêm Khách Hàng">
                <i class="glyphicon glyphicon-plus"></i>Thêm
            </a>
            <a href="javascript:void(0)" onclick="refreshKH()" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>Reset
            </a>
			<a href="javascript:void(0)" onclick="showFull(this,'customer',obj,objlen)">
				<i class="glyphicon glyphicon-resize-full"></i>
			</a>
        </div>
    </div>
@endsection
@section('excontent')
    <div id="viewkhachhang" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-title">Thông Tin Khách Hàng</div>
                </div>
                <div class="modal-body">
                    <form name="ttkhachhang">
                        <div class="row form-group">
                            <div class="col-lg-6">
                                <label>Tên</label>
                                <input type="text" name="name" class="form-control" readonly="">
                                <br>
                                <label>Giới tính</label>
                                <input type="text" name="gender" class="form-control" readonly="">
                                <br>
                                <label>Ngày sinh</label>
                                <input type="date" name="brtday" class="form-control" readonly="">
                                <br>
                                <label>Địa chỉ</label>
                                <input type="text" name="address" class="form-control" readonly="">
                            </div>
                            <div class="col-lg-6">
                                <label>Nickname</label>
                                <input type="text" name="nickname" class="form-control" readonly="">
                                <br>
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" readonly="">
                                <br>
                                <label>Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control" readonly="">
                            </div>
                        </div>
                        <div class="row form-group">
                            <h4>Các chuyến xe đã đi</h4>
                            <div id="chuyendi" style="height: 200px;">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[1].classList.add('selected');
        option[1].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/customer-hover.png")}}');
		var obj = {
                width: '100%',
                height: '100%',
                showTop: false,
                showBottom: false,
                collapsible: false,
                showHeader: true,
                filterModel: {on: true, mode: "AND", header: true},
                // scrollModel: {autoFit: true},
                resizable: false,
                roundCorners: false,
                rowBorders: true,
                postRenderInterval: -1,
                columnBorders: false,
                selectionModel: { type: 'row', mode: 'single' },
                hoverMode: 'row',
                numberCell: { show: true, title: 'STT', width: 50, align: 'center'},
                stripeRows: true,
                freezeCols: 1,
                cellDblClick: function (event,ui) {
                    /*viewkhachhang(ui.rowData["Mã"],ui.rowData["Tên"],ui.rowData["Ngày_sinh"],ui.rowData["Giới tính"],ui.rowData["Địa chỉ"],ui.rowData["Nickname"],ui.rowData["Email"],ui.rowData["Sđt"]);*/
                    window.open('{{url("admin/viewkhachhang")}}/'+ui.rowData['Mã']);
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
                        str += '<a title="Edit" id="idEditCustomer" ><i class="glyphicon glyphicon-edit  text-success" style="padding-right: 5px; cursor: pointer;"></i></a>';
                        str += '<a title="Delete" id="idDelCustomer" ><i class="glyphicon glyphicon-remove  text-danger" style="padding-right: 5px; cursor: pointer;"></i></a>';
                        return str;
                    },
                    postRender: function (ui) {
                        var rowData = ui.rowData,
                            $cell = this.getCell(ui);
                        //add button
                        $cell.find("a#idEditCustomer")
                            .unbind("click")
                            .bind("click", function (evt) {
                                window.open("{{url('admin/addkhachhang')}}"+"/"+rowData["Mã"]);
                            });
                        $cell.find("a#idDelCustomer")
                            .unbind("click")
                            .bind("click", function (evt) {
                                if(confirm("Bạn chắc chắn muốn xóa?"))
                                    location.assign("{{url('admin/delkhachhang')}}"+"/"+rowData["Mã"]);
                            });
                    }
                },
                {
                    title: "Tên",
                    width: 200,
                    dataIndx: "Tên",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    filter: {
                        type: 'textbox',
                        attr: "placeholder='Tìm theo tên'",
                        condition: 'contain',
                        listeners: ['keyup']
                    }
                },
                {
                    title: "Giới tính",
                    width: 150,
                    dataIndx: "Giới tính",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    render: function (ui) {
                        switch(ui.rowData["Giới tính"]){
                            case "0":
                                return "Không xác định";
                                break;
                            case "1":
                                return "Nam";
                                break;
                            case "2":
                                return "Nữ";
                                break;
                        }
                    },
                    filter: {
                        type: "select",
                        condition: "equal",
                        options: [
                            {"":"[All]"},
                            {"1":"Nam"},
                            {"2":"Nữ"},
                            {"0":"Không xác định"}
                            ],
                        listeners: ["change"]
                    }
                },
                {
                    title: "Ngày sinh",
                    width: 200,
                    dataIndx: "Ngày_sinh",
                    dataType: "date",
                    editor: false,
                    align: 'center',
                    filter: {
                        type: 'textbox', condition: 'between', init: pqDatePicker,
                        // attr: "placeholder='Từ'",
                        listeners: [{
                            'change': function (evt, ui) {
                                if (ui.value != "") {
                                    var d1 = ui.value.split('/');
                                    ui.value = d1[2] + '/' + d1[0] + '/' + d1[1];
                                }
                                if (ui.value2 != "") {
                                    var d1 = ui.value2.split('/');
                                    ui.value2 = d1[2] + '/' + d1[0] + '/' + d1[1];
                                }
                                var $grid = $(this).closest(".pq-grid");
                                $grid.pqGrid("filter", {
                                    oper: 'add',
                                    data: [ui]
                                });
                            }
                        }]},
                    render: function(ui){
                        var cellData = ui.cellData;
                        var str = '';
                        if (cellData != null) {
                            var d1 = cellData.split('-');
                            str += d1[2] + '/' + d1[1] + '/' + d1[0];
                        }
                        return {text: str};
                    }
                },
                {
                    title: "Email",
                    width: 300,
                    dataIndx: "Email",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    filter: {
                        type: 'textbox',
                        attr: "placeholder='Tìm theo Email'",
                        condition: 'contain',
                        listeners: ['keyup']
                    }
                },
                {
                    title: "Số điện thoại",
                    width: 170,
                    dataIndx: "Sđt",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    filter: {
                        type: 'textbox',
                        attr: "placeholder='Tìm theo Sđt'",
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
                data: {!! json_encode($customer) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
            var $grid = $("#customer").pqGrid(obj);
            if(objlen <= document.getElementById('customer').offsetWidth){
                $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
            }
            else{
                $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
            }
        });
        function refreshKH(){
			$.ajax({
                type:'POST',
                url:'{{asset("/admin/retrievedata")}}',
				data: {
					_token: '{{csrf_token()}}',
					typedata: 'khachhang'
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
                        var $grid = $("#customer").pqGrid(obj);
                        if(objlen <= document.getElementById('customer').offsetWidth){
                            $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                        }
                        else{
                            $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                        }
						$("#customer").pqGrid("reset",{filter : true});
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
    </script>
@endsection
