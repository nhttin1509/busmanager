@extends('tttn-web.main')
@section('title')
    Trang chủ
@endsection
@section('content')
  <!-- Chi tiết tin tức --> 
	<div class="mainviewtintuc">
		@foreach($tintuc as $t)
			<h2>{{$t->title}}</h2>
			<i class="fa fa-calendar" aria-hidden="true"><span> {{$t->created_at}}</span></i>
			<br>
			<span>{{$t->introduce}}</span>
      <br>
			<br>
			{!! $t->content !!}
		@endforeach
          <!-- Tin tức khác --> 
		<div class="tintuc">
            <div class="tentintuc"><h3>Tin Tức Khác</h3></div>
            <ul>
              @foreach($tintuckhac as $y)
                <li>
                     <?php  $id = $y->news_id; ?>
                    <img src="../upload/{{$y->image}}">
                    
                    <a href="{{asset("showtintuc/{$id}")}}"><strong>{{$y->title}}</strong></a>
                </li>
                @endforeach
            </ul>
            <div style="clear: left;"></div>
              <!-- Xem toàn bộ tin tức --> 
            <div class="tintucbutton">
                <button class="btn"><a href="{{asset('/tintuc')}}">Xem Toàn Bộ</a></button>
            </div>
        </div>
	</div>
@endsection