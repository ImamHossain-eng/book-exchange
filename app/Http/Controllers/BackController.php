<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Feedback;
use App\Models\Type;
use App\Models\Book;

use Image;
use File;

class BackController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
    }
    public function feedback_index(){
        $feedbacks = Feedback::all();
        //return $feedbacks;
        return view('admin.feedback_index', compact('feedbacks'));
    }
    public function feedback_destroy($id){
        Feedback::find($id)->delete();
        return redirect()->route('admin.feedback')->with('error', 'Removed Successfully');
    }
    public function feedback_show($id){
        $feedback = Feedback::find($id);
        return view('admin.feedback_show', compact('feedback'));
    }
    //Book Category start from here
    public function book_type(){
        $types = Type::all();
        return view('admin.book_type', compact('types'));
    }
    public function type_create(){
        return view('admin.type_create');
    }
    public function type_store(Request $request, Type $type){
        $this->validate($request, [
            'type' => 'required'
        ]);
        Type::create($request->all());
        return redirect()->route('admin.book_type')->with('success', 'Successfully Created');
    }
    public function type_destroy($id){
        Type::find($id)->delete();
        return redirect()->route('admin.book_type')->with('error', 'One Record Removed');
    }
    public function book_index(){
        $books = Book::all();
        return view('admin.book.index', compact('books'));
    }
    public function book_create(){
        $types = Type::all();
        return view('admin.book.create', compact('types'));
    }
    public function book_store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'category' => 'required'
        ]);
        if($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $file_name = time().'.'.$extension;
            Image::make($file)->resize(700, 400)->save(public_path('/contents/images/book/'.$file_name));
        }else{
            $file_name = 'no_image.png';
        }

        $book = new Book;
        $book->name = $request->input('name');
        $book->price = $request->input('price');
        $book->category = $request->input('category');
        $book->image = $file_name;
        $book->user = 0;
        $book->confirmed = true;
        $book->save();
        return redirect()->route('admin.book_index')->with('success', 'Successfully Created');
    }
    public function book_destroy($id){
        $book = Book::find($id);
        $oldImg = $book->image;
        if($oldImg != 'no_image.png'){
            File::delete(public_path('/contents/images/book/'.$oldImg));
        }
        $book->delete();
        return redirect()->route('admin.book_index')->with('error', 'Removed');
    }
    public function book_edit($id){
        $book = Book::find($id);
        $typeId = $book->category;
        $type = Type::find($typeId);
        return [$book, $type];
        //return view('admin.book.edit', compact('book', 'type'));
    }
}
