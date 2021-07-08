@extends('layouts.admin')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            Pending Book Request
        </div>
        <div class="card-body">
            <table class="table table-responsive table-light
            table-border">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>User</th>
                        <th>Book</th>
                        <th>Status</th>
                        <th>Sent Request</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $key => $order)
                        <tr>
                            <td> {{$key+1}} </td>
                            <td> {{User::find($order->user_id)->name}} </td>
                            <td> {{Book::find($order->book_id)->name}} </td>
                            <td>
                                @if($order->status == 0)
                                    <h6 style="color:rgba(209, 15, 15, 0.836);">Pending</h6>
                                @else 
                                <h6 style="color:rgba(60, 209, 15, 0.836);">Success</h6>
                                @endif
                            </td>
                            <td> {{$order->created_at->diffForHumans()}} </td>
                            <td>
                                <a href="#" class="btn btn-success">
                                    <i class="fa fa-check"></i>                                    
                                </a>
                                @if(Auth::user()->id == 1 && Auth::user()->is_admin == 1)
                                <a href="#" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>                                    
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty 
                        <tr>
                            <td colspan="6">
                                <center>No Order Found</center>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
@endsection