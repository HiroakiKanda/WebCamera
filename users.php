// users.php
<?php
/**
 * ���ʂ�json�ŕԋp����
 *
 * @param  array resultArray �ԋp�l
 * @return string json�ŕ\�����ꂽ���X�|���X
 * @author kobayashi
 **/
function returnJson($resultArray){
  if(array_key_exists('callback', $_GET)){
    $json = $_GET['callback'] . "(" . json_encode($resultArray) . ");";
  }else{
    $json = json_encode($resultArray);
  }
  header('Content-Type: text/html; charset=utf-8');
  echo  $json;
  exit(0);
}



/**
 * ���[�U�̈ꗗ��json�ŕԂ�
 *
 * @param string user_type  a,admin,o,operatorg,guest,�̂����ꂩ
 * @return array
 *          string result   OK,NG
 *          array  users   �������̂݁B���[�U���X�g
 *              string name ���[�U��
 *              int age �N��
 *          string message  ���s���̂݁B�G���[���b�Z�[�W
 *
 * @author kobayahshi
 **/
//---------------------------------------------------------
//  �����̊J�n
//---------------------------------------------------------
//  �l�̎擾�i���N�G�X�g�̎�t�j
$type = $_REQUEST['user_type'];

//  ���[�U���X�g�̏�����
$user_list = [];
//  �ԋp�l�̏�����
$result = [];

try {
  //  �l�̌���
  if (empty($type)) {
    throw new Exception("no type...");
  }

  //  ���[�U���X�g�̍쐬
  switch ($type) {
    case 'a':
    case 'admin':
      $user_list = [
        ['name'=>'����','age'=>18]
      ];
      break;
    case 'o':
    case 'operator':
      $user_list = [
        ['name'=>'�ؑ�','age'=>17],
        ['name'=>'�X','age'=>16]
      ];
      break;
    case 'g':
    case 'guest':
      $user_list = [
        ['name'=>'����','age'=>14],
        ['name'=>'����','age'=>15],
        ['name'=>'��_','age'=>15],
        ['name'=>'���c','age'=>15],
        ['name'=>'�X�c','age'=>15],
        ['name'=>'�O��','age'=>15],
        ['name'=>'����','age'=>15],
        ['name'=>'��{','age'=>15],
        ['name'=>'��m��','age'=>15]
      ];
      break;
    default:
      //  �s���Ȓl
      throw new Exception("Invalid value...");
      break;
  }

  //  �ԋp�l�̍쐬
  $result = [
    'result' => 'OK',
    'users' => $user_list
  ];
} catch (Exception $e) {
  $result = [
    'result' => 'NG',
    'message' => $e->getMessage()
  ];
}

//  JSON�Ń��X�|���X��Ԃ�
returnJson($result);