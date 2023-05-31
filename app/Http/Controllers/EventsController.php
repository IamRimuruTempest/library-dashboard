<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class EventsController extends Controller
{
    public function index() {
        return DB::connection('mysql')
            ->table('school_events')
            ->get();
    }

    public function store(Request $request) {

       
         $image = null;

          if($request->filled('image')) {
            $image = null;
        } else {
             if ($file = $request->file('image')) {
                $destinationPath = 'events/';
                $image = $request->title . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }

          return DB::connection('mysql')
        ->table('school_events')
        ->insert([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $request->status,
            'description' => $request->description,
            'image' => $image,
            'created_at' => new \Datetime
        ]);
    }

    public function update(Request $request) {
        $image = null;

        $imagePath = public_path('events\\' . $request->image);

         if (File::exists($imagePath)) {
            $image = $request->image;
        } else {
            if ($file = $request->file('image')) {
                $destinationPath = 'events/';
                $image = $request->title . "." .$file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }

        return DB::connection('mysql')
        ->table('school_events')
        ->where('id', '=', $request->id)
        ->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'location' => $request->location,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $request->status,
            'description' => $request->description,
            'image' => $image,
            'updated_at' => new \Datetime
        ]);
    }


    public function destroy(Request $request) {
          $imagePath = public_path('events\\' . $request->image);
       
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
       return DB::connection('mysql')
            ->table('school_events')
            ->where('id', '=', $request->id)
            ->delete();
    }
}
