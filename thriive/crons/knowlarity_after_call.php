<?php 
require '/var/www/html/wp-config.php';
global $wpdb;
$results = $wpdb->get_results("SELECT k.* FROM knowlarity_after_call k WHERE consultation_update=0");

//echo $response;
// $rs = json_decode($rs1,true);
 
// echo "<pre>";print_r($rs[0]); 
/* echo "<pre>";print_r($rs); */
if(!empty($results)){ //echo "<pre>";print_r($results);
	//for($i=0;$i < count($rs);$i++){
	foreach($results as $rs){
			
		//echo "<pre>";print_r($rs);
		echo $rs->call_time;echo "<br/>";
			$call_uuid = explode("_",$rs->call_uuid);
			$rs1 = $wpdb->get_row("SELECT o.id,o.session_duration,o.uid,o.email_sms FROM online_consultation o WHERE o.call_id LIKE '%". $call_uuid[0] ."%' AND action='call' ");
			
			//print_r($call_uuid);
			if($rs1){ 
				if($rs->call_status == 'Connected'){ 
					
					if($rs->call_transfer_status != 'Not Connected' && $rs->call_transfer_status != 'Missed'){
						$busy_date = date('Y-m-d H:i:s');
						if($rs->call_transfer_status == 'Connected'){ 
							$wpdb->query("UPDATE online_consultation SET connected=1,call_id='". $rs->call_uuid ."',busy_date='".$busy_date."',waiting=0 WHERE  id = '". $rs1->id ."'");
						} else {
							$wpdb->query("UPDATE online_consultation SET remaining_session = (remaining_session+1),call_id='". $rs->call_uuid ."',waiting=0,tid_accept=2 WHERE call_id LIKE '%". $call_uuid[0] ."%'");
						}
					} else {
						$wpdb->query("UPDATE online_consultation SET remaining_session = (remaining_session+1),connected=0,waiting=0,call_id='". $rs->call_uuid ."' WHERE id = '". $rs1->id ."'");
					}
						
				} else {
					//$busy_date = date('Y-m-d H:i:s', strtotime("+". $rs->session_duration ." mins"));
					$wpdb->query("UPDATE online_consultation SET remaining_session = (remaining_session+1),tid_accept=2,uid_accept=0,email_sms=1,waiting=0,connected=0 WHERE id = '". $rs1->id ."'");
					if($rs1->email_sms == 0){
						$umobile = get_user_meta($rs1->uid, 'mobile', true);
						$tempid = '1007684902010570563';
						$msg = "Hi from Thriive. Sorry the selected Therapist is not Reachable right now. Please select another Therapist or take your session whenever convenient";
						sendSMS($umobile,$msg,$tempid);
					}
				}
				$wpdb->query("UPDATE knowlarity_after_call SET consultation_update=1 WHERE ID = '". $rs->ID ."'");
			}
		
	}	
}

$results = $wpdb->get_results("SELECT k.* FROM transaction_info k WHERE 1 ORDER BY info_id DESC LIMIT 50");
if(!empty($results)){ 
	foreach($results as $rs){ 
		echo $rs->amount;echo "<br/>";
		$row = $wpdb->get_var("SELECT COUNT(*) FROM online_consultation  WHERE payment_txnid = '".$rs->txnid ."'  AND payment_status = 'success'");
		if($row == 0){
			$freemins = $rs->udf2;
			$sessionarr = explode(",",$freemins);
		
			
			$productinfo = $rs->productinfo;
			$date = $rs->created_date;
			
			$package = $rs->udf1;
			$txnid = $rs->txnid;
			$udf3 = explode(",",$rs->udf3);
			$firstname = $rs->firstname;
			$therapist_name = $wpdb->get_row("SELECT post_author,ID,post_title FROM wp_posts WHERE post_name = '". $udf3[0] ."'");
			$tid = $therapist_name->post_author;
			
			$tuserdata = get_userdata($therapist_name->post_author);
			$temail = $tuserdata->user_email;	
			$tfirst_name = $therapist_name->post_title;
			$email = $rs->uemail;
			$userdata = $wpdb->get_row("SELECT ID FROM wp_users WHERE user_login = '". $email ."'");
			$uid = $userdata->ID; 
			$status = $rs->payment_status;
			$data = array( 
				'uid' => $uid,
				'uname'	=> $firstname,
				'uemail' => $email,
				'tid' => $tid,
				'tname'	=> $tfirst_name,
				'temail' => $temail,
		
				'package' => $package,
				'therapy_name' => $productinfo,
			
				'cron_update' => 1,
				'payment_status' => $status,
				'payment_txnid' => $txnid,
				
				'created_date' => $date,
				'modified_date' => $date
			);
		
			$data['no_of_sessions'] = 1;
			
				$amount = $sessionarr[0];
				$data['amount'] = $amount;
				$action  = $sessionarr[5];
				$no_of_sessions = explode(" ", $sessionarr[1]);
				$data['no_of_sessions'] = 1;
				$data['action'] = $action;
				$data['pkgdescription'] = $sessionarr[2];
				$data['session_duration'] = $sessionarr[4];
				$session_duration = $sessionarr[4] + 5;
			
			$data['busy_date'] = date('Y-m-d H:i:s', strtotime("+". $session_duration . " mins"));
			if($status == 'success' && $productinfo != 'Appointment'){
				$data['remaining_session'] = 1;
			}	else {
				$data['remaining_session'] = 0;
			}
			$busy_date = $data['busy_date'];
			if($action == 'book_latercall'){
				$data['action'] =  "call";
			}elseif($action == 'book_laterchat'){
				$data['action'] =  "chat";	
			}	
			
			
			//if($tid != 15680){ 
				$wpdb->insert("online_consultation",$data);
				$lastid = $wpdb->insert_id;
				if($status == 'success' && $productinfo == 'Appointment'){
					$book_date_time = $udf3[1].','.$udf3[2];
					$split_datetime = explode(",",$book_date_time);
					$timestamp = strtotime($split_datetime[1]) + 300*60;
					$endtime = date('H:i:s', $timestamp);
					$video_data = array(
						'oc_id' => $lastid,
						'tid' => $tid,
						'uid' => $uid,
						'call_time' => '40',
						'channel' => rand().time(),
						'action' => $action,
						'book_date' => date("Y-m-d",strtotime($split_datetime[0])),
						'book_time' => date("H:i:s",strtotime($split_datetime[1])),
						'end_time' => $endtime == '00:00:00'?'23:59:59':$endtime,
						'page_id' => 0,
						'status' => 0
					);
					if($action == "videocall" ||  $action == "call" || $action == "chat"){
						$video_data['channel'] = rand().time();
						create_room($video_data['channel']);
					}	
					$wpdb->insert("oc_video_call",$video_data);
					$tmsg = "Your client ".$uname." has taken  taken a slot for video call session on ".date("dS M Y",strtotime($split_datetime[0]))." at ".date("h:i a",strtotime($split_datetime[1])).". Please login to your account and start online video call with the client on thriive.in"; 
				}	
				$tempid = '1007025056493402663';
				$uidmobile = get_user_meta($uid,'mobile',true);
				$uidmobile = preg_replace('/(?<=\d)\s+(?=\d)/', '', $uidmobile);
				$tidmobile = get_user_meta($tid,'mobile',true);
				$tidmobile = preg_replace('/(?<=\d)\s+(?=\d)/', '', $tidmobile);
				sendSMS($tidmobile,$tmsg,$tempid);
				sendSMS('8850630321',$tmsg,$tempid);
			//}
			if($action == 'call' && $productinfo != 'Appointment'){
				
				$curl = curl_init();
				$data = array("k_number"=> "+917411782154","agent_number"=> '+91'.$tidmobile,"customer_number"=>'+91'.$uidmobile,"caller_id"=> "+918048166487","additional_params"=>array("object_id"=> "1","user_id"=> "2", "call_log_id" => 1,"timeout"=> ($sessionarr[4])));
				curl_setopt_array($curl, array(
				  CURLOPT_URL => 'https://kpi.knowlarity.com/Basic/v1/account/call/makecall',
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'POST',
				  CURLOPT_POSTFIELDS =>json_encode($data),
				  CURLOPT_HTTPHEADER => array(
					'Authorization: 28637081-d7a7-4bf5-96aa-c79fbf2aba42',
					'x-api-key: lF4vZUSwA8Jab0ABWsITtxwM1ZwL6h2jZDdCTX30',
					'Content-Type: application/json'
				  ),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				$callresp = json_decode($response,true); 
		
		
				if(!empty($callresp)){
					if($callresp['success']['status'] == 'success'){
						$connected = 1; 
						$uid_accept = 1;
						$tid_accept = 1;
						$call_id = $callresp['success']['call_id'];
						$remaining_session =0;
					}
				}
				$wpdb->query("UPDATE online_consultation SET tid_accept='".$tid_accept."',uid_accept='".$uid_accept."',connected='".$connected."',remaining_session=0,waiting=1,email_sms=0,call_id = '".$call_id."' WHERE id='".$lastid."'");	
				if(strpos($firstname, "@") !== false){
					$uname = explode('@', $firstname)[0];
				} else{
					$uname = $firstname;
				}
				$tsubject = "New Session from ".$uname ." - ".$lastid;
				$usrdet = get_user_meta($tid,0);
				
				$tmsg ="Hello ".$usrdet['first_name'][0] ." ".$usrdet['last_name'][0] .",<br/>Your client ".$uname ." has taken ".$sessionarr[2] ." and is waiting for a session with you .";
				$tmsg .= "<br/><br/> Please Note:<br/>There is no auto disconnect for calls. It is your responsibility to end the session within the stipulated time.<br/><br/>Thriive will only make payment for the number of minutes purchased by the client.<br/><br/>You can encourage your client to extend the session, a link for which will be shared with the client after this call ends.";
				$tmsg .= "<br/><br/>Love and Light,<br/>Team Thriive";
				sendEmail($tuserdata->user_email, $tsubject, $tmsg);
			
				$tmsg = "Your client ".$uname ." has taken ".$sessionarr[2] ." and is waiting for a session with you. Please click on link given to accept the session with client within the next 2 minutes on thriive.in";
				$tempid = '1007507627626666229';
				sendSMS($tidmobile,$tmsg,$tempid);
			
			}
		}	
	}	
}

  

$query = "SELECT * FROM online_consultation WHERE (tid_accept=0 OR tid_accept='1') AND action='chat' AND cron_update=0 AND uid_accept=0 AND remaining_session=1 ORDER BY id DESC LIMIT 25";
$res = $wpdb->get_results($query);
$current_time = strtotime(date('Y-m-d H:i:s'));
$busy_time_exp = strtotime($res[0]->busy_date);
$up_string='';
foreach($res as $key){
$busy_time_def = strtotime($key->busy_date);
//$busy_time_exp = strtotime($key->busy_date)+240;
$busy_time_exp = strtotime($key->busy_date)-30;
$current_time = strtotime(date('Y-m-d H:i:s'));
$ocid = $key->id;
if($current_time > $busy_time_exp){
	$query = "UPDATE online_consultation SET uid_accept='-2' WHERE id=$ocid";
	echo $query;
	$wpdb->query($query);
}}




?>