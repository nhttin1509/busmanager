@extends('quanlydatve.main')
@section('content')
	<div class="sidebar col-lg-2">
        <span>Chuyến xe</span>
        <div class="chuyenxe">
            <ul>
            </ul>
        </div>
    </div>
    <div class="col-lg-10">
        <span>
            <ul>
                <a href="{{asset('qldv/giamsat')}}">
                    <li class="option selected">Bản đồ</li>
                </a>
				<a href="{{asset('qldv/datve')}}">
                    <li class="option">Nhập vé</li>
                </a>
            </ul>
        </span>
        <div class="content bando show">
			<span>Hiển thị bản đồ</span>
		</div>
    </div>
    
@endsection
@section('excontent')
@endsection
@section('script')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDPoe4NcaI69_-eBqxW9Of05dHNF0cRJ78"></script>
    <script>
		option = document.getElementsByClassName("option");
        for (var i = 0; i < 2; i++) {
            option[i].classList.remove('selected');
        }
        option[0].classList.add('selected');
		var arrMarkers = [];
		
		var locations = [];

		var beaches = [
			['Bondi Beach', -12.890542, 120.274856, 4],
			['Coogee Beach', -12.923036, 520.259052, 5],
			['Cronulla Beach', -12.028249, 1221.157507, 3],
			['Manly Beach', -12.80010128657071, 1121.28747820854187, 2],
			['Maroubra Beach', -33.950198, 121.259302, 1]
		];

		function setMarkers(map, locations) {
			for (var i = 0; i < locations.length; i++) {
				var beach = locations[i];
				var myLatLng = new google.maps.LatLng(beach[1], beach[2]);
				var infowindow = new google.maps.InfoWindow();
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					title: beach[0],
					zIndex: beach[3]
				});
				if(locations[i][3] == '{{session("qldv.idgiamsat")}}')
				{
					infowindow.setContent(locations[i][0]);
					infowindow.open(map, marker);
					map.setOptions({
						center: new google.maps.LatLng(locations[i][1], locations[i][2])
					});
				}
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						// infowindow.setContent(locations[i][0]);
						// infowindow.open(map, marker);
						// map.setOptions({
							// center: new google.maps.LatLng(locations[i][1], locations[i][2])
						// });
						location.href = '{{asset("qldv/giamsat")}}/'+locations[i][3];
					}
				})(marker, i));
		
				arrMarkers.push(marker);
			}
		}

		function initialize() {
			var mapOptions = {
				zoom: 15,
				center: new google.maps.LatLng(38.77417, -9.13417),
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
			map = new google.maps.Map(document.getElementsByClassName('bando')[0],mapOptions);

			setMarkers(map, beaches);
		}

		function removeMarkers(){
			var i;
			for(i=0;i<arrMarkers.length;i++){
			arrMarkers[i].setMap(null);
			}
			arrMarkers = [];

		}

		google.maps.event.addDomListener(window, 'load', initialize);
		
		function updateTheMarkers(){
			//We remove the old markers
			removeMarkers();
			beaches =[];//Erasing the beaches array
			//Adding the new ones
			for(var i=0;i < locations.length; i++) {
				beaches.push(locations[i]);
			}
			//Adding them to the map
			setMarkers(map, beaches);
		}
		// function showMap1(locations)
		// {
			// var locations = locations;
			// var center = [];
			
			// for (i = 0; i < locations.length; i++) {  
				// if(locations[i][3] == '{{session("qldv.idgiamsat")}}')
				// {
					// center = locations[i];
					// break;
				// }
			// }
			
			// var map = new google.maps.Map(document.getElementsByClassName('bando')[0], {
				// zoom: 15,
				// center: new google.maps.LatLng(center[1], center[2]),
				// mapTypeId: google.maps.MapTypeId.ROADMAP
			// });

			// var infowindow = new google.maps.InfoWindow();
			
			// var marker, i;

			// for (i = 0; i < locations.length; i++) {  
				// marker = new google.maps.Marker({
					// position: new google.maps.LatLng(locations[i][1], locations[i][2]),
					// map: map
				// });

				// google.maps.event.addListener(marker, 'click', (function(marker, i) {
					// return function() {
						// infowindow.setContent(locations[i][0]);
						// infowindow.open(map, marker);
					// }
				// })(marker, i));
			// }
			// infowindow.setContent(center[0]);
			// infowindow.open(map, marker);
		// }
		if(window.EventSource !== undefined){
			// supports eventsource object go a head...
			var es = new EventSource("{{route('qldv_sendgps')}}");
			es.addEventListener("message", function(e) {
				var arr = JSON.parse(e.data);
				var str = "";
				for(var i=0;i<arr.length;i++)
				{
					var locate = arr[i].location.split(",");
					var location = ["Chuyến xe "+arr[i].Mã,locate[0],locate[1],arr[i].Mã];
					locations.push(location);
				}
				for(var i=0;i<arr.length;i++)
				{
					if(arr[i].Mã == '{{session("qldv.idgiamsat")? session("qldv.idgiamsat"):"undefined"}}')
					{
						str += "<li style='background: red;' onclick='location.href = \"{{asset("qldv/giamsat")}}/"+arr[i].Mã+"\"' data-location='"+arr[i].location+"' data-id='"+arr[i].Mã+"'>Chuyến xe #"+arr[i].Mã+" <i class='glyphicon glyphicon-record' style='color: green;'></i></li>";
					}
					else
					{
						str += "<li onclick='location.href = \"{{asset("qldv/giamsat")}}/"+arr[i].Mã+"\"' data-location='"+arr[i].location+"' data-id='"+arr[i].Mã+"'>Chuyến xe #"+arr[i].Mã+" <i class='glyphicon glyphicon-record' style='color: green;'></i></li>";
					}
				}
				// if(document.getElementsByClassName("bando").length != 0)
				// {
					// showMap1(locations);
				// }		
				updateTheMarkers();
				document.getElementsByClassName("chuyenxe")[0].getElementsByTagName("ul")[0].innerHTML = str;
			}, false);
		} else {
			// EventSource not supported, 
			// apply ajax long poll fallback
		}
		window.onclick = function(ev){
			var chuyenxe = document.getElementsByClassName("chuyenxe")[0].getElementsByTagName("li");
			for(var i=0;i<chuyenxe.length;i++)
			{
				if(ev.target != chuyenxe[i])
				{
					chuyenxe[i].style.backgroundColor = "#004964";
				}
			}
		};
    </script>
@endsection
