@extends('layouts.admin')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h3>Book Request Approve</h3>
        </div>
        <div class="card-body">
            <div class="card-title">
               <strong>User:</strong> {{User::find($order->user_id)->name}}
            </div>
            <div class="card-sub-title">
                <strong>Book:</strong> {{Book::find($order->book_id)->name}}
             </div><br>
            {{Form::open(['route' => ['admin.book_request_update', $order->id], 'method' => 'POST'])}}
                @csrf 
                {{method_field('PUT')}}
                <div class="form-group">
                    <select name="status" class="form-control">
                        <option value="null">Choose Option</option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                    </select>
                </div><br>
                <input type="submit" value="Confirm" class="btn btn-primary">
            {{Form::close()}}
        </div>
    </div>
</body>

@endsection