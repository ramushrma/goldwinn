@extends('admin.body.adminmaster')

@section('content')
<div class="full_container mt-4">  
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @endif           
    <div class="card p-2">
        <div class="text-center">
            <h4 class="text-primary">Add Roles</h4>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <!-- Correct form action and method -->
                <form action="{{ route('createRole') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">KeyName</label>
                        <input type="text" class="form-control" id="terminal_id" name="terminal_id">
                    </div>
                    @if($errors->has('terminal_id'))
                        <div class="error">{{ $errors->first('terminal_id') }}</div>
                    @endif

                    <div class="form-group">
                        <label for="role">Role Select</label>
                        <select class="form-control" name="role_id" id="role_id">
                            <option selected value="">Choose...</option>
                            <option value="1">Admin</option>
                            <option value="2">Stockist</option>
                            <option value="3">SubStockist</option>
                            <option value="4">User</option>
                        </select>
                        @if($errors->has('role_id'))
                            <div class="error">{{ $errors->first('role_id') }}</div>
                        @endif
                    </div>
                    
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary">Add Role</button>    
                </form>  
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password') }}</div>
                @endif

                <div class="form-group">
                    <label for="under_role">Select Under Role</label>
                    <select class="form-control" name="terminalid" id="terminalid" disabled>
                        <option selected value="">Choose KeyNames</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#role_id').change(function() {
        var roleId = $(this).val();

        // Clear and disable the second select
        $('#terminalid').empty().append('<option selected value="">Choose KeyNames</option>').prop('disabled', true);

        if(roleId) {
            $.ajax({
                url: '{{ route("getTerminals") }}',
                type: 'POST',
                data: {
                    role_id: roleId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // Enable the second select and append options
                    $('#terminalid').prop('disabled', false);
                    $.each(data, function(index, value) {
                        $('#terminalid').append('<option value="' + value + '">' + value + '</option>');
                    });
                },
                error: function() {
                    alert('Error fetching terminal IDs.');
                }
            });
        }
    });
});
</script>
@endsection
