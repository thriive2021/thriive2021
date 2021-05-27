<?php $current_user = wp_get_current_user();
$seeker_id = $current_user->ID;
$seeker_email = $current_user->user_email;
$seeker_name = $current_user->display_name;
$post = get_query_var('post'); 

$onlinedetails = get_query_var('onlinedetails'); 


$remaining_session = $onlinedetails->remaining_session;
$therapist_details = get_user_by('ID',$onlinedetails->tid);
$therapist_id = $therapist_details->ID; 
$therapist_email = $therapist_details->data->user_email;
$role1 = '';
if(!empty($current_user->roles)){ 
  if(count($current_user->roles) > 1)
    $role1 =  $current_user->roles[1];
  else
    $role1 =  $current_user->roles[0];
}


$url = get_the_post_thumbnail_url( get_the_id(), 'post-thumbnail' );  
if(!is_mobile()){
  $event = 'desktop'; 
} else {
  $event = 'mobile';
}




?>

<table class="table table-bordered table-striped ">

	
	<tr>
		<td>Therapist name</td>
		<td><?php echo $post->post_title;?></td>
	</tr>
	<tr>
		<td>Package name</td>
		<td><?php echo $onlinedetails->pkgdescription;?></td>
	</tr>
	<?php 
	$therapy_terms = get_term_by('slug',$onlinedetails->therapy_name, 'therapy');
	?>
	<tr>
		<td>Therapy Name</td>
		<td><?php echo $therapy_terms->name;?></td>
	</tr>
	<?php
	 
	$query = "SELECT d.created_date,d.action FROM online_consultation o,online_session_details d WHERE d.uid = '". $onlinedetails->uid ."' AND d.tid = '". $onlinedetails->tid ."' AND d.therapy_name = '". $onlinedetails->therapy_name ."' AND o.id = d.oc_id ORDER BY sd_id DESC ";
	$result = $wpdb->get_results($query);
	foreach($result as $rs){
		
	?>
	<tr>
		<td>Session Date | Mode</td>
		<td><?php echo date("dS M Y H:i a",strtotime($rs->created_date)).' | '.$rs->action;?></td>
	</tr>
	<?php 	
		
	}
	?>
	
	
	<tr>
		<td>Status</td>
		<td><?php echo 'Completed';?></td>
	</tr>
</table>

