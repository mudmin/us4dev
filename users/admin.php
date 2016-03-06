<?php
/*
UserSpice 4
by Dan Hoover at http://UserSpice.com
*/
?>
<?php require_once("includes/userspice/us_header.php"); ?>

<?php require_once("includes/userspice/us_navigation.php"); ?>

<?php if (!securePage($_SERVER['PHP_SELF'])){die();} ?>
<?php
// To make this panel super admin only, uncomment out the lines below
// if($user->data()->id !='1'){
//   Redirect::to('account.php');
// }

//PHP Goes Here!
delete_user_online(); //Deletes sessions older than 30 minutes
//Find users who have logged in in X amount of time.
$date = date("Y-m-d H:i:s");
// echo $date."<br>";
$hour = date("Y-m-d H:i:s", strtotime("-1 hour", strtotime($date)));
$today = date("Y-m-d H:i:s", strtotime("-1 day", strtotime($date)));
$week = date("Y-m-d H:i:s", strtotime("-1 week", strtotime($date)));
$month = date("Y-m-d H:i:s", strtotime("-1 month", strtotime($date)));

$usersHourQ = $db->query("SELECT * FROM users WHERE last_login > ?",array($hour));
$usersHour = $usersHourQ->results();
$hourCount = $usersHourQ->count();

$usersTodayQ = $db->query("SELECT * FROM users WHERE last_login > ?",array($today));
$dayCount = $usersTodayQ->count();
$usersDay = $usersTodayQ->results();

$usersWeekQ = $db->query("SELECT username FROM users WHERE last_login > ?",array($week));
$weekCount = $usersWeekQ->count();

$usersMonthQ = $db->query("SELECT username FROM users WHERE last_login > ?",array($month));
$monthCount = $usersMonthQ->count();

$usersQ = $db->query("SELECT * FROM users");
$user_count = $usersQ->count();

$pagesQ = $db->query("SELECT * FROM pages");
$page_count = $pagesQ->count();

$levelsQ = $db->query("SELECT * FROM permissions");
$level_count = $levelsQ->count();

$settingsQ = $db->query("SELECT * FROM settings");
$settings = $settingsQ->first();

// count the number of logins in the last 24 hours
$numlogins24 = 0;
$numlogins_lookback = 86400;
$now = time();
$getnumlogins = $now - $numlogins_lookback;
if($getnumloginscount = countLoginsSince(1,$getnumlogins)){
	$numlogins24 = $getnumloginscount;
	}

  // get notification of new events
  $noticount = 0;
  $uid = $user->data()->id; // user id
  $now = time();

  	if (isset($_GET['n']))
  		{
  		$_SESSION['ll'] = time();
  		$_SESSION['lt'] = time();
  		}
  	else
  		{
  		$prev_login = $_SESSION['ll'];
  		$this_sessi = $_SESSION['lt'];

  		if($not1 = fetchAllLatest($uid,$this_sessi,$now,3))
  					{
  					$noticount = count($not1);
  					}
  		}

  $display_activitydata = '';
  $activityData = fetchAllAudit();
  // dnd($activityData);
  //Cycle through activity data
    foreach ($activityData as $v1)
  		{
  		$accuserid = ($v1->audit_userid == 666) ? 0 : $v1->audit_userid; // do something with baddies
  		$accuserip = $v1->audit_userip;
  		$accagodate = ago($v1->audit_timestamp);
  		$accaudate = date("d/M/Y G:i:s",$v1->audit_timestamp);
  		$adisp_name = ($v1->username == "") ? "Unknown" : $v1->username;
  		$adisp_rowc = ($v1->audit_eventcode == 3) ? "alert alert-danger" : '';
  		$adisp_rowc = (($adisp_rowc == '') && ($v1->audit_eventcode == 5)) ? "alert alert-success" : $adisp_rowc;
  		$display_activitydata .= '
  		<tr class="'.$adisp_rowc.'">
  		<td>'.$accagodate.'</td>
  		<td><a href="admin_user.php?id='.$accuserid.'">'.$adisp_name.'</a></td>
  		<td>'.$v1->audit_eventcode .'</td>
  		<td>'.$v1->audit_action .'</td>
  		<td>'.$accuserip.'</td>
  		</tr>
  		';
  		}
//if $_POST
if(!empty($_POST['settings'])){
  $token = $_POST['csrf'];
	if(!Token::check($token)){
		die('Token doesn\'t match!');
	}
if($settings->recaptcha != $_POST['recaptcha']) {
  $recaptcha = Input::get('recaptcha');
  $fields=array('recaptcha'=>$recaptcha);
  $db->update('settings',1,$fields);
}
if($settings->site_name != $_POST['site_name']) {
  $site_name = Input::get('site_name');
  $fields=array('site_name'=>$site_name);
  $db->update('settings',1,$fields);
}
if($settings->login_type != $_POST['login_type']) {
  $login_type = Input::get('login_type');
  $fields=array('login_type'=>$login_type);
  $db->update('settings',1,$fields);
}
if($settings->force_ssl != $_POST['force_ssl']) {
  $force_ssl = Input::get('force_ssl');
  $fields=array('force_ssl'=>$force_ssl);
  $db->update('settings',1,$fields);
}
if($settings->force_pr != $_POST['force_pr']) {
  $force_pr = Input::get('force_pr');
  $fields=array('force_pr'=>$force_pr);
  $db->update('settings',1,$fields);
}
if($settings->site_offline != $_POST['site_offline']) {
  $site_offline = Input::get('site_offline');
  $fields=array('site_offline'=>$site_offline);
  $db->update('settings',1,$fields);
}
if($settings->track_guest != $_POST['track_guest']) {
  $track_guest = Input::get('track_guest');
  $fields=array('track_guest'=>$track_guest);
  $db->update('settings',1,$fields);
}

Redirect::to('admin.php');
}

if(!empty($_POST)){
  if($settings->css_sample != $_POST['css_sample']) {
    $css_sample = Input::get('css_sample');
    $fields=array('css_sample'=>$css_sample);
    $db->update('settings',1,$fields);
  }

  if($settings->us_css1 != $_POST['us_css1']) {
    $us_css1 = Input::get('us_css1');
    $fields=array('us_css1'=>$us_css1);
    $db->update('settings',1,$fields);
  }
  if($settings->us_css2 != $_POST['us_css2']) {
    $us_css2 = Input::get('us_css2');
    $fields=array('us_css2'=>$us_css2);
    $db->update('settings',1,$fields);
  }

if($settings->us_css3 != $_POST['us_css3']) {
  $us_css3 = Input::get('us_css3');
  $fields=array('us_css3'=>$us_css3);
  $db->update('settings',1,$fields);
}
if($settings->css1 != $_POST['css1']) {
  $css1 = Input::get('css1');
  $fields=array('css1'=>$css1);
  $db->update('settings',1,$fields);
}
if($settings->css2 != $_POST['css2']) {
  $css2 = Input::get('css2');
  $fields=array('css2'=>$css2);
  $db->update('settings',1,$fields);
}
if($settings->css3 != $_POST['css3']) {
  $css3 = Input::get('css3');
  $fields=array('css3'=>$css3);
  $db->update('settings',1,$fields);
}

Redirect::to('admin.php');
}

?>
<div id="page-wrapper">

  <div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">
          Administrator Control Panel
        </h1>
    </div>

    <!-- /.row -->

<!-- Top Admin Panels -->
<?php require_once("views/admin_panel/_top_panels.php");?>
<?php require_once("views/admin_panel/_audit_table.php");?>
<?php if($settings->css_sample == 1){     require_once("views/admin_panel/_css_test.php");}?>
<?php require_once("views/admin_panel/_css_settings.php");?>
<?php require_once("views/admin_panel/_main_settings.php");?>

    <!-- footers -->
    <?php require_once("includes/userspice/us_page_footer.php"); // the final html footer copyright row + the external js calls ?>

    <!-- Place any per-page javascript here -->
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="js/plugins/flot/jquery.flot.pie.js"></script>

	<script type="text/javascript">
	$(document).ready(function(){

	// Cheeky animation http://codepen.io/shivasurya/pen/FatiB
	$('.count').each(function () {
		$(this).prop('Counter',0).animate({
			Counter: $(this).text()
		}, {
			duration: 1600,
			easing: 'swing',
			step: function (now) {
				$(this).text(Math.ceil(now));
			}
		});
	});


// Example graphs
// ------------------------------------------------------------------------- graph 0
// Signups
		var options = {

			xaxis: {
				mode: "time",
				timeformat: "%m/%d",
				},
			yaxis: {
				tickSize : 1,
				tickDecimals: 0
				},
        series: {
            lines: { show: true },
            points: {
                radius: 12,
                show: true,
                fill: true
            }
        }

		};

		$.ajax({
				url: 'json_chart.php',
				contentType: 'application/json; charset=utf-8',
				type: 'GET',
				data: {"chartid" : 0},
				dataType: 'json',
				success: function (data) {
				$.plot('#flotcontainer0', data, options);
					},
				failure: function (response) {
					alert(response.d);
				}
			});

// -------------------------------------------------------------------------
// Logins
		$.ajax({
				url: 'json_chart.php',
				contentType: "application/json; charset=utf-8",
				type: "GET",
				data: {"chartid" : 2},
				dataType: 'json',
				success: function (data) {

				$.plot($("#flotcontainer2"), data, {
					xaxis: { mode: "time"},
					yaxis: {tickSize : 1,tickDecimals: 0},
					series: { bars: { show: true } }
				});

					},
				failure: function (response) {
					alert(response.d);
				}
			});
// -------------------------------------------------------------------------
// The bottom left pie
		$.ajax({
				url: 'json_chart.php',
				contentType: "application/json; charset=utf-8",
				type: "POST",
				dataType: 'json',
				success: function (data) {
				$.plot($("#flotcontainer"), data, {
							series: {
								pie: {
									show: true,
									radius: 500,
									label: {
										show: true,
										radius: 3/4,
										background: {
											opacity: 0.5,
											color: '#000'
										}
									}
								}
							},
							legend: {
								show: false
							}
						});
					},
				failure: function (response) {
					alert(response.d);
				}
			});
// -------------------------------------------------------------------------
// The bottom right pie
		$.ajax({
				url: 'json_chart.php',
				contentType: "application/json; charset=utf-8",
				type: "GET",
				data: {"chartid" : 1},
				dataType: 'json',
				success: function (data) {
				$.plot($("#flotcontainer1"), data, {
							series: {
								pie: {
									show: true,
									radius: 1,
									label: {
										show: true,
										radius: 3/4,
										background: {
											opacity: 0.5,
											color: '#000'
										}
									}
								}
							},
							legend: {
								show: false
							}
						});
					},
				failure: function (response) {
					alert(response.d);
				}
			});

// -------------------------------------------------------------------------
		});
	</script>
    <?php require_once("includes/userspice/us_html_footer.php"); // currently just the closing /body and /html ?>
