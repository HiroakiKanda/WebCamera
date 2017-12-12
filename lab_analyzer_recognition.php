<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Labellio for Face WebAPI [ Lerning ]</title>
</head>

</head>
<body style="margin: 0 0 0 0;">
<button onclick="history.back()">Back</button>
</body>

<?php

	echo "ACCESS KEY=".$_POST["accesskey"];
	echo "<BR>";
	echo "FRAME KEY=".$_POST["framekey"];
	echo "<BR>";

	$base64 = explode(",", $_POST["framejpgb64"]);

//	echo $base64[0];
//	echo $base64[1];
	

	$msg = array(
        'FRAME_JPG_B64'=>$base64[1],
		'FRAME_KEY'=>$_POST["framekey"]
	);

    $data = array(
        'CMD'=>'analyzer_recognition_sync',
        'ACCESS_KEY'=>$_POST["accesskey"],
        'MSG'=>$msg
    );

    $data_json = json_encode($data);

	echo "<BR> analyzer_recognition_sync<BR><BR>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'https://54.199.181.249/l4f/api.php');
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	// Proxy
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
	curl_setopt($ch, CURLOPT_PROXY, 'http://10.254.30.228:8080');

    $result=curl_exec($ch);
    $res_json = json_decode($result , true );
    //echo $res_json['STATUS'];
	echo $result;
    curl_close($ch);

?>

</html>
