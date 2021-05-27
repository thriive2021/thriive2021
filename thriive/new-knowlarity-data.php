<?php /* Template Name: Knowlarity-data */
if(wp_get_current_user()->roles[0] == 'administrator'){}else{die('forbidden');}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<style type="text/css">
	.sub_form{
		background-color: #55B8FF;
		padding:10px;
		border-radius: 10px;
		width:max-content;
		margin:0 auto;
	}
	.sub_form input{
		font-size:18px;
		margin: 10px;
	}
		.sub_form button{
		font-size:18px;
		margin-left: 31%;

	}
	.err_red{
		color:red;
		text-align: center;
		margin:5px !important;
		font-weight: 400;
		font-size: 18px;
	}
	.knowlarity_table{
		margin:0 auto;
	}
	.knowlarity_table td{
		width: 30%;
    font-size: 20px;
	}
</style>


<h1 style="text-align: center">Enter Details For Knolarity Call</h1>
<table class="knowlarity_table">
	<tr>
		<td id="therapist" style="text-align: center">
			<p class="title_head">Therapist</p>
			<p class="title_name">---</p>
			<p class="title_mob">---</p>
		</td>
		<td>
			<form action="/wp-content/themes/thriive/horoscope_new/knowlarity/api_data.php" method="POST" class="sub_form">
				<!--<label for="agent">Enter Agent Number</label><br>-->
				<input type="number" maxlength="10" name="agent" placeholder="Enter Therapist Number" required onkeyup="validate();"><br>
				<!--<label for="customer">Enter Agent Number</label><br>-->
				<input type="number" maxlength="10" name="customer" placeholder="Enter User Number" required onkeyup="validate();"><br>
				<!--<label for="timer">Enter Time</label><br>-->
				<input type="number" maxlength="10" name="timer" placeholder="Enter Time" required onkeyup="validate();"><br>
				<input type="text"  name="payment_id" placeholder="Payment ID" ><br>
				<p class="err_red">All Fields Are Mandatory</p>
				<button disabled="true">SUBMIT</button>
			</form>
		</td>
		<td id="user" style="text-align: center">
			<p class="title_head">USER</p>
			<p class="title_name">---</p>
			<p class="title_mob">---</p>
		</td>	
	</tr>
</table>

<div style="text-align:center">
	<?php if($_GET){
		if($_GET['stat']=='success'){
			echo '<h1 style="color:green">Call Successfully Placed</h1>';
		}else{
			echo '<h1 style="color:red">Failed To Place the call</h1>';
		}
	}?>

</div>

<script>
	var val = 0;
	function validate(){
var inputs = document.querySelectorAll('input');
if(inputs[0].value.length != 10){
document.querySelector('.err_red').style.color = "red";
document.querySelector('.err_red').innerText = "Agent number must be 10 digits";
document.querySelector('button').disabled = true;
inputs[0].style.border = "2px solid red";
inputs[0].style.outline = "2px solid red";
val=0
}else{
inputs[0].style.border = "2px solid green";
inputs[0].style.outline = "2px solid green";
var pass = 1;
if(val!=1){ 
fetch_data('therapist');val=1}
}

if(pass == 1){
if(inputs[1].value.length != 10){
document.querySelector('.err_red').style.color = "red";
document.querySelector('.err_red').innerText = "Customer number must be 10 digits";
document.querySelector('button').disabled = true;
inputs[1].style.border = "2px solid red";
inputs[1].style.outline = "2px solid red";
val=1
}else{
inputs[1].style.border = "2px solid green";
inputs[1].style.outline = "2px solid green";
var pass = 2;
if(val!=2){ 
fetch_data('user');val=2}

}
}
if(pass == 2){
if(inputs[2].value < 1){
document.querySelector('.err_red').style.color = "red";
document.querySelector('.err_red').innerText = "INVALID TIMER COUNT";
document.querySelector('button').disabled = true;
inputs[2].style.border = "2px solid red";
inputs[2].style.outline = "2px solid red";
}else{
inputs[2].style.border = "2px solid green";
inputs[2].style.outline = "2px solid green";
document.querySelector('.err_red').innerText = "All Fields Are VALID";
document.querySelector('.err_red').style.color = "green";
document.querySelector('button').disabled = false;
}
}
}


function fetch_data(para){
if(para == 'therapist'){
	let static_data = document.querySelector('#therapist').children;
	para = '#'+para;
	let entered_num = document.getElementsByName('agent')[0].value;
	$.post('wp-content/themes/thriive/horoscope_new/knowlarity/fetch_num.php',{num:entered_num},function(data){
		let ret_vars = data.split(',');
		if(ret_vars[0] != 'error404'){
		static_data[1].innerText = ret_vars[0]+' '+ret_vars[1];
		static_data[2].innerText = ret_vars[2];	
		console.log(ret_vars);
		}else{static_data[1].innerText = 'No Therapist with that number';
			static_data[2].innerText = '---';
		}
	});
	
}
if(para == 'user'){
	let static_data = document.querySelector('#user').children;
	para = '#'+para;
	let entered_num = document.getElementsByName('customer')[0].value;
	$.post('wp-content/themes/thriive/horoscope_new/knowlarity/fetch_num.php',{num:entered_num},function(data){
		let ret_vars = data.split(',');
		if(ret_vars[0] != 'error404'){
		static_data[1].innerText = ret_vars[0]+' '+ret_vars[1];
		static_data[2].innerText = ret_vars[2];	
		console.log(ret_vars);
		}else{static_data[1].innerText = 'No Therapist with that number';
			static_data[2].innerText = '---';
		}
	});
	
}
}

</script>




