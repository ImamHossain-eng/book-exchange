@extends('layouts.user')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                Recharge Your Account / Cash In to Your Account
            </h2>
            <a href="/user/cash_in/request" class="btn btn-info">See Your Cash In / Recharge Request</a>
            <h6 class="card-sub-title" style="color:rgba(255, 0, 0, 0.753);">
                Send Money to the following Personal Number
            </h6>
        </div>
        <div class="card-body">
            <p class="card-text">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Bkash: 01915970075</li>
                    <li class="list-group-item">Nagat: 01915970075</li>
                    <li class="list-group-item">Rocket & Upay: 01915970075</li>
                </ul>
            </p>
            <p class="card-text" style="color:rgba(255, 0, 0, 0.753);">
                Then Submit the form with your Transaction Information
            </p>
            <hr>
            {{Form::open(['route' => 'user.cash_in_post', 'method' => 'POST'])}}
            @csrf
            <div class="form-group">
                <input type="number" name="number" class="form-control" placeholder="Sender Mobile Number">
            </div><br>
            <div class="form-group">
                <input type="number" name="amount" class="form-control" placeholder="Recharge Amount">
            </div><br>
            <div class="form-group">
                <input type="text" name="trans_id" class="form-control" placeholder="Enter the Transaction ID">
            </div><br>
            <div class="form-group">
                <select name="method" id="" class="form-control">
                    <option value="bkash">Bkash</option>
                    <option value="nagat">Nagat</option>
                    <option value="rocket">Rocket</option>
                    <option value="upay">Upay</option>
                </select>
            </div><br>
            <input type="submit" value="Send Details" class="btn btn-primary">
            {{Form::close()}}
        </div>
    </div>
</body>
@endsection