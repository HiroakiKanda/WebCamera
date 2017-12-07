<?php
/**
 * T&D ���JAPI�𗘗p���邽�߂̃T���v���v���O����
 * 
 * API�d�l�@http://ondotori.webstorage.jp/docs/api/
 *
 */

 
// device/current �𗘗p���Č��ݒl�ꗗ���擾����
$url = "https://api.webstorage.jp/v1/devices/current";
    
//�ݒ肷�� HTTP�w�b�_�t�B�[���h: API�̎d�l�ɍ��킹�Ă��������B
$headerdata = array(
    'Content-Type: application/json',
    'X-HTTP-Method-Override: GET'
);

//POST�ő��M����f�[�^��z��ňꎞ�쐬���܂��B�F���M�f�[�^�ɂ��Ă�API�̎d�l�����m�F���������B
//��������JSON�`���̕�����Ƃ��Đ������邽�߂ɕW���֐��𗘗p���A�z�񂩂�json�`���ւƕύX���Ă��܂��B

$param = array(
     "api-key" => "apikeyapikeyapikeyapikeyapikeyapikey"    //���肵��API KEY����͂��܂��B
    ,"login-id" => "your-login-id"   //���p��ID����͂��܂��B
    ,"login-pass" => "your-login-password"  //���p��ID�̃��O�C���p�X���[�h����͂��܂��B
    //�K�v�ɉ����ăp�����[�^�ǉ�

);

//���M�f�[�^��z�񂩂�JSON�`���ɕύX���܂��B
$postdata = json_encode($param);


//curl���g����HTTP POST���M����
$ch = curl_init($url);
$options = array (
     CURLOPT_POST           => true             //HTTP "POST" �ő��M����
    ,CURLOPT_RETURNTRANSFER => true             //curl_exec�̕Ԃ�l�𕶎��񉻂���
    ,CURLOPT_HEADER         => true             //�w�b�_�̓��e���o�́FHTTP�X�e�[�^�X�R�[�h�����[�g���~�b�g�󋵂��m�F����ꍇ�ɕK�v
    ,CURLOPT_HTTPHEADER     => $headerdata      //�ݒ肷�� HTTP�w�b�_�t�B�[���h�̔z��
    ,CURLOPT_POSTFIELDS     => $postdata        //HTTP "POST" �ő��M����f�[�^
);
curl_setopt_array($ch, $options);

//���ʂ���M���āAcurl�ɂ��ʐM�����
$response             = curl_exec($ch);                 //���ʂ𕶎���Ŋi�[
$response_info        = curl_getinfo($ch);              //���ʂɊւ�������i�[
$response_code        = $response_info['http_code'];    //�ʐM���ʂ�HTTP�X�e�[�^�X�R�[�h
$response_header_size = $response_info['header_size'];  //�ʐM���ʂ̃w�b�_�T�C�Y
curl_close($ch);            //�ʐM���N���[�Y

//���X�|���X�̃w�b�_���̒��Ƀ��[�g���~�b�g�Ɋւ����񂪂��邽�߃w�b�_�����ׂ����m�F����
$response_header = substr($response, 0, $response_header_size);     //�w�b�_�����̐؂�o��
$response_body   = substr($response, $response_header_size);        //�{�f�B�����̐؂�o��

//�w�b�_���m�F����
$array_header = decodeHeader($response_header); //�w�b�_�𕪉�
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

//�������ʂ̕\��
if ( $response_code == 200 ) {
    //�ʐM����
    print "[Result] success.\n";
}else{
    print "[Result] failed [$response_code].\n";
}

if ( $ratelimit["DataCount"]=="" ) {
    //�f�[�^�����ɂ�闘�p�������Ȃ��ꍇ
    print "[RateLimit] ".$ratelimit["Limit"]." ��/".$ratelimit["Reset"]." ���@�ɑ΂��āA���p�c��: ".$ratelimit["Remaining"]." ��\n";
}else{
    //�f�[�^�����ɂ�闘�p����������ꍇ
    print "[RateLimit] ".$ratelimit["Limit"]." ��/".$ratelimit["Reset"]." ���@�ɑ΂��āA���p�c��: ".$ratelimit["Remaining"]." ��A".$ratelimit["DataCount"]." ��\n";
}


//�{�f�B��JSON�f�[�^����������
print "[ResponseData]\n".trim($response_body);

exit();

/**
 * �w�b�_�����s�P�ʂŕ������āA�R�����ɂ�蕪�����Ĕz��Ɋi�[����
 *
 * @param string $header
 * @return array
 */
function decodeHeader($header){
    //���s�P�ʂŏ������A":"���f���~�^�Ƃ��ď�������
    $result = array();
    foreach (explode("\n", $header) as $i=>$line) {
        $temp = explode(":",$line);
        $temp = array_map('trim',$temp);    //�e�v�f��trim����
        if ( isset($temp[0]) and isset($temp[1]) ){
            //�u:�v�ŋ�؂�ꂽ�f�[�^������������
            $result[$temp[0]] = $temp[1];
        }
    }
    return $result;
}