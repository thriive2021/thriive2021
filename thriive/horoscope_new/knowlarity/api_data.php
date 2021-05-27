<?php
require '/var/www/html/wp-config.php';
global $wpdb;
if(wp_get_current_user()->roles[0] == 'administrator'){
	
//print_r($_POST);

$agent = '+91'.$_POST['agent'];
$customer = '+91'.$_POST['customer'];
$time = $_POST['timer'];
$payment_id = $_POST['payment_id'];
$POST_fields = '{
  "k_number": "+917411782154",
  "agent_number": "'.$agent.'",
  "customer_number": "'.$customer.'",
  "caller_id": "+918048166487",
  "additional_params":{"object_id": "1","user_id": "2", "call_log_id" : 1,"timeout":"'.$time.'"}
}';

$userdata = $wpdb->get_row("SELECT user_id FROM wp_usermeta WHERE meta_key = 'mobile' AND meta_value = '".$_POST['customer']."'");
$uid = $userdata->user_id; 

$therapistdata = $wpdb->get_row("SELECT user_id FROM wp_usermeta WHERE meta_key = 'mobile' AND meta_value = '".$_POST['customer']."'");
$tid = $therapistdata->user_id; 


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://kpi.knowlarity.com/Basic/v1/account/call/makecall',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$POST_fields,
  CURLOPT_HTTPHEADER => array(
    'Authorization: 28637081-d7a7-4bf5-96aa-c79fbf2aba42',
    'x-api-key: lF4vZUSwA8Jab0ABWsITtxwM1ZwL6h2jZDdCTX30',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;


}
 


if(strpos($response, 'success')>0){
	$callresp = json_decode($response,true); 
	$connected = 1;  
	$uid_accept = 1;
	$tid_accept = 1;
	$call_id = $callresp['success']['call_id'];
	$remaining_session =0;			
	
	$wpdb->query("UPDATE online_consultation SET tid_accept='".$tid_accept."',uid_accept='".$uid_accept."',connected='".$connected."',waiting=1,connected=1,remaining_session=0,def_warn=1,email_sms=0,call_id = '".$call_id."' WHERE payment_txnid = '".$payment_id."'");
	echo '<h1 style="color:green">Call Successfully Placed</h1>';
  header("location: https://www.thriive.in/knowlarity-data?stat=success");
}else{
  echo '<h1 style="color:red">Failed To place the call</h1>';
  header("location: https://www.thriive.in/knowlarity-data?stat=fail");
}

?>