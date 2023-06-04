<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    public function index() {
       
         return DB::connection('mysql')
            ->table('users')
            ->get();
    }

    public function store(Request $request) {
        $image = null;


        if($request->filled('image')) {
            $image = null;
        } else {
             if ($file = $request->file('image')) {
                $destinationPath = 'admins/';
                $image = $request->employee_id . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }
        

        return DB::connection('mysql')
        ->table('users')
        ->insert([
            'employee_id' => $request->employee_id,
            'name' => $request->full_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'email' => $request->email,
            'password' => $request->password,
            'image' => $image,
            'created_at' => new \Datetime
        ]);
    }

    public function update(Request $request) {
        $image = null;

        $imagePath = public_path('admins\\' . $request->image);

        if($request->filled('image')) {
            $image = null;
        } else {
             if ($file = $request->file('image')) {
                $destinationPath = 'admins/';
                $image = $request->employee_id . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }
        

        return DB::connection('mysql')
        ->table('users')
        ->where('id', '=', $request->id)
        ->update([
            'employee_id' => $request->employee_id,
            'name' => $request->full_name,
            'gender' => $request->gender,
            'age' => $request->age,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
            'email' => $request->email,
            'password' => $request->password,
            'image' => $image,
            'updated_at' => new \Datetime
        ]);
    }


         public function destroy(Request $request) {
          $imagePath = public_path('admins\\' . $request->image);
       
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
       return DB::connection('mysql')
            ->table('users')
            ->where('id', '=', $request->id)
            ->delete();
    }
}
