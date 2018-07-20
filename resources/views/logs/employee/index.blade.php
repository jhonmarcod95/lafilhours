@extends('layouts.app')

@section('content')
<div class="content">

  <div class="container-fluid">


    <!-- Breadcrumbs -->
    <div class="col-xl-3 col-sm-6 mb-3">
    </div>

    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
      </li>
      <li class="breadcrumb-item active">My Dashboard</li>
    </ol>


    <!-- Example Tables Card -->
    <div class="card mb-3">
      <div class="card-header">
        <i class="fa fa-table"></i>
        Log Records
      </div>
      <div class="card-body">
        <div class="table-responsive">

            <!-- DATE FILTERS -->
            <form method="GET">
              <div class="row">
                
                <div class="col-xl-3 col-sm-6 mb-3">
                  <label>
                    Date From : 
                  </label>
                  <input class="form-control input-sm" type="date" name="from" value="{{ $from_date->format('Y-m-d') }}" >
                </div>

                <div class="col-xl-3 col-sm-6 mb-3">
                  <label>
                    Date To : 
                  </label>
                  <input class="form-control input-sm" type="date" name="to" value="{{ $to_date->format('Y-m-d') }}">
                </div>
                <div class="col-xl-3 col-sm-6 mb-3">                
                  <label>
                    &nbsp;
                  </label>
                  <div class="form-inline">
                    <span class="input-group-btn">
                      <input type="submit" value="Filter" class="btn btn-primary">
                    </span>
                  </div>
                </div>
                
                <!--
                <div class="col-xl-3 col-sm-6 mb-3">                
                  <label>
                    &nbsp;
                  </label>
                  <div class="form-inline">
                    <button class="btn btn-primary">
                      Filter
                    </button>
                  </div>
                </div>
                -->
              </div>
            </form>

          <div class="row-2">
              @include('logs.employee.log')
          </div>
        </div>
      </div>
      <div class="card-footer small text-muted">
        Updated yesterday at 11:59 PM
      </div>
    </div>

  </div>
  <!-- /.container-fluid -->

</div>
<!-- /.content-wrapper -->

@endsection


