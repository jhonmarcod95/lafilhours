<?php
use App\Methods\Func;
?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body onload="exportXl()">
  <table id="table_wrapper" border="1" id="table_wrapper" style="font-size:12px;" class="table table-bordered" cellspacing="0">
    <thead>
      <tr>
        <th>Name</th>
        @foreach($dates as $date)
        <th>{{ func::toSimpleDate($date) }}</th>
        @endforeach
        <th>Rendered</th>
        <th>Lates</th>
        <th>Absent</th>
        <th>OT</th>
        <th>Days Present</th>
        <th>Authorized Rendered</th>
      </tr>
    </thead>

    <tbody>
       @foreach($employees as $employee)
        @if($employee->employee_name != null)
          <tr>
          
            <!--******************* TIME TABLE DISPLAY ************************* -->
            <?php

            $log_time = "";
            $absents = array();
            $lates = array();
            $overtime = array();
            $hrs_render = array();
            $authorized_render = array();
            $authorized_late = array();
            $authorized_ot = array();
            $schedule_id = "";
            $days = 0;
            $i = 1;

            foreach($dates as $date)
            {
              $chk_times = Func::getFilter($logs, $date, $employee->employee_name); #GET TIME

              #DISPLAY TIME
              foreach ($chk_times as $chk_time) {
                $tag = Func::attendance_msg($chk_time);
                $ot = $chk_time->ot;
                $ot_message = Func::text_message('Overtime', $ot);
                $late_message = Func::text_message('Late', Func::get_late($chk_time));

                
                #AVOID new line in schedule if no remarks -------------------------------------------------------------------
                $in_block = "";
                if(!empty($chk_time->remarks)){
                  $in_block = "d-inline-block";
                }
                #------------------------------------------------------------------------------------------------------------

                $log_time .= "
                <td>
                    $chk_time->time_in <br>
                    $chk_time->time_out <br>
                    $chk_time->render <br>

                    <span class=\"text-muted\">
                      $late_message
                      $ot_message 
                    </span>

                  <div><span class=\"text-muted\">$chk_time->approved_by</span></div> 
                  <div><span class=\"text-muted\">$chk_time->ap_approved_by</span></div> 
                  <div>$tag</div>  
                    <strong>
                      $chk_time->remarks $chk_time->updated_by
                      <br>
                      $chk_time->second_remarks $chk_time->second_updated_by
                    </strong>
                </td> ";  
              }

              #COUNT TOTALS
              $lates[] = $chk_time->late; #Lates
              $overtime[] = $ot; # Overtime
              $hrs_render[] = $chk_time->render; # Hrs. Render

              #Authorized render services ------------------------------------------
              if(!empty($chk_time->approved_by)) 
              {
                $authorized_render[] = $chk_time->render;
                $authorized_late[] = $chk_time->late;
                $authorized_ot[] = $ot;

              }
              #---------------------------------------------------------------------
              

              if(empty($chk_time->time_in) && 
                 empty($chk_time->time_out)) $absents[] = $date; # Absent Count
              else
                $days++;

              $i++;
            }
            
            $overtime = Func::sum_overtime($overtime);
            $hrs_render = Func::sum_time($hrs_render);
            $late_count = Func::count_late($lates);
            $lates = Func::sum_minute($lates);
            $absents = Func::count_absent($absents, $employee->days);

            $authorized_render = Func::sum_time($authorized_render);
            $authorized_late = Func::sum_minute($authorized_late);
            $authorized_ot = Func::sum_time($authorized_ot);

            $authorized_message = Func::authorized_message($authorized_render, $authorized_late, $authorized_ot);
            ?>
            <!-- ***************************************************************** -->

            <td>{{ $employee->employee_name }}

            <?php echo $log_time; ?>
            
            <td>{{ $hrs_render }}
            <td>{{ $lates }}
            <td>{{ $absents }}
            <td>{{ $overtime }}
            <td>{{ $days }}
            <td style="width:200px"><?php echo $authorized_message; ?>
          </tr>
        @endif
      @endforeach
    </tbody>
  </table>

</body>
</html>

@include('layouts.jscript')


<button id="" onclick="exportXl()">Click</button>

<script type="text/javascript">

var downloadTimer = setInterval(function(){
    window.close();
}, 500);


function exportXl()
{
  //getting data from our table
  var data_type = 'data:application/ms-excel';
  var table_div = document.getElementById('table_wrapper');
  var table_html = table_div.outerHTML.replace(/ /g, '%20');

  var a = document.createElement('a');
  a.href = data_type + ', ' + table_html;
  a.download = 'Attendance' + Math.floor((Math.random() * 99999) + 10000) + '.xls';
  a.click();
  
}
</script>