@extends("quantrivien.main")
@section('title')
	Quản lý Trạm dừng
@endsection
@section("content")
    <div class="content tramdung row show" style="overflow: hidden; position: relative; padding: 3em 1em 1em;">
        <h4 style="padding: .5em; position: absolute; top: 0; left: 0; width: 100%;">Bảng Trạm Dừng</h4>
        <div id="busstop"></div>
        <div class="nutthaotac" style="padding-right: 0;">
            <a href="javascript:void(0)" onclick="window.open('{{url("admin/addtramdung")}}')" title="Thêm Trạm Dừng">
                <i class="glyphicon glyphicon-plus"></i>Thêm
            </a>
            <a href="javascript:void(0)" onclick="refreshTD()" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>Reset
            </a>
            <a href="javascript:void(0)" onclick="showFull(this,'busstop',obj,objlen)">
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
        option[8].classList.add('selected');
        option[8].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/parking-hover.png")}}');
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
            hwrap: true,
            columnBorders: false,
            selectionModel: { type: 'row', mode: 'single' },
            hoverMode: 'row',
            numberCell: { show: true, title: 'STT', width: 50, align: 'center'},
            stripeRows: true,
			freezeCols: 1,
            cellDblClick: function (event,ui) {
                window.open("{{url('/admin/addtramdung')}}" + "/" + ui.rowData["Mã"]);
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
                    str += '<a title="Edit" id="idEditBusStop" ><i class="glyphicon glyphicon-edit  text-success" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    str += '<a title="Delete" id="idDelBusStop" ><i class="glyphicon glyphicon-remove  text-danger" style="padding-right: 5px; cursor: pointer;"></i></a>';
                    return str;
                },
                postRender: function (ui) {
                    var rowData = ui.rowData,
                        $cell = this.getCell(ui);
                    //add button
                    $cell.find("a#idEditBusStop")
                        .unbind("click")
                        .bind("click", function (evt) {
                            window.open("{{url('admin/addtramdung')}}"+"/"+rowData["Mã"]);
                        });
                    $cell.find("a#idDelBusStop")
                        .unbind("click")
                        .bind("click", function (evt) {
                            if(confirm("Bạn chắc chắn muốn xóa?"))
                                location.assign("{{url('admin/deltramdung')}}"+"/"+rowData["Mã"]);
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
                    attr: 'placeholder="Tìm theo Tên"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Tọa độ",
                width: 70,
                dataIndx: "Tọa_độ",
                dataType: "string",
                editor: false,
                align: "center",
                render: function(ui){
                    var str = "<a href='javascript:void(0)' data-toggle='modal' data-target='#viewmap' onclick='openmap("+ui.rowData['Tọa_độ']+",\""+ui.rowData['Tên']+"\")' title='Xem vị trí'><i class='glyphicon glyphicon-eye-open' style='color: #00bf00;'></i></a>";
                    return str;
                }
            },
            {
                title: "Nhân viên tạo",
                width: 170,
                dataIndx: "Nhân_viên_tạo",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Nhân viên tạo"',
                    cls: 'filterstyle',
                    condition: 'contain',
                    listeners: ['keyup']
                }
            },
            {
                title: "Nhân viên chỉnh sửa",
                width: 170,
                dataIndx: "Nhân_viên_chỉnh_sửa",
                dataType: "string",
                editor: false,
                align: 'center',
                filter: {
                    type: 'textbox',
                    attr: 'placeholder="Tìm theo Nhân viên sửa"',
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
                data: {!! json_encode($busstop) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 50, rPPOptions: [50, 100, 150, 200]};
            var $grid = $("#busstop").pqGrid(obj);
            if(objlen <= document.getElementById('busstop').offsetWidth){
                $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
            }
            else{
                $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
            }
        });
        function refreshTD(){
            $.ajax({
                type:'POST',
                url:'{{asset("/admin/retrievedata")}}',
				data: {
					_token: '{{csrf_token()}}',
					typedata: 'tramdung'
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
                        var $grid = $("#busstop").pqGrid(obj);
                        if(objlen <= document.getElementById('busstop').offsetWidth){
                            $grid.pqGrid('option','scrollModel',{autoFit: true}).pqGrid("refreshDataAndView");
                        }
                        else{
                            $grid.pqGrid('option','scrollModel',{horizontal: true,autoFit: false,flexContent: true}).pqGrid("refreshDataAndView");
                        }
						$("#busstop").pqGrid("reset",{filter : true});
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
        function openmap(x,y,name){
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
			infowindow.setContent(name);
			infowindow.open(map, marker);
			$("#viewmap .modal-title").html(name);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78"></script>
@endsection
