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


                        <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Active</th>
                        <th>bio</th>
                        <th>Offere</th>

                        <th>IMG</th>
                        <th>video</th>
                        <th>create_at</th>


                        <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($advisors  as $advisor)

                            <tr>
                                <td>{{$advisor->id }}</td>
                                <td>{{$advisor->name }}</td>
                                <td>
                                    @if ($advisor->approved)
                                    <span class="badge bg-success">Approved</span>
                                    @else
                                    <span class="badge bg-danger">Not Approved</span>
                                    @endif
                                </td>
                                <td>{{$advisor->bio }}</td>
                                <td>{{$advisor->Offere }} $ </td>
                                <td style="/* CSS for image */
                                .clickable-image {
                                    transition: transform 0.3s ease;
                                }

                                .clickable-image:hover {
                                    transform: scale(1.5); /* زيادة الحجم إلى 150٪ */
                                }

                                /* CSS for modal */
                                .modal {
                                    display: none;
                                    position: fixed;
                                    z-index: 1000;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                    background-color: rgba(0, 0, 0, 0.7);
                                }

                                .modal-content {
                                    margin: auto;
                                    display: block;
                                    max-width: 80%;
                                    max-height: 80%;
                                }

                                .close {
                                    color: #fff;
                                    font-size: 30px;
                                    font-weight: bold;
                                    position: absolute;
                                    top: 15px;
                                    right: 35px;
                                    cursor: pointer;
                                }">

                                    <?php $imagePath = $advisor->getMedia('advisor_profile_image')->last()->getUrl(); ?>
                                    <img src="{{ $imagePath }}" alt="Uploaded Image" class="clickable-image" height="99" >
                                    <div id="imageModal" class="modal">
                                        <span class="close">&times;</span>
                                        <img class="modal-content" id="modalImage" width="600" height="600">
                                    </div>
                                </td>

                                <td>
                                    <?php $videoPath = $advisor->getMedia('advisor_Intro_video')->last()->getUrl(); ?>
                                    <video class="clickable-video" controls height="150" width="150" >
                                        <source src="{{ $videoPath }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div id="videoModal" class="modal">
                                        <span class="close">&times;</span>
                                        <video class="modal-content" id="modalVideo"  width="600" height="400">
                                            <source src="" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </td>

                                <td>{{ \Carbon\Carbon::parse($advisor->create_at)->format('d-m-Y') }}</td>





                                <td>
                                    <div class="d-flex">
                                        <form action="{{route('admin.users.destroy', $advisor->id) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger me-2" style="margin-right:20px"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @if (!$advisor->approved)
                                        <form action="{{route('admin.users.approve', $advisor->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">Approve</button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Approved</span>
                                    @endif
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

@section('script')
<script>
// JavaScript for modal
$(document).ready(function() {
    $(".clickable-image").click(function() {
        var imagePath = $(this).attr("src");
        $("#modalImage").attr("src", imagePath);
        $("#imageModal").css("display", "block");
    });

    $(".close").click(function() {
        $("#imageModal").css("display", "none");
    });
});

</script>
@endsection
