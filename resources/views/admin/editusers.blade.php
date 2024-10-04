@extends('admin.body.adminmaster')
@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="full_container mt-4">  
    <div class="card p-4">
        <div class="text-center">
            <h3 class="text-primary mt-4">Edit Role</h3>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                    <form action="{{ route('admins.userupdate', $roleToEdit->id) }}" method="POST">
                          @csrf
                          @method('PUT')
                    <div class="form-group">
                        <label for="name">KeyName</label>
                        <input type="text" class="form-control" id="terminal_id" name="terminal_id" value="{{ old('terminal_id', $roleToEdit->terminal_id) }}" required>
                    </div>
                    @if($errors->has('terminal_id'))
                        <div class="error text-danger">{{ $errors->first('terminal_id') }}</div>
                    @endif
                    <label for="role">Role Select</label>
                    <select class="form-control" name="role_id" id="role_id">
                        <option selected value="">Choose...</option>
                        @if($role->role_id == 1) <!-- Admin -->
                            <option value="2" {{old('role_id',$roleToEdit->role_id ) == 2 ? 'selected' : ''}}>Stockist</option>
                            <option value="3"  {{old('role_id',$roleToEdit->role_id ) == 3 ? 'selected' : ''}}>SubStockist</option>
                            <option value="4"  {{old('role_id',$roleToEdit->role_id ) == 4 ? 'selected' : ''}}>User</option>
                        @elseif($role->role_id == 2) <!-- Stockist -->
                            <option value="3"  {{old('role_id',$roleToEdit->role_id ) == 3 ? 'selected' : ''}} >SubStockist</option>
                            <option value="4"  {{old('role_id',$roleToEdit->role_id ) == 4 ? 'selected' : ''}} >User</option>
                        @elseif($role->role_id == 3 || $role->role_id == 4) <!-- SubStockist or User -->
                            <option value="4" @if($roleToEdit->role_id == 4) selected @endif>User</option>
                        @endif
                    </select>
                    @if($errors->has('role_id'))
                        <div class="error text-danger">{{ $errors->first('role_id') }}</div>
                    @endif
                </div>
                <div class="col-sm-6">
                   <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" value="{{ old('password', $roleToEdit->password) }}">
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePasswordVisibility()">
                                    <i id="toggleIcon" class="fa fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <input type="hidden" class="form-control" id="hidden" name="createdby" value="{{ $authid ?? 0 }}" required>
                    </div>
                    @if($errors->has('password'))
                        <div class="error text-danger">{{ $errors->first('password') }}</div>
                    @endif
                      <div class="form-group">
                        <label for="under_role">Select Under Role</label>
                        <select class="form-control" name="under_role_terminal_id" id="under_role_terminal_id" value="xyz">
                         <option selected value="">Choose KeyNames</option>
                            <option value="{{$insideid->terminal_id}}" {{ old('created_inside', $insideid->terminal_id) == $insideid->terminal_id ? 'selected' : '' }}>{{ $insideid->terminal_id ?? admin}}</option>
                            @if($role->role_id == 2) <!-- Stockist -->
                                <!-- Stockist can see a list of all SubStockists when creating a User -->
                            @endif
                            @if($role->role_id == 1) <!-- Stockist -->
                                <!-- Stockist can see a list of all SubStockists when creating a User -->
                            @endif
                        </select>
                        @if($errors->has('password'))
                        <div class="error  text-danger">{{ $errors->first('under_role_terminal_id') }}</div>
                    @endif
                    </div>
                </div>
            </div>
            <div class="text-center">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary">Update Role</button> 
            </div>
             </form> 
        </div>
    </div>
</div>

<!-- Same script for dynamic population and password toggle -->
<script>
$(document).ready(function() {
    $('#under_role_terminal_id').select2({
        placeholder: "Choose KeyNames",
        allowClear: true
    });

    $('#role_id').change(function() {
        var roleId = $(this).val();
        var loggedInRoleId = "{{ $role->role_id }}";
        var auth = "{{ $authid }}";

        $('#under_role_terminal_id').empty().prop('disabled', false);

        if (roleId) {
            if (roleId == 3 && loggedInRoleId == 2) {
                var roleCreatorId = "{{ $insideid->terminal_id }}"; 
                $('#under_role_terminal_id').append('<option value="' + roleCreatorId + '">' + roleCreatorId + '</option>');
            } else {
                $.ajax({
                    url: '{{ route("getTerminals") }}',
                    type: 'POST',
                    data: {
                        role_id: roleId,
                        logged_in_role_id: loggedInRoleId,
                        auth: auth,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#under_role_terminal_id').append('<option value="">Choose KeyNames</option>');
                            $.each(data, function(index, value) {
                                $('#under_role_terminal_id').append('<option value="' + value + '">' + value + '</option>');
                            });
                        } else {
                            $('#under_role_terminal_id').append('<option>No Terminals Available</option>');
                        }
                    }
                });
            }
        }
    });
});
function togglePasswordVisibility() {
    var passwordField = document.getElementById('password');
    var toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = "password";
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>

@endsection

@section('scripts')
@endsection
