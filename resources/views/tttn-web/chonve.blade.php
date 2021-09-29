@extends('tttn-web.main')
@section('title')
    Chọn vé
@endsection
@section('content')
    <!-- Thêm sự kiện onload vào thẻ td  -->
      <script>
          $(function(){
            $('td[onload]').trigger('onload');
          });
      </script>
    <!-- Phần bước  -->
      <div class="buoc">
          <ul>
              <li onclick="vetrangtruoc2()" class="tay">Tìm Chuyến</li>
              <li onclick="vetrangtruoc()" class="tay">Chọn Vé</li>
              <li style="background: #f57812; color: #FFF;" class="stay tay">Chi Tiếc vé</li>
          </ul>
      </div>
    <!-- kết thức phần bước  -->
    <!-- Phần chọn vé  -->
    <div class="chonvemain">
        <!-- Thông tin chuyến xe -->
          <div class="chonveleft">
              <?php $makh=Session::get('makh'); ?>
              @foreach($chonve as $t)
              <h3>Thông tin vé</h3>
              <p><i class="fa fa-bus"></i> Nơi Khởi Hành: <a>{{$t->Nơi_đi}}</a></p><br>
              <p><i class="fa fa-bus"></i> Nơi đến: <a>{{$t->Nơi_đến}}</a></p> <br>
              <p><span class="glyphicon glyphicon-time"></span> Ngày đi: {{$t->Ngày_xuất_phát}}  {{$t->Giờ_xuất_phát}}</p><br>
              <p><span class="glyphicon glyphicon-bed"></span> Loại Ghế: {{($t->Loại_ghế==1)? 'Giường Nằm':'Ghế Ngồi'}} </p><br>
              <p><i class="fa fa-balance-scale"></i> Giá vé: {{$t->Tiền_vé/1000}}.000 VNĐ</p><br>
              <p><i class="fa fa-address-card-o"></i> Vé đang chọn: <b class="vedangchon">
                  @foreach($ve as $v)
                    @if($v->Trạng_thái == 2 && $v->Mã_khách_hàng == $makh )
                        {{$v->Vị_trí_ghế}}
                    @endif
                  @endforeach
              </b> </p><br>
              <button type="button" style="background: #f57812; border: none;" class="btn btn-success chondatve"  data-id={{$id}}>Đặt vé</button>
          </div>
          @endforeach
        <!-- kết thưc thông tin chuyến xe -->
        <!-- Phần sơ đồ xe -->
        <div class="chonveright">
            @if($sodo[0]->Loại_ghế == 1)
            <div class="tengiuong"><h3>Sơ đồ xe</h3></div>
            <!-- Chú ý giường -->
              <div class="chuygiuong">
                  <ul>
                    <li><i class="loaighetrong"></i> &nbsp;Còn trống</li>
                    <li><i class="loaighechon"></i> &nbsp;Đang chọn</li>
                    <li><i class="loaigheban"></i> &nbsp;Đã bán</li>
                    <li><i class="loaighecochon"></i> &nbsp;Có Người Chọn</li>
                    <li><buttontype="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#chonloaidexuat" style="background: #f57812; border: none;" title="Đề xuất vị trí">Đề xuất</button></li>
                  </ul>
              </div>
            <!-- kết thúc chú ý giường -->
            <!-- Sơ đồ xe giường nằm -->
                <?php  $sd = $sodo[0]->Sơ_đồ; $dem=0; ?>
                 <div class="sodogiuong">
                  <!-- Tầng dưới -->
                   <table class="bangve tangduoi">
                    <tr>
                        <td class="giuongtaixe" title="Ghế tài xế"></td>
                        <td colspan="4"></td>
                     </tr>
                      @for($i=1;$i<7;$i++)
                      <tr>
                         @for($j=0;$j<5;$j++)
                              @if($sd[$i * 5 + $j] == "1")
                                  @if($ve[$dem]->Trạng_thái == 1)
                                  <td class="giuong" title="Giường đã bán cho khách"><div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td>
                                  @elseif($ve[$dem]->Trạng_thái ==0)
                                  <td class="giuongcontrong" title="Ghế trống" data-ma={{$ve[$dem]->Mã}}><p class="text1"  type="text"  ></p>
                                      <div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td>
                                  @elseif($ve[$dem]->Trạng_thái == 2)
                                      @if($ve[$dem]->Mã_khách_hàng == Session::get('makh'))
                                          <td class="giuongdangchon" title="Ghế Đang Chọn" onload="demnguoc2({{$ve[$dem]->Mã}},{{$ve[$dem]->TG}})" data-ma={{$ve[$dem]->Mã}}><p class="text1"  type="text"  ></p><div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td>

                                      @else
                                           <td class="giuongcochon" title="Đã Có Người Chọn" onload="demnguoc1({{$ve[$dem]->Mã}},{{$ve[$dem]->TG}})" data-ma={{$ve[$dem]->Mã}}><p class="text1"  type="text"  ></p><!-- <div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td> -->

                                      @endif
                                  @endif
                                    <?php  $dem++; ?>
                              @else
                                  <td class="giuongtrong"></td>
                              @endif
                         @endfor
                      </tr>
                      @endfor
                  </table>
                  <!-- Kết thúc tầng dưới -->
                  <!-- Tầng trên -->
                      <table class="bangve tangtren">
                        <tr>
                          <td class="giuongtaixe" title="Ghế tài xế">
                          </td>
                          <td colspan="4"></td>
                        </tr>
                          @for($i=7;$i<13;$i++)
                          <tr>
                             @for($j=0;$j<5;$j++)
                                  @if($sd[$i * 5 + $j]==1)
                                      @if($ve[$dem]->Trạng_thái == 1)
                                      <td class="giuong" title="Giường đã bán cho khách"></div><div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td>
                                      @elseif($ve[$dem]->Trạng_thái ==0)
                                      <td class="giuongcontrong" title="Ghế trống" data-ma={{$ve[$dem]->Mã}}><p class="text1"  type="text"  ></p>
                                          <div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td>
                                      @elseif($ve[$dem]->Trạng_thái == 2)
                                          @if($ve[$dem]->Mã_khách_hàng == Session::get('makh'))
                                              <td class="giuongdangchon" title="Ghế Đang Chọn" onload="demnguoc2({{$ve[$dem]->Mã}},{{$ve[$dem]->TG}})" data-ma={{$ve[$dem]->Mã}}><p class="text1"  type="text"  ></p>
                                                <div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td>

                                          @else
                                               <td class="giuongcochon" title="Đã Có Người Chọn" onload="demnguoc1({{$ve[$dem]->Mã}},{{$ve[$dem]->TG}})" data-ma={{$ve[$dem]->Mã}}>
                                                <p class="text1"  type="text"  ></p>
                                                <!-- <div class="contentgiuong">{{$ve[$dem]->Vị_trí_ghế}}</div></td> -->

                                          @endif
                                      @endif
                                        <?php  $dem++; ?>
                                  @else
                                      <td class="giuongtrong"></td>
                                  @endif
                             @endfor
                          </tr>
                          @endfor
                      </table>
                  <!-- Kết thúc tầng trên -->
                  <!-- Phần chọn tầng -->
                      <div class="tang">
                          <button class="duoi">Tầng Dưới</button>
                          <button class="tren">Tầng Trên</button>
                      </div>
                  <!-- Kết thúc chọn tầng -->
                </div>
              <!-- Kết thúc sơ đồ xe giường nằm -->
               @else
              <!-- Phần sơ đồ xe ngồi -->
                <div class="tengiuong"><h3>Sơ đồ xe</h3></div>
                <!-- Chú ý xe ngồi -->
                  <div class="chuygiuong">
                      <ul>
                        <li><i class="loaighetrong"></i> &nbsp;Còn trống</li>
                        <li><i class="loaighechon"></i> &nbsp;Đang chọn</li>
                        <li><i class="loaigheban"></i> &nbsp;Đã bán</li>
                        <li><i class="loaighecochon"></i> &nbsp;Có Người Chọn</li>
                        <li><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#chonloaidexuat" style="background: #f57812;border: none;" title="Đề xuất vị trí">Đề xuất</button></li>
                      </ul>
                  </div>
                <!-- kết thúc chú ý xe ngồi -->
                <!-- Phần sơ đồ xe ngồi -->
                  <div class="sodoghe">
                      <!-- Sơ đồ xe ngồi -->
                         <table class="bangve">
                              <?php  $sd = $sodo[0]->Sơ_đồ; $dem=0; ?>
                              @for($i=0;$i<12;$i++)
                              <tr>
                                  @for($j=0;$j<6;$j++)
                                      @if($sd[$i * 6 + $j]==1 && ($i * 6 + $j)==0)
                                          <td class="ghetaixe" title="Ghế tài xế">
                                            <img src="../images/ghetaixe2.png">
                                          </td>
                                      @elseif($sd[$i * 6 + $j] == 1)
                                          @if($ve[$dem]->Trạng_thái == 1)
                                          <td class="ghe" title="Ghế đã bán cho khách" data-ma={{$ve[$dem]->Mã}} />
                                              <img src="../images/ghe.png"/>

                                              <div class="content">{{$ve[$dem]->Vị_trí_ghế}}</div></td>
                                          @elseif($ve[$dem]->Trạng_thái ==0)
                                              <td class="ghecontrong" title="Ghế trống" data-vitri={{$ve[$dem]->Vị_trí_ghế}} data-ma={{$ve[$dem]->Mã}}><img src="../images/ghe.png">
                                                <p class="text1" type="text"></p>
                                                  <div class="content">{{$ve[$dem]->Vị_trí_ghế}}</div></td>
                                          @elseif($ve[$dem]->Trạng_thái == 2)
                                              @if($ve[$dem]->Mã_khách_hàng == Session::get('makh'))
                                                  <td class="ghedangchon" title="Ghế Đang Chọn" onload="demnguoc2({{$ve[$dem]->Mã}},{{$ve[$dem]->TG}})" data-ma={{$ve[$dem]->Mã}}><img src="../images/ghe.png">
                                                     <p class="text1" type="text"  ></p><div class="content">{{$ve[$dem]->Vị_trí_ghế}}</div></td>

                                              @else
                                                   <td class="ghecochon" title="Đã Có Người Chọn" onload="demnguoc1({{$ve[$dem]->Mã}},{{$ve[$dem]->TG}})" data-ma={{$ve[$dem]->Mã}}><img src="../images/ghe.png">
                                                     <p class="text1"  type="text"  ></p><div class="content">{{$ve[$dem]->Vị_trí_ghế}}</div></td>

                                              @endif
                                          @endif
                                          <?php $dem++; ?>
                                      @else
                                          <td class="ghetrong"></td>
                                      @endif
                                  @endfor
                              </tr>
                              @endfor
                         @endif
                      </table>
                  <!-- Kết thúc sơ đồ xe ngồi -->
              </div>
            <!-- Kết thúc phần sơ đồ xe ngồi  -->
          </div>
        <!-- Kết thúc sơ đồ xe -->
    </div>
    <!-- kết thúc phần chọn vé -->
@endsection
@section('excontent')
<div id="khongcosan" class="modal fade">
        <div class="modal-dialog" style="width: 400px;">
            <div class="modal-content">
                <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
                    <button class="close dongthongbao" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
                    <h4 class="modal-title">Thông báo</h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
  <!-- Phần vé đề xuất -->
      <div id="vedexuat" class="modal fade" role="dialog">
          <div class="modal-dialog" style="width: 400px;">

          <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
                <button type="button" class="close dongdx" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
                <h4 class="modal-title">Vé Được Đề Xuất</h4>
              </div>
              <div class="modal-body">
                <div style="text-align: left;" >
                  <h4 style="background: #f57812; text-align: center; color: #FFF; height: 40px; line-height: 40px; border-radius: 0.5em;">Vé Tốt Nhất</h4>
                  <div class="vetotnhat"></div>
                </div>
                <div style="text-align: left;">
                  <h4 style="background: #f57812; text-align: center; color: #FFF; height: 40px; line-height: 40px; border-radius: 0.5em;">Các Vé Tiếp Theo</h4>
                  <div class="vetieptheo"></div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger dongdx" data-dismiss="modal">Close</button>
              </div>
            </div>
        </div>
    </div>
  <!-- Kết thúc phần vé đề xuất -->
  <!-- Phần chọn loại đề xuất -->
    <div id="chonloaidexuat" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:400px;">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
              <button type="button" class="close" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
              <h3 class="modal-title">Bạn muốn mua vé cho ai ?</h3>
            </div>
            <div class="modal-body" style="margin-left: 20%;">
                <button type="button" class="btn dxchominh" onclick="$(body).css('padding-right','0');"  data-dismiss="modal" data-toggle="modal" data-target="#vedexuat" data-backdrop="static" style="background: #f57812; color: #FFF; height: 50px;">Cho Mình</button>
                 <button type="button" class="btn dxnguoithan" onclick="$(body).css('padding-right','0');"  data-dismiss="modal" data-toggle="modal" data-target="#dexuatchonguoithan" style="background: #f57812;color: #FFF; height: 50px; margin-left: 30px;">Người Thân</button>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
    </div>
  <!-- Kết thúc phần chọn loại đề xuất -->
  <!-- Phần đề xuất cho người thân -->
    <div id="dexuatchonguoithan" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:400px;">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header" style="background: rgb(0,64,87); color: #FFF; text-align: center;">
              <button type="button" class="close" data-dismiss="modal" style="color: white;opacity: 1;">&times;</button>
              <h3 class="modal-title">Nhập thông tin người thân !</h3>
            </div>
            <div class="modal-body">
                <div class="input-group">
                  <span class="input-group-addon" style="background: #f57812; border: none; width: 100px; color: #FFF; height: 34px;">Giới Tính</span>
                   <label class="checkbox-inline" style="margin-top: 7px;">
                        <input type="radio" name="txtgioitinh" value="1" checked>Nam
                  </label>
                  <label class="checkbox-inline" style="margin-top: 7px;">
                        <input type="radio" name="txtgioitinh" value="2">Nữ
                  </label>
                </div>
                <br>
                <div class="input-group">
                  <span class="input-group-addon" style="background: #f57812; border: none; width: 100px; color: #FFF;  height: 33px;">Độ Tuổi</span>
                  <input type="number" style="width: 116px; height: 33px;" class="txttuoi" placeholder="....(tuổi)">
                  <span class="input-group-addon" style="background: #f57812; border: none;color: #FFF;height: 34px;"><i class="fa fa-arrow-right"></i></span>
                  <input type="number" style="width: 116px; height: 33px;" class="txttuoi2" placeholder="....(tuổi)">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn chondxnguoithan" onclick="$(body).css('padding-right','0');" data-backdrop="static"  data-dismiss="modal"  data-toggle="modal" data-target="#vedexuat" style="background: #f57812; color: #FFF;">Đề xuất</button>
              <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
    </div>
  <!-- Kết thúc phần đề xuất cho người thân -->
@endsection
@section('script')
    <script type="text/javascript">
        function vetrangtruoc(){
            history.back();
        }
         function vetrangtruoc2(){
            history.back();
             history.back();
        }
        mang=[];
       /*đếm thời gian giữ vé*/
            function demnguoc(ma,thoigian){
                thoigian = thoigian -1;
                if(thoigian !=-1){
                 $("td[data-ma='"+ma+"'] .text1").html(thoigian + "s");
                  setTimeout("demnguoc("+ma+","+thoigian+")",1000);
                }
                else{
                  $("td[data-ma='"+ma+"'] .text1").hide();

                }
            }
            function demnguoc1(ma,thoigian){
               /*$("td[data-ma='"+ma+"'] .text1").show();*/
              ma = $("td[data-ma='"+ma+"']").attr("data-ma");
                thoigian = thoigian -1;
                if(thoigian !=-1){
                 $("td[data-ma='"+ma+"'] .text1").html(thoigian + "s");
                  setTimeout("demnguoc1("+ma+","+thoigian+")",1000);
                }
                else{
                  $("td[data-ma='"+ma+"'] .text1").hide();
                    $("td[data-ma='"+ma+"']").addClass("ghecontrong");
                    $("td[data-ma='"+ma+"']").removeClass("ghecochon");

                }
            }
             function demnguoc2(ma,thoigian){
              /*$("td[data-ma='"+ma+"'] .text1").show();*/
              ma = $("td[data-ma='"+ma+"']").attr("data-ma");
                thoigian = thoigian -1;
                if(thoigian !=-1){
                 $("td[data-ma='"+ma+"'] .text1").html(thoigian + "s");
                  setTimeout("demnguoc1("+ma+","+thoigian+")",1000);
                }
                else{
                  $("td[data-ma='"+ma+"'] .text1").hide();
                    $("td[data-ma='"+ma+"']").addClass("ghecontrong");
                    $("td[data-ma='"+ma+"']").removeClass("ghedangchon");

                }
            }
        /*Xử lý đặt ghế*/
            function datghe(bien,ma,vitri){
                makh = {{Session::get('makh')}};
                     bien.addClass("ghedangchon");
                     bien.removeClass("ghecontrong");
                     mang.push(ma);
                      $("td[data-ma='"+ma+"'] .text1").show();
                     demnguoc(ma,600);
                     $(".vedangchon").append("<b> "+vitri+"</b>");
                    $.ajax({
                        url: '{{route("xulydatve")}}',
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token()}}',
                            MA: ma,
                            MAKH: makh
                        },
                        success: function (data) {
                           if(data.kq == 0){
                            bien.addClass("ghecontrong");
                            bien.removeClass("ghedangchon");
                            for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                           }
                           else if(data.kq==1){
                            bien.addClass("ghe");
                            bien.removeClass("ghedangchon");
                            for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                            $("#khongcosan .modal-body").html("Ghế đã có người mua!");
                           $("#khongcosan").modal("show");
                           }
                           else if(data.kq == 2){
                            time= data.TGC;
                            bien.addClass("ghecochon");
                            bien.removeClass("ghedangchon");
                            demnguoc(ma,time);
                            for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                            $("#khongcosan .modal-body").html("Ghế Đã có người đặt!");
                           $("#khongcosan").modal("show");
                           }
                        }
                 });
            }
        /*Xử lý đặt giường*/
            function datgiuong(bien,ma){
              makh = {{Session::get('makh')}};
                            bien.addClass("giuongdangchon");
                            bien.removeClass("giuongcontrong");
                            mang.push(ma);
                            $("td[data-ma='"+ma+"'] .text1").show();
                     demnguoc(ma,600);
                    $.ajax({
                        url: '{{route("xulydatve")}}',
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token()}}',
                            MA: ma,
                            MAKH: makh
                        },
                        success: function (data) {
                          if(data.kq==0){
                            bien.addClass("giuongcontrong");
                            bien.removeClass("giuongdangchon");
                            for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                          }
                           else if(data.kq==1){
                            bien.addClass("giuong");
                            bien.removeClass("giuongdangchon");
                            for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                            alert("Xin lổi - Ghế này đã có người mua !");
                            location.reload();
                           }
                           else if(data.kq == 2){
                            bien.addClass("giuongcochon");
                            bien.removeClass("giuongdangchon");
                            for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                            alert("Xin lổi - Ghế này đã có người chọn !");
                            location.reload();
                           }
                        }
                 });
            }
        $(document).ready(function(){
          /*kích chọn vé ghế*/
             $(".bangve").delegate(".ghecontrong","click",function(){
                    ma = $(this).attr("data-ma");
                    vitri = $(this).attr("data-vitri");
                    bien = $(this);
                    datghe(bien,ma,vitri);
                });
          /*kích hủy vé ghế*/
              $(".bangve").delegate(".ghedangchon","click",function(){
                    ma = $(this).attr("data-ma");
                    bien = $(this);
                    $.ajax({
                        url: '{{route("xulydatve2")}}',
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token()}}',
                            MA: ma,
                        },
                        success: function (data) {
                           if(data.kq == 1){
                            bien.addClass("ghecontrong");
                            bien.removeClass("ghedangchon");
                             /*$("td[data-ma='"+ma+"']").off('click',demnguoc);*/
                            $("td[data-ma='"+ma+"'] .text1").hide();
                           for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                           location.reload();
                           }
                        }
                 });
            });
          /*kích chọn vé giường*/
                $(".bangve").delegate(".giuongcontrong","click",function(){
                ma = $(this).attr("data-ma");
                bien = $(this);
                datgiuong(bien,ma);
              });
          /*kích hủy chọn vé giường*/
               $(".bangve").delegate(".giuongdangchon","click",function(){
                ma = $(this).attr("data-ma");
                bien = $(this);
                $.ajax({
                    url: '{{route("xulydatve2")}}',
                    type: 'POST',
                    data: {
                        _token: '{{csrf_token()}}',
                        MA: ma,
                    },
                    success: function (data) {
                       if(data.kq == 1){
                        bien.addClass("giuongcontrong");
                        bien.removeClass("giuongdangchon");
                       for (i=0; i < mang.length; i++) {
                           if(mang[i]==ma){
                                mang[i]=null;
                                break;
                           }
                       }
                       location.reload();
                       }
                    }
                  });
                });
            /*xử lý đặt vé*/
               $(".chondatve").click(function(){
                    id = $(this).attr("data-id");
                    makh ={{Session::get('makh')}};
                    dodai = mang.length;
                    $.ajax({
                        url: '{{route("chondatve")}}',
                    type: 'POST',
                    data: {
                        _token: '{{csrf_token()}}',
                        ID: id,
                        MANG:mang,
                        MAKH:makh,
                        DODAI:dodai
                    },
                    success: function (data) {
                       location.assign("{{asset('thongtinve')}}/"+id+"/"+makh);
                    }
                    });
               });
            /*xử lý chọn tầng*/
               $(".tren").click(function(){
                    $(".tren").css({"background":"#f57812","color":"#FFF"});
                    $(".duoi").css({"background":"#CCC","color":"#000"});
                    $(".tangtren").show();
                    $(".tangduoi").hide();
               });
               $(".duoi").click(function(){
                  $(".duoi").css({"background":"#f57812","color":"#FFF"});
                  $(".tren").css({"background":"#CCC","color":"#000"});
                  $(".tangduoi").show();
                  $(".tangtren").hide();
               });
            /*kết thúc xử lý chọn tầng*/
            /* đề xuất cho mình*/
              $(".dxchominh").click(function () {
                  $(".vetotnhat").empty();
                  $(".vetieptheo").empty();
                   makh = '{{session('makh')}}';
                   machuyenxe = '{{$chonve[0]->Mã}}';
                  $.ajax({
                      url: '{{route('ticketsuggestion')}}',
                      data: {
                          _token: '{{csrf_token()}}',
                          idkhachhang: makh,
                          idchuyenxe: machuyenxe
                      },
                      type: 'post',
                      success: function (data) {
                        if(data.kq == 0){
                          alert("Xin Lôi! Trang Chưa đủ dữ liệu để đề xuất !");
                          $("#vedexuat").modal("hide");
                        }
                        else{
                          dem = data.kq.length;
                          vitri = data.kq[0].Vị_trí_ghế;
                          ma = data.kq[0].Mã;
                          $(".vetotnhat").append("<div class='vitridx'>Vị Trí: <strong>"+vitri+"</strong> <button class='giuvedx' data-ma='"+ma+"'>Chọn Vé</button><button class='huygiudx' data-ma='"+ma+"' >Bỏ Chọn</button></div>");
                          for(i=1;i<6;i++){
                            vitri = data.kq[i].Vị_trí_ghế;
                            ma = data.kq[i].Mã;
                            $(".vetieptheo").append("<div class='vitridx'>Vị Trí: <strong>"+vitri+"</strong> <button class='giuvedx' data-ma='"+ma+"'>Chọn Vé</button><button class='huygiudx' data-ma='"+ma+"'>Bỏ Chọn</button></div>");
                             $(".vetieptheo").append("<br>");
                          }
                        }
                      }
                  }) ;
               });
            /*đề xuất cho người thân*/
               $(".chondxnguoithan").click(function () {
                  $(".vetotnhat").empty();
                  $(".vetieptheo").empty();
                   machuyenxe = '{{$chonve[0]->Mã}}';
                   var kt = true;
                   var gioitinh = document.getElementsByName("txtgioitinh");
                    for (var i = 0; i < gioitinh.length; i++){
                    if (gioitinh[i].checked === true){
                       gt = gioitinh[i].value;
                    }
                  }
                  var tuoi = $(".txttuoi").val();
                  var tuoi2 = $(".txttuoi2").val();
                 $.ajax({
                      url: '{{route('ticketsuggestion')}}',
                      data: {
                          _token: '{{csrf_token()}}',
                          gioitinh: gt,
                          idchuyenxe: machuyenxe,
                          tuoimin: tuoi,
                          tuoimax: tuoi2
                      },
                      type: 'post',
                      success: function (data) {
                        if(data.kq == 0){
                          alert("Xin Lôi! Trang Chưa đủ dữ liệu để đề xuất !");
                           $("#vedexuat").modal("hide");
                        }
                        else{
                            dem = data.kq.length;
                            vitri = data.kq[0].Vị_trí_ghế;
                            ma = data.kq[0].Mã;
                            $(".vetotnhat").append("<div class='vitridx'>Vị Trí: <strong>"+vitri+"</strong> <button class='giuvedx' data-ma='"+ma+"'>Chọn Vé</button><button class='huygiudx' data-ma='"+ma+"' >Bỏ Chọn</button></div>");
                            for(i=1;i<6;i++){
                          vitri = data.kq[i].Vị_trí_ghế;
                          ma = data.kq[i].Mã;
                          $(".vetieptheo").append("<div class='vitridx'>Vị Trí: <strong>"+vitri+"</strong> <button class='giuvedx' data-ma='"+ma+"'>Chọn Vé</button><button class='huygiudx' data-ma='"+ma+"'>Bỏ Chọn</button></div>");
                           $(".vetieptheo").append("<br>");
                        }
                      }
                      }
                  }) ;
               });
                /*Chọn vé đễ xuất*/
                $("#vedexuat").delegate(".giuvedx","click",function(){
                    ma = $(this).attr("data-ma");
                    makh = {{Session::get('makh')}};
                    mang.push(ma);
                    $(this).hide();
                    $(".huygiudx[data-ma='"+ma+"']").fadeIn();
                    $.ajax({
                        url: '{{route("xulydx")}}',
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token()}}',
                            MA: ma,
                            MAKH: makh
                        },
                        success: function (data) {
                            if(data.kq==0){
                                $(".huygiudx[data-ma='"+ma+"']").hide();
                                $(".giuvedx[data-ma='"+ma+"']").fadeIn();
                                for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                              }
                            }
                            if(data.kq==1){
                              $(".huygiudx[data-ma='"+ma+"']").hide();
                              $(".giuvedx[data-ma='"+ma+"']").hide();
                              for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                              alert("Xin lổi - Ghế này đã có người mua !");
                            }
                            if(data.kq==2){
                             $(".huygiudx[data-ma='"+ma+"']").hide();
                              $(".giuvedx[data-ma='"+ma+"']").hide();
                              for (i=0; i < mang.length; i++) {
                               if(mang[i]==ma){
                                    mang[i]=null;
                                    break;
                               }
                           }
                              alert("Xin lổi - Ghế này đã có người chọn !");
                            }
                        }
                 });
                });
                /*reload*/
                $(".dongthongbao").click(function(){
                  location.reload();
                });
            /* Hủy chọn vé đề xuất*/
                $("#vedexuat").delegate(".huygiudx","click",function(){
                    ma = $(this).attr("data-ma");
                    $(this).hide();
                    $(".giuvedx[data-ma='"+ma+"']").fadeIn();
                    $.ajax({
                        url: '{{route("huygiudx")}}',
                        type: 'POST',
                        data: {
                            _token: '{{csrf_token()}}',
                            MA: ma,
                        },
                        success: function (data) {
                           if(data.k ==1){
                              for (i=0; i < mang.length; i++) {
                                 if(mang[i]==ma){
                                      mang[i]=null;
                                      break;
                                 }
                             }
                             }
                        }
                 });

                });
                /*tắt đề xuất*/
                $(".dongdx").click(function(){
                  location.reload();
                });

      });
  </script>
@endsection
