<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\Feedback;
use App\Models\Type;
use App\Models\Book;
use App\Models\User;


use Image;
use File;
use Auth;


class BackController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
    }
    //manage admins
    public function admin_index(){
        if(Auth::user()->id == 1){
            $users = User::orderBy('created_at', 'desc')->where('is_admin', 1)->paginate(10);
            return view('admin.super.admins', compact('users'));
        }else{
            return redirect()->route('admin.route')->with('error', 'You are not the Super Admin');
        }
    }
    public function admin_create(){
        if(Auth::user()->id == 1){
            return view('admin.super.admin_create');
        }else{
            return redirect()->route('admin.route')->with('error', 'You are not the Super Admin');
        }
    }
    public function admin_store(Request $request){
        if(Auth::user()->id == 1){
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required'
            ]);
            $pass = $request->input('password');
            $password = Hash::make($pass);
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $password;
            $user->is_admin = 1;
            $user->save();
            return redirect()->route('admin.admin_index')->with('success', 'Successfully Created new Admin');
        }else{
            return redirect()->route('admin.route')->with('error', 'You are not the Super Admin');
        }
    }
    //User SHow
    public function user_index(){
        $users = User::where('is_admin', null)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.super.users', compact('users'));
    }
    public function user_edit($id){
        $user = User::find($id);
        return view('admin.super.user_edit', compact('user'));
    }
    public function user_update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required'
        ]);
        //validation of user types
        $user = User::find($id);
        $newConf = $request->input('config');
        $oldConf = $user->config;
        if($newConf !== 'null'){
            $conf = $newConf;
        }else{
            $conf = $oldConf;
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->config = $conf;
        $user->save();
        return redirect()->route('admin.user_index')->with('warning', 'Successfully Updated');

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
        $books = Book::orderBy('created_at', 'desc')->paginate(5);
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
        $types = Type::all();
        //return [$book, $type];
        return view('admin.book.edit', compact('book', 'types'));
    }
    public function book_update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'confirmed' => 'required'
        ]);
        $conf = $request->input('confirmed');
        if($conf != 'null'){
            $confirm = $conf;
        }else{
            $confirm = true;
        }

        //validation for new book
        $book = Book::find($id);
        $oldImg = $book->image;
        $oldType = $book->category;
        $newType = $request->input('category');
        if($newType !== 'null'){
            $type = $newType;
        }else{
            $type = $oldType;
        }
        
        if($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $file_name = time().'.'.$extension;
            Image::make($file)->resize(700, 400)->save(public_path('/contents/images/book/'.$file_name));
            if($oldImg != 'no_image.png'){
                File::delete(public_path('/contents/images/book/'.$oldImg));
            }
        }else{
            $file_name = $oldImg;
        }

        $book->name = $request->input('name');
        $book->price = $request->input('price');
        $book->category = $type;
        $book->image = $file_name;
        $book->user = 0;
        $book->confirmed = $confirm;
        $book->save();
        return redirect()->route('admin.book_index')->with('warning', 'Successfully Updated');
    }
    public function book_show($id){
        $book = Book::find($id);
        return view('admin.book.show', compact('book'));
    }
}
