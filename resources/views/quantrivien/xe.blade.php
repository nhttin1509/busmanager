@extends("quantrivien.main")
@section('title')
	Quản lý Xe
@endsection
@section("content")
    <div class="content xe row show" style="overflow: hidden; position: relative; padding: 3em 1em 1em;">
        <h4 style="padding: .5em; position: absolute; top: 0; left: 0; width: 100%;">Bảng Xe</h4>
        <div id="bus"></div>
        <div class="nutthaotac" style="padding-right: 0;">
            <a href="javascript:void(0)" onclick="window.open('{{url("admin/addxe")}}')" title="Thêm  Xe">
                <i class="glyphicon glyphicon-plus"></i>Thêm
            </a>
            <a href="javascript:void(0)" onclick="refreshXE()" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>Reset
            </a>
            <a href="javascript:void(0)" onclick="showFull(this,'bus',obj,objlen)">
                <i class="glyphicon glyphicon-resize-full"></i>
            </a>
        </div>
    </div>
@endsection
@section("excontent")
	<div class="modal fade" id="viewmap">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
					<button class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tên Trạm Dừng</h4>
                </div>
                <div class="modal-body" style="height: 600px">
                </div>
                <div class="modal-footer">
                    <span class="btn btn-danger" data-dismiss="modal" data-target="#viewmap">Close</span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("script")
    <script>
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[7].classList.add('selected');
        option[7].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/bus-hover.png")}}');
        var typebus = {!!json_encode($typebus)!!};
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
                window.open("{{url('/admin/addxe')}}" + "/" + ui.rowData["Mã"]);
            }
        };
        obj.colModel = [
            {
                title: "Thao tác",
                width: 70,
                editor: false,
                dataIndx: "View",
                align: 'center',
                render: function (ui) {
                    var str = '';
                    str += '<a title="Edit" id="idEditBus" ><i class="glyphicon glyphicon-edit  text-success" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    str += '<a title="Delete" id="idDelBus" ><i class="glyphicon glyphicon-remove  text-danger" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    return str;
                },
                postRender: function (ui) {
                    var rowData = ui.rowData,
                        $cell = this.getCell(ui);
                    //add button
                    $cell.find("a#idEditBus")
                        .unbind("click")
                        .bind("click", function (evt) {
                            window.open("{{url('admin/addxe')}}"+"/"+rowData["Mã"]);
                        });
                    $cell.find("a#idDelBus")
                        .unbind("click")
                        .bind("click", function (evt) {
                            if(confirm("Bạn chắc chắn muốn xóa?"))
                                location.assign("{{url('admin/delxe')}}"+"/"+rowData["Mã"]);
                        });
                }
            },
            {
                title: "Biển số",
                width: 200,
                dataIndx: "Biển_số",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Biển số"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Loại xe",
                width: 100,
                dataIndx: "Mã_loại_xe",
                dataType: "string",
                editor: false,
                align: "center",
                render: function (ui) {
                    return typebus[ui.rowData['Mã_loại_xe']];
                },
                filter: {
                    type: "select",
                    condition: "equal",
                    options: function (ui) {
                        var opts = [{ '': '[ All ]'}];
                        var properties = Object.getOwnPropertyNames(typebus);
                        for (var i = 0; i < properties.length; i++) {
                            var obj = {};
                            obj[properties[i]] = typebus[properties[i]];
                            opts.push(obj);
                        }
                        return opts;
                    },
                    listeners: ["change"]
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
                title: "Vị trí",
                width: 70,
                dataIndx: "location",
                dataType: "string",
                editor: false,
                align: "center",
                render: function(ui){
                    var str = "<a href='javascript:void(0)' data-toggle='modal' data-target='#viewmap' onclick='openmap("+ui.rowData['location']+",\""+ui.rowData['Biển_số']+"\")' title='Xem vị trí'><i class='glyphicon glyphicon-eye-open' style='color: #00bf00;'></i></a>";
                    return str;
                }
            },
            {
                title: "Lần bảo trì gần nhất",
                width: 200,
                dataIndx: "Ngày_bảo_trì_gần_nhất",
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
                title: "Lần bảo trì tiếp theo",
                width: 170,
                dataIndx: "Ngày_bảo_trì_tiếp_theo",
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
            }
        ];
        var objlen = 0;
        for(var i =0; i<obj.colModel.length;i++){
            objlen+=obj.colModel[i].width;
        }
        $(function () {
            obj.dataModel = {
                data: {!! json_encode($bus) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
            var $grid = $("#bus").pqGrid(obj);
            if(objlen <= document.getElementById('bus').offsetWidth){
                $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
            }
            else{
                $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
            }
        });
        function refreshXE(){
            $.ajax({
                type:'POST',
                url:'{{asset("/admin/retrievedata")}}',
				data: {
					_token: '{{csrf_token()}}',
					typedata: 'xe'
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
                        var $grid = $("#bus").pqGrid(obj);
                        if(objlen <= document.getElementById('bus').offsetWidth){
                            $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                        }
                        else{
                            $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                        }
						$("#bus").pqGrid("reset",{filter : true});
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
		function openmap(x,y,bienso){
            var mapOptions = {
                center: new google.maps.LatLng(x, y),
                zoom: 16,
                mapTypeId:google.maps.MapTypeId.ROADMAP
            };
			var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(x, y)
            });
            var map = new google.maps.Map(document.getElementById("viewmap").getElementsByClassName("modal-body")[0],mapOptions);
            marker.setMap(map);
			infowindow.setContent(bienso);
			infowindow.open(map, marker);
			$("#viewmap .modal-title").html("Xe "+bienso);
        }
    </script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78"></script>
@endsection
