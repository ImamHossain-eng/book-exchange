@extends('layouts.admin')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h4>Edit Existing Book</h4>
        </div>
        <div class="card-body">
            {{Form::open(['route' => ['admin.book_update', $book->id], 'method' => 'POST', 'enctype'=>'multipart/form-data'])}}
                {{method_field('PUT')}}
                <div class="form-group">
                    <input type="text" name="name" value="{{$book->name}}" placeholder="Enter Book Name" class="form-control">
                </div><br>
                <div class="form-group">
                    <input type="number" name="price" value="{{$book->price}}" placeholder="Enter Book Price" class="form-control">
                </div><br>
                <div class="form-group">
                    <input type="file" name="image" class="form-control">
                </div><br>
                <select name="category" class="form-control">
                    <option value="null">Choose Book Type....</option>
                    @foreach($types as $type)
                        <option value="{{$type->id}}"> {{$type->type}} </option>
                    @endforeach
                </select><br>
                <input type="submit" value="Save" class="btn btn-primary">
            {{Form::close()}}
        </div>
    </div>
</body>

@endsection