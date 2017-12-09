<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Photo</title>
</head>
<body>

<form method="POST" action="lab_learner_load_facedata.php">
	<input type="file" id="selfile"><br>
	<div id="bg"></div>
	<textarea id="log" name="base64" rows="10" cols="200" readonly></textarea>
	<label>ACCESS KEY</label><br />
	<input type="text" name="accesskey" /><br />
	<input type="submit" value="送信" />
</form>

</body>
<script type="text/javascript">
	var obj1 = document.getElementById("selfile");

    document.getElementById("log").value = "";

	obj1.addEventListener("change", function(evt){
	  var file = evt.target.files;
	  var reader = new FileReader();
	  
	  //dataURL形式でファイルを読み込む
	  reader.readAsDataURL(file[0]);
	  
	  //ファイルの読込が終了した時の処理
	  reader.onload = function(){
	    var dataUrl = reader.result;

	    //読み込んだ画像とdataURLを書き出す
	    document.getElementById("bg").innerHTML = "<img src='" + dataUrl + "'>";
	    document.getElementById("log").value = dataUrl;
	  }
	},false);
</script>
</html>
</html>