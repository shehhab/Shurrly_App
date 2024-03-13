@extends('admin.layout')

@section('main')
<div class="content-wrapper">

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3 class="mb-3">Add Product</h3>
            <div class="card">
                <div class="card-body p-5">

                    <form method="POST" action="{{route('products.index') }}">
                        @csrf
                        <div class="form-group">
                            <label>Name Product</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="categories_id">
                                @foreach($cats as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                            </select>
                        </div>

                        @if(session('existing_product'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('existing_product') }}
                            </div>
                        @endif

                        <div class="text-center mt-5">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection
