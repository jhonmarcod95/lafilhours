@extends('layouts.app')


@section('content')
<div class="content">

  <div class="container-fluid">

    <!-- Breadcrumbs -->
    <div class="col-xl-3 col-sm-6 mb-3">
    </div>

    <!-- Example Tables Card -->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i>
        Log Records
      </div>
      <div class="card-body">
        <div class="table-responsive">

            <!-- DATE FILTERS -->
            {{ Form::open(['method' => 'GET']) }}
            <!-- <form method="GET"> -->
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

                <!-- DEVICES -->
                <div class="col-xl-2 col-sm-6 mb-2">              
                  <label>
                    Biometrics Location : 
                  </label>
                  {{ Form::select('device', $devices, '', ['class' => 'form-control input-sm']) }}
                </div>

                <!-- Rate Type -->
                <div class="col-xl-2 col-sm-6 mb-2">
                  <label>
                    Rate Type :
                  </label>
                  {{ Form::select('rateType', $rateTypes, '', ['class' => 'form-control input-sm']) }}
                </div>

                <!-- <div class="col-xl-2 col-sm-6 mb-2">
                  <label>
                    Status : 
                  </label>
                  {{ Form::select('status', [
                    '1' => 'All',
                    '2' => 'Late',
                    '3' => 'Overtime'
                    ], '', ['class' => 'form-control input-sm']) }}
                </div> -->

                <div class="col-xl-3 col-sm-6 mb-3">                
                  <label>
                    &nbsp;
                  </label>
                  <div class="form-inline">
                    {{ Form::text('search', '', ['class' => 'form-control input-sm', 'placeholder' => 'Search For...']) }}
 
                    <span class="input-group-btn">
                      {{ Form::submit('Filter', ['class' => 'btn btn-primary']) }}
                    </span>
                  </div>
                </div>
              </div>
            {{ Form::close() }}
        </div>
      </div>
    </div>
    
    @include('logs.admin.log')

  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

@endsection
