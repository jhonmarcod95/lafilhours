<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use App\Employee;
use DB;
use Auth;
use Carbon\Carbon;
use App\Methods\Func;
use App\Schedule;
use Session;
use DateTime;

class ScheduleController extends Controller
{

	
	public function create($employee)
	{

		$days = rtrim(
					request('Mon') . ',' . 
					request('Tue') . ',' . 
					request('Wed') . ',' . 
					request('Thurs') . ',' . 
					request('Fri') . ',' . 
					request('Sat') . ',' . 
					request('Sun'), ','
				);


		#SCHEDULE VALIDATIONS --------------------------------------------------------------------------------
		$this->validate(request(), [
			'start_date' => 'required|before_or_equal:end_date',
			'end_date' => 'required'
		]);

		if(empty(str_replace(",", '', $days))){
			Session::flash('flash_message','Select working days');
			Session::flash('flash_font', 'alert alert-danger');
			return redirect('employees/' . $employee);
		}

		$schedule = DB::connection('sqlsrv')->select(
			"EXEC P_DATE_EXIST @date_from = '" . request('start_date') . "', @date_to = '" . request('end_date') . "', @emp_id = '$employee', @days = '" . str_replace(",", '', $days) . "'"
		);

		if(count($schedule)){
			Session::flash('flash_message','Selected schedule has a conflict in previous schedule (see. ' . $schedule[0]->start_date . ' - ' . $schedule[0]->end_date . ')');
			Session::flash('flash_font', 'alert alert-danger');
			return redirect('employees/' . $employee);
		}		
		#-----------------------------------------------------------------------------------------------------

		
		Schedule::create([
			'Userid' => $employee, 
			'start_time' => request('start_time'),
			'end_time' => request('end_time'), 
			'days' => Func::sched_days($days), 
			'start_date' => request('start_date'),
			'end_date' => request('end_date'), 
			'remarks' => request('remarks'),
			'created_at' => Carbon::now(),
			'user_id' => Auth::user()->id
		]);

		Session::flash('flash_message','Schedule successfully added.');
		Session::flash('flash_font', 'alert alert-success');

		return redirect(strtok('employees/' . $employee, '?'));
	}


	public function createAll(){
		$workers = request('checkitems');
		$workingdays = request('weekDays');

		#COMMON VALIDATION --------------------------------------------
		if(!count($workers)){
			Func::flash_message('Select a worker', 'alert alert-danger');
			return redirect('employees#AddSched/');
		}

		if(!count($workingdays)){
			Func::flash_message('Select a working days', 'alert alert-danger');
			return redirect('employees#AddSched/');
		}

		$this->validate(request(), [
			'start_date' => 'required|before_or_equal:end_date',
			'end_date' => 'required'
		]);
		#--------------------------------------------------------------


		$days = Func::workingDays($workingdays);


		#SCHEDULE VALIDATIONS ------------------------------------------------------------------------------
		$error_message = "";
		$schedules = array();
		foreach ($workers as $worker) {
			$schedule = DB::connection('sqlsrv')->select(
				"EXEC P_DATE_EXIST @date_from = '" . request('start_date') . "', @date_to = '" . request('end_date') . "', @emp_id = '$worker', @days = '" . str_replace(",", '', $days) . "'"
			);

			if(count($schedule)){
				$error_message .=  Employee::fullname($worker) . ' has conflict schedule (see. ' . $schedule[0]->start_date . ' - ' . $schedule[0]->end_date . ') <br><br>';
			}
			else{
				$schedules[] = $worker;
			}		
		}
		#-----------------------------------------------------------------------------------------------------

		if(!count($schedules)){
			Func::flash_message($error_message, 'alert alert-danger');
		}
		else
		{
			foreach ($schedules as $schedule) {
				Schedule::create([
					'Userid' => $schedule, 
					'start_time' => request('start_time'),
					'end_time' => request('end_time'), 
					'days' => $days, 
					'start_date' => request('start_date'),
					'end_date' => request('end_date'), 
					'remarks' => request('remarks'),
					'created_at' => Carbon::now(),
					'user_id' => Auth::user()->id
				]);
			}
			Func::flash_message('Schedule successfully added', 'alert alert-success');	
		}

		return redirect('employees#AddSched/');
	}


	public function edit($employee, $id)
	{
		if(Auth::check())
		{
			
			$schedule = Schedule::where('Userid', $employee)
				->where('id', $id)
				->first();

			$employees = DB::connection('sqlsrv')->select("EXEC P_SCHEDULE @emp_id = '$employee'");

			return view('employees.edit', compact(
				'employee',
				'schedule',
				'employees'
			));
		}
		else
		{
			return redirect()->route('login');
		}   
	}


	public function update($employee, $id)
	{
		$workingdays = request('weekDays');
		

		#SCHEDULE VALIDATIONS --------------------------------------------------------------------------------
		if(!count($workingdays)){
			Func::flash_message('Select a working days', 'alert alert-danger');
			return redirect('employees/edit/' . $employee . '/' . $id);
		}

		$this->validate(request(), [
			'start_date' => 'required|before_or_equal:end_date',
			'end_date' => 'required'
		]);

		$days = Func::workingDays($workingdays);

		$schedule = DB::connection('sqlsrv')->select(
			"EXEC P_DATE_EXIST @date_from = '" . request('start_date') . "', @date_to = '" . request('end_date') . "', @emp_id = '$employee', @days = '" . str_replace(",", '', $days) . "'"
		);

		#avoid equal to selected date
		$schedule = collect($schedule);
		$schedule = $schedule->where('id', '!=', $id);	
		// dd("EXEC P_DATE_EXIST @date_from = '" . request('start_date') . "', @date_to = '" . request('end_date') . "', @emp_id = '$employee', @days = '" . str_replace(",", '', $days) . "'");


		if(count($schedule)){
			Func::flash_message('Selected schedule has a conflict in previous schedule (see. ' . $schedule[0]->start_date . ' - ' . $schedule[0]->end_date . ')', 'alert alert-danger');	
			return redirect('employees/edit/' . $employee . '/' . $id);
		}		
		#-----------------------------------------------------------------------------------------------------

		

		$schedule = Schedule::find($id);
		$schedule->start_date = request('start_date');
		$schedule->end_date = request('end_date');
		$schedule->start_time = request('start_time');
		$schedule->end_time = request('end_time');
		$schedule->days = $days;
		$schedule->remarks = request('remarks');
		$schedule->updated_at = Carbon::now();
		$schedule->updated_by =Auth::user()->id;
		$schedule->save();

		Func::flash_message('Schedule successfully updated', 'alert alert-success');	
		return redirect('employees/edit/' . $employee . '/' . $id);
	}
	
	public function delete($employee, $id)
	{
	   $schedule = Schedule::find($id);
	   $schedule->delete();   

		Session::flash('flash_message','Schedule successfully deleted.');
		Session::flash('flash_font', 'alert alert-success');

	   return redirect('employees/' . $employee);
	}



	public function show()
	{
		
	}
}