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
            <h3 class="text-primary mt-4">Add Roles</h3>
           
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                <form action="{{ route('store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">KeyName</label>
                        <input type="text" class="form-control" id="terminal_id" name="terminal_id" value="{{ $creator_id->terminal_id ?? '' }}" required>
                    </div>
                    @if($errors->has('terminal_id'))
                        <div class="error text-danger">{{ $errors->first('terminal_id') }}</div>
                    @endif
                  <label for="role">Role Select</label>
                     <select class="form-control" name="role_id" id="role_id">
                         <option selected value="">Choose...</option>
                     
                         @if($role->role_id == 1) <!-- Role ID 1 ke liye -->
                             <option value="2">Stockist</option>
                             <option value="3">SubStockist</option>
                             <option value="4">User</option>
                         @elseif($role->role_id == 2) <!-- Role ID 2 ke liye -->
                             <option value="3">SubStockist</option>
                             <option value="4">User</option>
                         @elseif($role->role_id == 3 || $role->role_id == 4) <!-- Role ID 3 aur 4 ke liye -->
                             <option value="4">User</option>
                         @endif
                     </select>
                    @if($errors->has('role_id'))
                        <div class="error">{{ $errors->first('role_id') }}</div>
                    @endif
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <input type="hidden" class="form-control" id="hidden" name="createdby" value="{{$authid ?? 0}}" required>
                    
                </div>
                @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password') }}</div>
                @endif

                <div class="form-group">
                    <label for="under_role">Select Under Role</label>
                    <select class="form-control" name="under_role_terminal_id" id="under_role_terminal_id" 
                        @if($role->role_id == 2 && old('role_id') == 3) disabled @endif>
                        <!-- Stockist creating SubStockist, terminal_id is pre-filled -->
                        @if($role->role_id == 2 && old('role_id') == 3)
                            <option value="{{ $creator_id->terminal_id }}" selected>{{ $creator_id->terminal_id }}</option>
                        @else
                            <option selected value="{{ $creator_id->terminal_id ?? '' }}">{{ $creator_id->terminal_id ?? 'Choose KeyNames' }}</option>
                        @endif
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button type="submit" class="btn btn-primary">Add Role</button> 
            </div>
            </form> 
        </div>
    </div>
</div>

<!-- Add the AJAX script to dynamically populate the terminal list -->
<script>
 $(document).ready(function() {
    // Initialize Select2 for the dropdown
    $('#under_role_terminal_id').select2({
        placeholder: "Choose KeyNames",
        allowClear: true
    });

    // Handle role_id change and populate the terminal list
    $('#role_id').change(function() {
        var roleId = $(this).val();
        $('#under_role_terminal_id').empty().append('<option selected value="">Choose KeyNames</option>').prop('disabled', true);

        if (roleId) {
            $.ajax({
                url: '{{ route("getTerminals") }}',  // Ensure your route is correct
                type: 'POST',
                data: {
                    role_id: roleId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.length > 0) {
                        $('#under_role_terminal_id').prop('disabled', false);
                        $.each(data, function(index, value) {
                            $('#under_role_terminal_id').append('<option value="' + value + '">' + value + '</option>');
                        });
                        // Reinitialize Select2 after adding new options
                        $('#under_role_terminal_id').select2({
                            placeholder: "Choose KeyNames",
                            allowClear: true
                        });
                    } else {
                        $('#under_role_terminal_id').append('<option>No Terminals Available</option>');
                    }
                },
                error: function() {
                    alert('Error fetching terminal IDs.');
                }
            });
        }
    });
});

</script>
    <script>
$(document).ready(function() {
    // Initialize Select2 for the dropdown
    $('#under_role_terminal_id').select2({
        placeholder: "Choose KeyNames",
        allowClear: true
    });
    // Handle role_id change and manage terminal dropdown
    $('#role_id').change(function() {
        var roleId = $(this).val();
        var roleCreatorId = "{{ $creator_id->terminal_id }}"; // Terminal ID of logged-in Stockist
        if (roleId == 3 && "{{ $role->role_id }}" == 2) {
            // Stockist creating SubStockist, auto-fill terminal_id
            $('#under_role_terminal_id').empty().append('<option value="' + roleCreatorId + '" selected>' + roleCreatorId + '</option>').prop('disabled', true);
        } else {
            // Reset for other roles (Admin or SubStockist/User roles)
            $('#under_role_terminal_id').empty().append('<option selected value="">Choose KeyNames</option>').prop('disabled', false);
        }
    });
});

</script>


@endsection

@section('scripts')

@endsection
