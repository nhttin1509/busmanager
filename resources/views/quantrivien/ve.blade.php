@extends('quantrivien.main')
@section('title')
	Quản lý Vé
@endsection
@section('content')
    <div class="content row show" style="overflow: hidden; position: relative; padding: 3em 1em 1em">
        <h4 style="padding: .5em; position: absolute; top: 0; left: 0; width: 100%;">Bảng Vé Xe</h4>
        <div id="ticket">
        </div>
        <div class="nutthaotac">
            <a href="javascript:void(0)" onclick="refresh(2)" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>Reset
            </a>
            <a href="javascript:void(0)" onclick="showFull(this,'ticket',obj,objlen)">
                <i class="glyphicon glyphicon-resize-full"></i>
            </a>
        </div>
    </div>
@endsection
@section('excontent')
    <div id="editve" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Thông Tin Vé</h4>
                </div>
                <div class="modal-body">
                    <form name="editticket" class="row">
                        <input type="hidden" name="ID" value="">                        
                        <div class="col-lg-6 form-group">
                            <label>Giá</label>
                            <input type="number" min="0" class="form-control" name="giave" placeholder="Giá vé" readonly>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="trangthai">
                                <option value="0">Waiting</option>
                                <option value="1">Booked</option>
                                <option value="2">Locked</option>
                                <option value="3">Banned</option>
                            </select>
                        </div>                        
                    </form>
                </div>
                <div class="modal-footer" style="text-align: center;">
                    <button class="btn btn-success" id="btnsubmit" onclick="editVe()">Sửa Thông Tin Vé</button>
                </div>
            </div>
        </div>
    </div>
    <div id ="btnnotice">
        <div id="comment">
            Khi tạo chuyến xe, Vé sẽ được tự động tạo tương ứng với số ghế của loại xe dùng để chuyên chở trong chuyến xe!
        </div>
    </div>
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[3].classList.add('selected');
        option[3].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/bus-ticket-hover.png")}}');
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
            columnBorders: false,
            postRenderInterval: -1,
            selectionModel: { type: 'row', mode: 'single' },
            hoverMode: 'row',
            numberCell: { show: true, title: 'STT', width: 50, align: 'center'},
            stripeRows: true,
            freezeCols: 1,
            /*cellDblClick: function (event,ui) {
            }*/
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
                    str += '<a title="Edit" id="idEditTicket" ><i class="glyphicon glyphicon-edit  text-success" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    return str;
                },
                postRender: function (ui) {
                    var rowData = ui.rowData,
                        $cell = this.getCell(ui);
                    //add button
                    $cell.find("a#idEditTicket")
                        .unbind("click")
                        .bind("click", function (evt) {
                            document.forms["editticket"]["ID"].value = rowData["Mã"];
                            document.forms["editticket"]["giave"].value = rowData["Tiền_vé"];
                            document.forms["editticket"]["trangthai"].value = rowData["Trạng_thái"];
                            $("#editve").modal('show');
                        });
                }
            },
            {
                title: "Trạng thái",
                width: 100,
                dataIndx: "Trạng_thái",
                dataType: "string",
                editor: false,
                align: 'center',
                render: function(ui){
                    switch(ui.rowData["Trạng_thái"]){
                        case 0:
                            return '<small style="font-size: .8em;" class="btn btn-default">Waiting</small>';
                            break;
                        case 1:
                            return '<small style="font-size: .8em;" class="btn btn-success">Booked</small>';
                            break;
                        case 2:
                            return '<small style="font-size: .8em;" class="btn btn-warning">Locked</small>';
                            break;
                        case 3:
                            return '<small style="font-size: .8em;" class="btn btn-danger">Banned</small>';
                            break;
                    }
                },
                filter: {
                    type: 'select',
                    condition: 'equal',
                    options: [
                        {'':'[All]'},
                        {'0':'Waiting'},
                        {'1':'Booked'},
                        {'2':'Locked'},
                        {'3':'Banned'}
                    ],
                    listeners: ['change']
                }
            },
            {
                title: "Mã chuyến xe",
                width: 150,
                dataIndx: "Mã_chuyến_xe",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Mã chuyến xe"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Nhân viên đặt",
                width: 150,
                dataIndx: "Nhân_viên_đặt",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Nhân viên đặt"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Mã Khách Hàng",
                width: 150,
                dataIndx: "Mã_khách_hàng",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Mã khách hàng"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Vị trí ghế",
                width: 100,
                dataIndx: "Vị_trí_ghế",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Vị trí ghế"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Loại ghế",
                width: 100,
                dataIndx: "Loại_ghế",
                dataType: "string",
                editor: false,
                align: "center",
                render: function(ui){
                    if(ui.rowData["Loại_ghế"]==0)
                        return "Ghế ngồi";
                    else if(ui.rowData["Loại_ghế"])
                        return "Giường nằm";
                },
                filter: {
                    type: 'select',
                    condition: 'equal',
                    listeners: ['change'],
                    options: [
                        {'':'[All]'},
                        {'0':'Ghế ngồi'},
                        {'1':'Giường nằm'}
                    ]
                }
            },
            {
                title: "Giá",
                width: 200,
                dataIndx: "Tiền_vé",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Giá vé"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            }
            /*{
                title: "Ẩn",
                width: 100,
                dataIndx: "is_hide",
                dataType: "string",
                editor: false,
                align: 'center',
                render: function(ui){
                    if(ui.rowData["is_hide"]==1){
                        return "<i class='glyphicon glyphicon-remove-circle' style='color: grey'></i>";
                    }
                    else {
                        return "<i class='glyphicon glyphicon-ok-circle' style='color: lightgreen'></i>";
                    }
                },
                filter: {
                    type: 'select',
                    condition: 'equal',
                    options: [
                        {'':'All'},
                        {'0':'Show'},
                        {'1':'Hide'},
                    ],
                    listeners: ['change']
                }
            },*/
        ];
        var objlen = 0;
        for(var i =0; i<obj.colModel.length;i++){
            objlen+=obj.colModel[i].width;
        }
        $(function () {
            obj.dataModel = {
                data: {!! json_encode($ticket) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
            var $grid = $("#ticket").pqGrid(obj);
            if(objlen <= document.getElementById('ticket').offsetWidth){
                $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
            }
            else{
                $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
            }
        });
        function refresh(index) {
            $.ajax({
                type:'GET',
                url:'{{asset("/admin/ticket")}}/'+index,
                success:function(data){
                    if (index == 1) {
                    }
                    else if (index == 2) {
                        obj.dataModel = {
                            data: data.msg,
                            location: "local",
                            sorting: "local",
                            sortDir: "down"
                        };
                        obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
                        var $grid = $("#ticket").pqGrid(obj);
                        $grid.pqGrid("refreshDataAndView");
                        $("#ticket").pqGrid("reset",{filter : true});
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
        function editVe() {
            var id = document.forms["editticket"]["ID"].value;
            var trangthai = document.forms["editticket"]["trangthai"].value;
            $.ajax({
                url: '{{route("editticket")}}',
                type: 'POST',
                data: {
                    _token: '{{csrf_token()}}',
                    ID: id,
                    trangthai: trangthai
                },
                success: function (data) {
                    if(data.result==1){
                        $("#editve").modal('hide');
                        alert('Sửa thành công');
                        refresh(1);
                        refresh(2);
                    }
                    else {
                        alert('Sửa thất bại');
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
