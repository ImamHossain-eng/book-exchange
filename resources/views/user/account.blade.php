@extends('layouts.user')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-title">
                        <h2>{{User::find($account->user_id)->name}}</h2>
                    </div>
                    <br>
                    <h6 class="card-subtitle">Total
                            @if($transactions > 1) 
                                {{$transactions}} Transactions
                            @else 
                                {{$transactions}} Transaction
                            @endif
                    </h6>
                    <p class="card-text"><br>
                       <a href="/user/transaction" class="btn btn-outline-secondary">See All Transaction</a>
                    </p>
                </div>
                <div class="col-md-6"><br>
                    <a href="/user/cash_in" class="btn btn-warning">Recharge Account / Cash In</a><hr>
                    <a href="/user/cash_out" class="btn btn-info">Recharge Account / Cash Out</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Available Balance</th>
                        <th>Total Transaction</th>
                        <th>Last Transaction</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{User::find($account->user_id)->name}}</td>
                        <td>{{User::find($account->user_id)->email}}</td>
                        <td>{{number_format($account->balance, 2)}}</td>
                        <td>{{$transactions}}</td>
                        <td>{{$account->updated_at->diffForHumans()}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

@endsection