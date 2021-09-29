@extends('tttn-web.main')
@section('title')
    Chuyến xe
@endsection
@section('content')
    <!-- phần bước -->
        <div class="buoc">
            <ul>
                <li onclick="vetrangtruoc()" class="tay">Tìm Chuyến</li>
                <li class="stay tay">Chọn Chuyến</li>
                <li >Chi Tiết Vé</li>
            </ul>
        </div>
    <!-- kết thức phần bước -->
    <!-- Phần chuyến xe  -->
        <div class="chuyenxemain">
             @if(count($Chuyenxe)==0)
             <br>
             <i><h4 style="width: 70%; margin: auto; margin-top: 2em;">Xin Lổi! Không Tìm Thấy Chuyến Xe Nào Theo Yêu Cầu Của Bạn, Vui Lòng Tìm Chuyến Xe Khác, Cảm Ơn!</h4></i>
             @else
            <table>
                <tr>
                    <th>Mã chuyến</th>
                    <th>Tuyến</th>
                    <th>Giờ Xuất Bến</th>
                    <th>Thời Gian Đến</th>
                    <th>Loại Xe</th>
                    <th>Giá</th>
                    <th>Đặt Mua</th>
                </tr>
               
                
                 
                        @foreach($Chuyenxe as $t)
                        <tr>
                            <td>{{$t->Mã}}</td>
                            <td><span>{{$t->Nơi_đi}} -> {{$t->Nơi_đến}}</span></td>
                            <td><span>{{$t->Ngày_xuất_phát}} : {{$t->Giờ_xuất_phát}}</span></td>
                            <td><span>{{$t->Thời_gian_đến_dự_kiến}}</span></td>
                            <td><span>{{($t->Loại_ghế==1)? 'Giường Nằm':'Ghế Ngồi'}}</span></td>
                            <td><span>{{($t->Tiền_vé)/1000}}.000 VNĐ</span></td>
                            <td><div class="chuyenxetim">
                                    @if(Session::has('makh'))
                                        <i class="fa fa-arrow-right icon-flat bg-btn-actived"></i>
                                        <button type="button" class="btn"><a href="chonve/{{$id=$t->Mã}}">Đặt vé</a></button>
                                    @else
                                        <i class="fa fa-arrow-right icon-flat bg-btn-actived"></i>
                                        <button type="button" class="btn"><a data-toggle="modal" data-target="#login" data-dismiss="modal" >Đặt vé</a></button>
                                    @endif
                                </div></td>
                        </tr>
                        @endforeach
               

                
            </table>
            @endif
        </div>
    <!-- Kết thúc phần chuyến xe  -->
@endsection
@section('script')
    <script type="text/javascript">
        function vetrangtruoc(){
            history.back();
        }
    </script>
@endsection
