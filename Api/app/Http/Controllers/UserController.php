<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    public function getImage()
    {

        $user_id = Auth::user()->id;

        $path = (glob('upload/user/' . $user_id . '/avatar/avatar.*'));
        $path = ($path[0]) ?? '/nothing';

        $file = public_path($path);

        if (!file_exists($file)) {

            return response()->json(['success' => false, 'result' => 'Avatar not found.'], 400);
        }

        return response()->json(['success' => true, 'result' => $path], 200);
    }

    public function postImage(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'image'  => 'required|mimes:png,jpg,jpeg|max:2048',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['success' => false, 'result' => $validator->errors()], 401);
        }

        if ($file = $request->file('image')) {

            $user_id = Auth::user()->id;
            $original_filename = $file->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = './upload/user/' . $user_id . '/avatar';
            $image = 'avatar.' . $file_ext;

            if ($file->move($destination_path, $image)) {
                return response()->json(["success" => true, "result" => "File successfully uploaded"], 200);
            }
        }

        return response()->json(["success" => false, "result" => "Error saving file."], 400);
    }

    public function delete($user_id){

        $user = User::find($user_id);

        if($user) {
            $username = $user->username;
            $user->delete();
            return response()->json(['success' => true, 'result' => ["name" => $username]], 200);
        } else return response()->json(['success' => false, 'result' => 'User not found.'], 400);
    }

    public function update($user_id, Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'result' => $validator->errors()->first()], 404);

        $user = User::find($user_id);
        $user->fill($request->all());
        if($request->input('password')){
        $password = $request->input('password');
        $user->password = app('hash')->make($password);
        }
        $user->save();



        if($user) return response()->json(['success' => true, 'result' => $user], 200);
        else return response()->json(['success' => false, 'result' => 'Save error.'], 400);

    }

    public function edit($user_id){

        $user = User::find($user_id);
        if($user) return response()->json(['success' => true, 'result' =>  $user], 200);
        else return response()->json(['success' => false, 'result' => 'Company not found!'], 400);

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',

        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'result' => $validator->errors()->first()], 404);
        $user = new User($request->all());
        $password = $request->input('password');
        $user->password = app('hash')->make($password);
        $user->role = 'student';
        $user->save();


        if($user) return response()->json(['success' => true, 'result' => $user], 200);
        else return response()->json(['success' => false, 'result' => 'Save error.'], 400);
    }

}
