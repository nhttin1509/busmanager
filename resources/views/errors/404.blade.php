<!DOCTYPE html>
<html>
<head>
    <title>Lỗi 404</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
    <link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.theme.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/pqgrid.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/pqgrid.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/pqgrid.ui.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/paramquery-3.3.4/themes/custom/pqgrid.css')}}" rel="stylesheet">
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/Chart.min.js')}}"></script>
    <script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>
    <script src="{{asset('plugins/paramquery-3.3.4/pqgrid.min.js')}}"></script>
    <script src="{{asset('plugins/paramquery-3.3.4/jsZip-2.5.0/jszip.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('css/style-qtv.css')}}">
</head>
<body>
<div class="container-fluid">
	<div class="header">
        <div class="row">
            <h3 class="col-lg-4"><a href="{{asset('admin/')}}"></a></h3>
            <h5 class="col-lg-4"><a href="{{url('/')}}" title="Chuyển về trang khách hàng"><img src="{{asset('/images/icons/luggage.png')}}" height="30" alt="icon">AwesomeTravel</a></h5>
            <div class="col-lg-4 userzone">
            </div>
        </div>
        {{--<hr />--}}
    </div>
    <div class="login-form">
		<h2 style="color: white;">Lỗi 404 Không tìm thấy trang</h2>
		<a href="javascript:void(0);" onclick="window.history.back();">Trở về</a>
    </div>
</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	$(document).ready(function(){
	});

</script>
<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78&callback=showMap"></script> -->
</body>
</html>
