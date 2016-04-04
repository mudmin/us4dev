<?php
/*
UserSpice 4
An Open Source PHP User Management System
by Curtis Parham and Dan Hoover at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
 ?>
<?php require_once("includes/userspice/us_header.php"); ?>

<?php require_once("includes/userspice/us_navigation.php"); ?>
<div id="page-wrapper">

  <div class="container">
<?php
$settingsQ = $db->query("SELECT * FROM settings");
$settings = $settingsQ->first();
$email = Input::get('email');
$csrf = Input::get('csrf');
$vericode = rand(100000,999999);

//Decide whether or not to use email activation
$query = $db->query("SELECT * FROM email");
$results = $query->first();
$act = $results->email_act;

//Opposite Day for Pre-Activation - Basically if you say in email settings that you do NOT want email activation, this lists new users as active in the database, otherwise they will become active after verifying their email.
if($act==1){
  $pre = 0;
} else {
  $pre = 1;
}
//Logic if ReCAPTCHA is turned ON
if($settings->recaptcha == 1){
require_once("includes/recaptcha.config.php");
//reCAPTCHA 2.0 check
$response = null;

// check secret key
$reCaptcha = new ReCaptcha($privatekey);

if(!Token::check($csrf)){
  Redirect::to('index.php');
}else{
  // if submitted check response
if ($_POST["g-recaptcha-response"]) {
  $response = $reCaptcha->verifyResponse(
  $_SERVER["REMOTE_ADDR"],
  $_POST["g-recaptcha-response"]
);
}
if ($response != null && $response->success) {
    //add user to the database
    $user = new User();
    $join_date = date("Y-m-d H:i:s");
    $params = array(
      'fname' => Input::get('fname'),
      'email' => $email,
      'vericode' => $vericode,
    );

if($act == 1) {
    //Verify email address settings
     $to = $email;
     $subject = 'Welcome to UserSpice!';
     $body = email_body('verify.php',$params);
     email($to,$subject,$body);
}
    try {
      // echo "Trying to create user";
      $user->create(array(
        'username' => Input::get('username'),
        'fname' => Input::get('fname'),
        'lname' => Input::get('lname'),
        'email' => Input::get('email'),
        'password' =>
        password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12)),
        'permissions' => 1,
        'account_owner' => 1,
        'stripe_cust_id' => '',
        'join_date' => $join_date,
        'company' => Input::get('company'),
        'email_verified' => $pre,
        'active' => 1,
        'vericode' => $vericode,
      ));
    } catch (Exception $e) {
      die($e->getMessage());
    }
}
}
}else{
  //Logic if ReCAPTCHA is turned OFF
  if(!Token::check($csrf)){
    Redirect::to('index.php');
  }else{
      //add user to the database
      $user = new User();
      $join_date = date("Y-m-d H:i:s");
      $params = array(
        'fname' => Input::get('fname'),
        'email' => $email,
        'vericode' => $vericode,
      );

  if($act == 1) {
      //Verify email address settings
       $to = $email;
       $subject = 'Welcome to UserSpice!';
       $body = email_body('verify.php',$params);
       email($to,$subject,$body);
  }
      try {
        // echo "Trying to create user";
        $user->create(array(
          'username' => Input::get('username'),
          'fname' => Input::get('fname'),
          'lname' => Input::get('lname'),
          'email' => Input::get('email'),
          'password' =>
          password_hash(Input::get('password'), PASSWORD_BCRYPT, array('cost' => 12)),
          'permissions' => 1,
          'account_owner' => 1,
          'stripe_cust_id' => '',
          'join_date' => $join_date,
          'company' => Input::get('company'),
          'email_verified' => $pre,
          'active' => 1,
          'vericode' => $vericode,
        ));
      } catch (Exception $e) {
        die($e->getMessage());
      }
  }
  }

if($act == 1) {
      include 'views/join/_joinThankYouVerify.php';
}
if($act == 0) {
      include 'views/join/_joinThankYou.php';
}


?>
</div>
</div>
<!-- Content Ends Here -->
<!-- footers -->
<?php require_once("includes/userspice/us_page_footer.php"); // the final html footer copyright row + the external js calls ?>

<!-- Place any per-page javascript here -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php require_once("includes/userspice/us_html_footer.php"); // currently just the closing /body and /html ?>
