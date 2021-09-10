<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
{
    public function index() {
        return response()->json(Student::all());
    }

    public function edit($user_id){

        $student = Student::find($user_id);
        if($student) return response()->json(['success' => true, 'result' =>  $student], 200);
        else return response()->json(['success' => false, 'result' => 'Company not found!'], 400);
    }

    public function store(Request $request){
      //dd($request);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',

        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'result' => $validator->errors()->first()], 404);
        $student = new Student($request->all());
        $documents = [];
        if($request->files){
            foreach($request->files as $file){


                $original_filename = $file->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/students/' . date('Y-m-d') . '/documents';
                $image = uniqid() . mt_rand(1, 100000).$file_ext;
                $file->move($destination_path, $image);
                $documents[] =  $destination_path.'/'.$image;
            }
            $student->documents = $documents;
        }




        $student->save();


        if($student) return response()->json(['success' => true, 'result' => $student], 200);
        else return response()->json(['success' => false, 'result' => 'Save error.'], 400);
    }

    public function delete($user_id){

        $student = Student::find($user_id);

        if($student) {
            $username = $student->first_name.' '.$student->last_name;
            $student->delete();
            return response()->json(['success' => true, 'result' => ["name" => $username]], 200);
        } else return response()->json(['success' => false, 'result' => 'Student not found.'], 400);
    }

    public function update($user_id, Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',

        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'result' => $validator->errors()->first()], 404);

        $student = Student::find($user_id);
        $student->fill($request->all());
        $documents = [];
        if($request->files){
            foreach($request->files as $file){


                $original_filename = $file->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './upload/students/'. date('Y-m-d') . '/documents';
                $image = uniqid() . mt_rand(1, 100000).$file_ext;
                $file->move($destination_path, $image);
                $documents[] =  $destination_path.'/'.$image;
            }
            $student->documents = $documents;
        }




        $student->save();



        if($student) return response()->json(['success' => true, 'result' => $student], 200);
        else return response()->json(['success' => false, 'result' => 'Save error.'], 400);

    }
}
