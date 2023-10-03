<?php
include "config.php";
error_reporting(0);
//extract($_REQUEST);

	$jointvalue = $_REQUEST['selectedvalue'];
	$result_explode = explode('|', $jointvalue);
    $timezone  = $result_explode[0];
	
    
	$date = $_REQUEST['date'];
		
	$day = strtok($date, ", ");
	
	
    $dayquery = $db->query("SELECT * FROM `admin_schedule` WHERE `day` = '".$day."' ")->fetch();

	$admin_day = $dayquery['day'];
	
    $datetime_1 = $dayquery['start_time'];
	
	
	$datetime_2 = $dayquery['end_time'];

	$start_datetime = new DateTime($datetime_1); 
	$diff = $start_datetime->diff(new DateTime($datetime_2)); 

	$time_diff = $diff->h; 
	$items = strval($time_diff);

	$val = '+'.$items.'hours';
	//echo $datetime_1;
if (!empty($timezone)) {
	$date = new DateTime($datetime_1, new DateTimeZone('Australia/Sydney'));
	//echo $date->format('Y-m-d H:i:sP') . "\n";
	
	$date->setTimezone(new DateTimeZone($timezone));
	//echo $timezone;
	$start = $date->format('h:i A') . "\n";
	//echo $start;
	
	$end_date = date('h:i A',strtotime($val,strtotime($start)));
// echo $end_date;
// exit;
	$current_time = $result_explode[1];
	//$current_ti71me = '12:30 PM';
	$current_day = date("l");
	if($day == $current_day)
	{
		$current_date_val=date_create($current_time);
		$modify_current = date_format($current_date_val,"H:i");
		//echo $modify_current."<br>";
		$start_val= date_create($start);
		$modify_start = date_format($start_val,"H:i");
		//echo $modify_start."<br>";

		$end_val= date_create($end_date);
		$modify_end = date_format($end_val,"H:i");
		// echo $modify_end;
		// exit;
		if($modify_current > $modify_start && $modify_current < $modify_end)
		{
			$range = range(strtotime($current_time), strtotime($end_date), 15*60);
		}
		else
		{ ?>
		<script>
			$('body').removeClass("addRightside");
		swal({
				title: "!! Empty Slot !!",
				text: "Slots Are Not Available.",
				icon: "info",
				button: "Ok",
				//timer: 2000,DGFJ

				'				
			});
			
			</script>
		<?php
			//echo "<span style='color: red;padding: 4px 8px;font-size: 1.2em;text-align: center;'>Slots Are Not Available! </span>";
		}
	}
	else
	{
		$range = range(strtotime($start), strtotime($end_date), 15*60);
	}

	

		
	}

	if(!empty($admin_day))
	{

	$time_slots_str = "";

	foreach($range as $time)
	{	
		$time_slots_str .= '<div class="right-side-button-item">
								<button class="btn-time sub-time" type="button" onClick="buttonval(this.id)" id="' . date("h:i A", $time) . '">' . date("h:i A", $time) . '</button>
								<div class="right-side-button-item-select">
									<button class="btn-time-select" type="button">' . date("h:i A", $time) . '</button>
									<button class="btn-next"  name="submitdata" type="submit">Next</button>
								</div>
							</div>';
		
	}

	echo $time_slots_str;
}
else
{
	echo "<span style='color: red;padding: 4px 8px;font-size: 1.6em;text-align: center;font-weight:bold;'>Appoinment Slots Are Not Available! </span>";
}


?>
<script>
    $('.right-side-button-item > .sub-time').click(function() {
            $('.sub_time_select').removeClass("sub_time_select");
            $(this).parent().addClass("sub_time_select");
        });
</script>