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
            ->whereNull('deleted_at')
            ->get();
    }

   

   

    public function store(Request $request) {
       

       $image = null;
       $category = ($request->category === 'null' || $request->category === 'undefined') ? null : $request->category;
       $year = ($request->year === 'null' || $request->year === 'undefined') ? null : $request->year;
       $isbn = ($request->isbn === 'null' || $request->isbn === 'undefined') ? null : $request->isbn;
       $datePurchased = ($request->datePurchased === 'null' || $request->datePurchased === 'undefined') ? null : $request->datePurchased;
       $price = ($request->price === 'null' || $request->price === 'undefined') ? null : $request->price;
       $shelfNumber = ($request->shelfNumber === 'null' || $request->shelfNumber === 'undefined') ? null : $request->shelfNumber;
       $description = ($request->description === 'null' || $request->description === 'undefined') ? null : $request->description;


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
        ->table('reservations')
        ->insert([
            'isbn' => $isbn
        ]);

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
            'quantity' => $request->quantity,
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
        $category = ($request->category === 'null' || $request->category === 'undefined') ? null : $request->category;
        $year = ($request->year === 'null' || $request->year === 'undefined') ? null : $request->year;
        $isbn = ($request->isbn === 'null' || $request->isbn === 'undefined') ? null : $request->isbn;
        $datePurchased = ($request->datePurchased === 'null' || $request->datePurchased === 'undefined') ? null : $request->datePurchased;
        $price = ($request->price === 'null' || $request->price === 'undefined') ? null : $request->price;
        $shelfNumber = ($request->shelfNumber === 'null' || $request->shelfNumber === 'undefined') ? null : $request->shelfNumber;
        $description = ($request->description === 'null' || $request->description === 'undefined') ? null : $request->description;

        $imagePath = public_path('book-cover\\' . $request->image);

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

        $quantity = DB::connection('mysql')
        ->table('books')
        ->where('isbn', $request->isbn)
        ->value('quantity');

        if($quantity == 1){
            DB::connection('mysql')
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
                'quantity' => $request->quantity,
                'price' => $price,
                'shelf_no' => $shelfNumber,
                'status' => 'Available',
                'book_description' => $description,
                'book_image' => $image,
                'updated_at' => new \Datetime
            ]);
        } else {
            DB::connection('mysql')
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
                'quantity' => $request->quantity,
                'price' => $price,
                'shelf_no' => $shelfNumber,
                'status' => $request->status,
                'book_description' => $description,
                'book_image' => $image,
                'updated_at' => new \Datetime
            ]);
        }
       
       

        //  DB::connection('mysql')
        // ->table('ratings')
        // ->update([
        //    'isbn' => $isbn,
        // ]);

        // DB::connection('mysql')
        // ->table('reservations')
        // ->update([
        //    'isbn' => $isbn,
        // ]);
    }



    public function destroy(Request $request) {
    //    $imagePath = public_path('book-cover\\' . $request->book_image);
       
    //     if (File::exists($imagePath)) {
    //         File::delete($imagePath);
    //     }
        DB::connection('mysql')
            ->table('books')
            ->where('id', '=', $request->id)
            ->update(['deleted_at' => new \Datetime]);

        // DB::connection('mysql')
        //     ->table('ratings')
        //     ->where('isbn', '=', $request->isbn)
        //     ->update(['deleted_at' => new \Datetime]);
    }

    public function all_borrowed_books() {
        return DB::connection('mysql')
        ->table('borrowed_books as bb')
        ->join('accounts', 'bb.student_id','accounts.student_id')
        ->join('books', 'bb.isbn','books.isbn')
        ->select([
            "bb.id",
            'bb.date_borrowed',
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
            "books.status",
            "books.price",
            "bb.id as borrowed_id",
            "bb.date_borrowed",
            "bb.penalty",
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
        $quantity = DB::connection('mysql')
        ->table('books')
        ->where('isbn', $request->isbn)
        ->value('quantity');
       

        if($quantity == 2) {
            DB::connection('mysql')
            ->table('books')
            ->where('isbn', $request->isbn)
            ->update([
            'borrowers' => $request->student_id,
            'quantity' => DB::raw('quantity - 1'),
            'status' => 'Unavailable',
            'updated_at' => new \Datetime
            ]);
         } else {
            DB::connection('mysql')
            ->table('books')
            ->where('isbn', $request->isbn)
            ->update([
            'borrowers' => $request->student_id,
            'quantity' => DB::raw('quantity - 1'),
            'updated_at' => new \Datetime
            ]);
         }

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

     public function update_borrowed_status(Request $request) {
       
        DB::connection('mysql')
        ->table('borrowed_books')
        ->where('id', $request->id)
        ->update([
          'penalty' => $request->status,
          'updated_at' => new \Datetime
        ]);

    }


    public function insert_to_return_books(Request $request) {
        $quantity = DB::connection('mysql')
        ->table('books')
        ->where('isbn', $request->isbn)
        ->value('quantity');

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
        ->where('id', $request->bbid)
        ->update([
            'status' => 'Returned'
        ]);

        if($quantity == 1) { 
            DB::connection('mysql')
            ->table('books')
            ->where('isbn', $request->isbn)
            ->update([
                'quantity' => DB::raw('quantity + 1'),
                'status' => 'Available',
                'borrowers' => NULL
            ]);
        } else {
             DB::connection('mysql')
            ->table('books')
            ->where('isbn', $request->isbn)
            ->update([
                'quantity' => DB::raw('quantity + 1'),
                'borrowers' => NULL
            ]);
        }
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

     public function get_archived_books() {
        return DB::connection('mysql')
            ->table('books')
            ->whereNotNull('deleted_at')
            ->get();
    }

     public function restore_book(Request $request) {
            DB::connection('mysql')
            ->table('books')
            ->where('id', '=', $request->id)
            ->update(['deleted_at' => NULL]);
    }


    public function get_reserved_books(){
       return DB::connection('mysql')
        ->table('reservations as r')
        ->join('books as b', 'b.isbn','r.isbn')
        ->join('accounts as a', 'a.id','r.student_id')
        ->whereNotNull('r.student_id')
        ->select([
            'b.isbn',
            'b.title',
            'a.student_id',
            'a.first_name',
            'a.middle_name',
            'a.last_name',
            'a.suffix',
            'a.department',
            'a.course',
            'a.year',
            'a.phone_number',
            'r.id as rid',
            'r.created_at as reserved_date',
        ])
        ->get();
    }

    public function cancel_reservations(Request $request){
        DB::connection('mysql')
        ->table('reservations')
        ->where('id', $request->rid)
        ->update([
            'student_id' => NULL,
            'created_at' => NULL
        ]);
    }

    public function approved_reservation(Request $request) {
        $this->insert_borrowed_book($request);
        $this->cancel_reservations($request);
    }
    
}
