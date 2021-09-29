@extends("quantrivien.main")
@section("content")
    <div class="content tramdung show row">
        <div class="col-lg-6">
            <div id="duongdi"></div>
            <a href="javascript:void(0)" onclick="window.open('{{url("admin/addtramdung")}}')" style="width: 2em; height: 2em; line-height: 2em; background: white; font-size: 1.5em; position: absolute; bottom: 1em; left: 2em; box-shadow: 0 0 5px black; border-radius: 50%;" title="Thêm Đường Đi">
                <i class="glyphicon glyphicon-plus"></i>
            </a>
            <a href="javascript:void(0)" onclick="refreshTD()" style="width: 2em; height: 2em; line-height: 2em; background: white; font-size: 1.5em; position: absolute; bottom: 4em; left: 2em; box-shadow: 0 0 5px black; border-radius: 50%;" title="Làm Mới">
                <i class="glyphicon glyphicon-refresh"></i>
            </a>
        </div>
        <div class="col-lg-6"></div>
    </div>
@endsection
@section("excontent")
    <div class="modal fade" id="viewmap">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
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
        option[7].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/parking-hover.png")}}');
        $(function () {
            var employee = {!!json_encode($employee)!!};
            var obj = {
                width: '100%',
                height: '100%',
                showTop: false,
                showBottom: false,
                collapsible: false,
                showHeader: true,
                filterModel: {on: true, mode: "AND", header: true},
                scrollModel: {autoFit: true},
                resizable: false,
                roundCorners: false,
                rowBorders: true,
                postRenderInterval: -1,
                hwrap: true,
                columnBorders: false,
                selectionModel: { type: 'row', mode: 'single' },
                numberCell: { show: true, title: 'STT', width: 50, align: 'center'},
                stripeRows: true,
                cellDblClick: function (event,ui) {
                    window.open("{{url('/admin/addtramdung')}}" + "/" + ui.rowData["Mã"]);
                }
            };
            obj.colModel = [
                {
                    title: "Tên",
                    width: 200,
                    dataIndx: "Tên",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    filter: {
                        type: 'textbox',
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
                    filter: {
                        type: 'textbox',
                        condition: 'contain',
                        listeners: ['keyup']
                    },
                    render: function(ui){
                        var str = "<a href='javascript:void(0)' data-toggle='modal' data-target='#viewmap' onclick='openmap("+ui.rowData['Tọa_độ']+")' >"+ui.rowData['Tọa_độ']+"</a>";
                        return str;
                    }
                },
                {
                    title: "Nhân viên tạo",
                    width: 200,
                    dataIndx: "Mã_nhân_viên_tạo",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    // filter: {
                    //     type: 'textbox',
                    //     condition: 'contain',
                    //     listeners: ['keyup']
                    // },
                    render: function(ui){
                        return employee[ui.rowData['Mã_nhân_viên_tạo']];
                    }
                },
                {
                    title: "Nhân viên chỉnh sửa",
                    width: 170,
                    dataIndx: "Mã_nhân_viên_chỉnh_sửa",
                    dataType: "string",
                    editor: false,
                    align: 'center',
                    // filter: {
                    //     type: 'textbox',
                    //     condition: 'contain',
                    //     listeners: ['keyup']
                    // },
                    render: function(ui){
                        return employee[ui.rowData['Mã_nhân_viên_chỉnh_sửa']];
                    }
                },
                {
                    title: "Action",
                    width: 100,
                    editor: false,
                    dataIndx: "View",
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
                }
            ];

            obj.dataModel = {
                data: {!! json_encode($busstop) !!},
                location: "local",
                sorting: "local",
                sortDir: "down"
            };
            obj.pageModel = {type: 'local', rPP: 20, rPPOptions: [20, 30, 40, 50]};
            var $grid = $("#busstop").pqGrid(obj);
            $grid.pqGrid("refreshDataAndView");
        });
        function refreshTD(){
            $("#busstop").pqGrid("reset",{filter : true});
        }
        function openmap(x,y){
            var mapOptions = {
                center: new google.maps.LatLng(x, y),
                zoom: 16,
                mapTypeId:google.maps.MapTypeId.ROADMAP
            };
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(x, y)
            });
            var map = new google.maps.Map(document.getElementById("viewmap").getElementsByClassName("modal-body")[0],mapOptions);
            marker.setMap(map);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78"></script>
@endsection
