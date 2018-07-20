<?php
use App\Methods\Func;
?>


<form method="POST" action="{{ url('/approval') }}">
  <input type="submit" value="Submit Approval" class="btn btn-default">
  {{ csrf_field() }}
  <!-- overflow: scroll; -->
  <div style="">
  <table id="example" style="font-size:12px; white-space: nowrap; width: 100%" class="table table-bordered" cellspacing="0">
    <thead>
      <tr>
        <th>Name</th>
        <!-- RUNNING DATE DISPLAY -->
        <?php
        $i = 1;
        foreach($dates as $date){
          echo "<th><input type=\"checkbox\" onClick=\"checkAll(this,'chk_$i')\"> &nbsp;" .
                  func::toSimpleDate($date) . "
                </th>";
          $i++;
        }
        ?>
        <!-- ******************** -->
        <th>Rendered</th>
        <th>Lates</th>
        <th>Absent</th>
        <th>OT</th>
        <th>Days Present</th>
        <th class="text-white bg-success">Authorized Rendered</th>
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

                #CHECKBOXES STATE: if approved auto check tag ---------------------------------------------------------------
                $checkbox_state = "";
                // if(!empty($chk_time->approved_by))
                // {
                //     $checkbox_state = "checked";
                // }
                #------------------------------------------------------------------------------------------------------------

                #VALIDATION : hides checkbox if no logs / no-timein / no-timeout / has approved and confirm / no sched ------
                if(empty($chk_time->time_in) || empty($chk_time->time_out) ||
                   $chk_time->time_in == 'No Timein' || $chk_time->time_out == 'No Timeout' || empty($chk_time->schedule_id))
                {
                    $checkbox_input = "";
                }
                // has been approved and confirm
                elseif(!empty($chk_time->approved_by) && !empty($chk_time->ap_approved_by))
                {
                    $checkbox_input = "";
                }
                else
                {
                    $checkbox_input = "<input id=\"chk_$i\" name=\"approval[]\" type=\"checkbox\" value=\"$chk_time->logid\" $checkbox_state>";
                }
                #------------------------------------------------------------------------------------------------------------

                #AVOID new line in schedule if no remarks -------------------------------------------------------------------
                $in_block = "";
                if(!empty($chk_time->remarks)){
                  $in_block = "d-inline-block";
                }
                #------------------------------------------------------------------------------------------------------------

                #REMARKS ID (use for adding a remarks) ----------------------------------------------------------------------
                if(empty($chk_time->logid)){
                  $remarksID = $chk_time->logid_out;
                }
                else{
                  $remarksID = $chk_time->logid;
                }
                #------------------------------------------------------------------------------------------------------------


                $log_time .= "
                <td>
                  <div style=\"font-size:10px;\" class=\"text-muted\">
                    $checkbox_input
                  </div>
                  <a href=\"#\" data-toggle=\"modal\" data-target=\"#ModalRemarks\" onclick=\"SetValue('Logid', '$remarksID')\" class=\"text-dark\" >
                    $chk_time->time_in <br>
                    $chk_time->time_out <br>
                    $chk_time->render <br>

                    <span class=\"text-muted\">
                      $late_message
                      $ot_message
                    </span>
                  </a>

                  <div><span class=\"text-muted\">$chk_time->approved_by</span></div>
                  <div><span class=\"text-muted\">$chk_time->ap_approved_by</span></div>
                  <div>$tag</div>
                  <a href=\"#\" data-toggle=\"modal\" data-target=\"#ModalViewRemarks\" class=\"text-info $in_block text-truncate\" style=\"max-width: 200px;\" onclick=\"ElementWriteText('text_remarks', '$chk_time->remarks $chk_time->updated_by'); SetValue('remarksID', '$remarksID')\" >
                    <strong>
                      $chk_time->remarks $chk_time->updated_by
                    </strong>
                  </a>
                  <br>
                  <a href=\"#\" data-toggle=\"modal\" data-target=\"#ModalViewSecondRemarks\" class=\"text-info $in_block text-truncate\" style=\"max-width: 200px;\" onclick=\"ElementWriteText('second_text_remarks', '$chk_time->second_remarks $chk_time->second_updated_by'); SetValue('secondRemarksID', '$remarksID')\" >
                    <strong>
                      $chk_time->second_remarks $chk_time->second_updated_by
                    </strong>
                  </a>

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


            <!-- ********************* COLUMN ACC. INFO *************************** -->
            <td>
              <a class="btn btn-success btn-sm fa fa-ellipsis-v" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              </a>
              &nbsp;
              {{ $employee->employee_name }}
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a id="summary" class="dropdown-item" href="{{ url('/summary/' . $employee->user_id . '?from=' . request('from') . '&to=' . request('to') . '&start_time=' . request('start_time') . '&end_time=' . request('end_time') . '&hrs_render=' . $hrs_render . '&lates=' . $lates . '&absents=' . $absents . '&overtime=' . $overtime . '&late_count=' . $late_count . '&present=' . $days) }}" target="_blank">Summary Report</a>
                <a class="dropdown-item" href="{{ url('/employees/' . $employee->user_id) }}" target="">Account Info</a>
              </div>
            </td>
            <!-- ****************************************************************** -->

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
  </div>

</form>

@include('reports.attendance.modal.add_remarks')