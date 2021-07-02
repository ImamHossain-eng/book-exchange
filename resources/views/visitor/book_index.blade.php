@extends('layouts.app')
@section('content')
<body>
    <div class="container">
        @auth
            <div class="row">
            <div class="col-md-6">
                {{Form::open(['route' => 'book.search', 'method' => 'POST', 'enctype' => 'multipart/form-data'])}}
                <div class="form-group">
                    <select name="type" class="form-control">
                        <option value="null">Choose Book Category</option>
                        @foreach($types as $type)
                        <option value="{{$type->id}}"> {{$type->type}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="submit" value="Search" class="btn btn-primary" style="display: inline;">
                    {{Form::close()}}
                </div>
                
            </div>
        </div>
        @endauth
        <div class="row">
            @forelse($books as $key => $book)
            <div class="col-md-4">
                <div class="card" style="width: 100%;">
                    <img class="card-img-top" src="{{asset('/contents/images/book/'.$book->image)}}" alt="Card image cap">
                    <div class="card-body">
                        <h1 class="card-title">{{$book->name}}</h1>
                        <h4 class="card-subtitle mb-2 text-muted">{{$book->author}}</h4>
                        <p class="card-text">{{$book->created_at->diffForHumans()}}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Price: {{number_format($book->price, 2)}}</li>
                        <li class="list-group-item">Group: {{Type::find($book->category)->type}}</li>
                      </ul>
                      <div class="card-body">
                        <a href="/book/{{$book->id}}" class="bt btn-primary" style="padding: 1em;">Show Details</a>
                      </div>
                 </div>
            </div>
            @empty
                <div class="card" style="margin:auto;">
                    <div class="card-header">
                        No Contents Found
                    </div>
                    <div class="card-body">
                        Coming Soon
                    </div>
                </div>
            @endforelse
        </div>
        <br>
        {{$books->links()}}
        <br>
    </div>
</body>

@endsection