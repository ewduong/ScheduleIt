<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ScheduleIt</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">

    <!-- Custom Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css" type="text/css">
    	<link rel="stylesheet" href="css/web-fonts.css" type="text/css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="css/animate.min.css" type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/creative.css" type="text/css">
	<link rel="stylesheet" href="css/buttons.css" type="text/css">
    <link rel="stylesheet" href="css/custom.dropdown.css" type="text/css">
	
	<!-- Calender Stuff -->
    <link href='css/fullcalendar.css' rel='stylesheet' />
    <link href='css/fullcalendar.print.css' rel='stylesheet' media='print' />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script src="js/ajaxdrop.js" type="text/javascript"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<style>
@media print {
    .noprint { display:none; }
}
@page { size:11in 8.5in; margin: 2cm }
</style>
</head>

<body id="page-top">

<?php include('includes/nav.html');?>
	<header>
<?php
/*echo '<pre>';
print_r ($_POST);
echo '</pre>';*/
?>
	<section id="courses">
			<div style="padding:0 3%;" >
				<div class="row">
				  <div class="col-lg-12 text-center">
<?php
//echo $return_mid;
//rtrim (',', $return_mid)
# #lst = [("PHIL",4501),("ENGL",1100),("MGMT",1500),("MATH",1850)]
# python ./schedulegenerator.py '[("COMP",4499),("ELEC",4200),("ELEC",4225),("LITR",4301)],"spring_2016"' 
# python ./schedulegenerator.py '[[("COMP",4499),("ELEC",4200),("ELEC",4225),("COMP",4970)], "spring_2016"'

if ($_POST) {
	$x=0;
	for ($i=0;$i<5;$i++){
		if ($_POST ['subject'][$i] != '' && $_POST ['course'][$i] != '') {
			$x++;
			$return_mid .= '("'.$_POST ['subject'][$i].'",'.$_POST ['course'][$i].'),';
		}
	}
}

if (!$_POST) {
	include ('includes/schedule-header.html');
	include ('includes/schedule-form.html');
} elseif ($x == 0) {
	print ('<div class="system-message">
	<h2>Classes Not Found</h2>
	<p>All classes were not found in the database.  Search again below</p>
	</div>
	<h3 class="section-heading">Select Courses To Create Schedule</h3><br />');
	include ('includes/schedule-form.html');
}else {
	include('includes/dbconnect.php');	
	$return = 'python ./schedulegenerator.py \'['.rtrim ($return_mid, ',').'], "'.$_POST ['season'].'"\'';
//	echo $return;
	$command = escapeshellcmd($return);
	//$output = eval(shell_exec($command." 2>&1"));
	$output = shell_exec($command." 2>&1");
	//$output = shell_exec('whoami');
//	echo "</br>output: ".$output;
	$conn->select_db($_POST['season']);
	$result="";
	$numOfSchedules=0;
	$s=1;
	$del="[[";
	$printCRN="Schedule 1: ";
	foreach(eval("return ".$output.";") as $set) {
		$valid_crn=0;
		foreach($set as $CRN) {
			if ($CRN >100) {
				$valid_crn++;
			}
//			echo "</br>CRN: ".$CRN;
			$printCRN.=$CRN.",";
			$query = "SELECT a.course_CRN, b.title, a.day, a.start, a.end, a.instructor, a.location FROM class a, course b WHERE a.course_CRN = b.CRN AND a.course_CRN=$CRN";
			$result = $conn->query($query);
			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$class_length = strtotime (test($row['end']))- strtotime (test($row['start']));
					// echo 'Class Length ['.$row['course_CRN'].']:'.gmdate('H:i', $class_length).' : '.($class_length).'<br>';
					if ($class_length > 3000)
					{
						$row ['title'] = format_title(clean($row["title"]))." - ".format_title(clean($row["instructor"]));
					}else {
						$row ['title'] = format_title(clean($row["title"]));
					}
					if($row["day"] == "M") {
							$del .= "{id: '".$row["course_CRN"]."', title: '".$row["title"]."', start: '2016-02-15T".test($row["start"])."', end: '2016-02-15T".test($row["end"])."'}";
					} else if($row["day"] == "T") {
							$del .= "{id: '".$row["course_CRN"]."', title: '".$row["title"]."', start: '2016-02-16T".test($row["start"])."', end: '2016-02-16T".test($row["end"])."'}";
					} else if($row["day"] == "W") {
							$del .= "{id: '".$row["course_CRN"]."', title: '".$row["title"]."', start: '2016-02-17T".test($row["start"])."', end: '2016-02-17T".test($row["end"])."'}";
					} else if($row["day"] == "R") {
							$del .= "{id: '".$row["course_CRN"]."', title: '".$row["title"]."', start: '2016-02-18T".test($row["start"])."', end: '2016-02-18T".test($row["end"])."'}";
					} else if($row["day"] == "F") {
							$del .= "{id: '".$row["course_CRN"]."', title: '".$row["title"]."', start: '2016-02-19T".test($row["start"])."', end: '2016-02-19T".test($row["end"])."'}";
					} else if($row["day"] == "S") {
							$del .= "{id: '".$row["course_CRN"]."', title: '".$row["title"]."', start: '2016-02-20T".test($row["start"])."', end: '2016-02-20T".test($row["end"])."'}";
					}
					$del.=", ";
				}
			}
		}
		if ($valid_crn>0)
		{
			$s++;
			$printCRN=substr($printCRN,0, strlen($printCRN)-1);
			$printCRN.="</br>Schedule $s: ";
			$numOfSchedules++;
			$del.="] , [";
		}
	}

	if ($numOfSchedules == 0) {
		print ('<div class="system-message noprint">
	<h2>No Class Found</h2>
	<p>Please double check your information or contact us about your problem.</p>
	</div>
	<h3 class="section-heading">Select Courses To Create Schedule</h3>');
		include ('includes/schedule-form.html');
	}else{
		print ('<div class="system-message success noprint">
	<h2>Classes Found</h2>
	<p>Select a tab on the calendar below to view a schedule</p>
			<p><a href="schedule.php" name="schedule" class="btn btn-default btn-md" >Search Again</a></p>	
	</div>');
	}

	$del=substr($del,0,strlen($del)-6)."]]";
	for($i=0; $i<$numOfSchedules;$i++){
		$j=($i+1);
		echo "<button style=\"padding: 0 10px;margin:0 5px 0 0;border-radius:10px 10px 0 0;float:left;\" class=\"button button-pill button-flat-primary cal-button\" onclick=\"addEvents($del,$i);\">Schedule $j</button>";
	}
	echo "<button style=\"padding: 0 10px; border-radius:10px 10px 0 0;float:right;\" class=\"button button-pill cal-button\" onclick=\"printPage()\">Print Schedule</button></br>";
	echo "<div id='printarea'><div id='calendar'></div>";
	$printCRN=substr($printCRN,0,strlen($printCRN)-13).'</div>';
	echo $printCRN;
	$conn->close();
}
function test($string){
	if ($_POST['season'] == 'fall_2016')
	{
		return TRUE;
	}
	$hour = explode (':', $string);
	if( $hour[0] >= 1 && $hour[0] < 7) {
			$hour[0] += 12;
			return implode (':', $hour);
	}else {
			return ($string);
	}
}
function format_title($var)
{
	if (strstr ($var, '- LAB'))
	{
		return substr (ucwords (strtolower(str_replace ('- LAB', '', $var))), 0,22).'(lab)';
	}
	if (strstr ($var, '-LAB'))
	{
		return substr (ucwords (strtolower(str_replace ('-LAB', '', $var))), 0,22).'(lab)';
	}
	return substr (ucwords (strtolower($var)), 0,26);
}
function clean ($var) {
	return preg_replace('/[^\w\d\s]+/', '', strip_tags(html_entity_decode($var)));
}


?>
              </div>
            </div>
        </div>
    </section>
</header>

<?php include('includes/footer.html');?>
<?php include('includes/search-form.html');?>	

    <!-- Plugin JavaScript -->
	<script type="text/javascript" src="js/buttons.js"></script>
	
	<!-- Calender Stuff -->
    <script src='lib/moment.min.js'></script>
    <script src='fullcalendar.min.js'></script>
	
	<script>
		var calendar;
		$(document).ready(function() {
		calendar = $('#calendar');
			calendar.fullCalendar({
				theme: true,
				header: false,
				contentHeight: 'auto',
				columnFormat: 'ddd',
				defaultView: 'agendaWeek',
				defaultDate: '2016-02-14',
				minTime: '08:00:00',
				maxTime: '22:00:00',
				allDaySlot: false,
				editable: false,
				eventLimit: false,
				eventColor: '#378006',
				eventClick: function(calEvent, jsEvent, view) {

        		alert('Event: ' + calEvent.title);

       			 // change the border color just for fun
        		$(this).css('border-color', 'red');

    			}
			});
       	});

        function addEvent(event) {
			calendar.fullCalendar('renderEvent', event);
        }
		
        function addEvents(events,num){
			for (j = 0; j < events.length; j++) {
					removeEvents(events[j]);
			}
			var event2 = events[num];
			for (i = 0; i < event2.length; i++) {
				addEvent(event2[i]);
			}
        }
		
        function removeEvent(event){
			calendar.fullCalendar('removeEvents', event.id);
        }
		
        function removeEvents(events){
			for (i = 0; i < events.length; i++) {
				removeEvent(events[i].id);
			}
        }
	
		function printPage(){
			window.print();
		}
      </script>
</body>
</html>