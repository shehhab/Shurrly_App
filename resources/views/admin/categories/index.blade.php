@extends('admin.layout')

@section('main')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Users </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3>All Products</h3>
                                <a href="{{url('dashboard/add_Skills')}}"  class="btn btn-success">
                                    Add new
                                </a>
                            </div>
                            @if(Session::has('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif


                        <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                        <th>ID</th>
                        <th>Skill Name</th>
                        <th>Catogories</th>
                        <th>create_at</th>
                        <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

            @foreach($categories as $index => $category)

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $category->name}}</td>
                <td>n/o</td>
                <td>{{ $category->created_at}}</td>
                <td>

                        <div class="d-flex">
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="me-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger me-2" style="margin-right:20px"><i class="fas fa-trash"></i></button>
                            </form>

                            <form action="{{ route('categories.edit', $category->id) }}" method="GET" >
                                @csrf
                                <button type="submit" class="btn btn-primary ms-2" ><i class="fas fa-edit"></i></button>
                            </form>
                        </div>

                </td>

            </tr>
            @endforeach

                        </tbody>
                        </table>



                        </div>

                        </div>

                        </div>
                        </div>

                </div>


        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection
