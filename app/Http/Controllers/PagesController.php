<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Feedback;
use App\Models\Book;
use App\Models\Type;

class PagesController extends Controller
{
    public function index(){
        $books = Book::orderby('created_at', 'desc')->where('confirmed', true)->take(3)->get();
        return view('pages.homepage', compact('books'));
    }
    public function feedback(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required'
        ]);
        $feedback = new Feedback;
        $feedback->name = $request->input('name');
        $feedback->email = $request->input('email');
        $feedback->phone = $request->input('phone');
        $feedback->message = $request->input('message');
        $feedback->save();
        return redirect()->route('homepage')->with('success', 'Successfully sent the Message');
    }
    public function book_show($id){
        $book = Book::find($id);
        return view('visitor.book_show', compact('book'));
    }
    public function book_index(){
        $books = Book::orderBy('created_at', 'desc')->where('confirmed', true)->paginate(6);
        $types = Type::all();
        return view('visitor.book_index', compact('books', 'types'));
    }
    public function book_find(Request $request){
        $this->validate($request, [
            'type' => 'required'
        ]);
        $newType = $request->input('type');
        if($newType !== 'null'){
            $books = Book::orderBy('created_at', 'desc')->where('category', $newType)->where('confirmed', true)->paginate(100);
            $types = Type::all();
            return view('visitor.book_index', compact('books', 'types'))->with('success', 'Filtered by Type');
        }else{
            return redirect()->route('visitor.book_index')->with('error', 'Select a group of book');
        }
        
    }
}
