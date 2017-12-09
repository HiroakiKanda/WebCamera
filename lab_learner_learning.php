<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="SHIFT-JIS">
    <title>Labellio for Face WebAPI [ Lerning ]</title>
</head>

</head>
<body style="margin: 0 0 0 0;">
</body>

<?php

        $data = array(
                'CMD'=>'learner_learning',
                'ACCESS_KEY'=>'bc7e036e-f591-44a9-9cfd-3d4787e93dd3',
                'MSG'=> array(
                'FRAME_JPG_B64'=>
'
',
				'PERSON_CODE'=>'',
				'PERSON_NAME'=>''
                ),
        );
        $data_json = json_encode($data);
		echo "<BR> learner_learning<BR><BR>";
//        echo $data_json;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, 'https://54.199.181.249/l4f/api.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// 社内からのアクセスはPROXY必要
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		curl_setopt($ch, CURLOPT_PROXY, 'http://10.254.30.228:8080');

        $result=curl_exec($ch);
        $res_json = json_decode($result , true );
        //echo $res_json['STATUS'];
		echo $result;
  //      echo var_dump($res_json);
        curl_close($ch);

?>

</html>
