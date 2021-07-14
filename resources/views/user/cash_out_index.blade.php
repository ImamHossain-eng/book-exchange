@extends('layouts.user')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Cash Out / Payment Request 
            </h3>
            <div class="row">
                <div class="col-md-4">
                    <a href="/user/cash_out" class="btn btn-info">Recharge Account / Cash Out</a>                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-responsive table-hover table-striped table-border">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Sender Number</th>
                        <th>Amount</th>
                        <th>Transaction ID</th>
                        <th>Via</th>
                        <th>Status</th>
                        <th>Requested at</th>
                        <th>Confirmed at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recharges as $key => $recharge)
                        <tr>
                            <td> {{$key+1}} </td>
                            <td> {{$recharge->number}} </td>
                            <td> {{$recharge->amount}} </td>
                            <td> {{$recharge->trans_id}} </td>
                            <td> {{$recharge->method}} </td>
                            <td>
                                @if($recharge->confirmed == 0)
                                    <h6 style="color:rgba(219, 17, 17, 0.829);">Pending</h6>
                                @elseif($recharge->confirmed == 1)
                                    <h6 style="color:rgba(108, 219, 17, 0.829);">Success</h6>
                                @else
                                    <h6 style="color:rgba(17, 47, 219, 0.829);">Unknown</h6>
                                @endif
                            </td>
                            <td> {{$recharge->created_at->diffForHumans()}} </td>
                            <td>@if($recharge->created_at == $recharge->updated_at)
                                <h5>Processing</h5> 
                                @else
                                {{$recharge->updated_at->diffForHumans()}} </td>
                                @endif
                        </tr>
                    @empty 
                        <tr>
                            <td colspan="8">
                                <center>No Transaction Yet</center>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
@endsection