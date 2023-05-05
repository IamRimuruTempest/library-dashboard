<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class BooksController extends Controller
{
    public function index() {
        return DB::connection('mysql')
            ->table('books')
            ->get();
        // return "HEllo world Dos!";
    }

   

    public function store(Request $request) {
        $image = null;

        $category = $request->category;
        $year = $request->year;
        $isbn = $request->isbn;
        $datePurchased = $request->datePurchased;
        $price = $request->price;
        $shelfNumber = $request->shelfNumber;
        $description = $request->description;

        if($category == 'null' || $category == 'undefined') {
            $category = null;
        } 
        if($year == 'null' || $year == 'undefined') {
            $year = null;
        } 
        if($isbn == 'null' || $isbn == 'undefined') {
            $isbn = null;
        }
        if( $datePurchased == 'null' || $datePurchased == 'undefined') {
            $datePurchased = null;
        }
        if($price == 'null' || $price == 'undefined') {
            $price = null;
        }
        if($shelfNumber == 'null' || $shelfNumber == 'undefined') {
            $shelfNumber = null;
        }
         if($description == 'null' || $description == 'undefined') {
            $description = null;
        }

        if($request->filled('image')) {
            $image = null;
        } else {
             if ($file = $request->file('image')) {
                $destinationPath = 'book-cover/';
                $image = $request->bookNum . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }

        return DB::connection('mysql')
        ->table('books')
        ->insert([
            'book_id' => $request->bookNum,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'category' => $category,
            'year' => $year,
            'isbn' => $isbn,
            'date_purchased' => $datePurchased,
            'price' => $price,
            'shelf_no' => $shelfNumber,
            'status' => $request->status,
            'book_description' => $description,
            'book_image' => $image,
            'created_at' => new \Datetime
        ]);
    } 


    public function update(Request $request) {
        $image = null;

        $category = $request->category;
        $year = $request->year;
        $isbn = $request->isbn;
        $datePurchased = $request->datePurchased;
        $price = $request->price;
        $shelfNumber = $request->shelfNumber;
        $description = $request->description;

        $imagePath = public_path('book-cover\\' . $request->image);

        if($category == 'null' || $category == 'undefined') {
            $category = null;
        } 
        if($year == 'null' || $year == 'undefined') {
            $year = null;
        } 
        if($isbn == 'null' || $isbn == 'undefined') {
            $isbn = null;
        }
        if( $datePurchased == 'null' || $datePurchased == 'undefined') {
            $datePurchased = null;
        }
        if($price == 'null' || $price == 'undefined') {
            $price = null;
        }
        if($shelfNumber == 'null' || $shelfNumber == 'undefined') {
            $shelfNumber = null;
        }
         if($description == 'null' || $description == 'undefined') {
            $description = null;
        }

         if (File::exists($imagePath)) {
            $imagePath = $request->image;
        } else {
            if ($file = $request->file('image')) {
                $destinationPath = 'book-cover/';
                $image = $request->bookNum . "." .$file->getClientOriginalExtension();
                $file->move($destinationPath, $image);
                $input['image'] = "$image";
            }
        }

        return DB::connection('mysql')
        ->table('books')
        ->where('id', '=', $request->id)
        ->update([
            'book_id' => $request->bookNum,
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'category' => $category,
            'year' => $year,
            'isbn' => $isbn,
            'date_purchased' => $datePurchased,
            'price' => $price,
            'shelf_no' => $shelfNumber,
            'status' => $request->status,
            'book_description' => $description,
            'book_image' => $image,
            'updated_at' => new \Datetime
        ]);
    }



    public function destroy(Request $request) {
       $imagePath = public_path('book-cover\\' . $request->book_image);
       
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
       return DB::connection('mysql')
            ->table('books')
            ->where('id', '=', $request->id)
            ->delete();
    }
    
}
