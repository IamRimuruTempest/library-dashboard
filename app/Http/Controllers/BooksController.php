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

        DB::connection('mysql')
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

        
        DB::connection('mysql')
        ->table('ratings')
        ->insert([
           'isbn' => $isbn,
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
            $image = $request->image;
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
        DB::connection('mysql')
            ->table('books')
            ->where('id', '=', $request->id)
            ->delete();

        DB::connection('mysql')
            ->table('ratings')
            ->where('isbn', '=', $request->isbn)
            ->delete();
    }

    public function all_borrowed_books() {
          return DB::connection('mysql')
        ->table('borrowed_books as bb')
        ->join('accounts', 'bb.student_id','accounts.student_id')
        ->join('books', 'bb.isbn','books.isbn')
        ->select([
            "bb.id",
            'bb.date_borrowed',
            'bb.created_at',
            "books.title",
            "accounts.first_name",
            "accounts.middle_name",
            "accounts.last_name",
            "accounts.suffix",
        ])
        ->get();
        
    }


    public function borrowed_books() {
        return DB::connection('mysql')
        ->table('borrowed_books as bb')
        ->join('accounts', 'bb.student_id','accounts.student_id')
        ->join('books', 'bb.isbn','books.isbn')
        ->where('bb.status', 'Borrowed')
        ->select([
            "books.id",
            "books.title",
            "books.isbn",
            "books.updated_at",
            "accounts.id as uid",
            "accounts.student_id",
            "accounts.first_name",
            "accounts.middle_name",
            "accounts.last_name",
            "accounts.suffix",
            "accounts.department",
            "accounts.course",
            "accounts.year",
            "accounts.phone_number"
        ])
        ->get();
        
    }



     public function insert_borrowed_book(Request $request) {
        DB::connection('mysql')
        ->table('books')
        ->where('isbn', $request->isbn)
        ->update([
          'borrowers' => $request->student_id,
          'status' => 'Unavailable',
          'updated_at' => new \Datetime
        ]);

        return DB::connection('mysql')
            ->table('borrowed_books')
            ->insert([
                'isbn' => $request->isbn,
                'student_id' => $request->student_id,
                'librarian' => $request->admin,
                'date_borrowed' => new \Datetime,
                'status' => 'Borrowed',
                'created_at' => new \Datetime,
            ]);
    }


    public function insert_to_return_books(Request $request) {
        DB::connection('mysql')
        ->table('returned_books')
        ->insert([
            "isbn" => $request->isbn,
            "student_id" => $request->uid,
            "receiver" => $request->receiver ,
            "date_received" => $request->date_received,
            "created_at" => new \Datetime ,
        ]);

        DB::connection('mysql')
        ->table('borrowed_books')
        ->where('isbn', $request->isbn)
        ->update([
            'status' => 'Returned'
        ]);

        DB::connection('mysql')
        ->table('books')
        ->where('isbn', $request->isbn)
        ->update([
            'status' => 'Available',
            'borrowers' => NULL
        ]);
    }


    
    public function get_returned_books() {
        return DB::connection('mysql')
            ->table('returned_books as rb')
            ->join('books as b', 'b.isbn','rb.isbn')
            ->join('accounts as a', 'a.id','rb.student_id')
            ->select([
                "rb.id",
                "rb.receiver",
                "rb.date_received",
                "rb.created_at",
                "b.isbn",
                "b.title",
                "a.student_id",
                "a.first_name",
                "a.middle_name",
                "a.last_name",
                "a.suffix",
                "a.course",
                "a.phone_number",
            ])
            ->get();
    }

    
}
