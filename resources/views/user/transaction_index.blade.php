@extends('layouts.user')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                {{Auth::user()->email}}
            </h2>
            <h4 class="card-subtitle mb-2 text-muted">
                {{$transactions->count()}} Transactions
            </h4>
            <p class="card-text">
                Available balance: {{number_format($trans_credit-$trans_debit, 2)}}
            </p>
        </div>
        <div class="card-body">
            <table class="table table-responsive table-border table-light table-hover">
                <thead>
                    <tr>
                        <th>Serial No</th>
                        <th>Book</th>
                        <th>Price</th>
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Transaction Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $key => $trans)
                    <tr @if($trans->credit == 1) 
                        class="table-success"
                        @else 
                        class="table-warning"
                        @endif>
                        <td> {{$key+1}} </td>
                        <td> {{$trans->book_id}} </td>
                        <td> {{$trans->price}} </td>
                        <td> 
                            @if($trans->credit == 1)
                                <i class="fa fa-check"></i>
                            @else 
                            <i class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>
                            @if($trans->debit == 1)
                                <i class="fa fa-check"></i>
                            @else 
                                <i class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>{{ date('F d, Y(D)', strtotime($trans->created_at))}} at {{ date('g:ia', strtotime($trans->created_at))}} in words {{$trans->created_at->diffForHumans()}} </td>
                    </tr>
                    @empty 
                    <tr>
                        <td colspan="6">
                            <center>No Transaction Yet</center>
                        </td>
                    </tr>
                    @endforelse
                    <tr class="table-danger">
                        <td></td>
                        <td></td>
                        <td>Mean Balance: {{$trans_credit-$trans_debit}}/=</td>
                        <td>Total Credit: {{$trans_credit}}/=</td>
                        <td>Total Debit: {{$trans_debit}}/=</td>
                        <td></td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
@endsection