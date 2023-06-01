<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AccountsController extends Controller
{
    public function index() {
          return DB::connection('mysql')
            ->table('accounts')
            ->get();
    }

    public function store(Request $request) {
       $image = null;
        

        $suffix = $request->suffix;
        if($suffix == 'null' || $suffix == '') {
            $suffix = '';
        } 

          if($request->filled('image')) {
            $image = null;
        } else {
             if ($file = $request->file('image')) {
                $destinationPath = 'profiles/';
                $image = $request->student_id . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }
        

        return DB::connection('mysql')
        ->table('accounts')
        ->insert([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $suffix,
            'department' => $request->department,
            'course' => $request->course,
            'year' => $request->year,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'username' => $request->student_id,
            'password' => $request->student_id,
            'image' => $image,
            'created_at' => new \Datetime
        ]);
    }


    public function update(Request $request) {
         $image = null;

        $suffix = $request->suffix;
        if($suffix == 'null' || $suffix == '') {
            $suffix = '';
        } 

        $imagePath = public_path('profiles\\' . $request->image);

         if (File::exists($imagePath)) {
            $image = $request->image;
        } else {
            if ($file = $request->file('image')) {
                $destinationPath = 'profiles/';
                $image = $request->student_id . "." .$file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }

        return DB::connection('mysql')
        ->table('accounts')
        ->where('id', '=', $request->id)
        ->update([
            'student_id' => $request->student_id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'department' => $request->department,
            'course' => $request->course,
            'year' => $request->year,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'password' => $request->password,
            'status' => $request->status,
            'image' => $image,
            'updated_at' => new \Datetime
        ]);
    }

     public function destroy(Request $request) {
          $imagePath = public_path('profiles\\' . $request->image);
       
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
       return DB::connection('mysql')
            ->table('accounts')
            ->where('id', '=', $request->id)
            ->delete();
    }
   
}
