@extends('admin.layout.master')

@section('title', 'Users')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Users</div>
    </div>
    <div class="ibox-body">
        <form method="get">
            <div class="row">
                <div class="col-sm-4 form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control required" required value="{{ request('name') }}">
                </div>
                <div class="col-sm-4 form-group">
                    <label>&nbsp;</label><br>
                    <button class="btn btn-info" type="submit">Submit</button>
                    <a href="{{ route('admin.users') }}" class="btn btn-warning"><i class="fa fa-refresh"></i></a>
                </div>
            </div>
        </form>
        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $user->fullName() }}</td>
                        <td>{{ $user->email ?: 'N/A' }}</td>
                        <td>{{ $user->mobile ?: 'N/A' }}</td>
                        <td>
                            <a href="javascript:void(0);" data-id="{{ $user->id }}" class="btn {{ ($user->status == 1) ? 'btn-success' : 'btn-danger' }} btn-sm status" title="Status Change">{{ $user->status == 1 ? 'Active' : 'In-Active' }}</a>
                        </td>
                        <td style="text-align: right;">
                            <a href="javascript:void(0);" data-id="{{ $user->id }}" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="float-right">
            {!! $users->appends(['name' => request('name')])->links() !!}
        </div>
        <p><br></p>
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
                      url: "{{ route('admin.userDelete') }}?id="+id,
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
                      url: "{{ route('admin.userStatusChange') }}?id="+id,
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