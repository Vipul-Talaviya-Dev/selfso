@extends('admin.layout.master')

@section('title', 'Team Members')

@section('content')
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Team Members</div>
    </div>
    <div class="ibox-body">
        <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Mobile</th>
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
                        <td style="text-align: right;">
                            <!-- <a href="javascript:void(0);" data-id="{{ $user->id }}" class="btn btn-danger btn-sm delete" title="Delete"><i class="fa fa-remove"></i></a> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
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
                  url: "{{ route('admin.users') }}?id="+id,
                  success: function(response) { 
                    if(response.status) {
                        swal({
                          title: 'Status!',
                          text: "Your record has been Deleted.",
                          icon: 'success'
                        });
                        setTimeout(function(){ location.reload(true); }, 2000);
                    } else {
                        swal("Cancelled", "Invalid Selected Id :)", "error");        
                    }
                    // console.log(response);
                  }
                });
              }
            })
            }
        });

        $('#example-table').DataTable({
            pageLength: 10,
                //"ajax": './assets/demo/data/table_data.json',
                /*"columns": [
                    { "data": "name" },
                    { "data": "office" },
                    { "data": "extn" },
                    { "data": "start_date" },
                    { "data": "salary" }
                    ]*/
                });
    })
</script>
@endsection