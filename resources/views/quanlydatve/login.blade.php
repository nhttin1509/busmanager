<!DOCTYPE html>
<html>
<head>
    <title>Quản lý đặt vé</title>
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
    <link rel="stylesheet" type="text/css" href="{{asset('css/style-qldv.css')}}">
</head>
<body>
<div class="container-fluid">
<div class="header">
        <div class="row">
            <h3 class="col-lg-4"><a href="{{asset('qldv/')}}">AWE QL Đặt vé</a></h3>
            <h5 class="col-lg-4"><a href="{{url('/')}}" title="Chuyển về trang khách hàng"><img src="{{asset('/images/icons/luggage.png')}}" height="30" alt="icon">AwesomeTravel</a></h5>
            <div class="col-lg-4 userzone">
            </div>
        </div>
        {{--<hr />--}}
    </div>
    <div class="login-form">
        <form action="{{route('qldv_login')}}" method="post">
            @csrf
			<div class="panel panel-default">
				<div class="panel-heading">QL Đặt vé Login</div>
				<div class="panel-body">
					<div class="alert alert-danger" style='{{session("alert")? "display: block":""}}'>{{session("alert")? session("alert"):"*"}}</div>
					<div class="form-group">
						<label>Tài khoản</label>
						<input type="text" class="form-control" name="username" placeholder="Tài khoản" value='{{session("username")? session("username"):""}}'>
					</div>
					<div class="form-group">
						<label>Mật khẩu</label>
						<input type="password" class="form-control" name="password" placeholder="Mật khẩu">
					</div>
					<div class="form-group">
						<input type="submit" class="form-control" value="Đăng Nhập">
					</div>
				</div>
			</div>
        </form>
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
