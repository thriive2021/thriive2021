
<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package thriive
 */ 


?>
<?php include_once '/var/www/html/wp-content/themes/thriive/horoscope_new/chat-renew/chat_modals/modals.php'; ?>
  <footer class="footerwrap mt-3">
    <div class="contactdetails">
      <aside class="contact">
       <div>
          <h4 class="hdh4">Contact Us</h4>
          <p>support@thriive.in </p>
        </div>
      </aside>
      <aside class="social">
        <h4 class="hdh4">Follow us</h4>
        <div class="ssbx">
          <a href="https://www.facebook.com/thriiveindia" target="_blank" class="ss fb"><img src="<?php echo get_template_directory_uri() .'/assets/images/newsoulimg/ss-fb.svg'; ?>" alt=""></a>
          <a href="https://www.instagram.com/thriiveindia/" target="_blank" class="ss insta"><img src="<?php echo get_template_directory_uri() .'/assets/images/newsoulimg/ss-insta.svg'; ?>" alt=""></a>
          <a href="https://in.linkedin.com/company/thriive-india-group" target="_blank" class="ss in"><img src="<?php echo get_template_directory_uri() .'/assets/images/newsoulimg/ss-in.svg'; ?>" alt=""></a>
          <a href="https://www.youtube.com/channel/UCmN0ipKhWCOlvAbLKvzp1Ww" target="_blank" class="ss yt"><img src="<?php echo get_template_directory_uri() .'/assets/images/newsoulimg/ss-yt.svg'; ?>" alt=""></a>
          <a href="https://twitter.com/thriiveindia" target="_blank" class="ss tw"><img src="<?php echo get_template_directory_uri() .'/assets/images/newsoulimg/ss-tw.svg'; ?>" alt=""></a>
        </div>
      </aside>
    </div>
    <p class="copyrights">© <?php echo date('Y'); ?> THRIIVE ART & SOUL LLP. All Rights Reserved.</p>
    <p class="copyrights">Please ensure to have read the <a href="https://www.thriive.in/wp-content/themes/thriive/assets/pdf/general-terms-of-use.pdf">T&C</a> and disclaimer prior to using the services of our website.</p>


  </footer>
  <?php $current_user = wp_get_current_user(); ?>  
  <input type="hidden" name = "session_user" id="session_user" value="<?php echo $current_user->ID ?>" />
  <div class="table-responsive">
    <div id="user_details"></div>
    <div id="user_model_details"></div>
  </div>
  <?php if(isset($_GET['chat']) && $_GET['chat'] == 'yes'){
    echo "<script>check_box_open()</script>";
  } 
  wp_footer(); ?>
  <?php include_once get_stylesheet_directory().'/new-rsm-modal.php'; ?>
 <script src=<?php echo "'".  get_site_url().'/wp-content/themes/thriive/js/timepicki.js?v=2021226.0'."'";?>></script>

<!--<script src="<?php// echo get_site_url();?>/wp-content/themes/thriive/horoscope_new/chat-renew/scripts/chat-script.js?v=20210420.22"></script>-->






 
<?php
$current_user = wp_get_current_user()->ID;
$curr_role = get_user_meta($current_user)['wp_capabilities'];
$curr_name = get_user_meta($current_user)['first_name'][0];
if(strpos($curr_role[0], 'subscriber')!=false){
  $curr_role = 'sub';
}else if(strpos($curr_role[0], 'therapist')!=false){
  $curr_role = 'therapist';
}

if($curr_role == 'sub'){?>

<style>
  #nUser{
    display: none;
  }
</style>

<?php }?>




</body>
</html>