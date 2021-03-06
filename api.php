<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="SHIFT-JIS">
    <title>Labellio for Face WebAPI [ Lerning ]</title>
    <script type="text/javascript" src="./js/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="./js/jquery.xdomainajax.js"></script>
    <script type="text/javascript" src="./js/jquery.json2html.js"></script>
    <script type="text/javascript" src="./js/json2html.js"></script>
    <script type="text/javascript" src="./js/createjs-2015.11.26.min.js"></script>
    <script type="text/javascript" src="./js/preloadjs-0.6.2.min.js"></script>
    <script type="text/javascript" src='./js/bootstrap.js'></script>
    <script type="text/javascript" src="./js/l4f_video.js"></script>
    <link href="./css/l4f.css" rel="stylesheet">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
</head>

</head>
<body style="margin: 0 0 0 0;">
    <header class="l4f__header">
        <nav>
            <ul>
				<li class="l4f__hd__title">
					<a href="#">Labellio for Face Admin</a>
				</li>
				<li class="l4f__hd__item">
					<a href="./analyzer_load_log_list.html">Analyzer Load Log</a>
				</li>
				<li class="l4f__hd__item">
					<a href="./learner_load_facedata_list.html">Load Face Data</a>
				</li>
				<li class="l4f__hd__item">
					<a href="./learner_learning.html">Learning</a>
				</li>
            </ul>
        </nav>
    </header>
    <div class="l4f__body" style="width:100%;">
        <form id="form" onsubmit="return false;">
            <div class="container-fluid">
                <div class="page__title" style="vertical-align:middle;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4"><h4><strong>Learning Face Data</strong></h4></div>
                        <div class="col-xs-12 col-sm-4">
                            <input type="text" class="form-control input-sm" id="accesskey" placeholder="ACCESS KEY">
                        </div>
                        <div class="col-xs-12 col-sm-2">
                                <select id="fps" class="form-control input-sm">
                                <option value="0.5">0.5 fps</option>
                                <option value="1">1 fps</option>
                                <option value="2" selected="selected">2 fps</option>
                                <option value="5">5 fps</option>
                                <option value="10">10 fps</option>
                                <option value="20">20 fps</option>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-1">
                            <input id="rec_bot" type="button" value="rec"/>
                        </div>
                        <div class="col-xs-12 col-sm-1">
                            <input id="clear_bot" type="button" value="clear"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="container-fluid" style="margin-top:10px;">
            <canvas id="rescanvas" style="position:absolute;z-index:11;"></canvas>
            <video id="video" autoplay="1" style="position:absolute;z-index:10;"></video>
            <canvas id="videocanvas" style="position:absolute;z-index:9;display:none;"></canvas>
        </div>
    </div>
</body>

<?php

        $data = array(
                'CMD'=>'learner_load_facedata',
                'ACCESS_KEY'=>'bc7e036e-f591-44a9-9cfd-3d4787e93dd3',
                'MSG'=> array(
//				'AUTH_SRC_ID'=>'video0'
                'PERSON_NAME'=>'HIROAKI KANDA'
                ),
        );
        $data_json = json_encode($data);
        echo $data_json;

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
//      echo $res_json['STATUS'];
        echo var_dump($res_json);
        curl_close($ch);

?>

</html>
