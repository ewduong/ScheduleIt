
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Schedule IT</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">

    <!-- Custom Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" type="text/css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="css/animate.min.css" type="text/css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/creative.css" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel='stylesheet' href='../lib/cupertino/jquery-ui.min.css' />
    <link href='fullcalendar.css' rel='stylesheet' />
    <link href='fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='lib/moment.min.js'></script>
    <script src='lib/jquery.min.js'></script>
    <script src='fullcalendar.min.js'></script>

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
          allDaySlot: false,
          editable: false,
          eventLimit: true,
          eventColor: '#378006',
          events: [
				{
          id: "Event1",
          title:"my new event",
          start: '2016-02-14T18:00:00+00:00',
          end: '2016-02-14T19:0:00+00:00',
          backgroundColor: "red"
				}
			]
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
      <style>

      	body {
      		margin: 40px 10px;
      		padding: 0;
      		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
      		font-size: 14px;
      	}

      	#calendar {
      		max-width: 700px;
      		margin: 0 auto;
      	}

        </style>
</head>

<body id="page-top">

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button> -->
                <a class="navbar-brand page-scroll" href="#page-top" color="red">Schedule IT</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#about">About</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="search.php">Search Courses</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">Portfolio</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header>
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
 /*              while($row = $result->fetch_assoc()) {
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
        echo $del."{id:'".$row["course_CRN"]."',title:'".$row["location"]."',start:'".$row[     "start"]."',end:'".$row["end"]."'}";
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
      <button style="background-color:transparent" id='AddanEvent' onclick="addEvents([myEvent,myEvent2])">ADD AN EVENT</button>
      <button style="background-color:transparent" id='removeEvent' onclick="removeEvent([myEvent,myEvent2])">REMOVE EVENT</button>
    </header>

</body>
</html>
