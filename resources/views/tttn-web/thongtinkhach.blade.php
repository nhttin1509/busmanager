@extends('tttn-web.main')
@section('title')
    Liên hệ
@endsection
@section('content')
    <div class="modal fade" id="doithongtin" role="dialog">
        <div class="modal-dialog" style="width: 500px;">
      <!-- Đổi thông tin-->
      <div class="modal-content">
        <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
          <button type="button" class="close" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
          <h4 class="modal-title">Đổi Thông Tin</h4>
        </div>
        <div class="modal-body">
            <div class="hienloi">
            <div class="dnloi"></div>
            <div class="dnloi2"></div>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                 <input type="text" class="form-control dttname"  placeholder="Họ và tên">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-hourglass"></span></span>
                 <input type="date" class="form-control dttngaysinh">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-transgender-alt"></i></span>
                 <label class="checkbox-inline">
                        <input type="radio" name="txtgioitinh" value="1" checked>Nam
                </label>
                <label class="checkbox-inline">
                        <input type="radio" name="txtgioitinh" value="2">Nữ
                </label>
            </div>
            <br>
             <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                 <textarea class="form-control dttdiachi"></textarea>
            </div>
            <br>
             <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                <input  type="text" class="form-control dttemail" placeholder="Email">
             </div>
            <br>
        </div>
        <div class="modal-footer">
            <button class=" btn btn-success capnhat">Cập nhật</button>
          <button type="button" class="btn btn-danger colsedoithongtin" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
        </div>
    </div>
    <div  class="modal fade" id="doimatkhau" role="dialog">
         <div class="modal-dialog" style="width: 450px;">
    
      <!-- Đổi mật khẩu-->
      <div class="modal-content">
        <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
          <button type="button" class="close" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
          <h4 class="modal-title">Đổi Mật Khẩu</h4>
        </div>
        <div class="modal-body">
            <div class="hienloi">
            <div class="dmkloi"></div>
            <div class="dmkloi2"></div>
            <div class="dmkloi3"></div>
            <div class="dmkloi4"></div>
            <div class="thanhcong"></div>
            </div>
             <div class="input-group">
                <span class="input-group-addon"> <span class="glyphicon glyphicon-briefcase"></span></span>
                <input type="password" class="form-control mkcu"  placeholder="Mật khẩu cũ !">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                <input type="password" class="form-control mkmoi"  placeholder="Mật khẩu mới !">
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-repeat"></i></span>
                <input type="password" class="form-control remkmoi"  placeholder="Xác nhận Mật khẩu !">
            </div>
            <br>
        </div>
        <div class="modal-footer">
            <button class="btn btn-success capnhatmk" >Cập nhật</button>
          <button type="button" class="btn btn-danger closedoimatkhau" data-dismiss="modal">Close</button>
        </div>
      </div>       
     </div>
     </div>
    </div>
     <!-- Hiển thị thông tin khách -->
    <div class="thongtinkhach1">
    <div class="mainthongtinkhach">
        <div class="tenthongtinkhach"><h3>THÔNG TIN CÁ NHÂN</h3></div>
        @foreach($thongtinkhach as $t)
        <table style="margin: 1em; margin-left: 4em;">
            <tr>
                <td><strong>Số điện thoại: </strong></td>
                <td>{{$t->Sđt}}</td>
            </tr>
            <tr>
                <td><strong>Tên:  </strong></td>
                @if($t->Tên != NULL)
                    <td class="tenkh">{{$t->Tên}}</td>
                @else
                    <td><span>Thông tin chưa cập nhật !</span></td>
                @endif
            </tr>
            <tr>
                <td><strong>Ngày sinh: </strong></td>
                @if($t->Ngày_sinh != Null)
                <td class="ngaysinhkh">{{$t->Ngày_sinh}}</td>
                 @else
                    <td><span>Thông tin chưa cập nhật !</span></td>
                @endif
            </tr>
            <tr>
                <td><strong>Giới tính: </strong></td>
                @if($t->{"Giới tính"} != Null)
                    @if($t->{"Giới tính"} == 1)
                        <td>Nam</td>
                    @else
                        <td>Nữ</td>
                    @endif
                @else
                    <td><span>Thông tin chưa cập nhật !</span></td>
                @endif
            </tr>
            <tr>
                <td><strong>Địa chỉ: </strong></td>
                @if($t->{"Địa chỉ"} != Null)
                    <td class="diachikh">{!!$t->{"Địa chỉ"}!!}</td>
                @else
                    <td><span>Thông tin chưa cập nhật !</span></td>
                @endif
            </tr>
            <tr>
                <td><strong>Email: </strong></td>
                @if($t->Email != Null)
                    <td class="emailkh">{{$t->Email}}</td>
                @else
                    <td><span>Thông tin chưa cập nhật !</span></td>
                @endif
            </tr>
            <tr style="border: none;">
                 <!-- button đổi thông tin -->
                <td><button type="button" class="btn btn btn-warning btn-lg buttondoithongtin" data-toggle="modal" data-target="#doithongtin">Đổi thông tin</button></td>
                 <!-- button đổi mật khẩu -->
                <td><button class="btn btn btn-warning btn-lg buttondoimatkhau" data-toggle="modal" data-target="#doimatkhau">Đổi mật khẩu</button></td>
            </tr>
        </table>
        @endforeach
    </div>
     <!-- Lịch sử chuyến đi -->
    <div class="lichsudi">
        <?php $i=0;?>
        <div class="tenthongtinkhach"><h3>Lịch Sử Đã Đặt</h3></div>
             <table>
            <tr>
                <th>Tuyến</th>
                <th>Giờ Xuất Bến</th>
                <th>Thời Gian Đến</th>
                <th>Loại Xe</th>
                <th>Chổ Ngồi</th>
                <th>Giá</th>
            </tr>
            @foreach($lichsudi as $t)
                <tr class="dongthongtinve" data-id = {{ $i }}>
                    <td><span>{{$t->Nơi_đi}} -> {{$t->Nơi_đến}}</span></td>
                    <td><span>{{$t->Ngày_xuất_phát}} : {{$t->Giờ_xuất_phát}}</span></td>
                    <td><span>{{$t->Thời_gian_đến_dự_kiến}}</span></td>
                    <td><span>{{($t->Loại_ghế==1)? 'Giường Nằm':'Ghế Ngồi'}}</span></td>
                     <td><span>{{$t->Vị_trí_ghế}}</span></td>
                    <td><span>{{($t->Tiền_vé)/1000}}.000 VNĐ</span></td>
                </tr>
                <?php $i++; ?>
                @endforeach
        </table>
    </div>
</div>
@endsection
@section('excontent')
    <div id="thongtinve" class="modal fade">
        <div class="modal-dialog" style="width: 500px;">
            <div class="modal-content">
                <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
                    <button class="close" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
                    <h4 class="modal-title">Thông Tin Vé</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Nơi đi: </span>
                        <input id="Noidi" type="text" readonly class="form-control" >
                      </div>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Nơi đến: </span>
                        <input id="Noiden" type="text" readonly class="form-control" >
                      </div>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Thời gian đi: </span>
                        <input id="Thoigiandi" type="text" readonly class="form-control" >
                      </div>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Thời gian đến: </span>
                        <input id="Thoigianden" type="text" readonly class="form-control" >
                      </div>
                </div>
                 <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Loại xe: </span>
                        <input id="Loaixe" type="text" readonly class="form-control" >
                      </div>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Tiền vé: </span>
                        <input id="Tienve" type="text" readonly class="form-control" >
                      </div>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <span class="input-group-addon">Vị trí: </span>
                        <textarea id="Vitri" type="text" readonly class="form-control" ></textarea>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            /*Xử lý đổi thông tin*/
            $(".buttondoithongtin").click(function(){
                var tenkh = $(".tenkh").html();
                var diachikh = $(".diachikh").html();
                var emailkh = $(".emailkh").html();
                var ngaysinh = $(".ngaysinhkh").html();
                $(".dttname").val(tenkh);
                 $(".dttdiachi").val(diachikh);
                  $(".dttemail").val(emailkh);
                  $(".dttngaysinh").val(ngaysinh);
            });
            /*Xử lý đóng đổi thông tin*/
            $(".colsedoithongtin").click(function(){
                $(".dnloi").html("");
                $(".dnloi2").html("");
            });
            /*Cập nhật đổi thông tin*/
            $(".capnhat").click(function(){
                var kt=true;
                var bieuthuc = /[a-zA-Z][^#&<>\"~;$^%{}?]{1,50}$/;
                var bieuthuc2 = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/;
                var name = $(".dttname").val();
                var gioitinh = document.getElementsByName("txtgioitinh");
                 for (var i = 0; i < gioitinh.length; i++){
                    if (gioitinh[i].checked == true){
                       gt = gioitinh[i].value;
                    }
                }
                var ngaysinh = $(".dttngaysinh").val();
                var diachi = $(".dttdiachi").val();
                var email = $(".dttemail").val();
                var ma = {{$thongtinkhach[0]->Mã}}
                if(email.search(bieuthuc2)==-1){
                     $(".dnloi2").html("<div class='alert alert-danger'><strong>Email bạn nhập không hợp lệ !</strong></div>");
                     kt = false;
                 }
                  else{
                    $(".dnloi").html("");
                   }
                 if(name.search(bieuthuc)==-1){
                     $(".dnloi").html("<div class='alert alert-danger'><strong>Tên bạn nhập không hợp lệ !</strong></div>");
                     kt = false;
                 }
                  else{
                    $(".dnloi").html("");
                   }
                 
                 if(kt==true){
                        $.ajax({
                            url: '{{route("capnhattt")}}',
                            type: 'POST',
                            data: {
                                _token: '{{csrf_token()}}',
                                MA: ma,
                                NAME: name,
                                NGAYSINH: ngaysinh,
                                GT: gt,
                                DIACHI: diachi,
                                EMAIL: email
                            },
                            success: function (data) {
                               if(data.kq==1){
                                    $(".doithongtin").fadeOut();
                                    location.assign("{{asset('thongtin')}}/"+ma);
                               }
                               else{
                                    $(".dnloi2").html("<div class='alert alert-danger'><strong>Email bạn nhập đã có người đăng ký !</strong></div>");
                               }
                            }
                     });
                    }
               
            });
            /* xem thong tin ve*/
            $(".dongthongtinve").dblclick(function(){
                var id = $(this).attr("data-id");
                var lichsudi = JSON.parse('{!!json_encode($lichsudi)!!}');
                $("#Noidi").val(""+lichsudi[id].Nơi_đi+"");
                $("#Noiden").val(""+lichsudi[id].Nơi_đến+"");
                $("#Thoigiandi").val(""+lichsudi[id].Ngày_xuất_phát+" : "+lichsudi[id].Giờ_xuất_phát+"");
                if(lichsudi[id]==1){
                     $("#Loaixe").val("Giường Ngồi");
                }
                else{
                    $("#Loaixe").val("Ghế Ngồi");
                }
                $("#Tienve").val(""+lichsudi[id].Tiền_vé/1000+".000 VNĐ");
                $("#Vitri").val(""+lichsudi[id].Vị_trí_ghế+"");
                $("#Thoigianden").val(""+lichsudi[id].Thời_gian_đến_dự_kiến+"");
               $("#thongtinve").modal("show"); 
            });
            /*Đổi mật khẩu*/
            $(".closedoimatkhau").click(function(){
                $(".doimatkhau").fadeOut();
                $(".dmkloi").html("");
                $(".dmkloi2").html("");
                $(".dmkloi3").html("");
                $(".dmkloi4").html("");
                $(".thanhcong").html("");
                $(".mkcu").val("");
                $(".mkmoi").val("");
                $(".remkmoi").val("");
            });
            /*Xử lý cập nhật mật khẩu*/
            $(".capnhatmk").click(function(){
                var kt2 = true;
                var xemmk=true;
                var mkcu = $(".mkcu").val();
                var mkmoi =$(".mkmoi").val();
                var remkmoi = $(".remkmoi").val();
                var ma = {{$thongtinkhach[0]->Mã}};
                if(mkcu==""){
                    $(".dmkloi").html("<div class='alert alert-danger'><strong>Mật khẩu cũ không được để trống !</strong></div>");
                    kt2 = false;
                }
                else if(mkcu.length> 30 || mkcu.length <6){
                    $(".dmkloi").html("<div class='alert alert-danger'><strong>Độ dài mật khẩu cũ ít nhất 6 ký tự và  không quá 30 ký tự !</strong></div>");
                    kt2 = false;
                }
                else{
                     $(".dmkloi").html("");
                }
                if(mkmoi==""){
                    $(".dmkloi2").html("<div class='alert alert-danger'><strong>Mật khẩu mới không được để trống !</strong></div>");
                    kt2 = false;
                    xemmk = false;
                }
                else if(mkmoi.length> 30 || mkmoi.length <6){
                    $(".dmkloi2").html("<div class='alert alert-danger'><strong>Độ dài mật khẩu mới ít nhất 6 ký tự và  không quá 30 ký tự !</strong></div>");
                    kt2 = false;
                    xemmk = false;
                }
                else{
                    $(".dmkloi2").html("");
                }
                /*rematkhau*/
                if(remkmoi==""){
                    $(".dmkloi3").html("<div class='alert alert-danger'><strong>Nhập lại mật khẩu không được để trống !</strong></div>");
                    kt2 = false;
                     xemmk = false;
                }
                else if(remkmoi.length > 30 || remkmoi.length < 6){
                    $(".dmkloi3").html("<div class='alert alert-danger'><strong>Nhập lại mật khẩu có độ dài không đúng!</strong></div>");
                    kt2 = false;
                     xemmk = false;
                }
                else{
                    $(".dmkloi3").html("");
                }
                if(xemmk==true && mkmoi!=remkmoi){
                         $(".dmkloi4").html("<div class='alert alert-danger'><strong>Nhập lại mật khẩu không giống mật khẩu!</strong></div>");
                            kt2 = false;
                }
                else{
                    $(".dmkloi4").html("");
                }
               if(kt2==true){
                    $.ajax({
                            url: '{{route("doimatkhau")}}',
                            type: 'POST',
                            data: {
                                _token: '{{csrf_token()}}',
                                MA: ma,
                                MKCU: mkcu,
                                MKMOI: mkmoi,
                            },
                            success: function (data) {
                               if(data.kq==1){
                                   $(".thanhcong").html("<div class='alert alert-success'><strong>Bạn đổi mật khẩu thành công !</trong></div>");
                                   $(".mkcu").val("");
                                    $(".mkmoi").val("");
                                    $(".remkmoi").val("");
                               }
                               else{
                                $(".dmkloi").html("<div class='alert alert-danger'><strong>Bạn nhập mật khẩu không đúng !</strong></div>");
                               }
                            }
                     });
               }
            });
        });
    </script>
@endsection

