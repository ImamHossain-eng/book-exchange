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
                        <th>Price</th>
                        <th>Group/Area/Category</th>
                        <th>Status</th>
                        <th>Creator</th>
                        <th>Created At</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $key => $book)
                        <tr @if($book->user !== 'admin') 
                            class="table-danger"
                            @else 
                            class="table-warning"
                            @endif
                            >
                            <td> {{$key+1}} </td>
                            <td> {{$book->name}} </td>
                            <td> {{$book->price}} </td>
                            <td> {{Type::find($book->category)->type}} </td>
                            <td>
                                @if($book->confirmed == 1)
                                Published
                                @elseif($book->confirmed == 0)
                                Pending
                                @elseif($book->confirmed == 2)
                                Out of Stock
                                @else 
                                Processing
                                @endif
                            </td>
                            <td>
                                @if($book->user !== 'admin')
                                User: {{User::find($book->user)->email}}
                                @else 
                                admin 
                                @endif
                            </td>
                            <td> {{$book->created_at->diffForHumans()}} </td>
                            <td>
                                <a href="/admin/book/{{$book->id}}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
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
            {{$books->links()}}
        </div>
    </div>
</body>
@endsection