<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Labellio for Face WebAPI [ Lerning ]</title>
</head>

</head>
<body style="margin: 0 0 0 0;">
<button onclick="history.back()">Back</button>
<BR>
<?php

	echo "ACCESS KEY=".$_POST["accesskey"];
	echo "<BR>";
	echo "AUTH SRC ID=".$_POST["authsrcid"];
	echo "<BR>";
	echo "PERSON NAME=".$_POST["personname"];
	echo "<BR>";


	$msg = array(
			'AUTH_SRC_ID'=>$_POST["authsrcid"],
	        'PERSON_NAME'=>$_POST["personname"]
	);
	$msg_filter = array_filter($msg);

	print_r($msg_filter);

	$data = array(
	        'CMD'=>'learner_load_facedata',
	        'ACCESS_KEY'=>$_POST["accesskey"],
	        'MSG'=>$msg_filter
	);

	$data_json = json_encode($data);
//	echo $data_json;

	echo "<BR> learner_load_facedata ReturnCode<BR><BR>";
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
	echo $result;
	//        echo var_dump($res_json);
	curl_close($ch);

?>
</body>

</html>
