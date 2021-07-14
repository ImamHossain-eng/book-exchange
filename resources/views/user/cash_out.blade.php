@extends('layouts.user')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                 Cash Out Your Account Money
            </h2>
            <a href="/user/cash_out/request" class="btn btn-info">See Your Cash Out / Payment Request</a>
            
        </div>
        <div class="card-body">
            {{Form::open(['route' => 'user.cash_out_post', 'method' => 'POST'])}}
            @csrf
            <div class="form-group">
                <input type="number" name="number" class="form-control" placeholder="Receiver Mobile Number">
            </div><br>
            <div class="form-group">
                <input type="number" name="amount" class="form-control" placeholder="Payment Amount">
            </div><br>
            <div class="form-group">
                <select name="method" id="" class="form-control">
                    <option value="Bkash">Bkash</option>
                    <option value="Nagat">Nagat</option>
                    <option value="Rocket">Rocket</option>
                    <option value="Upay">Upay</option>
                </select>
            </div><br>
            <input type="submit" value="Send Details" class="btn btn-primary">
            {{Form::close()}}
        </div>
    </div>
</body>
@endsection