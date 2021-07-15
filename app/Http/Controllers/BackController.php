<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Models\Feedback;
use App\Models\Type;
use App\Models\Book;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Account;
use App\Models\Recharge;


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
    public function admin_edit($id){
        if(Auth::user()->id == 1){
            $user = User::find($id);
            return view('admin.super.admin_edit', compact('user'));
        }else{
            return redirect()->route('admin.route')->with('error', 'You are not the Super Admin');
        }
    }
    public function admin_update(Request $request, $id){
        if(Auth::user()->id == 1){
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required'
            ]);
            $password = $request->input('password');
            if($password != ''){
                $pass = Hash::make($password);
            }else{
                $pass = $request->input('pass');
            }
            $user = User::find($id);
            $oldType = $user->is_admin;
            $newType = $request->input('is_admin');
            if($newType != 'null'){
                $type = $newType;
            }else{
                $type = $oldType;
            }
            
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $pass;
            $user->is_admin = $type;
            $user->save();
            return redirect()->route('admin.admin_index')->with('success', 'Successfully Created new Admin');
        }else{
            return redirect()->route('admin.route')->with('error', 'You are not the Super Admin');
        }
        
    }
    public function admin_destroy($id){
        $user = User::find($id);
        if($user->id !== 1){
            $user->delete();
            return redirect()->route('admin.admin_index')->with('error', 'Removed Done');
        }else{
            return redirect()->route('admin.admin_index')->with('error', 'You are master admin.');
        }
    }
    //User SHow
    public function user_index(){
        $users = User::where('is_admin', null)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.super.users', compact('users'));
    }
    public function user_destroy($id){
        $user = User::find($id);
        if($user->is_admin !== 1){
            $recharges = Recharge::where('user_id', $id)->get();
            foreach($recharges as $recharge){
                if($recharge->user_id == $user->id){
                    $recharge->delete();
                }
            }
            $books = Book::where('user', $id)->get();
            foreach($books as $book){
                if($book->user == $user->id){
                    $book->delete();
                }
            }
            $orders = Order::where('user_id', $id)->get();
            foreach($orders as $order){
                if($order->user_id == $user->id){
                    $order->delete();
                }
            }
            $user->delete();
            return redirect()->route('admin.user_index')->with('error', 'Removed Done');
        }else{
            return redirect()->route('admin.user_index')->with('error', 'Failed User type admin.');
        }
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
    //User Transaction
    public function user_transaction($id){
        $user = $id;
        $transactions = Transaction::orderBy('created_at', 'desc')->where('user_id', $user)->get();
        $trans_credit = Transaction::orderBy('created_at', 'desc')->where('user_id', $user)->where('credit', 1)->sum('price');
        $trans_debit = Transaction::orderBy('created_at', 'desc')->where('user_id', $user)->where('debit', 1)->sum('price');
        return view('admin.transaction_index', compact('transactions', 'trans_credit', 'trans_debit', 'user'));
    }
    //Books Request
    public function book_request(){
        $orders = Order::orderBy('created_at', 'desc')->paginate('10');
        return view('admin.book_request', compact('orders'));
    }
    public function book_request_destroy($id){
        Order::find($id)->delete();
        return redirect()->route('admin.book_request')->with('error', 'Removed a Book request');
    }
    //Request Review
    public function book_request_edit($id){
        $order = Order::find($id);
        return view('admin.book_request_edit', compact('order'));
    }
    public function book_request_update(Request $request, $id){
        $order = Order::find($id);
        $oldStatus = $order->status;
        $newStatus = $request->input('status');
        if($newStatus == 'null'){
            $status = $oldStatus;
            return redirect()->route('admin.book_request')->with('error', 'Error while updating');
        }elseif($newStatus == 0){
            $status = $oldStatus;
            return redirect()->route('admin.book_request')->with('error', 'Pending Request');
        }elseif($newStatus == 1){
            $order->status = $newStatus;
            //validate the account balance of that user
            $account = Account::where('user_id', $order->user_id)->first();
            $user_balance = $account->balance;
            $book_obj = Book::find($order->book_id);
            $book_obj->confirmed = 2;
            $book_obj->save();
            $book_price = $book_obj->price;


            if($user_balance > $book_price){
                $transaction = new Transaction;
                    $transaction->user_id = $order->user_id;
                    $transaction->book_id = $order->book_id;
                    $transaction->recharge_id = 0;
                    $transaction->price = $book_price;
                    $transaction->credit = false;
                    $transaction->debit = true;
                    $transaction->save();
                $order->save();
                return redirect()->route('admin.book_request')->with('success', 'Successfully Updated');
            }else{
                return redirect()->route('admin.book_request')->with('error', 'Insufficient Balance');
            }
        }else{
            $status = null;
            return redirect()->route('admin.book_request')->with('error', 'Error 404 not found');
        }
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
        $books = Book::orderBy('created_at', 'desc')->paginate(10);
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
        $book->author = $request->input('author');
        $book->price = $request->input('price');
        $book->category = $request->input('category');
        $book->description = $request->input('description');
        $book->image = $file_name;
        $book->user = Auth::user()->id;
        $book->confirmed = 1;
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
        //Confirmed validation 
        $conf = $request->input('confirmed');
        $oldConf = $book->confirmed;
        if($conf !== 'null'){
            $confirm = $conf;
        }else{
            $confirm = $oldConf;
        }
        //transaction validation
        if($conf == 1){
            //effect to the transaction table
            $trans = Transaction::all();
            //CHeck if transaction exists
            foreach($trans as $tran){
                if($tran->book_id !== $book->id && $tran->user_id !== $book->user){
                    $ab = true;
                }else{
                    $ab = false;
                }
            }
            if($ab == true){
                    $transaction = new Transaction;
                    $transaction->user_id = $book->user;
                    $transaction->book_id = $book->id;
                    $transaction->recharge_id = 0;
                    $transaction->price = $request->input('price');
                    $transaction->credit = true;
                    $transaction->debit = false;
                    $transaction->save();
            }
        }
        //image validation
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
        $book->author = $request->input('author');
        $book->price = $request->input('price');
        $book->description = $request->input('description');
        $book->category = $type;
        $book->image = $file_name;
        $book->confirmed = $confirm;
        $book->save();
        return redirect()->route('admin.book_index')->with('warning', 'Successfully Updated');
    }
    public function book_show($id){
        $book = Book::find($id);
        return view('admin.book.show', compact('book'));
    }
    //CashIn Request
    public function cashin_request(){
        $recharges = Recharge::orderBy('created_at', 'desc')->where('type', 'cashin')->paginate(10);
        return view('admin.cashin_index', compact('recharges'));
    }
    public function cashout_request(){
        $recharges = Recharge::orderBy('created_at', 'desc')->where('type', 'cashout')->paginate(10);
        return view('admin.cashout_index', compact('recharges'));
    }
    public function cashin_edit($id){
        $recharge = Recharge::find($id);
        return view('admin.cashin_edit', compact('recharge'));
    }
    public function cashout_edit($id){
        $recharge = Recharge::find($id);
        return view('admin.cashout_edit', compact('recharge'));
    }
    public function cashin_update(Request $request, $id){
        $recharge = Recharge::find($id);
        $oldConf = $recharge->confirmed;
        $newConf = $request->input('confirmed');
        //confirmation validation
        if($newConf == 1){
            $conf = true;
             //effect to the transaction table
             $trans = Transaction::all();
             //CHeck if transaction exists
             foreach($trans as $tran){
                 if($tran->recharge_id !== $recharge->id){
                     $ab = true;
                 }else{
                     $ab = false;
                 }
             }
             if($ab == true){
                $transaction = new Transaction;
                $transaction->user_id = $recharge->user_id;
                $transaction->book_id = 0;
                $transaction->recharge_id = $recharge->id;
                $transaction->price = $recharge->amount;
                $transaction->credit = true;
                $transaction->debit = false;
                $transaction->save();
             }elseif($ab == false){
                return redirect()->route('admin.cashin_request')->with('error', 'You have already confirmed this Recharge');
             }else{
                return redirect()->route('admin.cashin_request')->with('error', 'Transaction Failed');
             }
            
        }elseif($newConf == 0){
            $conf = $oldConf;
        }else{
            $conf = $oldConf;
        }
        $recharge->confirmed = $conf;
        $recharge->save();
        return redirect()->route('admin.cashin_request')->with('warning', 'Recharge Confirmed Successfully');     
    }
    public function cashout_update(Request $request, $id){
        $recharge = Recharge::find($id);
        $oldConf = $recharge->confirmed;
        $newConf = $request->input('confirmed');
        $tr_id = $request->input('trans_id');
        //confirmation validation
        if($newConf == 1){
            $conf = true;
            if(Transaction::where('recharge_id', '=', $recharge->id)->exists()) {
                return redirect()->route('admin.cashout_request')->with('error', 'You have already confirmed this Recharge');
             }else{
                $transaction = new Transaction;
                $transaction->user_id = $recharge->user_id;
                $transaction->book_id = 0;
                $transaction->recharge_id = $recharge->id;
                $transaction->price = $recharge->amount;
                $transaction->credit = false;
                $transaction->debit = true;
                $transaction->save(); 
             }
            
        }elseif($newConf == 0){
            $conf = $oldConf;
        }else{
            $conf = $oldConf;
        }
        $recharge->confirmed = $conf;
        $recharge->trans_id = $tr_id;
        $recharge->save();
        return redirect()->route('admin.cashout_request')->with('warning', 'Recharge Confirmed Successfully');
    }
    public function cashout1_update(Request $request, $id){
        $recharge = Recharge::find($id);
        $oldConf = $recharge->confirmed;
        $newConf = $request->input('confirmed');
        //confirmation validation
        if($newConf == 1){
            $conf = true;
             //effect to the transaction table
             $trans = Transaction::all();
             //CHeck if transaction exists
             foreach($trans as $tran){
                 if($tran->recharge_id !== $recharge->id && $tran->user_id !== $recharge->user_id){
                     $ab = true;
                 }else{
                     $ab = false;
                 }
             }
             if($ab == true){
                $transaction = new Transaction;
                $transaction->user_id = $recharge->user_id;
                $transaction->book_id = 0;
                $transaction->recharge_id = $recharge->id;
                $transaction->price = $recharge->amount;
                $transaction->credit = true;
                $transaction->debit = false;
                $transaction->save();
             }elseif($ab == false){
                return redirect()->route('admin.cashin_request')->with('error', 'You have already confirmed this Recharge');
             }else{
                return redirect()->route('admin.cashin_request')->with('error', 'Transaction Failed');
             }
            
        }elseif($newConf == 0){
            $conf = $oldConf;
        }else{
            $conf = $oldConf;
        }
        $recharge->confirmed = $conf;
        $recharge->save();
        return redirect()->route('admin.cashin_request')->with('warning', 'Recharge Confirmed Successfully');
    }
    public function cashin_destroy($id){
        $recharge = Recharge::find($id);
        $conf = $recharge->confirmed;
        if($conf == 1){
            $recharge->delete();
            return redirect()->route('admin.cashin_request')->with('error', 'One Recharge Record Removed'); 
        }elseif($conf == 0){
            return redirect()->route('admin.cashin_request')->with('error', 'You can not delete this until confirmation');
        }else{
            return redirect()->route('admin.cashin_request')->with('error', 'Unknown Transaction');
        }
    }
}
