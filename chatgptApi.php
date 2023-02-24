<?php

// 判断调用来路
/* 允许跨域请求，接口建议允许 */
header('Access-Control-Allow-Origin:*');

$num = strpos(str_replace('https://','',str_replace('http://','',$_SERVER['HTTP_REFERER'])),'/admin/article.php');
$get_url = substr(str_replace('https://','',str_replace('http://','',$_SERVER['HTTP_REFERER'])),0,$num);
if(!isset($_SERVER['HTTP_REFERER'])|| strcmp($get_url,$_SERVER['HTTP_HOST'])!=0) {
        echo '不支持外部调用！'.$_SERVER['HTTP_REFERER'].$get_url;
        exit();
}
$text=$_GET['text'];
$id=$_GET['id'];
$key=$_GET['apikey'];
if($_GET['apikey']==""){
  $result = array(
      'code'=> 200,
      'msg'=>"0",
  );
  echo json_encode($result,320);
  exit();
}
function str_re($str){
  $str = str_replace(' ', "", $str);
  $str = str_replace("\n", "", $str);
  $str = str_replace("\t", "", $str);
  $str = str_replace("\r", "", $str);
  return $str;
}
if($id==1){
  session_start();
  if (!$_SESSION['chatgptSessionPrompt']) {
          $_SESSION['chatgptSessionPrompt'] = '';
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $prompt=$_SESSION['chatgptSessionPrompt'] .'\n提问:' . $text . '\n Ai:';
  $chatgptaray=array('\n提问:','\nAI:');
  curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, '{
    "model": "text-davinci-003",
    "prompt": "'.$prompt.'",
    "max_tokens": 2048
  }');
  curl_setopt($ch, CURLOPT_POST, 1);
  $headers = array();
  $headers[] = "Content-Type: application/json";
  $headers[] = "Authorization: Bearer ".$key;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  $response_data = json_decode($response, true);
  $_SESSION['chatgptSessionPrompt'] = $prompt . (isset($response_data['data']) ? $response_data['data'] : "");
  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  } else {

    $result = array(
        'code'=> 200,
        'msg'=>"获取成功",
        'data'=>array(
            'html'=> (isset($response_data['choices'][0]['text']) ? $response_data['choices'][0]['text'] : ""),
            'title'=>$text
        ),
    );
    echo json_encode($result,320);
    exit();
    
  }
  curl_close($ch);
}


if($id==3){
  session_start();
  if (!$_SESSION['chatgptSessionPrompt']) {
          $_SESSION['chatgptSessionPrompt'] = '';
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/dashboard/billing/credit_grants");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  
  // Set the API key as an HTTP header
  $headers = array();
  $headers[] = "Content-Type: application/json";
  $headers[] = "Authorization: Bearer ".$key;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $response = curl_exec($ch);
  $response_data = json_decode($response, true);
  $_SESSION['chatgptSessionPrompt'] = $prompt . (isset($response_data['id']) ? $response_data['id'] : "");
  if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
  } else {
    $result = array(
        'code'=> 200,
        'msg'=>"获取成功",
        'data'=>array(
            'html'=>  (isset($response_data['total_available']) ? $response_data['total_available'] : ""),
        ),
    );
    echo json_encode($result,320);
    exit();
    
  }
  curl_close($ch);   
}
?>