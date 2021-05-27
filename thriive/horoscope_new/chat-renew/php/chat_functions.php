<?php 

require '/var/www/html/wp-config.php';
include_once get_stylesheet_directory() . '/framework/core-functions.php';
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

global $wpdb;
$action = $_POST['action'];
if($action == 'create'){
	$from = $_POST['from'];
	$to = $_POST['to'];
	$mail = $_POST['mail'];
	
	create($to, $from,$mail);
	//echo '<script>console.log("'.$action.'")</script>';
}else if($action == 'write'){
	//echo '<script>console.log("'.$action.'")</script>';
	//echo '<script>alert('.print_r($_FILES['img']['name']).')</script>';
	//echo '<script>console.log("'.$action.'")</script>';
	//print_r($file['name']);
	$from = $_POST['from'];
	$to = $_POST['to'];
	$mail = $_POST['mail'];
	$msg = $_POST['msg'];
	$filename = $_POST['filename'];
	$file = $_FILES['img'];
	$text_msg = $_POST['text_msg'];
	write($from, $to, $mail, $msg, $filename,$file,$text_msg);
}else if($action == 'read'){
	$from = $_POST['from'];
	$to = $_POST['to'];
	$filename = $_POST['filename'];
	$arr_count = $_POST['arr_count'];
	$mail_to = $_POST['mail_to'];
	read($from, $to, $arr_count, $filename, $mail_to);
}else if($action == 'check'){
	active_status();
}else if($action == 'unblock'){
	$from = $_POST['from'];
	$to = $_POST['to'];
	unblock_user($_POST['from'],$_POST['to']);
}else if($action == 'block'){
	$from = $_POST['from'];
	$to = $_POST['to'];
	block_user($from,$to);
}else if($action == "delete"){
	$from = $_POST['from'];
	$to = $_POST['to'];
	$count = $_POST['count'];
	delete_chat($from, $to, $count);
}else if($action == 'feed'){
	$id_string = $_POST['id_string'];
	feed_data($id_string);
}else if($action == 'complete'){
	$to = $_POST['to'];
	$from = $_POST['from'];
	complete_chat($to,$from);
}else if($action == 'tname'){
	$to = $_POST['to'];
	$from = $_POST['from'];
	tname($to,$from);
}else if($action == 'accept'){
	$uid = $_POST['curr_user'];
	$role = $_POST['curr_role'];
	accept($uid,$role);
}else if($action == 'incomplete'){
	$to = $_POST['to'];
	$from = $_POST['from'];
	incomplete($to,$from);
}else if($action == 'accept_chat'){
	$to = $_POST['to'];
	$from = $_POST['from'];
	$oc_id = $_POST['oc_id'];
	accept_chat($to,$from,$oc_id);
}else if($action == 'get_curr_user'){
	get_curr_user();
}else if($action == 'browse'){
	$ocid=$_POST['ocid'];
	$act = $_POST['act'];
	browse_user($ocid,$act);
}else if($action == 'set_time'){
	$curr_time = $_POST['curr_time'];
	$ocid = $_POST['ocid'];
	curr_time($curr_time,$ocid);
}else if($action == 'reject_chat'){
	$to = $_POST['to'];
	$from = $_POST['from'];
	$ocid = $_POST['ocid'];
	reject_chat($to,$from,$ocid);
}else if($action == 'revive'){
	$ocid = $_POST['ocid'];
	revive($ocid);
}else if($action == 'busy_ther'){
	$ocid = $_POST['ocid'];
	busy_ther($ocid);
}else if($action == 'warn'){
	$ocid = $_POST['ocid'];
	def_warn($ocid);
}

//echo '<script>console.log("'.$action.'")</script>';

function tname($to,$from){
	//print_r(get_user_meta($to)['wp_capabilities'][0]);
	//$capabilities = get_user_meta($from)['wp_capabilities'][0];
	if(strpos(get_user_meta($to)['wp_capabilities'][0],'therapist')){
		$tname = get_user_meta($to)['first_name'][0].' '.get_user_meta($from)['last_name'][0];
		echo $tname;
	}
}


function create($to, $from,$mail){
	date_default_timezone_set("Asia/Kolkata");
	global $wpdb;
	$query = "SELECT * FROM chat_message_details WHERE ((to_user_id=$to AND from_user_id=$from) OR (to_user_id=$from AND from_user_id=$to))  ORDER BY chat_time ASC";
	$results = $wpdb->get_results($query);
	$results_encode = json_encode($results);
	$results1 = json_decode($results_encode, true);
	//print_r($results1);
		//echo '<p class="message-left">left</p><br><p class="message-right">right</p>';

	if(strpos(get_user_meta($from)['wp_capabilities'][0],'therapist')){
		$tname = get_user_meta($from)['first_name'][0].' '.get_user_meta($from)['last_name'][0];
		//echo $tname;
	}

	if(strpos(get_user_meta($to)['wp_capabilities'][0],'therapist')>0){
		$tname = get_user_meta($to)['first_name'][0].' '.get_user_meta($from)['last_name'][0];
	}else{$tname = strstr($mail,'@', true);}
	$first_hello=0;
	foreach($results1 as $key){
		if($key['to_user_id'] == $to){
			if($first_hello==0){
				echo '<div class="message-left" style="display:none">'.$key['chat_message'].'<p class="right_date">'.date('d-M', strtotime($key['chat_time'])).' '.explode(':', explode(' ', $key['chat_time'])[1])[0].':'.explode(':', explode(' ', $key['chat_time'])[1])[1].'</p></div><p style="font-size:14px !important;margin:0 !important;padding-left:5px;display:none">You</p><br>';		$first_hello++;
			}else{
				echo '<div class="message-left">'.$key['chat_message'].'<p class="right_date">'.date('d-M', strtotime($key['chat_time'])).' '.explode(':', explode(' ', $key['chat_time'])[1])[0].':'.explode(':', explode(' ', $key['chat_time'])[1])[1].'</p></div><p style="font-size:14px !important;margin:0 !important;padding-left:5px;">You</p><br>';
					$first_hello++;
			}
			}else{
				if($first_hello==0){
				echo '<div class="message-right" id="C'.$from.'">'.$key['chat_message'].'<p class="right_date">'.date('d-M', strtotime($key['chat_time'])).' '.explode(':', explode(' ', $key['chat_time'])[1])[0].':'.explode(':', explode(' ', $key['chat_time'])[1])[1].'</p></div><p style="font-size:14px !important;margin:0 !important;padding-right:5px;text-align:right;">'.$tname.'</p></br>';		$first_hello++;
			}else{
		echo '<div class="message-right" id="C'.$from.'">'.$key['chat_message'].'<p class="right_date">'.date('d-M', strtotime($key['chat_time'])).' '.explode(':', explode(' ', $key['chat_time'])[1])[0].':'.explode(':', explode(' ', $key['chat_time'])[1])[1].'</p></div><p style="font-size:14px !important;margin:0 !important;padding-right:5px;text-align:right;">'.$tname.'</p></br>';
		}
		}
	}
		
	if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json')){
		$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json';
	}else if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json')){
		$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json';
	}else{
		$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json';
		$fp = fopen('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json', 'w');
		fwrite($fp, '[]');
		fclose($fp);
		//echo 'file-created';
		echo '<script>var arr_count = '.count($json_contents_array).';setTimeout(function(){document.querySelector(".messages").scrollTop = 10000;},500);</script>';
	}
	
	if(file_exists($filename)){
		$json_contents = file_get_contents($filename);
		$json_contents_array = json_decode($json_contents, true);
	if(strpos(get_user_meta($from)['wp_capabilities'][0],'therapist')){
		$tname = get_user_meta($from)['first_name'][0].' '.get_user_meta($from)['last_name'][0];
	}
		$json_fmsg=0;
		foreach($json_contents_array as $key){
			if($key['to_user_id'] == $to){
				$json_fmsg++;
		echo '<div class="message-left">'.$key['chat_message'].'<p class="right_date">'.date('d-M', strtotime($key['chat_time'])).' '.explode(':', explode(' ', $key['chat_time'])[1])[0].':'.explode(':', explode(' ', $key['chat_time'])[1])[1].'</p></div><p style="font-size:12px !important;margin:0 !important;padding-left:5px;padding-top:5px;">You</p><br>';
			if($json_fmsg==1){
					echo '<p style="color:blue;"id="nUser"><br>Please wait for user to come online for at least 5-7 mins.The chat time will begin after User has sent 1st message. If user does not come online for 5-7 mins you can press user not available from chat menu<br></p>';
				}
			
		}else{

		echo '<div class="message-right" id="C'.$from.'">'.$key['chat_message'].'<p class="right_date">'.date('d-M', strtotime($key['chat_time'])).' '.explode(':', explode(' ', $key['chat_time'])[1])[0].':'.explode(':', explode(' ', $key['chat_time'])[1])[1].'</p></div><p style="font-size:12px !important;margin:0 !important;padding-right:5px;text-align:right;padding-top:5px;">'.$tname.'</p></br>';
		}
		}
		echo '<script>var arr_count = '.count($json_contents_array).'</script>';
		echo '<script>var filename = "'.$filename.'"</script>';

	}else{
		
	}
	
	$query = "SELECT * FROM check_delete_status WHERE from_user_id=$from";
	$res = $wpdb->get_results($query);
	$res = json_encode($res);
	$res = json_decode($res, true);
	$res_count = $res[0]['count'];
	if(count($res)>0){
		echo "<script>var del_chat = document.querySelectorAll('#C".$from."');for(i=0;i<".$res_count.";i++){del_chat[i].nextElementSibling.replaceWith('');del_chat[i].style.display = 'none';}</script>";
	}

	$query = "SELECT complete_status FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
	$res = $wpdb->get_results($query);
	//print_r($res[0]->complete_status);
	if($res[0]->complete_status == 1){
		echo "<script>document.querySelector('.msg').innerHTML = 'This chat is now complete'</script>";
	}

	$query = "SELECT incomplete_status FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
	$res = $wpdb->get_results($query);
	//print_r($res[0]->complete_status);
	if($res[0]->incomplete_status == 1){
		echo "<script>document.querySelector('.msg').innerHTML = 'Marked as Incomplete'</script>";
	}



	$query = "SELECT chat_init FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to ORDER BY id DESC";
	$res = $wpdb->get_results($query);
	if(count($res)==0){
	}else if(strlen($res[0]->chat_init)>2){
	}else{
		date_default_timezone_set('Asia/Kolkata');
		$time = date('d-m-Y H:i:s');
		$query = "UPDATE chat_block_users SET chat_init = '$time' WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to ORDER BY id DESC";
		$wpdb->query($query);
	}


}


function write($from, $to, $mail, $msg, $filename,$file,$text_msg){
	date_default_timezone_set("Asia/Kolkata");
	if(!$filename){
		echo 'Temporary Error';
	}
	$date = date("Y-m-d h:i:s");
	
	if($file){
		$uploaded_file_name = $file['name'];
		$tmp_file_name = $file['tmp_name'];
		$explode_file_name = explode('.', $uploaded_file_name);
		$explode_length = count($explode_file_name);
		$file_extension_default = $explode_file_name[$explode_length-1];
		$file_extension = strtolower($file_extension_default);
		$check_array = array(
				'jpg',
				'jpeg',
				'png'
			);
		if(in_array($file_extension, $check_array)){
		$new_file_name = uniqid('', true).'.'.$file_extension;
		$file_destination = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/uploads/'.$new_file_name;
		$file_url = get_site_url()."/wp-content/themes/thriive/horoscope_new/chat-renew/uploads/".$new_file_name;
		move_uploaded_file($tmp_file_name, $file_destination);}else{echo 'File Must Be An Image';}
		if(file_exists($file_destination)){echo 'file uploaded';$msg = "<img src= ".$file_url.">";}else{/*echo $file_destination;*/}
	}else{//echo 'no file';
			}

	$feed_array = array(
					'to_user_id' => $to,
					'from_user_id' => $from,
					'chat_message' => $msg,
					'chat_time' => $date
						);

	$file_contents = file_get_contents($filename);
	$file_contents_array = json_decode($file_contents, true);
	if(is_array($file_contents_array)){
		array_push($file_contents_array, $feed_array);
	}
	$file_contents_array_json = json_encode($file_contents_array);
	
	//print_r($file_contents_array);
	$fp = fopen($filename, 'w');
	fwrite($fp, $file_contents_array_json);
	fclose($fp);


/*
	if(wp_get_current_user()->caps['therapist']==1){
		$mobileNo = get_user_meta(wp_get_current_user()->ID)['mobile'][0];
		$msg = 'Hi, your chat request has been accepted by our Therapist. Please click here to start chatting now. Love & Light - Team Thriive.';
		sendSMS($mobileNo, $msg);
	}
*/


	//if($file){print_r($file['name']);}else{echo 'no file';}


	
	//echo '<script>alert('.print_r($_FILES['name']).')</script>';
	
	//echo '<div class="message-right">'.$msg.'</div></br>';
	//echo '<script>var arr_count = '.count($file_contents_array).'</script>';


	/*
	$filename_explode = explode('.', $filename);
	$filename_w_ext = $filename_explode[0];
	$filename_check =  $filename_w_ext.'_check.json';
	$fp = fopen($filename_check, 'w');
	fwrite($fp, 'this is a string');$headers .= "Bcc: productmanager@thriive.in\r\n";
	fclose($fp);

	if(file_exists($filename_check)){
		echo 'yes';
	}*/

	echo filesize($filename);
	
	$to = $mail;
	$from = 'akash@thriive.in';
	$body = $text_msg;
	$subject = 'Thriive Chat';
	$headers .= 'From: noreply@thriive.in' . "\r\n";


	//if(mail($to,$subject,$body,$headers)){}else{}




	$from = $file_contents_array[0]['from_user_id'];
	$to = $file_contents_array[0]['to_user_id'];
	$from_count=0;
	$to_count=0;
	foreach($file_contents_array as $key){
		if($key['from_user_id'] == $from AND $status == 'c'){$status ='d';break;
	}else if($key['from_user_id']==$from){
			$status = 'b';
			$from_count++;
			$last_user = $from;
	}else if($key['from_user_id']==$to){
			$status = 'c';
			$to_count++;
			$last_user=$to;
	}
}

//echo $status.'--'.$from_count.'--'.$from;

if($from_count == 1 AND $last_user==$from){
	if(strpos(get_user_meta($from)['wp_capabilities'][0], 'therapist')>0){
		$curr_role = 'therapist';
		$mob_msg = 'Hi Your Chat request has been accepted by our Therapist please click on the link https://www.thriive.in/my-account-page to start chatting.Love & Light - Team Thriive';
		$mob_no = get_user_meta($to)['mobile'][0];
		sendSMS($mob_no,$mob_msg);
		//echo 'from';
	}else{$curr_role = 'subsciber';
		  $mob_msg = 'Hi you have a chat request please visit your thriive dashboard to start chatting by clicking on this link https://www.thriive.in/my-account-page . Love & Light - Team Thriive';
		  $mob_no = get_user_meta($to)['mobile'][0];
		  //sendSMS($mob_no,$mob_msg);
		  //echo 'sub from';
}
}else if($to_count == 1 AND $last_user==$to){
	if(strpos(get_user_meta($to)['wp_capabilities'][0], 'therapist')>0){
		$curr_role = 'therapist';
		$mob_msg = 'Hi Your Chat request has been accepted by our Therapist please click on the link https://www.thriive.in/my-account-page to start chatting. Love & Light - Team Thriive';
		$mob_no = get_user_meta($from)['mobile'][0];
		sendSMS($mob_no,$mob_msg);
	}else{$curr_role = 'subsciber';
		  $mob_msg = 'Hi you have a chat request please visit your thriive dashboard to start chatting by clicking on this link https://www.thriive.in/my-account-page . Love & Light - Team Thriive';
		  $mob_no = get_user_meta($from)['mobile'][0];
		  //sendSMS($mob_no,$mob_msg);
}
}
//print_r($mob_no.'--'.$mob_msg);













}

function read($from, $to, $arr_count,$filename,$mail_to){

	date_default_timezone_set("Asia/Kolkata");
	//echo '<script>alert("test")</script>';
	if(file_exists($filename)){
		$file_contents = file_get_contents($filename);
		$file_contents_array = json_decode($file_contents, true);
		$file_contents_array_count = count($file_contents_array);
		//echo '<script>alert('.$file_contents_array_count.')</script>';
		if($file_contents_array_count>$arr_count){
			//echo '<script>alert("yes")</script>';
			for($i=$arr_count;$i<$file_contents_array_count;$i++){
			//echo '<script>alert("yes")</script>';
				if($file_contents_array[$i]['from_user_id'] == $from){
					
					echo '<div class="message-left">'.$file_contents_array[$i]['chat_message'].'<p class="right_date">'.date('d-M', strtotime($file_contents_array[$i]['chat_time'])).' '.explode(':', explode(' ', $file_contents_array[$i]['chat_time'])[1])[0].':'.explode(':', explode(' ', $file_contents_array[$i]['chat_time'])[1])[1].'</p></div><p style="font-size:12px !important;margin:0 !important;padding-left:5px;padding-top:5px;">You</p><br>';
					echo '<script>var elem = document.querySelector(".messages");elem.scrollTop = elem.scrollHeight;</script>';
					$role = get_user_meta($from)['wp_capabilities'][0];
					if(strpos($role,'therapist')>0){$ther = 1;}else{$ther = 0;}
					if($arr_count==0 AND $ther==1){
					echo '<p style="color:blue;"id="nUser"><br>Please wait for user to come online for at least 5-7 mins.The chat time will begin after User has sent 1st message. If user does not come online for 5-7 mins you can press user not available from chat menu<br></p>';
					}





					//echo '<script>console.log("'.date('d-M H:m', strtotime($file_contents_array[$i]['chat_time'])).'"");</script>';
					}else{
					echo '<div class="message-right" id="C'.$from.'">'.$file_contents_array[$i]['chat_message'].'<p class="right_date">'.date('d-M', strtotime($file_contents_array[$i]['chat_time'])).' '.explode(':', explode(' ', $file_contents_array[$i]['chat_time'])[1])[0].':'.explode(':', explode(' ', $file_contents_array[$i]['chat_time'])[1])[1].'</p></div><p style="font-size:12px !important;margin:0 !important;padding-right:5px;text-align:right;padding-top:5px;">'.strstr($mail_to,'@', true).'</p></br>';
					echo '<script>var elem = document.querySelector(".messages");elem.scrollTop = elem.scrollHeight;</script>';
					//echo '<script>console.log("'.$file_contents_array[$i]['chat_time'].'");</script>';
				}
			}
			echo '<script>var arr_count = '.count($file_contents_array).'</script>';
			
		}else{echo '<script>console.log("fail")</script>';}
	}

	/*if(file_exists($filename)){
		$file_contents = file_get_contents($filename);
		$file_contents_array = json_decode($file_contents, true);
		$file_contents_array_count = count($file_contents_array);

		if($file_contents_array_count>$arr_count){
			for($i=$arr_count;$i<$file_contents_array_count;$i++){
				if($file_contents_array[$i]['to_user_id'] == $from){
					echo '<div class="message-left">'.$file_contents_array[$i]['chat_message'].'</div><br>';
					echo '<script>var arr_count = '.count($json_contents_array).'</script>';
					}else{
					echo '<div class="message-right">'.$file_contents_array[$i]['chat_message'].'</div></br>';
					echo '<script>var arr_count = '.count($json_contents_array).'</script>';
				}
			}
		}
	}*/
}

function active_status(){
	$from = $_POST['from'];
	$to = $_POST['to'];
	$to = rtrim($to,',');
	$to = explode(',', $to);
	$size_array = array();
	for($i=0;$i<count($to);$i++){
		$to[$i]= substr($to[$i], "21");
		
		if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to[$i].'_'.$from.'.json')){
			$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to[$i].'_'.$from.'.json';
			$files_count[$i] = filesize($filename);
			//echo '<script>to_count['.$i.'] = '.filesize($filename).';</script>';
			$size_array[$to[$i]] = filesize($filename);
		}else if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to[$i].'.json')){
			$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to[$i].'.json';
			$files_count[$i] = filesize($filename);
			//echo '<script>to_count['.$i.'] = '.filesize($filename).';</script>';
			$size_array[$to[$i]] = filesize($filename);
		}


	}

	$size_array_json = json_encode($size_array);
	echo $size_array_json;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
//_______________________________________UNBLOCK USER________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function unblock_user($from,$to){
	global $wpdb;
	$query = "SELECT * FROM chat_block_users WHERE from_user_id = $from AND to_user_id = $to";
	$res = $wpdb->get_results($query);
	//echo count($res);
	if(count($res)>0){
		$query = "UPDATE chat_block_users SET block_status=0 WHERE from_user_id=$from AND to_user_id=$to";
		$res = $wpdb->query($query);
		echo 'User Unblocked Successfully';
	}




	//$insert = "INSERT INTO chat_block_users (to_user_id, from_user_id, block_status, block_details) VALUES ($to, $from, 8, 'none')";
	//print_r($insert);
	//$res = $wpdb->query($insert);
	
/*	
	if($res == 1){
		echo 'user unblocked';
	}else{
		echo 'Please try after some time';
	}
*/
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
//_______________________________________UNBLOCK USER________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________BLOCK USER________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function block_user($from,$to){
	global $wpdb;
	$query = "SELECT * FROM chat_block_users WHERE from_user_id=$to AND to_user_id=$from";
	$res = $wpdb->get_results($query);
	print_r($res);
	if(count($res)>0){
		$query = "UPDATE chat_block_users SET block_status=1 WHERE from_user_id=$to AND to_user_id=$from";
		$res = $wpdb->query($query);
		//echo 'User Blocked Successfully'.'else';
	}else{
		$query = "INSERT INTO chat_block_users (`to_user_id`,`from_user_id`,`block_status`,`block_details`) VALUES ($from, $to, 1, 'none')";
		$res = $wpdb->query($query);
		echo $res.'else';
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________BLOCK USER________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________DELETE____________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////

/*
function delete_chat($from, $to){
	global $wpdb;
	$file_address1 = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json';
	$file_address2 = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json';
	$del_array = array();
	$del_array_count = 0;
	if(file_exists($file_address1)){
		echo 'file1';
	}else if(file_exists($file_address2)){
		$file_contents = file_get_contents($file_address2);
		$file_contents_array = json_decode($file_contents, true);
		$file_contents_array_count = count($file_contents_array);
		//echo $file_contents_array_count;
		//print_r($file_contents_array);
		foreach ($file_contents_array as $key) {
				if($key['from_user_id'] != $from){
					$del_array[$del_array_count] = $key;
					$del_array_count = $del_array_count+1;
				}
		}
		print_r($del_array);
	}else{echo 'else';}
}
*/


function delete_chat($from, $to, $count){
	global $wpdb;
	$query = "SELECT * FROM check_delete_status WHERE from_user_id = $from";
	$res = $wpdb->get_results($query);
	$res = json_encode($res);
	$res = json_decode($res, true);
	$res_count = $res[0]['count']-1;
	$res_ind = $res_count;
	$count_rev = $count;
	if(count($res)>0){
		$query = "UPDATE check_delete_status SET count=$count WHERE from_user_id=$from";
		//echo $query;
		$res = $wpdb->query($query);
		$count_sub = $count-$res_count;
		echo $count_sub;
		$count =  $count-$count_sub;
		echo "<script>var del_chat = document.querySelectorAll('#C".$from."');for(i=".$res_count.";i>".$count.";i--){del_chat[i].nextElementSibling.replaceWith('');del_chat[i].style.display = 'none';}</script>";
	}else{
		$query = "INSERT INTO check_delete_status (`to_user_id`, `from_user_id`, `status`, `count`) VALUES ($to, $from, 1, $count)";
		$res = $wpdb->query($query);
		echo "<script>var del_chat = document.querySelectorAll('#C".$from."');for(i=0;i<".$count.".length;i++){del_chat[i].nextElementSibling.replaceWith('');del_chat[i].style.display = 'none';}</script>";

		}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________DELETE____________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________Feed Data_________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function feed_data($id_string){
	global $wpdb;
	$id_string = rtrim($id_string,'|');
	$id_array = explode('|', $id_string);
	$filename_array = array();
	for($i=0;$i<count($id_array);$i++){
		$id_array[$i] = explode('_', $id_array[$i]);
		if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$id_array[$i][0].'_'.$id_array[$i][1].'.json')){
			$filename_array[$i] = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$id_array[$i][0].'_'.$id_array[$i][1].'.json';
		}else if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$id_array[$i][1].'_'.$id_array[$i][0].'.json')) {
			$filename_array[$i] = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$id_array[$i][1].'_'.$id_array[$i][0].'.json';
		}
	}					
	//print_r($filename_array);	
	$query_string = "";
	$query_string_array = array();
	$contents = array();		
	for($i=0;$i<count($filename_array);$i++){
		
	if(file_exists($filename_array[$i])){
		$contents[$i] = file_get_contents($filename_array[$i]);
		$contents[$i] = json_decode($contents[$i], true);
	}}
	for($i=0;$i<count($contents);$i++){
		echo $i;
		foreach($contents[$i] as $key){
			$query_string_array[$i] .= '("'.$key['to_user_id'].'","'.$key['from_user_id'].'","'.$key['chat_message'].'","'.$key['chat_time'].'"),';			
		}
	}
	for($i=0;$i<count($query_string_array);$i++){
		$query_string .= $query_string_array[$i];
	}
	$query_string = rtrim($query_string,',');
	echo $query_string;

	$query = "INSERT INTO chat_message_details(to_user_id, from_user_id, chat_message,chat_time) VALUES $query_string";
	echo $wpdb->query($query);
	
	for($i=0;$i<$filename_array;$i++){
		unlink($filename_array[$i]);
	}
	







	/*$query = "INSERT INTO chat_message_details(to_user_id, from_user_id, chat_message,chat_time) VALUES $query_string";
	echo $wpdb->query($query);
	unlink($filename_array[$i]);
	echo 'yes';*/
	//print_r(count($query_string));

}

///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________Feed Data_________________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________COMPLETE CHAT_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function complete_chat($to,$from){
	global $wpdb;
	$query = "SELECT complete_status,oc_id FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
	$res = $wpdb->get_results($query);
	if($res[0]->oc_id == NULL){
		$query = "SELECT id FROM online_consultation WHERE (tid=$to and uid=$from OR tid=$from and uid=$to) AND action='chat' ORDER BY id DESC LIMIT 1";
		echo $query;
		$oc_res = $wpdb->get_results($query);
		if(count($oc_res)>0){
			$oc_id = $oc_res[0]->id;
		}
	}else{
		$oc_id = $res[0]->oc_id;
	}
	//print_r(count($res));
	if(count($res)>0){
		//$oc_id = $res[0]->oc_id;
		$query = "UPDATE chat_block_users SET complete_status = 1,oc_id=$oc_id WHERE to_user_id=$from and from_user_id=$to OR from_user_id=$from and to_user_id=$to";
		$res = $wpdb->query($query);
	echo $res;
	} else {
		$query = "INSERT INTO `chat_block_users`(`to_user_id`, `from_user_id`,`block_status`,`block_details`,`oc_id`,`complete_status`) VALUES ('$to','$from',0,'none','$oc_id',1)";
		$res = $wpdb->query($query);
		echo $res;
	}	
	$query = "SELECT oc_id FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to ORDER BY id DESC LIMIT 1";
	$res = $wpdb->get_results($query);
	$ocid = $res[0]->oc_id;

	$query = "UPDATE online_consultation SET remaining_session = 0 WHERE id=$ocid";
	$res = $wpdb->query($query);


	if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json')){
		$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json';
	}else if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json')){
		$filename = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json';
	}

	if($filename){
	$file_contents = file_get_contents($filename);
	$file_contents = json_decode($file_contents, true);
	if(is_array($file_contents) AND count($file_contents)>0){
		$query_string = '';
		foreach($file_contents as $key){
			$query_string .= '("'.$key['to_user_id'].'","'.$key['from_user_id'].'","'.$key['chat_message'].'","'.$key['chat_time'].'","'.$oc_id.'"),';
		}
		$query_string = rtrim($query_string, ',');
		$query = "INSERT INTO chat_message_details(from_user_id, to_user_id, chat_message,chat_time,oc_id) VALUES $query_string";
		$query_status = $wpdb->query($query);
		
		if($query_status>0){
				$query = "INSERT INTO inserted_files_chat (`filename`,`status`)Values('$filename','success')";
				$wpdb->query($query);
				unlink($filename);
			}
	}

	}






}
///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________COMPLETE CHAT_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________ACCEPT CHAT_______________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////




function accept($uid,$role){
	global $wpdb;
	$status = 'a';
	if($role == 'sub'){
	$query = "SELECT * FROM online_consultation WHERE uid = $uid ORDER BY id DESC LIMIT 10";
	$res = $wpdb->get_results($query);
	$filename_array = array();
	$return_array = array();
	$rcount = 0;
	$last_user;
	//echo $query;
	//print_r($res);
	//echo count($res);
	for($i=0;$i<count($res);$i++){
		$to = $res[$i]->tid;
		$from = $uid;

	if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json')){
		$filename_array[$i] = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json';
	}else if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json')){
		$filename_array[$i] = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json';
	}
	$query = "SELECT complete_status FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
	$complete_res = $wpdb->get_results($query);
	if(count($complete_res)>0){
		if($complete_res[0]->complete_status == 1){
			unset($filename_array[$i]);
		}
	}

}
	}else if($role == 'therapist'){
	$query = "SELECT * FROM online_consultation WHERE tid = $uid ORDER BY id DESC LIMIT 10";
	$res = $wpdb->get_results($query);
	$filename_array = array();
	$return_array = array();
	$rcount = 0;
	$count = 0;
	$last_user;
	$string ='';
	//echo $query;
	//print_r($res);
	//echo count($res);
	for($i=0;$i<count($res);$i++){
		$to = $res[$i]->uid;
		$from = $uid;
		$count++;
	if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json')){
		$filename_array[$i] = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$to.'_'.$from.'.json';
	}else if(file_exists('/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json')){
		$filename_array[$i] = '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/json_files/'.$from.'_'.$to.'.json';
	}
	$query = "SELECT complete_status FROM chat_block_users WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
	$complete_res = $wpdb->get_results($query);
	if(count($complete_res)>0){
		if($complete_res[0]->complete_status == 1){
			unset($filename_array[$i]);
		}
	}

}
	}

	$filename_array = array_unique($filename_array);
	//print_r($filename_array);

	foreach($filename_array as $key){
		
		$contents = file_get_contents($key);
		$contents = json_decode($contents, true);
		$from = $contents[0]['from_user_id'];
		$to = $contents[0]['to_user_id'];
		foreach($contents as $row){
			if($row['from_user_id'] == $from AND $status =='c'){
				$status = 'd';
				$last_user = $row['from_user_id'];
				break;
			}else if($row['from_user_id'] == $from){
				$status='b';
				$last_user = $row['from_user_id'];
			}else if($row['from_user_id'] == $to){
				$status='c';
				$last_user = $row['from_user_id'];
			}
			
		}
		$lname = get_user_meta($last_user)['first_name'][0];
		$return_array[$rcount]['lid'] = $last_user;
		$return_array[$rcount]['status'] = $status;
		$return_array[$rcount]['lname'] = $lname;

		$rcount++;


		/*
		if($from_count>0 AND $to_count>0){
			$query = "";
		}else{echo 'NO';}*/

	}
	echo json_encode($return_array);
	



}












///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________ACCEPT CHAT_______________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////



///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________MARK AS INCOMPLETE CHAT___________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function incomplete($to,$from){

	//echo $to.'--'.$from;
	global $wpdb;
	$current_time = date('Y-m-d H:i:s');
	if(strpos(get_user_meta($from)['wp_capabilities'][0], 'therapist')>0){$tid = $from;$uid=$to;}else{$tid=$to;$uid=$from;}
	$query = "SELECT * FROM online_consultation WHERE tid=$tid AND uid=$uid ORDER BY id DESC LIMIT 1";
	//echo $query;
	$res= $wpdb->get_results($query);
	$oc_id=$res[0]->id;
	//$query = "SELECT * FROM chat_block_users WHERE oc_id=$oc_id LIMIT 1";
	$query = "SELECT * FROM chat_block_users WHERE  from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
	$res=$wpdb->get_results($query);
	//echo $query;
	if(count($res)>0){
		$id=$res[0]->id;
		$query = "UPDATE chat_block_users SET incomplete_status=1,complete_status=1,oc_id=$oc_id WHERE from_user_id=$to and to_user_id=$from OR from_user_id=$from and to_user_id=$to";
		echo $wpdb->query($query);
		$query = "UPDATE online_consultation SET remaining_session=1,tid_accept='-2',busy_date='$current_time' WHERE id=$oc_id";
		$wpdb->query($query);
	}else{
		$query = "INSERT INTO chat_block_users (to_user_id, from_user_id,block_status,block_details,complete_status,incomplete_status,complete_time,chat_init,oc_id) VALUES ('$to','$from', 0, 'null',1,1,'null','null','$oc_id')";
		echo $wpdb->query($query);
		$query = "UPDATE online_consultation SET remaining_session=1,tid_accept='-2',busy_date='$current_time' WHERE id=$oc_id";
		$wpdb->query($query);

	}

	//$mob_msg="Your chat on Thriive has been marked incomplete by our therapist as you were offline.Please Contact thriive support for re-initiating the chat";
	//$mob_no=get_user_meta($uid)['mobile'][0];
	//sensSMS($mob_no, $mob_msg);*/

}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________MARK AS INCOMPLETE CHAT___________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________ACCEPT_CHAT_______________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////



function accept_chat($to,$from,$oc_id){
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
	global $wpdb;
	$current_user = wp_get_current_user();
	$session_id = $current_user->ID;
	$query = "SELECT * FROM online_consultation WHERE id=$oc_id";
	$res = $wpdb->get_results($query);

	if($res[0]->tid == $session_id){
		$current_time = date('Y-m-d H:i:s');
		$busy_time = strtotime(date('Y-m-d H:i:s'))+120;
		$busy_time = date('Y-m-d H:i:s', $busy_time);
		$query = "UPDATE online_consultation SET tid_accept=1,tid_accept_time='$current_time',waiting=0,busy_date='$busy_time',uid_accept=0 WHERE id=$oc_id";
		if($wpdb->query($query)==1){
			echo $res[0]->uemail.',therapist';
		}

	}else{
		$current_time = date('Y-m-d H:i:s');
		$query = "UPDATE online_consultation SET uid_accept=1,no_of_sessions=0,uid_accept_time='$current_time' WHERE id=$oc_id";
		if($wpdb->query($query)==1){
			$chatblock = $wpdb->get_results("SELECT * FROM chat_block_users WHERE (to_user_id = $to AND from_user_id = $from) OR (to_user_id = $from AND from_user_id = $to) ORDER BY id DESC");
			$cb_id = $chatblock[0]->id;
			if(count($chatblock) > 0){
				echo $res[0]->temail.',user';
				if($wpdb->query("UPDATE chat_block_users SET block_status = 0,complete_status=0,incomplete_status=0,oc_id = $oc_id WHERE id=$cb_id ")==1){
					
				}
			} else {
				$chat_block_data = array(
					'to_user_id' => $tid,
					'from_user_id' => $uid,
					'oc_id' => $oc_id,
					'block_status' => 0,
					'complete_status'=>0
				);
				if($wpdb->insert("chat_block_users",$chat_block_data)==1){
						echo $res[0]->temail.',user';	
				}
			}
			$chat_data = array(
				'to_user_id' => $tid,
				'from_user_id' => $uid,
				'chat_message' => 'hello',
				'oc_id' => $lastid
			);
			$wpdb->insert("chat_message_details",$chat_data);
		}
	}
	
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________ACCEPT_CHAT_______________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________GET_CURR_USER_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function get_curr_user(){
	global $wpdb;
	$current_user = wp_get_current_user();
	$session_id = $current_user->ID;	
	$curr_user_role = $current_user->roles;
	foreach($curr_user_role as $key){
		if($key == 'therapist'){
			$curr_user_role = 'therapist';
		}else{
			$curr_user_role = 'user';
		}
	}

	if($curr_user_role=='therapist'){
		$query = "SELECT * FROM online_consultation WHERE tid=$session_id AND action='chat' AND remaining_session=1 ORDER BY id DESC LIMIT 1";
		$res = $wpdb->get_results($query);
		if(count($res)>0){
		if($res[0]->uid_accept=='-2'){
			//late_therapist($res[0]->id,$res[0]->busy_date);
			echo 't5,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id.','.$res[0]->def_warn;
		}else if($res[0]->tid_accept==0){
			//late_therapist($res[0]->id,$res[0]->busy_date);
			echo 't0,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;			
		}else if($res[0]->tid_accept==1 AND $res[0]->uid_accept==0){
			//late_therapist($res[0]->id,$res[0]->busy_date);
			echo 't1,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id.','.$res[0]->def_warn;
			send_accept_sms($res[0]->id,$res[0]->uid);
		}else if($res[0]->tid_accept==1 AND $res[0]->uid_accept==1 AND $res[0]->remaining_session==1){
			echo 't2,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id.','.$res[0]->uemail;
		}else if($res[0]->tid_accept==2){
			echo 't3,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id.','.$res[0]->def_warn.','.$query;
		}else if($res[0]->tid_accept=='-1'){
			echo 't4,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;
		}else if($res[0]->tid_accept=='-2'){
			echo 't5,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;
		}
	}else{
			echo 't6,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id.','.$res[0]->def_warn;
		}

	}else{
		$query = "SELECT * FROM online_consultation WHERE uid=$session_id AND action='chat' AND remaining_session=1 ORDER BY id DESC LIMIT 1";
		$res = $wpdb->get_results($query);
		if(($res[0]->tid_accept==0 OR $res[0]->tid_accept=='-1') && count($res)>0){
			echo 'u0,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;
			if(strlen($res[0]->call_uuid)<5){/*initiate_call('therapist',$res[0]->id,$res[0]->tid,$res[0]->uid);*/}
		}else if($res[0]->uid_accept=='-2' AND $res[0]->remaining_session==1){
			echo 'u5,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;
		}else if($res[0]->tid_accept==1 AND $res[0]->uid_accept==0){
			echo 'u1,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id.','.$res[0]->temail;
		}else if($res[0]->tid_accept==1 AND $res[0]->uid_accept==1 AND $res[0]->remaining_session==1){
			send_accept_sms($res[0]->id,$res[0]->tid);
			echo 'u2,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;
		}else if($res[0]->tid_accept=='-2' AND $res[0]->remaining_session==1){
			echo 'u4,'.$res[0]->tid.','.$res[0]->uid.','.$res[0]->tname.','.$res[0]->uname.','.$res[0]->id;
		}else{
			echo 'u3,';
		}

	}
echo ',end';
}
	



///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________GET_CURR_USER_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function late_therapist($ocid,$busy_time){
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$busy_time = strtotime($busy_time);
		$busy_time = $busy_time + 300;
		if($current_time>$busy_time){
			$query = "UPDATE online_consultation SET tid_accept=2 WHERE id=$ocid";
			$wpdb->query($query);
		}else{
		
		}
}




///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________SEND_SMS_ACCEPT_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////

function send_accept_sms($ocid,$uid){
	global $wpdb;
		$mob_no = get_user_meta($uid)['mobile'][0];
		$msg = 'Hi from Thriive. The Therapist has accepted your Chat request. Please click on the link to start chat or go to your Dashboard to Start Chat - https://bit.ly/3v6tHCD';
		$query = "SELECT email_sms FROM online_consultation WHERE id=$ocid";
		$result = $wpdb->get_results($query);
		if($result[0]->email_sms==0){
			sendSMS($mob_no,$msg,'1007023623328159255');
		$query = "UPDATE online_consultation SET email_sms=1 WHERE id=$ocid";
		$wpdb->query($query);
		}else if($result[0]->email_sms==1){
			//sendSMS($mob_no,$msg,'1007023623328159255');
		//$query = "UPDATE online_consultation SET email_sms=2 WHERE id=$ocid";
		//$wpdb->query($query);
		}



}








///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________SEND_SMS_ACCEPT_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////













///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________BROWSE_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function browse_user($ocid,$action){
	global $wpdb;
	if($action==1){
	$query = "UPDATE online_consultation SET tid_accept=2,uid_accept='-3',remaining_session=1 WHERE id=$ocid";
	echo $wpdb->query($query);	
	}else if($action==2){
	$query = "UPDATE online_consultation SET tid_accept=2,uid_accept='-3',remaining_session=1 WHERE id=$ocid";
	echo $wpdb->query($query);
	}else{
	//$query = "UPDATE chat_block_users SET complete_status=1 WHERE oc_id=$ocid";
	$query = "UPDATE online_consultation SET tid_accept=2,remaining_session=1 WHERE id=$ocid";
	if($wpdb->query($query)==1){
		echo 1;
	}else{echo 0;}

	if(!$_SESSION){
		session_start();
		$_SESSION['curr_time'] = '0:59';
		//print_r($_SESSION.'test');
	}else{
		session_start();
		$_SESSION['curr_time'] = '0:59';

	}
}
}









///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________BROWSE_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________curr_time_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function curr_time($curr_time,$ocid){
	global $wpdb;
	echo $curr_time;
	if($curr_time == 'reset'){
		if(!$_SESSION){
			session_start();
			$_SESSION['curr_time'] = '4:59';
			print_r($_SESSION.'test');
		}		
	}else if(!$_SESSION){
		session_start();
		$_SESSION['curr_time'] = $curr_time;
		print_r($_SESSION.'test');
	}else{
		$_SESSION['curr_time'] = $curr_time;
		$current_time = date('Y-m-d H:i:s');
		$query = "UPDATE online_consultation SET rem_time='$current_time' WHERE id=$ocid";
		echo $query;
		$wpdb->query($query);

	}
}



///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________curr_time_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________REJECT_CHAT_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////



function reject_chat($to,$from,$ocid){
	global $wpdb;
	$query = "UPDATE online_consultation SET tid_accept='-1' WHERE id=$ocid";
	if($wpdb->query($query)==1){
		echo 'tr';
	}else{
		echo 'error';
	}
}




///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________REJECT_CHAT_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________REVIVE_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function revive($ocid){
	global $wpdb;
	$query = "UPDATE online_consultation SET tid_accept=0,remaining_session=1 WHERE id=$ocid";
	if($wpdb->query($query)==1){
		echo 'done';
			$query = "SELECT * FROM online_consultation WHERE id=$ocid";
			$result = $wpdb->get_results($query);
			$mob = get_user_meta($result[0]->tid)['mobile'][0];
			$msg = 'Your client '.$result[0]->uname.' has taken '.$result[0]->pkgdescription.' and is waiting for a response from you. Please login to your account and start online chat with the client on thriive.';
			sendSMS($mob,$msg,'1007102003722838536');



	}else{
		echo 'not';
	}


}







///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________REVIVE_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________BUSY_THERAPIST_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function busy_ther($ocid){
	global $wpdb;
	$wait_time = date('Y-m-d H:i:s',strtotime('+1 minutes'));
	$query = "UPDATE online_consultation SET busy_date='$wait_time' WHERE id=$ocid";
	$wpdb->query($query);
}







///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________BUSY_THERAPIST_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////







///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________DEF_WARNING_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function def_warn($ocid){
	global $wpdb;
	$query = "UPDATE online_consultation SET def_warn=1 WHERE id=$ocid";
	$wpdb->query($query);
}







///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________DEF_WARNING_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////






///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________INITIATE CALL_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////


function initiate_call($call_to,$ocid,$tid,$uid){
	global $wpdb;
	if($call_to == 'therapist'){
	$mobile = $mob_no = get_user_meta($tid)['mobile'][0];
	$ivr_id = '1000057655';
	}

	if(1==2){
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://kpi.knowlarity.com/Basic/v1/account/call/campaign',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
	"ivr_id":"1000057655",
	"additional_number":"+917999886050",
	"timezone":"Asia/Kolkata",
	"priority":8,
	"start_time":"2021-04-26 12:30",
	"call_scheduling_start_time":"09:00",
	"call_scheduling_stop_time":"23:00",
	"is_transactional":1,
	"k_number":"+918035387035",
	"call_scheduling": "[1, 1, 1, 1, 1, 1, 1]",
	"end_time":"2021-04-26 13:55"
	}',
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json',
	    'x-api-key: OuiqbzxM0B9CvqFFhXf2W8vA5DA78PEO6trfIjsI',
	    'Authorization: 28637081-d7a7-4bf5-96aa-c79fbf2aba42'
	  ),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	echo $response;
	}

	$response = '{"order_id": 33382510, "result": "placed order successfully", "status_code": 1}';
	$response2 = '{"result": "Campaign cannot be created! You have entered a parameter - start_time less than the current time. Kindly enter it correctly and try again.", "status_code": 0}';
	$response = json_decode($response, true);

	if($response['status_code']==1){
		$order_id = $response['order_id'];
		$query = "UPDATE online_consultation SET call_id=$order_id WHERE id=$ocid AND action='chat'";
		$wpdb->query($query);
	}




}

///////////////////////////////////////////////////////////////////////////////////////////////////////
//_________________________________________INITIATE CALL_____________________________________________//
///////////////////////////////////////////////////////////////////////////////////////////////////////
