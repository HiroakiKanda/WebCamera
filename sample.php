<?php
/**
 * T&D 公開APIを利用するためのサンプルプログラム
 * 
 * API仕様　http://ondotori.webstorage.jp/docs/api/
 *
 */

 
// device/current を利用して現在値一覧を取得する
$url = "https://api.webstorage.jp/v1/devices/current";
    
//設定する HTTPヘッダフィールド: APIの仕様に合わせてください。
$headerdata = array(
    'Content-Type: application/json',
    'X-HTTP-Method-Override: GET'
);

//POSTで送信するデータを配列で一時作成します。：送信データについてはAPIの仕様をご確認ください。
//※正しくJSON形式の文字列として生成するために標準関数を利用し、配列からjson形式へと変更しています。

$param = array(
     "api-key" => "apikeyapikeyapikeyapikeyapikeyapikey"    //入手したAPI KEYを入力します。
    ,"login-id" => "your-login-id"   //利用者IDを入力します。
    ,"login-pass" => "your-login-password"  //利用者IDのログインパスワードを入力します。
    //必要に応じてパラメータ追加

);

//送信データを配列からJSON形式に変更します。
$postdata = json_encode($param);


//curlを使ったHTTP POST送信処理
$ch = curl_init($url);
$options = array (
     CURLOPT_POST           => true             //HTTP "POST" で送信する
    ,CURLOPT_RETURNTRANSFER => true             //curl_execの返り値を文字列化する
    ,CURLOPT_HEADER         => true             //ヘッダの内容も出力：HTTPステータスコード＆レートリミット状況を確認する場合に必要
    ,CURLOPT_HTTPHEADER     => $headerdata      //設定する HTTPヘッダフィールドの配列
    ,CURLOPT_POSTFIELDS     => $postdata        //HTTP "POST" で送信するデータ
);
curl_setopt_array($ch, $options);

//結果を受信して、curlによる通信を閉じる
$response             = curl_exec($ch);                 //結果を文字列で格納
$response_info        = curl_getinfo($ch);              //結果に関する情報を格納
$response_code        = $response_info['http_code'];    //通信結果のHTTPステータスコード
$response_header_size = $response_info['header_size'];  //通信結果のヘッダサイズ
curl_close($ch);            //通信をクローズ

//レスポンスのヘッダ情報の中にレートリミットに関する情報があるためヘッダ情報を細かく確認する
$response_header = substr($response, 0, $response_header_size);     //ヘッダ部分の切り出し
$response_body   = substr($response, $response_header_size);        //ボディ部分の切り出し

//ヘッダを確認する
$array_header = decodeHeader($response_header); //ヘッダを分解
//X-Ratelimit-Limit
if ( isset($array_header["X-RateLimit-Limit"]) ) {
    $ratelimit["Limit"] = $array_header["X-RateLimit-Limit"];
}else{
    $ratelimit["Limit"] = "unknown";
}
//X-Ratelimit-Reset
if ( isset($array_header["X-RateLimit-Reset"]) ) {
    $ratelimit["Reset"] = $array_header["X-RateLimit-Reset"];
}else{
    $ratelimit["Reset"] = "unknown";
}
//X-Ratelimit-Remaining
if ( isset($array_header["X-RateLimit-Remaining"]) ) {
    $ratelimit["Remaining"] = $array_header["X-RateLimit-Remaining"];
}else{
    $ratelimit["Remaining"] = "unknown";
}
//X-Ratelimit-Remaining-DataCount
if ( isset($array_header["X-RateLimit-Remaining-DataCount"]) ) {
    $ratelimit["DataCount"] = $array_header["X-RateLimit-Remaining-DataCount"];
}else{
    $ratelimit["DataCount"] = "";
}

//処理結果の表示
if ( $response_code == 200 ) {
    //通信成功
    print "[Result] success.\n";
}else{
    print "[Result] failed [$response_code].\n";
}

if ( $ratelimit["DataCount"]=="" ) {
    //データ件数による利用制限がない場合
    print "[RateLimit] ".$ratelimit["Limit"]." 回/".$ratelimit["Reset"]." 分　に対して、利用残数: ".$ratelimit["Remaining"]." 回\n";
}else{
    //データ件数による利用制限がある場合
    print "[RateLimit] ".$ratelimit["Limit"]." 回/".$ratelimit["Reset"]." 分　に対して、利用残数: ".$ratelimit["Remaining"]." 回、".$ratelimit["DataCount"]." 件\n";
}


//ボディのJSONデータを処理する
print "[ResponseData]\n".trim($response_body);

exit();

/**
 * ヘッダ情報を行単位で分解して、コロンにより分割して配列に格納する
 *
 * @param string $header
 * @return array
 */
function decodeHeader($header){
    //改行単位で処理し、":"をデリミタとして処理する
    $result = array();
    foreach (explode("\n", $header) as $i=>$line) {
        $temp = explode(":",$line);
        $temp = array_map('trim',$temp);    //各要素をtrimする
        if ( isset($temp[0]) and isset($temp[1]) ){
            //「:」で区切られたデータだけ処理する
            $result[$temp[0]] = $temp[1];
        }
    }
    return $result;
}