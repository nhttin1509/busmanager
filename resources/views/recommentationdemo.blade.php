<!DOCTYPE html>
<html>
	<head>
		<title>Recommentation System Demo</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0" />
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="" />
		<style>
			body>table:first-child {
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%,-60%);
				border-collapse: collapse;
			}
			table td {
				text-align: center;
				padding: 2px;
			}
		</style>
	</head>
	<body style="overflow: scroll;">
		<div id="popup" style="position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(80,80,80,0.5);">
			<div style="position: absolute; top: 50%; left: 50%; padding: 3em; transform: translate(-50%,-80%); background: white; border-radius: 5px;">
			<form name="first" oninput="usernum.value=a.value;itemnum.value=b.value;">
				<fieldset>
					<legend>Nhập số lượng user và item:</legend>
					<input type="range" id="a" min="1" max="50" value="10" />
					<br />
					<output name="usernum" for="a">10</output>&nbsp;users
					<br />
					<input type="range" id="b" min="1" max="50" value="10" />
					<br />
					<output name="itemnum" for="b">10</output>&nbsp;items
					<br />
					<input type="button" name="makeMatrix" value="Tạo ma trận" />
				</fieldset>
			</form>
			</div>
		</div>
		<div id="popup1" style="display: none; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(80,80,80,0.5);">
			<div style="position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; padding: 3em; transform: translate(-50%,-50%); background: white; border-radius: 5px; overflow: scroll;">
			</div>
		</div>
		<div id="popup2" style="display: none; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(80,80,80,0.5);">
			<div style="position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; padding: 3em; transform: translate(-50%,-50%); background: white; border-radius: 5px; overflow: auto;">
			</div>
		</div>
		<div id="popup3" style="display: none; position: fixed; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(80,80,80,0.5);">
			<div style="position: absolute; top: 50%; left: 50%; width: 100%; height: 100%; padding: 3em; transform: translate(-50%,-50%); background: white; border-radius: 5px; overflow: scroll;">
			</div>
		</div>
		<script>
			var makeMatrix=document.forms["first"]["makeMatrix"];
			var usernum=document.forms["first"]["usernum"];
			var itemnum=document.forms["first"]["itemnum"];
			var popup=document.getElementById("popup");
			var matrix=[];
			var similarmatrix=[];
			var tb=[];
			var normmatrix=[];
			var normmatrixfull=[];
			var normmatrixfinal=[];
			makeMatrix.onclick=function(){
				var cols=usernum.value;
				var rows=itemnum.value;
				var str="<table border='1'>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					var matr=[];
					for(var j=0;j<cols;j++){
						matr[j]=Math.floor(Math.random()*10);
						str+="<td>"+matr[j]+"</td>";
					}
					str+="</tr>";
					matrix[i]=matr;
				}
				str+="<tr>";
				str+="<td colspan='"+(Math.floor((parseInt(cols)+1)/2))+"'><button onclick='fix();'>Sửa</button></td>";
				str+="<td colspan='"+(parseInt(cols)+1-Math.floor((parseInt(cols)+1)/2))+"'><button onclick='calc();'>Tính</button></td>";
				str+="</tr>";
				str+="</table>";
				popup.style.display="none";
				document.body.innerHTML=str+document.body.innerHTML;				
			};
			function fix(){
				var content=document.getElementById("popup1").getElementsByTagName("div")[0];
				var rows=matrix.length;
				var cols=matrix[0].length;
				var str="<table border='1' style='border-collapse: collapse; background: white;'>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					for(var j=0;j<cols;j++){
						str+="<td><input type='text' style='width: 2em;' maxlength='1' value='"+matrix[i][j]+"' /></td>";
					}
					str+="</tr>";
				}
				str+="<tr>";
				str+="<td colspan='"+(Math.floor((parseInt(cols)+1)/2))+"'><button onclick='save();'>Lưu</button></td>";
				str+="<td colspan='"+(parseInt(cols)+1-Math.floor((parseInt(cols)+1)/2))+"'><button onclick='cancel();'>Hủy</button></td>";
				str+="</tr>";
				str+="</table>";
				content.innerHTML=str;
				content.parentNode.style.display="block";
			}
			function cancel(){
				document.getElementById("popup1").style.display="none";
			}
			function cancel1(){
				document.getElementById("popup2").style.display="none";
			}
			function cancel2(){
				document.getElementById("popup3").style.display="none";
			}
			function save(){
				var val=document.getElementById("popup1").getElementsByTagName("input");
				var rows=matrix.length;
				var cols=matrix[0].length;
				var str="<tbody>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					for(var j=0;j<cols;j++){
						var index=i*cols+j;
						if(val[index].value==""||isNaN(val[index].value)){
							matrix[i][j]="?";
						}
						else{
							matrix[i][j]=parseInt(val[index].value);
						}
						str+="<td>"+matrix[i][j]+"</td>";
					}
					str+="</tr>";
				}
				str+="<tr>";
				str+="<td colspan='"+(Math.floor((parseInt(cols)+1)/2))+"'><button onclick='fix();'>Sửa</button></td>";
				str+="<td colspan='"+(parseInt(cols)+1-Math.floor((parseInt(cols)+1)/2))+"'><button onclick='calc();'>Tính</button></td>";
				str+="</tr>";
				str+="</tbody>";
				document.getElementById("popup1").style.display="none";
				document.body.getElementsByTagName("table")[0].innerHTML=str;
			}
			function calc(){
				var content=document.getElementById("popup2").getElementsByTagName("div")[0];
				var rows=matrix.length;
				var cols=matrix[0].length;
				var str="<table border='1' style='border-collapse: collapse; background: white; display: block;'>";
				str+="<caption>Tính tần suất trung bình</caption>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					for(var j=0;j<cols;j++){
						str+="<td>"+matrix[i][j]+"</td>";
					}
					str+="</tr>";
				}
				str+="<tr>";
				str+="<td>tần suất trung bình</td>"
				for(var j=0;j<cols;j++){
					var s=0;
					var s1=rows;
					for(var i=0;i<rows;i++){						
						if(isNaN(matrix[i][j])){
							s+=0;
							s1-=1;
						}
						else{
							s+=parseInt(matrix[i][j]);
						}
					}
					tb[j]=(s/s1).toFixed(2);
					str+="<td>"+tb[j]+"</td>";					
				}
				str+="</tr>";
				str+="</table>";
				str+="<table border='1' style='border-collapse: collapse; background: white; display: block;'>";
				str+="<caption>Tính độ lệnh so với tần suất trung bình</caption>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					var temp=[];
					for(var j=0;j<cols;j++){
						if(isNaN(matrix[i][j])){
							temp[j]="?";
						}
						else{
							temp[j]=(matrix[i][j]-tb[j]).toFixed(2);
						}						
						str+="<td>"+temp[j]+"</td>";
					}
					normmatrix[i]=temp;
					str+="</tr>";
				}
				str+="</table>";
				str+="<table border='1' style='border-collapse: collapse; background: white; display: block;'>";
				str+="<caption>Ma trận tương đồng User-User</caption>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var j=0;j<cols;j++){
					str+="<tr>";
					str+="<td>user"+j+"</td>";
					var temp=[];
					for(var i=0;i<cols;i++){
						var s=0;
						var t1=0;
						var t2=0;
						for(var k=0;k<rows;k++){
							if(!isNaN(normmatrix[k][j])&&!isNaN(normmatrix[k][i])){
								s+=normmatrix[k][j]*normmatrix[k][i];
							}
							if(!isNaN(normmatrix[k][j])){
								t1+=Math.pow(normmatrix[k][j],2);
							}
							if(!isNaN(normmatrix[k][i])){
								t2+=Math.pow(normmatrix[k][i],2);
							}
						}
						temp[i]=(s/(Math.sqrt(t1)*Math.sqrt(t2))).toFixed(2);
						str+="<td>"+temp[i]+"</td>";
					}
					similarmatrix[j]=temp;
					str+="</tr>";
				}
				str+="</table>";
				str+="<table border='1' style='border-collapse: collapse; background: white; display: block;'>";
				str+="<caption>Ma trận dự đoán</caption>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					var temp=[];
					for(var j=0;j<cols;j++){
						if(isNaN(normmatrix[i][j])){
							var max1=-1;
							var max2=-1;
							var s1=0;
							var s2=0;
							for(var k=0;k<cols;k++){
								if(j==k){
									continue;
								}
								if(similarmatrix[j][k]>max1&&!isNaN(normmatrix[i][k])){
									max1=parseFloat(similarmatrix[j][k]);
									s1=max1*normmatrix[i][k];
								}
								if(max1>max2){
									max2=max1+max2;
									max1=max2-max1;
									max2=max2-max1;
									s2=s1+s2;
									s1=s2-s1;
									s2=s2-s1;
								}
							}
							temp[j]=((s1+s2)/(Math.abs(max1)+Math.abs(max2))).toFixed(2);
						}
						else{
							temp[j]=normmatrix[i][j];
						}						
						str+="<td>"+temp[j]+"</td>";
					}
					normmatrixfull[i]=temp;
					str+="</tr>";
				}
				str+="</table>";
				str+="<table border='1' style='border-collapse: collapse; background: white; display: inline-block;'>";
				str+="<caption>Ma trận dự đoán hoàn chỉnh</caption>";
				str+="<tr>";
				str+="<td></td>";
				for(var i=0;i<cols;i++){
					str+="<td>user"+i+"</td>";
				}
				str+="</tr>";
				for(var i=0;i<rows;i++){
					str+="<tr>";
					str+="<td>item"+i+"</td>";
					var temp=[];
					for(var j=0;j<cols;j++){
						temp[j]=(parseFloat(normmatrixfull[i][j])+parseFloat(tb[j])).toFixed(2);						
						str+="<td>"+temp[j]+"</td>";
					}
					normmatrixfinal[i]=temp;
					str+="</tr>";
				}
				str+="<tr>";
				str+="<td colspan='"+(Math.floor((parseInt(cols)+1)/2))+"'>";
				str+="<select onchange='recomment(this);'>";
				str+="<option>Chọn người dùng để đề nghị</option>";
				for(var i=0;i<cols;i++){
					str+="<option value='"+i+"'>user"+i+"</option>";
				}
				str+="</select>";
				str+="</td>";
				str+="<td colspan='"+(parseInt(cols)+1-Math.floor((parseInt(cols)+1)/2))+"'><button onclick='cancel1();'>Quay lại</button></td>";
				str+="</tr>";
				str+="</table>";
				content.innerHTML=str;
				content.parentNode.style.display="block";
			}
			function recomment(ev){
				if(!isNaN(ev.value)){
					var rows=normmatrixfinal.length;
					var max=0;
					var denghi=[];
					for(var i=0;i<rows;i++){
						denghi[i]={ item:i, ivalue:normmatrixfinal[i][ev.value]};
					}
					denghi.sort(function(a, b){return b.ivalue - a.ivalue});
					var content=document.getElementById("popup3").getElementsByTagName("div")[0];
					var str="<table border='1' style='border-collapse: collapse; background: white;'>";
					str+="<tr>";
					str+="<td colspan='2'>Đề nghị theo thứ tự từ trên xuống các item bên dưới</td>";
					str+="</tr>";
					str+="<tr>";
					str+="<td></td>";
					str+="<td>user"+ev.value+"</td>";
					str+="</tr>";
					for(var i=0;i<rows;i++){
						str+="<tr>";
						str+="<td>item"+denghi[i].item+"</td>";
						str+="<td>"+denghi[i].ivalue+"</td>";
						str+="</tr>";
					}
					str+="<tr>";
					str+="<td colspan='2'><button onclick='cancel2();'>Hủy</button></td>";
					str+="</tr>";
					str+="</table>";
					content.innerHTML=str;
					content.parentNode.style.display="block";
					
				}
			}
		</script>
	</body>
</html>