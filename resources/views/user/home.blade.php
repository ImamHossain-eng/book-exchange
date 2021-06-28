@extends('layouts.user')
@section('content')
    <div class="card">
        <div class="card-header">
            @if(Auth::user()->config == 0)
                <h3>
                    You're a user
                </h3>
            @else
                <h3>
                    Wait For Admin Approval. User Request Pending.
                </h3>
            @endif
        </div>
        <div class="card-body">
            @if(Auth::user()->config == 0)
                <div class="row">
                    <div class="col-sm-6">
                       Join: {{ date('F d, Y(D)', strtotime(Auth::user()->created_at))}} at {{ date('g:ia', strtotime(Auth::user()->created_at))}}
                    </div>
                    <div class="col-sm-6">
                        Approval: {{ date('F d, Y(D)', strtotime(Auth::user()->updated_at))}} at {{ date('g:ia', strtotime(Auth::user()->updated_at))}}
                     </div>
                </div>
            @endif
        </div>
    </div>
@endsection