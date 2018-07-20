<!DOCTYPE html>
<html lang="en">

<head>
  @include('layouts.head')
  <link rel="stylesheet" type="text/css" href="{{ asset('datatableCustom/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('datatableCustom/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('datatableCustom/fixedColumns.bootstrap4.min.css') }}">

  <script type="text/javascript" language="javascript" src="{{ asset('datatableCustom/jquery-3.3.1.js') }}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('datatableCustom/jquery.dataTables.min.js') }}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('datatableCustom/dataTables.bootstrap4.min.js') }}"></script>
  <script type="text/javascript" language="javascript" src="{{ asset('datatableCustom/dataTables.fixedColumns.min.js') }}"></script>

  <script type="text/javascript" class="init">
      $(document).ready(function() {

          var table = $('#example').DataTable( {
              scrollY:        "500px",
              scrollX:        true,
              scrollCollapse: true,
              paging:         false,
              searching:      false,
              ordering:       false,
              fixedColumns:   {
                  leftColumns: 1
              }
          } );

      } );
  </script>
</head>

<body class="fixed-nav sticky-footer" id="page-top">

  @include('layouts.nav')


  <div class="content">

    <!-- Breadcrumbs -->
    <div class="col-xl-3 col-sm-6 mb-3">
    </div>

    <div class="container-fluid">
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-clock-o"></i>
          Attendance
        </div>


        <div class="card-body">
          <div class="">

            <!-- DATE FILTERS -->
            {{ Form::open(['method' => 'GET']) }}
            <div class="row">

              <div class="col-xl-2 col-sm-6 mb-2">
                <label>
                  Date From :
                </label>
                {{ Form::date('from', date('Y-m-d'), ['class' => 'form-control input-sm', 'max' => date('Y-m-d')]) }}
              </div>

              <div class="col-xl-2 col-sm-6 mb-2">
                <label>
                  Date To :
                </label>
                {{ Form::date('to', date('Y-m-d'), ['class' => 'form-control input-sm', 'max' => date('Y-m-d')]) }}
              </div>

              <div class="col-xl-2 col-sm-6 mb-2">
                <label>
                  Start Time :
                </label>
                {{ Form::time('start_time', '00:00', ['class' => 'form-control input-sm']) }}
              </div>

              <div class="col-xl-2 col-sm-6 mb-2">
                <label>
                  End Time :
                </label>
                {{ Form::time('end_time', '23:59', ['class' => 'form-control input-sm']) }}
              </div>

              <!-- DEPARTMENT -->
              @if($dept_assign == null)
                <div class="col-xl-2 col-sm-6 mb-2">
                  <label>
                    Department :
                  </label>
                  {{ Form::select('department', $departments, '', ['class' => 'form-control input-sm']) }}
                </div>
            @endif


            <!-- DEVICES -->
              <div class="col-xl-2 col-sm-6 mb-2">
                <label>
                  Biometrics Location :
                </label>
                {{ Form::select('device', $devices, '', ['class' => 'form-control input-sm']) }}
              </div>

              <div class="col-xl-2 col-sm-6 mb-2">
                <label>
                  Rate Type :
                </label>
                {{ Form::select('rateType', $rateTypes, '', ['class' => 'form-control input-sm']) }}
              </div>


              <div class="col-xl-3 col-sm-6 mb-3">
                <label>
                  &nbsp;
                </label>
                <div class="form-inline">

                  {{ Form::text('search', '', ['class' => 'form-control input-sm', 'placeholder' => 'Search For...']) }}
                  <span class="input-group-btn">
                    {{ Form::submit('Filter', ['class' => 'btn btn-default']) }}
                  </span>
                </div>
              </div>

              <div class="col-xl-3 col-sm-6 mb-3">
                <label>
                  &nbsp;
                </label>
                <div class="form-inline">
                  <a class="btn btn-primary" href="{{ url('/attendance/export?' . $_SERVER['QUERY_STRING']) }}"
                     target="_blank">Export to xls</a>
                  <!-- <button class="btn btn-primary" id="btnExport">Export to xls</button> -->
                </div>
              </div>
            </div>
            {{ Form::close() }}
          </div>
          <div class="col-xl-6 col-sm-8 mb-6">
            @include('layouts.errors')
          </div>
        </div>
      </div>
    </div>


    <div class="container-fluid">
        <div class="row-2">
            @include('reports.attendance.record')
        </div>
    </div>

  </div>


  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
  </a>


  <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('js/popper/popper.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script>



</body>

</html>

