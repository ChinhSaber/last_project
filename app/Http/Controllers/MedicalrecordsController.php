<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speciallist;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\Medicalrecords;
use Session;
use DB;


class MedicalrecordsController extends Controller
{
	public function Medicalrecords_index(Request $rq){
		$speciallist = Speciallist::get();
		$doctor = Doctor::get();
		$patient = Patient::get();
		$medicine = Medicine::get();
		$search = $rq->search;
		$array_list = Medicalrecords::latest()->join('patient','medicalrecords.patient_id','patient.patient_id')
                                              ->where('patient.last_name','like',"%$search%")
			                                  ->paginate(10);
		return view('medicalrecords.index',[
			'speciallist'=> $speciallist,
			'doctor'=> $doctor,
			'patient'=> $patient,
			'medicine' => $medicine,
			'array_list' => $array_list,
			'search'=> $search,
		]);
	}
	public function Medicalrecords_doctor(){
		$speciallist =Speciallist::get();
		$doctor = Doctor::get();
		$patient = Patient::get();
		$medicine = Medicine::get();
		$array_list = Medicalrecords::where('doctor_id',Session::get('doctor_id'))->get();
		return view('medicalrecords.index',[
			'speciallist'=> $speciallist,
			'doctor'=> $doctor,
			'patient'=> $patient,
			'medicine' => $medicine,
			'array_list' => $array_list,
		]);
	}
	public function view_update($medicalrecords_id){
		$speciallist =Speciallist::get();
		$doctor = Doctor::get();
		$patient = Patient::get();
		$medicine = Medicine::get();
		$medicalrecords = Medicalrecords::find($medicalrecords_id);
		return view('medicalrecords.update',[
			'speciallist'=> $speciallist,
			'doctor'=> $doctor,
			'patient'=> $patient,
			'medicine' => $medicine,
			'medicalrecords' => $medicalrecords,
		]);
	}
	public function being_treated(Request $rq){
		$speciallist =Speciallist::get();
		$doctor = Doctor::get();
		$patient = Patient::get();
		$medicine = Medicine::get();
		$search = $rq->search;
		$array_list = Medicalrecords::latest()->join('patient','medicalrecords.patient_id','patient.patient_id')
                                              ->where('patient.last_name','like',"%$search%")
                                              ->where('treatment','0')
                                              ->paginate(10);
		return view('medicalrecords.being_treated',[
			'speciallist'=> $speciallist,
			'doctor'=> $doctor,
			'patient'=> $patient,
			'medicine' => $medicine,
			'array_list' => $array_list,
			'search'=> $search,
		]);
	}
    public function discharged(Request $rq){
        $speciallist =Speciallist::get();
        $doctor = Doctor::get();
        $patient = Patient::get();
        $medicine = Medicine::get();
        $search = $rq->search;
        $array_list = Medicalrecords::orderBy('updated_at', 'desc')->join('patient','medicalrecords.patient_id','patient.patient_id')
            ->where('patient.last_name','like',"%$search%")
            ->where('treatment','1')
            ->paginate(10);
        return view('medicalrecords.discharged',[
            'speciallist'=> $speciallist,
            'doctor'=> $doctor,
            'patient'=> $patient,
            'medicine' => $medicine,
            'array_list' => $array_list,
            'search'=> $search,
        ]);
    }
    public function discharge(Request $rq){
        $medicalrecords_id = $rq->medicalrecords_id;
        $price = $rq->price;
        Medicalrecords::where('medicalrecords_id',$medicalrecords_id)->update([
            'treatment' => 1,
            'price' => $price
        ]);
        return redirect()->back();
    }
	public function change_room(Request $rq){
		$medicalrecords_id = $rq->medicalrecords_id;
		$room = $rq->room;
		Medicalrecords::where('medicalrecords_id',$medicalrecords_id)->update([
			'room' => $room
		]);
		return redirect()->back();
	}
	public function massDone(Request $rq){
		$id = $rq->id;
		foreach ($id as $id)
		{
			User::where('id', $id)->update([
				'status' => '1'
			]);
		}
		return redirect()->back();
	}
	public function process_insert(Request $rq){
        Medicalrecords::create($rq->all());
        return redirect()->route('medicalrecords.medicalrecords_index');
    }
	public function process_update(Request $rq){
		$medicalrecords_id = $rq->medicalrecords_id;
		$doctor_id = $rq->doctor_id;
		$speciallist_id = $rq->speciallist_id;
		$room = $rq->room;
		$price = $rq->price;
		$advice = $rq->advice;
		Medicalrecords::where('medicalrecords_id',$medicalrecords_id)->update([
			'doctor_id' => $doctor_id,
			'speciallist_id' => $speciallist_id,
			'room' => $room,
			'price' => $price,
			'advice' => $advice,
        ]);
        return redirect()->back();
    }
    public function hospitalized_view(Request $rq){
        $doctor = Doctor::get();
        $speciallist = Speciallist::get();
        $medicalrecordsData = Medicalrecords::select('patient_id')->get();
        $patient = Patient::whereNotIn('patient_id',$medicalrecordsData)->get();

        return view('medicalrecords.hospitalized_view',[
            'doctor'=> $doctor,
            'speciallist'=> $speciallist,
            'patient' => $patient,
        ]);
    }
    public function hospitalized_insert(Request $rq){
        $doctor_id = $rq->doctor_id;
        $speciallist_id = $rq->speciallist_id;
        $patient_id = $rq->patient_id;
        $room = $rq->room;
        $advice = $rq->advice;
        $status = $rq->status;

        Medicalrecords::create([
            'patient_id' => $patient_id,
            'doctor_id' => $doctor_id,
            'speciallist_id' => $speciallist_id,
            'room' => $room,
            'advice' => $advice,
            'status' => $status,
            'treatment' => '0',
        ]);
        return redirect()->route('medicalrecords.being_treated');
    }
	public function process_assignment_teacher(Request $rq){
		$input = $rq -> all();

		// dd($input);
		Assignment::create($input);

    	return redirect()->route('assignment.view_assignment_teacher');

	}

	public function assignment_class(){
		$courses =Course::get();
		$classs=Classs::get();
		$subjects=Subject::get();
		$teachers=Teacher::get();

		return view('assignment.assigment_class',[
			'courses'=> $courses,
			'classs'=> $classs,
			'subjects' => $subjects,
			'teachers' => $teachers
		]);

	}

	public function view_assignment_teacher()
	{
		$array_classes=Classs::get();
		$array_disciplines=Discipline::get();
		return view('assignment.view_assignment_teacher',[
				'array_classes'=> $array_classes,
				'array_disciplines'=> $array_disciplines,

			]);
	}


}
