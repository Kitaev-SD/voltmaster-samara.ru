<?php

define('RECAP_CLIENT', '6Lf4HGcaAAAAAMMWRfJZfoUeBflYtpc3jGiGLDYf');
define('RECAP_SERVER', '6Lf4HGcaAAAAABmMjCT9LTiMtD5-S0H18qRP3dHi');
 
 
function checkRecap() {
  $recaptcha_key = RECAP_SERVER;
 
 
  $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
  $recaptcha_params = array(
    'secret' => $recaptcha_key,
    'response' => $_POST['recap_response'],
    'remoteip' => $_SERVER['REMOTE_ADDR'],
  );
 
  $ch = curl_init($recaptcha_url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha_params);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 
  $response = curl_exec($ch);
  if (!empty($response)) {
    $decoded_response = json_decode($response);
  }
 
  $recaptcha_success = false;
//dbg2f($decoded_response, 'responce');
 
  if ($decoded_response && $decoded_response->score > 0) {
    $recaptcha_success = $decoded_response->score;
// обрабатываем данные формы, которая защищена капчей
  } else {
// прописываем действие, если пользователь оказался ботом
  }
 
  if($recaptcha_success > 1.5) return $recaptcha_success;
 
  return 0;
 
}