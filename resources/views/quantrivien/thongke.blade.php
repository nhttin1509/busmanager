@extends('quantrivien.main')
@section('title')
	Quản lý Thống kê
@endsection
@section('content')
    <div class="content thongke show">
        <div>
            <div class="col-lg-4">
                <div class="the">
                    <a href="{{url('admin/khachhang')}}">
                        <div>
                            <div class="glyphicon glyphicon-user"></div>
                        </div>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <div>
                            <p>Khách hàng</p>
                            <p>Đã đăng ký: <i>{{$slkhachhang}}</i></p>
                            <p>Đang online: <i>10</i></p>
                            <p>Lượng truy cập: <i>0</i></p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="the">
                    <a href="{{url('admin/chuyenxe')}}">
                        <div>
                            <div class="glyphicon glyphicon-calendar"></div>
                        </div>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <div>
                            <p>Lịch trình</p>
                            <p>Số chuyến xe: <i>{{$slchuyenxe}}</i></p>
                            <p>Đã đi: <i>{{$slchuyenxedadi}}</i></p>
                            <p>Đang chờ: <i>{{$slchuyenxedangcho}}</i></p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="the">
                    <a href="">
                        <div>
                            <div class="glyphicon glyphicon-credit-card"></div>
                        </div>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <div>
                            <p>Doang thu</p>
                            <p>Theo tháng: <i>{{($tongdt/10000000)." triệu"}}</i></p>
                            <p>Theo quý: <i>{{($tongdt/10000000)." triệu"}}</i></p>
                            <p>Theo năm: <i>{{($tongdt/10000000)." triệu"}}</i></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div>
            <div class="col-lg-6">
                <canvas id="bieudo1" style="width: 100%; height: 100%;"></canvas>
            </div>
            <div class="col-lg-6">
                <canvas id="bieudo2" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
@endsection
@section('excontent')
@endsection
@section('script')
    {{--<script async="" src="http://www.google-analytics.com/analytics.js"></script>
    <script src="{{asset('plugins/chartjs/Chart.bundle.js')}}"></script>
    <script src="{{asset('plugins/chartjs/utils.js')}}"></script>--}}
    <script>
        document.getElementById("bieudo1").width=document.getElementById("bieudo1").parentNode.clientWidth - 15;
        document.getElementById("bieudo1").height=document.getElementById("bieudo1").parentNode.clientHeight - 15;
        document.getElementById("bieudo2").width=document.getElementById("bieudo2").parentNode.clientWidth - 15;
        document.getElementById("bieudo2").height=document.getElementById("bieudo2").parentNode.clientHeight - 15;
        // function showMap(){
        // 	var mapOptions = {
        // 		center: new google.maps.LatLng(51.2, 46),
        // 		zoom: 10,
        // 		mapTypeId: google.maps.MapTypeId.HYBRID
        // 	};
        // 	var map = new google.maps.Map(document.getElementsByClassName("bando")[0],mapOptions);
        // }
        option = document.getElementsByClassName("option");
        for (var i = 0; i < option.length; i++) {
            option[i].classList.remove('selected');
        }
        option[0].classList.add('selected');
        option[0].getElementsByTagName('img')[0].setAttribute('src','{{asset("images/icons/report-hover.png")}}');
		var bieudo1 = document.getElementById('bieudo1');
		var bieudo2 = document.getElementById('bieudo2');
		var myChart1 = new Chart(bieudo1, {
    type: 'line',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: '# năm {{$chuyenxe[0]["Năm"]}}',
            data: {{json_encode($chuyenxe[0]["Doanh_thu"])}},
            pointBackgroundColor: 'rgba(0,73,100,1)',
			pointBorderColor: 'rgba(0,73,100,1)',
			backgroundColor: 'rgba(255, 99, 132, 1)',
			fill: false,
            borderWidth: 1,
			borderColor: 'rgba(255, 99, 132, 1)'
        },
		{
            label: '# năm {{$chuyenxe[1]["Năm"]}}',
            data: {{json_encode($chuyenxe[1]["Doanh_thu"])}},
            pointBackgroundColor: 'rgba(0,73,100,1)',
			pointBorderColor: 'rgba(0,73,100,1)',
			backgroundColor: 'rgba(54, 162, 235, 1)',
			fill: false,
            borderWidth: 1,
			borderColor: 'rgba(54, 162, 235, 1)'
        }]
    },
    options: {
		responsive: true,
		title: {
			display: true,
			text: "Biểu đồ đường doanh thu năm {{$chuyenxe[0]['Năm']}}/{{$chuyenxe[1]['Năm']}}", 
		},
        scales: {
            yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: "Đơn vị(triệu đồng)"
				},
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
var myChart2 = new Chart(bieudo2, {
    type: 'bar',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: '# năm {{$chuyenxe[0]["Năm"]}}',
            data: {{json_encode($chuyenxe[0]['Chuyến_xe'])}},
            backgroundColor: 'rgba(255, 99, 132, 1)',
            borderColor: 'rgba(255,99,132,1)',
            borderWidth: 1
        },
		{
            label: '# năm {{$chuyenxe[1]["Năm"]}}',
            data: {{json_encode($chuyenxe[1]['Chuyến_xe'])}},
            backgroundColor: 'rgba(54, 162, 235, 1)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
		responsive: true,
		title: {
			display: true,
			text: "Biểu đồ cột chuyến xe năm {{$chuyenxe[0]['Năm']}}/{{$chuyenxe[1]['Năm']}}", 
		},
        scales: {
            yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: "Đơn vị(chuyến xe)"
				},
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
    </script>
@endsection
