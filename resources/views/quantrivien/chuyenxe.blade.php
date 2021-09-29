@extends('quantrivien.main')
@section('title')
	Quản lý Chuyến xe
@endsection
@section('content')
    <div class="content row show" style="overflow: hidden; position: relative; padding: 3em 1em 1em;">
        <h4 style="padding: .5em; position: absolute; top: 0; left: 0; width: 100%;">Bảng Chuyến Xe</h4>
        <div id="chuyenxe">
        </div>
        <div class="nutthaotac">
            <a href="javascript:void(0)" onclick="window.open('{{url("admin/addchuyenxe")}}')" title="Thêm Chuyến Xe">
                <i class="glyphicon glyphicon-plus"></i>Thêm
            </a>
            <a href="javascript:void(0)" onclick="refresh(1)" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>Reset
            </a>
            <a href="javascript:void(0)" onclick="showFull(this,'chuyenxe',obj,objlen)">
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
@endsection
@section('script')
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[2].classList.add('selected');
        option[2].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/chuyenxe-hover.png")}}');
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
            /*cellDblClick: function (event,ui) {
                window.open( + "/" + ui.rowData["Mã"]);
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
                    str += '<a title="Edit" id="idEditChuyenXe" ><i class="glyphicon glyphicon-edit  text-success" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    str += '<a title="Delete" id="idDelChuyenXe" ><i class="glyphicon glyphicon-remove  text-danger" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    return str;
                },
                postRender: function (ui) {
                    var rowData = ui.rowData,
                        $cell = this.getCell(ui);
                    //add button
                    $cell.find("a#idEditChuyenXe")
                        .unbind("click")
                        .bind("click", function (evt) {
                            window.open('{{url("/admin/addchuyenxe")}}/'+rowData['Mã']);
                        });
                    $cell.find("a#idDelChuyenXe")
                        .unbind("click")
                        .bind("click", function (evt) {
                            if(confirm("Bạn chắc chắn muốn xóa?")){
                                location.assign('{{url("/admin/delchuyenxe")}}/'+rowData['Mã']);
                            }
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
                            return '<small style="font-size: .8em;" class="btn btn-success">Running</small>';
                            break;
                        case 2:
                            return '<small style="font-size: .8em;" class="btn btn-warning">Completed</small>';
                            break;
                        case 3:
                            return '<small style="font-size: .8em;" class="btn btn-danger">Locked</small>';
                            break;
                    }
                },
                filter: {
                    type: 'select',
                    condition: 'equal',
                    options: [
                        {'':'[All]'},
                        {'0':'Waiting'},
                        {'1':'Running'},
                        {'2':'Completed'},
                        {'3':'Locked'}
                    ],
                    listeners: ['change']
                }
            },
            {
                title: "Mã",
                width: 130,
                dataIndx: "Mã",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Mã"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Nơi đi",
                width: 150,
                dataIndx: "Nơi_đi",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Nơi đi"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Nơi đến",
                width: 150,
                dataIndx: "Nơi_đến",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Nơi đến"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Nhân viên tạo",
                width: 150,
                dataIndx: "Nhân_viên_tạo",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Nhân viên tạo"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Tài xế",
                width: 150,
                dataIndx: "Tài_xế",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Tài xế"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Xe biển số",
                width: 100,
                dataIndx: "Biển_số",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Biển số"',
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
                    else if(ui.rowData["Loại_ghế"]==1)
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
                title: "Ngày xuất phát",
                width: 200,
                dataIndx: "Ngày_xuất_phát",
                dataType: "date",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox', condition: 'between', init: pqDatePicker,
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
                    if (cellData != "") {
                        var d1 = cellData.split('-');
                        str += d1[2] + '/' + d1[1] + '/' + d1[0];
                    }
                    return {text: str};
                }
            },
            {
                title: "Giờ xuất phát",
                width: 150,
                dataIndx: "Giờ_xuất_phát",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Giờ xuất phát"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Tiền vé",
                width: 100,
                dataIndx: "Tiền_vé",
                dataType: "string",
                editor: false,
                align: "center",
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Tiền vé"',
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
                data: {!! json_encode($chuyenxe) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
            var $grid = $("#chuyenxe").pqGrid(obj);
            if(objlen <= document.getElementById('chuyenxe').offsetWidth){
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
                        obj.dataModel = {
                            data: data.msg,
                            location: "local",
                            sorting: "local",
                            sortDir: "down"
                        };
                        obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
                        var $grid = $("#chuyenxe").pqGrid(obj);
                        if(objlen <= document.getElementById('chuyenxe').offsetWidth){
                            $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                        }
                        else{
                            $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                        }
                        $("#chuyenxe").pqGrid("reset",{filter : true});
                    }
                    else if (index == 2) {
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
