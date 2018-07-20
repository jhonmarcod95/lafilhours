<?php
use Carbon\Carbon;
use App\Methods\Func;
?>

<!DOCTYPE html>
<html lang="en" dir="LTR" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Summary Report</title>

    <link href="{{ asset('css/summary.css') }}" rel="stylesheet">

</head>
<body class="reports">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <header>
                    <div class="header-left">
                        <div class="report-wrap">
                            <div class="report-logo" style="background: none;">
                                <img src="http://194.135.89.248/assets/logo.php?id=0&type=logo&t=f1510131490" class="logo" alt="Logo">
                            </div>
                        </div>
                        <div class="report-curve">
                        </div>
                    </div>
                    <div class="header-right">
                    </div>
                </header>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="report-bars"></div>
                            Summary Report ({{ request('from') . ' - ' . request('to') }})
                        </div>
                        <div class="panel-body">
                            <table style="margin-bottom: 0;" class="table">
                                <tr>
                                    <td>
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Employee Name : </th>
                                                    <td>{{ $employee_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Rendered Hours : </th>
                                                    <td>{{ $hrs_render }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Lates : </th>
                                                    <td>{{ $lates }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Overtime : </th>
                                                    <td>{{ $overtime }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td>
                                        <table class="table">
                                            <tbody>
                                                
                                                <tr>
                                                    <th>No. of Day(s) Late : </th>
                                                    <td>{{ $late_count }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No. of Day(s) Absent : </th>
                                                    <td>{{ $absents }}</td>
                                                </tr>
                                                <tr>
                                                    <th>No. of Day(s) Present : </th>
                                                    <td>{{ $present }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                
                        <div class="col-xl-2 col-sm-6 mb-2">
                            Generated At : {{ Func::toSimple12Date(Carbon::now()) }}
                        </div>
                        <div class="col-xl-2 col-sm-6 mb-2">
                            Prepared By : {{ Auth::user()->name }}
                        </div>
                    </div>
                    
                </div>
        </div>
    </div>
</body>
</html>