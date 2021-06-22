@extends('layouts.admin')
@section('content')
<body>
    <div class="card">
        <div class="card-header">
            <h4>Book Index</h4>
        </div>
        <div class="card-body">
            <a href="/admin/book/create" class="btn btn-primary">Add New Book</a>
            <table class="table table-striped table-responsive table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>category</th>
                        <th>Price</th>
                        <th>Created At</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $key => $book)
                        <tr>
                            <td> {{$key+1}} </td>
                            <td> {{$book->name}} </td>
                            <td> {{$book->category}} </td>
                            <td> {{$book->price}} </td>
                            <td> {{$book->created_at->diffForHumans()}} </td>
                            <td>
                                <a href="/admin/book/{{$book->id}}/edit" class="btn btn-success">
                                    <i class="fa fa-check"></i>

                                </a>
                                {{Form::open(['method'=>'DELETE', 'route'=>['admin.book_destroy',$book->id],'style'=>'display:inline;']) }}
                                <button type="submit" style="display:inline;" class="btn btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                                {{Form::close()}}
                            </td>
                        </tr>
                    @empty
                        <tr class="table-warning">
                            <td colspan="6">
                                <center>Coming Soon</center>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
@endsection