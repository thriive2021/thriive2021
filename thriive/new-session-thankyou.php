<?php /* Template Name: New Oc Session Thank you page */ 
session_start();
global $wpdb;
$current_user = wp_get_current_user();

include_once get_stylesheet_directory().'/new-header.php';
$slug = $_GET['q'];
$action = $_GET['a'];
$therapy = $_GET['t'];

/* $slug = $_SESSION['paymentoption']['slug'];
$action = $_SESSION['paymentoption']['actionc'];
$therapy = $_SESSION['paymentoption']['therapy'];
$oc_id = $_GET['oc_id']; */

$mypost = get_page_by_path($_GET['q'], '', 'therapist'); 


$post = get_post($mypost->ID);

$tuserdata = get_userdata($post->post_author);

?>
<style>
	.imgcontainer{
	    text-align: center;
	}
	.avatar{
	    border-radius: 50%;
	}
	.footer_text{
	    margin-top: 25px;
	}
	.tlistpage{
		background: #fff;
	}
	@media only screen and (max-width: 600px) { 
		.octhank{padding-left:0px;padding-right:0px}
		.contg{font-weight:600;}
	}
	
	h5{color: #666666d9 !important;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php
/* if($oc_id != ''){
	$sessionrow = $wpdb->get_row("SELECT * FROM online_consultation WHERE   id = '".$oc_id ."'");
} else { */
	$sessionrow = $wpdb->get_row("SELECT * FROM online_consultation WHERE  uid = '".$current_user->ID ."'   AND remaining_session > 0 AND therapy_name != 'Free Covid Session'  LIMIT 1");
//}

//sendEmail($sessionrow->uemail, 'tsubject', 'tmsg');exit();
//if(isset($_SESSION['no_of_sessions'])){ 
if($sessionrow >0 && isset($_SESSION['no_of_sessions'])){
		$wait_time = date('Y-m-d H:i:s',strtotime('+'. $sessionrow->session_duration .'minutes'));
		$_SESSION["wait_time"] =  $wait_time; 
		$usrdet = get_user_meta($mypost->post_author,0);
		$date = date("Y-m-d H:i:s");
		$tid =  $mypost->post_author;
		$uid =  $current_user->ID;
		$data = array(
			'uid' => $current_user->ID,
			'uname'	=> $sessionrow->uname,
			'uemail' => $sessionrow->uemail,
			'tid' => $mypost->post_author,
			
			'tname'	=> $usrdet['first_name'][0] ." ".$usrdet['last_name'][0],
			'temail' => $tuserdata->user_email,
			'action' => $action,
			'package' => $sessionrow->package,
			'therapy_name' => $therapy,
			'waiting' => 1,
			'session_duration' => $sessionrow->session_duration,
			'amount' => $sessionrow->amount,
			'payment_status' => $sessionrow->payment_status,
			'payment_txnid' => $sessionrow->payment_txnid,
			'coupon_code' => $sessionrow->coupon_code,
			'busy_date' => $wait_time,
			'created_date' => $date,
			'modified_date' => $date
		);
		$uname = $data['uname'];
		$sessionarr = explode(",",$freemins);
		if($action == 'book_latercall'){
			$data['action'] =  "call";
		}else if($action == 'book_laterchat' || $action == 'chat'){
			$data['action'] =  "chat";	
		}	
		$data['no_of_sessions'] = 1;
		$data['pkgdescription'] = $sessionrow->pkgdescription;
		//if($action == 'chat'){ 
			$data['remaining_session'] = 1;
		/* } else {
			$data['remaining_session'] = 0;
		}  */
		
	//	$wpdb->insert("online_consultation",$data);
		$wpdb->query("INSERT INTO online_consultation (uid,uname,uemail,tid,tname,temail,action,package,therapy_name,waiting,session_duration,amount,payment_status,payment_txnid,no_of_sessions,pkgdescription,remaining_session,coupon_code,busy_date,created_date,modified_date) VALUES('".$data['uid']."','".$data['uname']."','".$data['uemail']."','".$data['tid']."','".$data['tname']."','".$data['temail']."','".$data['action']."','".$data['package']."','".$data['therapy_name']."','".$data['waiting']."','".$data['session_duration']."','".$data['amount']."','".$data['payment_status']."','".$data['payment_txnid']."','".$data['no_of_sessions']."','".$data['pkgdescription']."','".$data['remaining_session']."','".$data['coupon_code']."','".$data['busy_date']."','".$data['created_date']."','".$data['modified_date']."')");
		$lastid = $wpdb->insert_id; 
		$data = array('oc_id'=>$lastid,'uid' => $current_user->ID,'tid' => $mypost->post_author,'therapy_name' => $therapy  ,'action' => $data['action'],'created_date' => $date);
		$wpdb->query("INSERT INTO online_session_details (oc_id,uid,tid,therapy_name,action,created_date) VALUES ('".$lastid."','".$current_user->ID ."','". $mypost->post_author ."','".$therapy."','".$data['action']."','".$date."')");
		//$wpdb->insert("online_session_details",$data);  
		//$result = oc_assign_masked_number_to_user($mypost->user_email,$sessionrow->uemail);
		$wpdb->query("UPDATE online_consultation SET remaining_session = 0,modified_date = '".$date."' WHERE id='". $sessionrow->id ."'");
		
		
		$tmobile = get_user_meta($mypost->post_author,'mobile',true);
		$umobile = get_user_meta($sessionrow->uid,'mobile',true);

	//	print_r($usrdet);exit();
		/* while ( have_rows('package_details','option') ) : the_row();
			foreach(get_sub_field('call_packages') as $call){ 
				if($call['title'] == $sessionrow->package && $call['no_of_sessions'] == $sessionrow->no_of_sessions .' Session'){
					$tdescmsg = ' for '.$call['description'];
					
				}
			}
		endwhile;  */
		if(strpos($sessionrow->uname, "@") !== false){
		    $uname = explode('@', $sessionrow->uname)[0];
		} else{
		    $uname = $sessionrow->uname;
		}
		if($action == 'call'){
			//$link = site_url()."/accept-reject?ocid=".$lastid; 
			//$cgenerated_url = generateBitlyURL($link)['data']['url'];
			$tmsg = "Your client ".$uname ." has taken ".$sessionrow->pkgdescription ." and is waiting for a session with you. Please click on link given to accept the session with client within the next 2 minutes on thriive.in";
			
		
		//	$tmsg .= ".Love and Light,Team Thriive";
			//$tempid = '1007631596005333862';
			$password="password";
			$encrypted_ocid = openssl_encrypt($lastid,"AES-128-ECB",$password);
			$encrypted_uid = openssl_encrypt($uid,"AES-128-ECB",$password);
			//$tmsg .= site_url()."/accept-reject?ocid=".$lastid; 
			//sendSMS('8850630321',$tmsg,$tempid);
			$tempid = '1007507627626666229';
			
	//	if($sessionrow->tid_accept == 0){
			$uidmobile = get_user_meta($uid,'mobile',true);
			$uidmobile = preg_replace('/(?<=\d)\s+(?=\d)/', '', $uidmobile);
			$tidmobile = get_user_meta($tid,'mobile',true);
			$tidmobile = preg_replace('/(?<=\d)\s+(?=\d)/', '', $tidmobile);
			sendSMS($tidmobile,$tmsg,$tempid);
			//sendSMS('8850630321',$tmsg,$tempid);
			$curl = curl_init();
			$data = array("k_number"=> "+917411782154","agent_number"=> '+91'.$tidmobile,"customer_number"=>'+91'.$uidmobile,"caller_id"=> "+918048166487","additional_params"=>array("object_id"=> "1","user_id"=> "2", "call_log_id" => 1,"timeout"=> ($sessionrow->session_duration)));
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
			//print_r($callresp);
		
	
			if(!empty($callresp)){
				if($callresp['success']['status'] == 'success'){
					$connected = 1; 
					$uid_accept = 1;
					$tid_accept = 1;
					$call_id = $callresp['success']['call_id'];
					$remaining_session =0;
					//$busy_date = $data['busy_date'];
				}
			} 
			$wpdb->query("UPDATE online_consultation SET tid_accept='".$tid_accept."',uid_accept='".$uid_accept."',connected='".$connected."',modified_date = '".date('Y-m-d H:i:s')."',waiting=1,remaining_session=0,email_sms=0,call_id = '".$call_id."' WHERE id='".$lastid."'");	
		//}
		    if($_GET['a'] == 'call'){  
					
		       /* $tmsg =	"You have  1 session from ".$sessionrow->package .".You will get a call from ". $post->post_title ." within 24 hrs so please keep your phone line free."; */
				
			}			
		//	$tmsg1 = "Love and Light,Team Thriive";
			//$tmsg .= site_url()."/accept-reject?tid=".$encrypted_tid.'&uid='.$encrypted_uid.'&ocid='.$lastid.'&accept=1';
			//sendSMS($umobile,$tmsg.$tmsg1);
			$tmsg .= "<br/><br/>Love and Light,<br/>Team Thriive";
			$tsubject = "New Session from ".$uname ." - ".$lastid;
			sendEmail($sessionrow->uemail, $tsubject,$tmsg);
			
			$tmsg ="Hello ".$usrdet['first_name'][0] ." ".$usrdet['last_name'][0] .",<br/>Your client ".$uname ." has taken ".$sessionrow->pkgdescription ." and is waiting for a session with you .";
			
		//	$tmsg .= site_url()."/accept-reject?ocid=".$lastid; 
						
			$tmsg .= "<br/><br/> Please Note:<br/>There is no auto disconnect for calls. It is your responsibility to end the session within the stipulated time.<br/><br/>Thriive will only make payment for the number of minutes purchased by the client.<br/><br/>You can encourage your client to extend the session, a link for which will be shared with the client after this call ends.";
			$tmsg .= "<br/><br/>Love and Light,<br/>Team Thriive";
			
		}
		
		if($action == 'chat'){
			//$wpdb->query("UPDATE online_consultation SET remaining_session = (remaining_session-1),modified_date = '".$date."' WHERE id='". $sessionrow->id ."'");
			$umsg = "Your client ". $uname ." has taken ".$sessionrow->pkgdescription ." and is waiting for a response from you. Please login to your account and start online chat with the client . ";
			
			/* if($_GET['t'] == "nutritionist"){
				$tmsg .= "Goals :".$goals[$last_user['goals']];
				if($last_user['height'] != ""){
					$tmsg .= ". Their BMI index is  ".round($last_user['bmi'],2).". Their Height is  ".$last_user['height'].". Their Weight is  ".$last_user['weight'].". DOB :".$last_user['dob'];
				}
				if($last_user['medcon'] != ""){
					$tmsg .= "<br/> Medical Condition : ";
					$medconarr =  explode(",",$last_user['medcon']);
					foreach($medconarr as $ma){
						$tmsg .= $medcon[$ma];
						
					}
					
				}	
				
				$tmsg .= "Love and Light,Team Thriive";
			} */
			




			/*$chat_data = array(
				'to_user_id' => $mypost->post_author,
				'from_user_id' => $current_user->ID,
				'chat_message' => 'hello',
				'oc_id' => $lastid
			);
			$wpdb->insert("chat_message_details",$chat_data);
			$chatblock = $wpdb->get_row("SELECT * FROM chat_block_users WHERE to_user_id = '".$mypost->post_author."' AND from_user_id = '".$current_user->ID ."'");
			if($chatblock){
				$wpdb->query("UPDATE chat_block_users SET block_status = 0,complete_status=1,oc_id = '".$lastid."' WHERE to_user_id = '".$mypost->post_author ."' AND from_user_id = '".$current_user->ID ."' ");
				$wpdb->query("UPDATE chat_block_users SET block_status = 0,complete_status=1,incomplete_status=0,oc_id = '".$lastid."' WHERE to_user_id = '".$current_user->ID ."' AND from_user_id = '".$mypost->post_author ."' ");
			} else {
				$chat_block_data = array(
					'to_user_id' => $mypost->post_author,
					'from_user_id' => $current_user->ID,
					'block_status' => '0',
					//'complete_status'=>'0',
					'oc_id' => $lastid
				);
				$wpdb->insert("chat_block_users",$chat_block_data);
			}*/






			$call_chat_history = array(
				'tid' => $mypost->post_author,
				'uid' => $current_user->ID,
				'oc_id' => $lastid
				
			);
			$wpdb->insert("call_chat_history",$call_chat_history);
			
			//$wpdb->query("UPDATE chat_block_users SET block_status = 0,complete_status=1,incomplete_status=1 WHERE oc_id = '".$sessionrow->id ."'  ");
			$tempid = '1007645385754747483';
			$password="password";
			$encrypted_tid = openssl_encrypt($tid,"AES-128-ECB",$password);
			$encrypted_uid = openssl_encrypt($uid,"AES-128-ECB",$password);
			//$tmsg .= site_url()."/accept-reject?tid=".$encrypted_tid.'&uid='.$encrypted_uid.'&ocid='.$lastid.'&accept=1'; 
			//sendSMS($umobile,$tmsg,$tempid);
			
		   // $tmsg = "Hello,You have  1 session from ".$sessionrow->package .". To connect with ".$usrdet['first_name'][0] ." ".$usrdet['last_name'][0] ." click on start chat to connect with an expert.";
		//	$tmsg1 = "Love and Light,Team Thriive,*Terms & conditions apply";
			$tmsg = "Your client ".$uname." has taken ".$sessionrow->package." and is waiting for a response from you. Please login to your account and start online chat with the client on thriive.";
			$password="password";
			$encrypted_tid = openssl_encrypt($tid,"AES-128-ECB",$password);
			$encrypted_uid = openssl_encrypt($uid,"AES-128-ECB",$password);
			//$tmsg .= site_url()."/accept-reject?tid=".$encrypted_tid.'&uid='.$encrypted_uid.'&ocid='.$lastid.'&accept=1'; 
			$tempid = '1007102003722838536';
			sendSMS($tmobile,$tmsg,$tempid);
			sendSMS('7999886050',$tmsg,$tempid);
			
			$tmsg .= "<br/><br/>Love and Light,<br/>Team Thriive<br/><br/>*Terms & conditions apply";
			$tsubject = "New Session from ".$uname ." - ".$lastid;
			sendEmail($sessionrow->temail, $tsubject,$tmsg);
			
		     $tmsg = "Hello ".$usrdet['first_name'][0] ." ".$usrdet['last_name'][0].",<br/>Your client ".$uname ." is waiting for a session with you.<br/><br/>";
			
			$tmsg .="Chat needs to begin within 2 minutes<br/>Their payment is completed for ".$sessionrow->pkgdescription ."<br/><br/>Connect NOW with the client on your dashboard<br/><br/> Please Note:<br/>There is no auto complete for chat. It is your responsibility to end the session within the stipulated time.<br/><br/>Thriive will only make payment for the number of minutes purchased by the client.<br/><br/>You can encourage your client to extend the session, a link for which will be shared with the client after this call ends.";
			$mobile = get_user_meta($mypost->ID,'mobile',true);
		    //sendEmailWithInvoice($temail, $tsubject, $tmsg, $uid, 'therapist', 'oc_invoice.php');
			/* if($_GET['t'] == "nutritionist"){
				$tmsg .= "<br/><br/>Goals :".$goals[$last_user['goals']]."<br/>";
				if($last_user['height'] != ""){
					$tmsg .= "Their BMI index is  ".round($last_user['bmi'],2)."<br/><br/>Their Height is  ".$last_user['height']."<br/><br/> Their Weight is  ".$last_user['weight']."<br/><br/>DOB :".$last_user['dob'];
				}
				if($last_user['medcon'] != ""){
					$tmsg .= " Medical Condition : ";
					$medconarr =  explode(",",$last_user['medcon']);
					foreach($medconarr as $ma){
						$tmsg .= $medcon[$ma]."<br/>";
						
					}
					
				}	
			} */
			
			$tmsg .= "<br/><br/>Love and Light,<br/>Team Thriive";
		}	
		$tempid = "1007921355648742887";
		$alertmsg = $uname ." connected via ".$action." to ".$usrdet['first_name'][0] ." ".$usrdet['last_name'][0]." for ". $sessionrow->package ." and for therapy ".$therapy." on thriive";
		// sendEmail('manishj@thriive.in','Alert! User made the payment and wants to connect with therapist',$alertmsg);
		//sendEmail('accountmanager1@thriive.in','Alert! User made the payment and wants to connect with therapist',$alertmsg);
		//sendSMS('7208811389',$alertmsg,$tempid);
		//sendSMS('9820686971',$alertmsg,$tempid);
		sendSMS('9930502459',$alertmsg,$tempid);
		//sendSMS('8850630321',$alertmsg,$tempid);
		sendSMS('8369415594',$alertmsg,$tempid);
		sendEmail($tuserdata->user_email, $tsubject, $tmsg);
		//sendEmail('prathamesh@thriive.in', $tsubject,$tmsg);
		
		$term = get_term_by('slug', $therapy, 'therapy');
		
		 
		?>
		<div class="container octhank">
		            <div class="imgcontainer mt2">
		                <img src="wp-content/uploads/2020/07/chech.png" alt="" class="avatar" width="60" height="60">
		            </div>
		            <div class="text-center mt-2" style="margin:bottom:10px;">
		                <h5 class="text-center">We hope you have a wonderful session.</h5>
		            </div>
		            <?php if($_GET['a'] == 'call'){ 
		            	if($_GET['t'] == 'all-page' || $_GET['t'] == 'cosmic-astrology' || $_GET['t'] == 'numerology' || $_GET['t'] == 'tarot-card-reading' || $_GET['t'] == 'angel-reading' || $_GET['t'] == 'vastu-shastra'){?>
				            <div class="imgcontainer mt2">
				                <img src="wp-content/uploads/2020/07/Screenshot_117.png" alt="" class="avatar" width="60" height="60">
				            </div>
				            <!--<div class="text-center mt-2">
				                <h5 class="text-center">Your First <?php echo $sessionrow->package; ?>  Sessions are free</h5>
				            </div>-->
				        <?php } ?>
			           <!-- <div class="text-center mt-2">
			                <h5 class="text-center">Please keep your phone line free</h5>
			            </div>-->
			            <div class="text-center ">
			                 <!--<h5 class="text-center" style="margin:5px 5px;padding:5px 5px;">You will receive a call from <?php echo $post->post_title; ?> soon</h5>-->
			                 <h5 class="text-center" style="margin:5px 5px;padding:5px 5px;">Please wait for  <?php echo $post->post_title; ?> to Accept the Session. </h5>
			            </div>
		        	<?php } if($_GET['a'] == 'chat') { ?>
						<div class="imgcontainer">
			                <img src="wp-content/uploads/2020/07/chat_2.png" alt="" class="avatar" width="60" height="60">
			            </div>
		        		<div class="text-center footer_text">
			                 <h5 class="text-center" style="padding-left: 10px; padding-right: 10px;color: #666666d9;">Please wait for <?php echo $post->post_title; ?> to Accept the Chat. You will also Receive an SMS. Thank You.</h5>
			            </div>
			        <?php } ?>
			        <?php if($_GET['a'] == 'chat') {?>
			            <!--<div class="col-sm-3 col-xs-12"></div>
		            	<div class="col-sm-6 col-xs-12 p-sm-0">
		            		<section class="topreadingList">
								<?php set_query_var('uid',$userId);
								set_query_var('therapy',array($_GET['t'])); 
								get_template_part( 'template-parts/therapist-content1'); ?>
							</section>
						</div> 
						<div class="col-sm-3 col-xs-12"></div>-->
					<?php } ?>
		            <div style="clear:both"></div>
			        <div class="" style="border-top: 1px dashed;"></div>
			        <div class="text-center mt-2" style=" padding: 5px;">
		                <!--<h5 class="text-center contg">Have more questions to ask your previous expert?</h5>-->
		                <!--<h5 class="text-center"><b><a href="<?php echo get_site_url()."/payment-details?q=".$_GET['q']."&a=".$_GET['a']."&t=".$_GET['t']; ?>">Click here</a> to continue consultation</b></h5>-->
						 <?php 
					   if($_GET['t'] == 'tarot-card-reading' || $_GET['t'] == 'angel-reading' || $_GET['t'] == 'vastu-shastra' || $_GET['t'] == 'cosmic-astrology' || $_GET['t'] == 'numerology'){
						?>
						 <!--<h5 class="text-center"><b><a href="<?php echo get_site_url()."/new-payment-apage?q=".$_GET['q']."&a=".$_GET['a']."&t=".$_GET['t']; ?>">Click here</a> to continue consultation</b></h5>-->
						<?php 
					    } else {
						?>
						<!--<h5 class="text-center"><b><a href="<?php echo get_site_url()."/payment-details?q=".$_GET['q']."&a=".$_GET['a']."&t=".$_GET['t']; ?>">Click here</a> to continue consultation</b></h5>-->
						<?php 	
						}
						?>
						<!--<h5 class="text-center"><b><a href="<?php echo get_site_url()."/new-payment-apage?q=".$_GET['q']."&a=".$_GET['a']."&t=".$_GET['t']; ?>">Click here</a> to continue consultation</b></h5>-->
						<h5 class="text-center" >Incase of any issue you can reach us on <br><a href="">support@thriive.in</a></h5>
		            </div>
					<div class="" style="border-top: 1px dashed;"></div>
		            <div class="text-center footer_text">
		                 
		            </div><br>
		        </div>	
		<!--<div class="container octhank">
		            <div class="imgcontainer mt2">
		                <img src="wp-content/uploads/2020/07/chech.png" alt="" class="avatar" width="60" height="60">
		            </div>
		            <div class="text-center mt-2" style="margin:bottom:10px; padding:0px 15px;">
		                <h5 class="text-center">Thank you ,your session with <?php echo $post->post_title;?> for <?php echo $term->name.' via '.$action;?> is booked.Remaining Session is <?php echo ($remaindetails->remaining_session) - 1;?></h5>
		            </div>
		            <?php 
					if($_GET['a'] == 'call'){ ?>
		            	<div class="imgcontainer mt2">
			                <img src="wp-content/uploads/2020/07/Screenshot_117.png" alt="" class="avatar" width="60" height="60">
			            </div>
			            
			            <div class="text-center" style="padding:0px 15px;">
			                 <h5 class="text-center" style="margin:5px 5px;padding:5px 5px;">You will receive a call from <?php echo $post->post_title; ?> soon</h5>
			            </div>
		        	<?php 
					} ?> 
		            <div style="clear:both"></div>
			        <div class="" style="border-top: 1px dashed;"></div>
			        <div class="text-center footer_text">
		                <h5 class="text-center" >Incase of any issue you can reach us on <br><a href="">support@thriive.in</a></h5>
		            </div>
					<div class="" style="border-top: 1px dashed;"></div>
		        </div>-->
	<?php  
	//} else { ?>
		<!--<h5 class="text-center">Sorry  no more  session with <?php echo $post->post_title;?> for <?php echo $term->name.' via '.$action;?> is remaining.</h5>-->
	<?php
	//}
	unset($_SESSION['no_of_sessions']);
	
} else {
	wp_redirect('login'); exit;
?>

	<h5 class="text-center">Sorry  your session has expired. </h5>
<?php	
}	

include_once get_stylesheet_directory().'/new-footer.php'; ?>
