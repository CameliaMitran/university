<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function getSettings()
    {

        $result = UserInterface::select('theme', 'header_c', 'sidebar_c')->where('user_id', Auth::user()->id)->first();
        if ($result) return response()->json(['success' => true, 'result' => $result], 200);
        else return response()->json(['success' => false, 'result' => 'No data found for this user.'], 404);
    }

    public function updateSettings(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'theme' => 'required|string',
            'header_c' => 'required|string',
            'sidebar_c' => 'required|string',
        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'result' => $validator->errors()->first()], 400);

        $theme = $request->input('theme');
        $header_c = $request->input('header_c');
        $sidebar_c = $request->input('sidebar_c');

        $ui = UserInterface::updateOrCreate(
            ['user_id' => Auth::user()->id],
            ['theme' => $theme, 'header_c' => $header_c, 'sidebar_c' => $sidebar_c]
        );

        if ($ui) return response()->json(['success' => true, 'result' => $ui], 200);
        else return response()->json(['success' => false, 'result' => 'Update or save error.'], 422);
    }

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
}
