<!DOCTYPE html>
<!--[if lt IE 8 ]><html class="no-js ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="no-js ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 8)|!(IE)]><!--><html class="no-js" lang="en"> <!--<![endif]-->
<head>

   <!--- Basic Page Needs
   ================================================== -->
   <meta charset="utf-8">
	<title>ScheduleCreator</title>
	<meta name="description" content="">
	<meta name="author" content="">

   <!-- Mobile Specific Metas
   ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
    ================================================== -->
   <link rel="stylesheet" href="css/default.css">
   <link rel="stylesheet" href="css/layout.css">
   <link rel="stylesheet" href="css/media-queries.css">

   <!-- Script
   ================================================== -->
	<script src="js/modernizr.js"></script>
    <link rel='stylesheet' href='../lib/cupertino/jquery-ui.min.css' />
    <link href='fullcalendar.css' rel='stylesheet' />
    <link href='fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='lib/moment.min.js'></script>
    <script src='lib/jquery.min.js'></script>
    <script src='fullcalendar.min.js'></script>
    <script src='fullcalendar.js'></script>
        <script>
var calendar;
$(document).ready(function() {
  calendar = $('#calendar');
  calendar.fullCalendar({
    theme: true,
    header: false,
    height: 525,
    columnFormat: 'ddd',
    defaultView: 'agendaWeek',
    defaultDate: '2016-02-14',
    minTime: '08:00:00',
    maxTime: '20:00:00',
    //events:  'eval(document.getElementById('calendar').getAttribute("data-events"))',
    allDaySlot: false,
    editable: false,
    eventLimit: true,
    eventColor: '#378006'
  });
 });
 var myEvent = {
    id: "Event1",
    title:"my new event",
    start: '2016-02-14T18:00:00+00:00',
    end: '2016-02-14T19:0:00+00:00',
    backgroundColor: "red"
  }
  var myEvent2 = {
     id: "Event2",
     title:"my new event",
     start: '2016-02-15T13:00:00+00:00',
     end: '2016-02-15T14:0:00+00:00'
  }

  function addEvent(event) {
    calendar.fullCalendar('renderEvent', event);
  }
  function addEvents(events){
    for (i = 0; i < events.length; i++) {
      addEvent(events[i]);
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
        </script>
   <!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="favicon.ico" > 

</head>

<body>

   <!-- Header
   ================================================== -->
   <header>

      <div class="row">

         <div class="twelve columns">

            <div class="logo">
               <a href="index.html"><img alt="" src="images/logo2.png"></a>
            </div>

            <nav id="nav-wrap">

               <a class="mobile-btn" href="#nav-wrap" title="Show navigation">Show navigation</a>
	            <a class="mobile-btn" href="#" title="Hide navigation">Hide navigation</a>

               <ul id="nav" class="nav">

	               <li><a href="index.html">Home</a></li>
	               <li class="current"><a href="search.php">Search Courses</a>
				   <!--
	               <li><a href="about.html">About</a></li>
                  <li><a href="contact.html">Contact</a></li>
                  <li><a href="styles.html">Features</a></li>
				  -->

               </ul> <!-- end #nav -->

            </nav> <!-- end #nav-wrap -->

         </div>

      </div>

   </header> <!-- Header End -->

   <!-- Intro Section
   ================================================== -->
   <section id="intro">

      <!-- Flexslider Start-->
	   <div>

		   <ul class="slides">

			   <!-- Slide -->
			   <li>
				   <div class="row">
					   <div class="twelve columns">
						   <div class="slider-text">
							   <h1>ScheduleCreator<span>.</span></h1>
								<form method ="post" id="search-form">
									<p>Course CRN: <input type='text' placeholder='Search...' id="search-text-input" name="course_CRN" /></p>
									<p>Day: <input type='text' placeholder='Search...' id="search-text-input" name="day" /></p>
									<p>Start: <input type='text' placeholder='Search...' id="search-text-input" name="start" /></p>
									<p>End: <input type='text' placeholder='Search...' id="search-text-input" name="end" /></p>
									<p>Professor: <input type='text' placeholder='Search...' id="search-text-input" name="instructor" /></p>
									<p>Location: <input type='text' placeholder='Search...' id="search-text-input" name="location" /></p>
									<input type="submit" name="Submit" style="display: none">
								</form>
						   </div>
					   </div>
				   </div>
			   </li>
			   <div class="row align-center text-center centered">
					<?php
						include('includes/dbconnect.php');
						
						// replace last occurence of $search string (for sql statement remove last AND)
						function str_lreplace($search, $replace, $subject) {
							$pos = strrpos($subject, $search);

							if($pos !== false) {
								$subject = substr_replace($subject, $replace, $pos, strlen($search));
							}

							return $subject;
						}

						$fields = array('course_CRN', 'day', 'start', 'end', 'instructor', 'location');
						$course_CRN = $title = $day = $start = $end = $instructor = $location = false;
						$sql = "SELECT course_CRN, day, start, end, instructor, location FROM class WHERE ";
						$wfieldname = "";

						if (isset($_POST['Submit'])) {
							foreach($fields AS $fieldname) { // Loop trough each field to see if empty or not
								if(!isset($_POST[$fieldname]) || empty($_POST[$fieldname])) {
									//echo 'Field '.$fieldname.' empty!';
									$$fieldname = false;
									//echo $$fieldname ? ' true <br />' : ' false <br />';
								} else {
									$$fieldname = true;
									//echo $fieldname.' ';
									//echo $$fieldname ? ' true <br />' : ' false <br />';
								}
							}
							
							// sql statement generator based on text boxes filled in (still wip)
							foreach($fields AS $fieldname) {
								if ($$fieldname) {
									$wfieldname = $_POST[$fieldname];
									// if there is whitespace, replace with %
									if (preg_match('/\s/', $wfieldname)) {
										str_replace(" ", "%", $wfieldname);
									}
									$sql .= $fieldname." LIKE '%".$_POST[$fieldname]."%' AND ";
									
									//echo "sql generated: ".$sql."<br />";
								}
							}
							
							$sql = str_lreplace("AND", "", $sql); // remove trailing AND in sql statement
							
							/*
							if (!(empty($_REQUEST['instructor']) && empty($_REQUEST['location']))) {
								$sql = "SELECT course_CRN, day, start, end, instructor, location FROM class WHERE instructor LIKE '%".$_POST['instructor']."%' AND location LIKE '%".$_POST['location']."%'";
							} else if (!empty($_REQUEST['instructor'])) {
								$sql = "SELECT course_CRN, day, start, end, instructor, location FROM class WHERE instructor LIKE '%".$_POST['instructor']."%'";
								//echo "sql generated: ".$sql."<br />";
							} else {
								$sql = "SELECT course_CRN, day, start, end, instructor, location FROM class";  
							}
							*/
							
							//echo "sql generated: ".$sql."<br />";
							//$sql = "SELECT course_CRN, day, start, end, instructor, location FROM class WHERE instructor LIKE '%Yuna%Kim%'";
							echo "sql generated: ".$sql."<br />";
							
							
							$result = $conn->query($sql);
							if ($result->num_rows > 0) {
								echo "<center><table class=\"bordered\">
									<tr>
										<th class='text-center'>Course CRN</th>
										<th class='text-center'>Day</th>
										<th class='text-center'>Start</th>
										<th class='text-center'>End</th>
										<th class='text-center'>Instructor</th>
										<th class='text-center'>Location</th>
									</tr>";
									
								// output data of each row
						/*		while($row = $result->fetch_assoc()) {
									echo "<tr>
										<td class='text-center'>".$row["course_CRN"]."</td>
										<td class='text-center'>".$row["day"]."</td>
										<td class='text-center'>".$row["start"]."</td>
										<td class='text-center'>".$row["end"]."</td>
										<td class='text-center'>".$row["instructor"]."</td>
										<td class='text-center'>".$row["location"]."</td>
									</tr>";
								}
						*/		
						/**/		
                                                      //          echo "</br>"
                                                                $del="";
                                                                echo "<div id=\"calendar\" data-events=\"[";
                                                                while($row = $result->fetch_assoc()) {
                                                                   echo $del."{id:'".$row["course_CRN"]."',title:'".$row["location"]."',start:'".$row["start"]."',end:'".$row["end"]."'}"; 
                                                                   $del=","; 
								}
                                                                echo "]\">";		
								echo "</div>";
									
						/**/		
								echo "</table>";
								} else {
									echo "0 results";
								}
						}			
						$conn->close();

                                 			?>
			   </div>
		
	   </div> <!-- Flexslider End-->

   <!-- footer
   ================================================== -->
   <footer>

      <div class="row">

         <div class="twelve columns">

            <ul class="footer-nav">
					<li><a href="index.html">Home.</a></li>
              	<li><a href="search.html">Search Courses</a></li>
              	<li><a href="#">About</a></li>
              	<li><a href="#">Contact</a></li>
               <li><a href="#">Features.</a></li>
			   </ul>

            <ul class="footer-social">
               <li><a href="#"><i class="fa fa-facebook"></i></a></li>
               <li><a href="#"><i class="fa fa-twitter"></i></a></li>
               <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
               <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
               <li><a href="#"><i class="fa fa-skype"></i></a></li>
               <li><a href="#"><i class="fa fa-rss"></i></a></li>
            </ul>

            <ul class="copyright">
               <li>Copyright &copy; 2016 CSC</li>
              
            </ul>

         </div>

         <div id="go-top" style="display: block;"><a title="Back to Top" href="#">Go To Top</a></div>

      </div>

   </footer> <!-- Footer End-->

   <!-- Java Script
   ================================================== -->
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
   <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script>
   <script type="text/javascript" src="js/jquery-migrate-1.2.1.min.js"></script>

   <script src="js/jquery.flexslider.js"></script>
   <script src="js/doubletaptogo.js"></script>
   <script src="js/init.js"></script>

</body>

</html>