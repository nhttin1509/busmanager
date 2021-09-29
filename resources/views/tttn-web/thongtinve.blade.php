@extends('tttn-web.main')
@section('title')
    Trang chủ
@endsection
@section('content')
    <!-- Thông tin vé -->
    <div class="mainthongtinve">
        <h3>Thông Tin Vé Đã Đặt</h3>
            <?php $dem=0;
                $tien= 0;
            ?>
            @foreach($chonve as $t)
            <p><i class="fa fa-bus"></i> Nơi Khởi Hành:<a> {{$t->Nơi_đi}}</a></p><br>
            <p><i class="fa fa-bus"></i> Nơi đến: <a>{{$t->Nơi_đến}}</a></p> <br>
            <p><span class="glyphicon glyphicon-time"></span> Thời gian khởi hành: {{$t->Ngày_xuất_phát}} : {{$t->Giờ_xuất_phát}}</p><br>
            <p><span class="glyphicon glyphicon-bed"></span> {{($t->Loại_ghế==1)? 'Giường Nằm':'Ghế Ngồi'}}</p><br>
            <p><i class="fa fa-balance-scale"></i> Giá vé : {{$t->Tiền_vé/1000}}.000 VNĐ </p><br>
            <?php $tien=$t->Tiền_vé; ?>
            @endforeach
            <p><i class="fa fa-address-card-o"></i> Vé đã đặt :
            @foreach($vedadat as $a)
                <b>{{$a->Vị_trí_ghế}}</b>,
                <?php $dem++; ?>
            @endforeach
             </p><br>
            <p><i class="fa fa-gavel"></i> Tổng tiền: <b>{{$dem * $tien/1000 }}.000 VNĐ</b> </p></divbr>
            
            <h4 style="color: rgb(0,64,87);"">Nhân viên chúng tôi sẻ liên hệ với quý khách để quý khách thanh toán tiền.</h4>
            <h3 style="color: rgb(0,64,87);">Chúc quý khách có một chuyến đi vui vẻ.</3>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="js/js.js"></script>
    <script type="text/javascript" src="js/date.js"></script>
@endsection
