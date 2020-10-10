@extends('admin.layout.master')

@section('title', 'Categories')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Categories</div>
        <a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add New</a>
    </div>
    <div class="ibox-body">
        @if($errors->any())
           @foreach ($errors->all() as $error)
               <div style="color: red;">* {{$error}}</div><br>
           @endforeach
        @endif
        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <a href="javascript:void(0);" data-id="{{ $category->id }}" class="btn {{ ($category->status == 1) ? 'btn-success' : 'btn-danger' }} btn-sm status" title="Status Change">{{ $category->status == 1 ? 'Active' : 'In-Active' }}</a>
                        </td>
                        <td style="text-align: right;">
                            <!-- <a href="{{ route('admin.category.edit', ['id' => $category->id]) }}" class="btn btn-success btn-sm" title="View"><i class="fa fa-eye"></i></a> | -->
                            <a href="javascript:void(0);" data-id="{{ $category->id }}" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Category Create</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="{{ route('admin.category.create') }}">
            @csrf
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control required" required value="{{ old('name') }}">
                </div>
                <div class="col-sm-12 form-group">
                    <label>&nbsp;</label><br>
                    <button class="btn btn-info" type="submit">Submit</button>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $("body").on("click", ".delete", function() {
            var id = $(this).attr('data-id');
            if(id) {
                swal({
                  title: "Are you sure?",
                  text: "You will be able to delete record!",
                  icon: "warning",
                  buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                  ],
                  dangerMode: true,
                }).then(function(isConfirm) {
                  if (isConfirm) {
                    $.ajax({
                      type: 'GET',
                      url: "{{ route('admin.category.delete') }}?id="+id,
                      success: function(response) { 
                        if(response.status) {
                            swal({
                              title: 'Delete!',
                              text: "Your record has been Deleted.",
                              icon: 'success'
                            });
                            setTimeout(function(){ location.reload(true); }, 2000);
                        } else {
                            swal("Cancelled", "Invalid Selected Id :)", "error");        
                        }
                      }
                    });
                  }
                });
            }
        });

        $("body").on("click", ".status", function() {
            var id = $(this).attr('data-id');
            if(id) {
                swal({
                  title: "Are you sure?",
                  text: "You will be able to status change!",
                  icon: "warning",
                  buttons: [
                    'No, cancel it!',
                    'Yes, I am sure!'
                  ],
                  dangerMode: true,
                }).then(function(isConfirm) {
                  if (isConfirm) {
                    $.ajax({
                      type: 'GET',
                      url: "{{ route('admin.category.statusChange') }}?id="+id,
                      success: function(response) { 
                        if(response.status) {
                            swal({
                              title: 'Status!',
                              text: "Your record has updated.",
                              icon: 'success'
                            });
                            setTimeout(function(){ location.reload(true); }, 2000);
                        } else {
                            swal("Cancelled", "Invalid Selected Id :)", "error");        
                        }
                      }
                    });
                  }
                });
            }
        });
    });
</script>
@endsection