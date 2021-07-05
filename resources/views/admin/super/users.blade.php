@extends('layouts.admin')
@section('content')
<body>
    <table class="table table-responsive table-border table-light">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Status</th>
                <th>Option</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $key => $user)
                <tr @if($user->is_admin == 1)
                        class="table-warning" 
                    @elseif($user->config== '1')
                     class="table-danger"
                    @else
                     class="table-success"
                    @endif >
                    <td> {{$user->name}} </td>
                    <td> {{$user->email}} </td>
                    <td> {{$user->created_at->diffForHumans()}} </td>
                    <td>
                        @if($user->config == '1')
                        <h6 style="color:brown;">Unregistered</h6>
                        @elseif($user->config == '0')
                        <h6 style="color:green;">Registered</h6>
                        @else 
                        <h6 style="color:rgba(38, 0, 255, 0.842);">New User</h6>
                        @endif
                    </td>
                    <td>
                        @if(Auth::user()->is_admin==1)
                            <a href="#" class="btn btn-primary">
                                <i class="fa fa-eye"></i>
                            </a>
                            @if($user->is_admin !== 1)
                                <a href="/admin/users/{{$user->id}}/edit" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                </a>
                            @endif
                            @if($user->is_admin == 1 && AUth::user()->id == $user->id)
                                <a href="#" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                </a>
                            @endif
                            @if(Auth::user()->id !== $user->id && $user->is_admin !== 1)
                                <a href="#" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>                    
            @empty
                <tr>
                    <td colspan="5">COming Soon</td>
                </tr>
                    
            @endforelse
    
        </tbody>
    </table>
</body>
@endsection