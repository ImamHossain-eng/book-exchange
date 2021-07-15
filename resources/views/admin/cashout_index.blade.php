@extends('layouts.admin')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Cash Out / Payment Request 
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-responsive table-hover table-striped table-border">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>User Name</th>
                        <th>User Email</th>                        
                        <th>Receiver Mobile Number</th>
                        <th>Amount</th>
                        <th>Transaction ID</th>
                        <th>Via</th>
                        <th>Status</th>
                        <th>Requested at</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recharges as $key => $recharge)
                        <tr>
                            <td> {{$key+1}} </td>
                            <td> {{User::find($recharge->user_id)->name}} </td>
                            <td> {{User::find($recharge->user_id)->email}} </td>
                            
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
                            <td>
                                <a href="/admin/cashout/request/{{$recharge->id}}" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                </a>
                                {{Form::open(['method' => 'DELETE', 'style' =>'display:inline;', 'route' => ['admin.cashin_destroy', $recharge->id]])}}
                                <button class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                                {{Form::close()}}
                            </td>
                        </tr>
                    @empty 
                        <tr>
                            <td colspan="10">
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