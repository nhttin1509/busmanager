@extends('tttn-web.main')
@section('title')
    Đặt vé
@endsection
@section('content')
    <!-- Phần bước -->
        <div class="buoc">
            <ul>
                <li style="background: #f57812; color: #FFF;" class="stay tay">Tìm Chuyến</li>
                <li>Chọn Chuyến</li>
                <li>Chi Tiết Vé</li>
            </ul>
        </div>
    <!-- kết thúc phần bước -->
    <!-- Phần tìm vé -->
        <div class="maindatve row">
            <!-- Form thông tin -->
            <form name="formdatve" action="{{route('chuyenxe1')}}" method="POST">
                 <input type="hidden" name="_token" value="{{csrf_token()}}">
                 <div class=" col-lg-4 diadiemdatve">
                    <label>Chọn Nơi Khởi Hành</label>
                    <div class="the">
                         <i class="fa fa-bus"></i>
                         <input type="text" name="noidi" class="form-control txtnoidi" list="diadiem" placeholder="Nơi đi">
                     </div>
                     <div class="chonloaixe">
                                   <p>Chọn loại xe</p>
                                       <input checked="checked" name="loaixe" type="radio" value="1" />Giường nằm
                                       <br>
                                       <input type="radio" name="loaixe" value="0" />Ghế ngồi                            
                               </div>
                 </div>   
                 <div class=" col-lg-4 diadiemdatve">
                    <label>Chọn Nơi Đến</label>
                    <div class="the">
                         <i class="fa fa-bus"></i>
                         <input type="text" name="noiden" class="form-control txtnoiden" list="diadiem" placeholder="Nơi đến">
                     </div>
                 </div>  
                  <div class="col-lg-3 ngaydidatve">
                       <label>Chọn Thời Gian đi </label>
                       <div class="form-group" style="width: 350px;">
                           <div class='input-group date' style="box-shadow: 0 3px #F3AD45; ">
                               <span class="input-group-addon" style="background: #FFF; border: none;">
                               <span class="glyphicon glyphicon-calendar" style="color: #f57812;"></span>
                               </span>
                               <input type='date' class="form-control txtngaydi" style="border: none;" name="Ngaydi" id="txtdate" />
                           </div>
                        </div>
                        <div class="tim">
                                   <i class="fa fa-ticket icon-flat bg-btn-actived"></i>
                                    <button type="button" class="btn" id="chontimve" ><a href="javascript:void(0)" >Tìm vé</a></button>
                        </div>
                    </div>
                </form>
           
        <!-- Kết thúc form thông tin -->
        </div>
    <!-- Kết thúc phần tìm vé -->
@endsection
@section('excontent')
     <datalist id="diadiem" style="display: none;">
        @isset($tinh)
            @foreach($tinh as $t)
                <option value="{{$t->Tên}}" label="{{$t->Tên_không_dấu}}">
            @endforeach
        @endisset
    </datalist>
    <div id="hienloidatve" class="modal fade">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content">
                <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
                    <button class="close" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
                    <h4 class="modal-title">Thông báo</h4>
                </div>
                <div class="modal-body">
                   
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        /*Xử lý input date*/
        function chonngay(){
            var d = new Date();
            var ngay = d.getDate()<10? ('0'+d.getDate()):d.getDate();
            var thang = d.getMonth() + 1 <10? ('0'+d.getMonth()+1):d.getMonth()+1;
            var nam = d.getFullYear();
            document.getElementById("txtdate").min=nam+"-"+thang+"-"+ngay;
            document.getElementById("txtdate").value= nam+"-"+thang+"-"+ngay;
        }
        chonngay();
         $(document).ready(function(){
            $("#chontimve").click(function(){
                var noidi = $(".txtnoidi").val();
                var noiden = $(".txtnoiden").val();
                var ngaydi = $(".txtngaydi").val();
                var diadiem = $("#diadiem option");
                var loaixe = document.getElementsByName("txtloaixe");
                 for (var i = 0; i < loaixe.length; i++){
                    if (loaixe[i].checked == true){
                       lx = loaixe[i].value;
                    }
                }
                /*ngay di*/
                var d = new Date();
                var ngay = d.getDate()<10? ('0'+d.getDate()):d.getDate();
                var thang = d.getMonth() + 1 <10? ('0'+d.getMonth()+1):d.getMonth()+1;
                var nam = d.getFullYear();
                var ngayhientai =nam+"-"+thang+"-"+ngay;
                
                /*kiem tra noi di*/
                if(noidi == "" || noiden =="" || ngaydi==""){
                    $("#hienloidatve .modal-body").html("Bạn phải nhập đủ thông tin!");
                    $("#hienloidatve").modal("show");
                }
                else{
                    if(Date.parse(ngaydi)<Date.parse(ngayhientai)){  
                        $("#hienloidatve .modal-body").html("Ngày đi không được nhỏ hơn ngày hiện tại!");
                        $("#hienloidatve").modal("show");
                     }
                     else{
                    var ktnoidi =false;
                    var ktnoiden =false;
                    for(i=0;i<diadiem.length;i++){
                        if(noidi == diadiem[i].value)
                        {
                            ktnoidi = true;

                        }                   
                            if(noiden == diadiem[i].value)
                        {
                            ktnoiden = true;
                            
                        }
                    }

                    if(ktnoidi == false && ktnoiden == false){
                         $("#hienloidatve .modal-body").html("Bạn phải nhập đúng nơi đi, nơi đến!");
                         $("#hienloidatve").modal("show");    
                    }
                    else if(ktnoidi == false){
                        $("#hienloidatve .modal-body").html("Bạn phải nhập đúng nơi đi!");
                         $("#hienloidatve").modal("show");  
                    }
                    else if(ktnoiden == false){
                        $("#hienloidatve .modal-body").html("Bạn phải nhập đúng nơi đến!");
                         $("#hienloidatve").modal("show");  
                    }
                    else if(noidi == noiden){
                         $("#hienloidatve .modal-body").html("Nơi đi không được trùng với nơi đến!");
                         $("#hienloidatve").modal("show"); 
                    }
                    else{
                         document.forms["formdatve"].submit();
                    }
                }
                }
            });     
         });
    </script>
@endsection