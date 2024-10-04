@extends('admin.body.adminmaster')
@section('content')
@if(session()->has('message'))
<div class="alert alert-success">
    {{ session()->get('message') }}
</div>
@endif
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                        <h2>Users</h2>
                        <select id="stockist-select" class="form-control select2">
                            <option value="">Select Stockist</option>
                            @foreach($admins as $admin)
                            @if($admin->role_id == 2)
                            <!-- Stockist role -->
                            <option value="{{ $admin->id }}">{{ $admin->terminal_id }}</option>
                            @endif
                            @endforeach
                        </select>
                        <select id="substockist-select" class="form-control select2 mt-1">
                            <option value="">Select Substockist</option>
                        </select>
                        <select id="user-select" class="form-control select2 ml-1">
                            <option value="">Select User</option>
                        </select>
                     
                    </div>
                    <div class="table_section padding_infor_info">
                        <div class="table-responsive">
                            <!-- Changed class to table-responsive for better responsiveness -->
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>

                                        <th>Id</th>
                                        <th>Role</th>
                                        <th>KEY NAME</th>
                                        <th>PASSWORD</th>
                                        <th>WALLET</th>
                                        <th>DAY WALLET</th>
                                        <th>TODAY ADD& WALLET</th>
                                        <th>CREATED DATE</th>
                                        <th>STATUS</th>
                                        <th>ACTION</th> <!-- Add Action column -->
                                        <th>TransactionHistory</th> <!-- Add Action column -->
                                    </tr>
                                </thead>
                                <tbody class="tdata">
                                    @forelse($admins as $admin)
                                    <tr>
                                        <td>{{ $admin->id }}</td>
                                        <td>
                                            @if($admin->role_id == 1)
                                            <b class="text-primary">Admin</b>
                                            @elseif($admin->role_id == 2)
                                            <b class="text-primary">Stockist</b>
                                            @elseif($admin->role_id == 3)
                                            <b class="text-primary">Substockist</b>
                                            @elseif($admin->role_id == 4)
                                            <b class="text-primary">User</b>
                                            @endif
                                        </td>
                                        <td>{{ $admin->terminal_id }}</td>
                                        <td>{{ $admin->password }}</td>
                                        <td class="text-center">
                                            {{ $admin->wallet }}
                                            <div class="d-flex align-items-center justify-content-center">
                                                <span class="me-2"></span>
                                                <div class="d-flex">
                                                    <button class="btn btn-sm btn-success me-1" data-toggle="modal"
                                                        data-target="#addModal{{ $admin->id }}">
                                                        <i class="bi bi-plus-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger ml-1" data-toggle="modal"
                                                        data-target="#deductModal{{ $admin->id }}">
                                                        <i class="bi bi-dash-square"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $admin->day_wallet }}</td>
                                        <td>{{ $admin->today_add_money }}</td>
                                        <td>{{ $admin->created_at }}</td>
                                        <td>
                                            @if($admin->status == 1)
                                            <button class="btn btn-sm btn-success" data-toggle="modal"
                                                data-target="#statusModal{{ $admin->id }}">Active</button>
                                            @else
                                            <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#statusModal{{ $admin->id }}">Inactive</button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{$admin->id}}/edit" class="btn btn-sm btn-warning mr-1"><i
                                                        class="fa fa-edit"></i></a>
                                                <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                    data-target="#deleteModal{{ $admin->id }}"><i
                                                        class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('transaction.history', ['id' => $admin->id]) }}"
                                                class="btn btn-sm btn-success">Transaction History</a>
                                        </td>
                                    </tr>

                                    <!-- Status Modal -->
                                    <div class="modal fade" id="statusModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="statusModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="statusModalLabel{{ $admin->id }}">
                                                        {{ $admin->status == 1 ? 'Inactive' : 'Activate' }} User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to
                                                    {{ $admin->status == 1 ? 'Inactivate' : 'activate' }} this user?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admins.updateStatus', $admin->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status"
                                                            value="{{ $admin->status == 1 ? 0 : 1 }}">
                                                        <button type="submit"
                                                            class="btn btn-primary">{{ $admin->status == 1 ? 'Inactive' : 'Activate' }}</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="deleteModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $admin->id }}">Delete
                                                        User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this user?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admins.destroy', $admin->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add Modal -->
                                    <div class="modal fade" id="addModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="addModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="addModalLabel{{ $admin->id }}">Add
                                                        Wallet Amount</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-footer">
                                                    <form id="operationForm"
                                                        action="{{ route('admins.addwallet', ['id' => $admin->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <label id="modalLabel" for="modalAmount">Amount</label>
                                                        <input type="number" name="amount" id="modalAmount"
                                                            class="form-control" required step="0.01">
                                                        <input type="hidden" name="operation" value="add">
                                                        <input type="hidden" name="authid" value="{{$authid}}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success"
                                                        id="modalSubmitBtn">Submit</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Deduct Modal -->
                                    <div class="modal fade" id="deductModal{{ $admin->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="deductModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deductModalLabel{{ $admin->id }}">Deduct
                                                        Money from User</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admins.addwallet', $admin->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="deduct-amount">Enter Amount to Deduct</label>
                                                            <input type="number" name="deduct-amount"
                                                                class="form-control" id="deduct-amount" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Deduct
                                                                Money</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="10">No users found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>
    @endsection

    @section('scripts')
   <script>
                        $(document).ready(function() {
                            // Initialize Select2 for all select elements
                            $('.select2').select2();

                            // Stockist selection
                            $('#stockist-select').on('change', function() {
                                var stockistId = $(this).val();
                                if (stockistId) {
                                    // Get Substockists
                                    $.ajax({
                                        url: '/getSubstockists/' + stockistId,
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            $('#substockist-select')
                                                .empty(); // Clear previous options
                                            $('#substockist-select').append(
                                                '<option value="">Select Substockist</option>'
                                            );
                                            $.each(data, function(key, value) {
                                                $('#substockist-select').append(
                                                    '<option value="' + value
                                                    .id + '">' + value
                                                    .terminal_id + '</option>');
                                            });

                                            // Clear user dropdown since stockist has changed
                                            $('#user-select').empty();
                                            $('#user-select').append(
                                                '<option value="">Select User</option>');

                                            // Update table based on Stockist selection
                                            updateTable(stockistId, null,
                                                null
                                                ); // Passing Stockist ID to updateTable function
                                        }
                                    });
                                } else {
                                    $('#substockist-select').empty();
                                    $('#user-select').empty();
                                    $('.tdata').empty(); // Clear table if no stockist is selected
                                }
                            });

                            // Substockist selection
                            $('#substockist-select').on('change', function() {
                                var substockistId = $(this).val();
                                if (substockistId) {
                                    // Get Users under selected Substockist
                                    $.ajax({
                                        url: '/getUsers/' + substockistId,
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            $('#user-select')
                                                .empty(); // Clear previous options
                                            $('#user-select').append(
                                                '<option value="">Select User</option>');
                                            $.each(data, function(key, value) {
                                                $('#user-select').append(
                                                    '<option value="' + value
                                                    .terminal_id + '">' + value
                                                    .terminal_id + '</option>');
                                            });

                                            updateTable(null, substockistId,
                                                null
                                                ); // Passing Substockist ID to updateTable function
                                        }
                                    });
                                } else {
                                    $('#user-select').empty();
                                    $('.tdata').empty(); // Clear table if no substockist is selected
                                }
                            });

                            // User selection
                            $('#user-select').on('change', function() {
                                var terminalId = $(this).val();
                                if (terminalId) {
                                    // Fetch and display user details based on terminal_id
                                    $.ajax({
                                        url: '/getUserByTerminal/' + terminalId,
                                        type: 'GET',
                                        dataType: 'json',
                                        success: function(data) {
                                            $('.tdata')
                                                .empty(); // Clear previous table rows
                                            if (data) { // Check if valid data is returned
                                                $('.tdata').append('<tr>' +
                                                    '<td>' + data.id + '</td>' +
                                                    '<td>' + (data.role_id == 1 ?
                                                        "<b class='text-primary'>Admin</b>" :
                                                        data.role_id == 2 ?
                                                        "<b class='text-primary'>Stockist</b>" :
                                                        data.role_id == 3 ?
                                                        "<b class='text-primary'>Substockist</b>" :
                                                        "<b class='text-primary'>User</b>"
                                                    ) + '</td>' +
                                                    '<td>' + data.terminal_id +
                                                    '</td>' +
                                                    '<td>' + data.password + '</td>' +
                                                    '<td class="text-center">' + data
                                                    .wallet +
                                                    '<div class="d-flex align-items-center justify-content-center">' +
                                                    '<div class="d-flex">' +
                                                    '<button class="btn btn-sm btn-success me-1" data-toggle="modal" data-target="#addModal' +
                                                    data.id + '">' +
                                                    '<i class="bi bi-plus-square"></i>' +
                                                    '</button>' +
                                                    '<button class="btn btn-sm btn-danger ml-1" data-toggle="modal" data-target="#deductModal' +
                                                    data.id + '">' +
                                                    '<i class="bi bi-dash-square"></i>' +
                                                    '</button>' +
                                                    '</div>' +
                                                    '</div></td>' +
                                                    '<td>' + data.day_wallet + '</td>' +
                                                    '<td>' + data.today_add_money +
                                                    '</td>' +
                                                    '<td>' + data.created_at + '</td>' +
                                                    '<td>' + (data.status == 1 ?
                                                        '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#statusModal' +
                                                        data.id + '">Active</button>' :
                                                        '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#statusModal' +
                                                        data.id + '">Inactive</button>'
                                                    ) +
                                                    '</td>' +
                                                    '<td>' +
                                                    '<div class="d-flex">' +
                                                    '<a href="' + data.id +
                                                    '/edit" class="btn btn-sm btn-warning mr-1"><i class="fa fa-edit"></i></a>' +
                                                    '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal' +
                                                    data.id +
                                                    '"><i class="fa fa-trash"></i></button>' +
                                                    '</div>' +
                                                    '</td>' +
                                                    '<td><a href="/transaction/history/' +
                                                    data.id +
                                                    '" class="btn btn-sm btn-success">Transaction History</a></td>' +
                                                    '</tr>');

                                                // Add the modals dynamically for the Add, Deduct, Status, and Delete actions
                                                $('#modalContainer').append(`
                        <!-- Add Wallet Modal -->
                        <div class="modal fade" id="addModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="addModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addModalLabel${data.id}">Add Wallet Amount</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add Wallet form or content here -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deduct Wallet Modal -->
                        <div class="modal fade" id="deductModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="deductModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deductModalLabel${data.id}">Deduct Wallet Amount</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Deduct Wallet form or content here -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Change Status Modal -->
                        <div class="modal fade" id="statusModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel${data.id}">Change Status</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to change the status of this user?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteModal${data.id}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel${data.id}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel${data.id}">Delete User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this user?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.log('Error: ' +
                                                error); // Log error if AJAX request fails
                                        }
                                    });
                                } else {
                                    $('.tdata').empty(); // Clear table if no user is selected
                                }
                            });

                            // Function to update table dynamically
                            function updateTable(stockistId, substockistId, terminalId) {
                                $.ajax({
                                    url: '/getTableData', // Backend route for fetching data
                                    type: 'GET',
                                    data: {
                                        stockist_id: stockistId,
                                        substockist_id: substockistId,
                                        terminal_id: terminalId // Add terminal_id for user filtering
                                    },
                                    dataType: 'json',
                                    success: function(data) {
                                            $('.tdata').empty(); // Clear previous table rows
                                            $.each(data, function(key, value) {
                                                var statusButton = value.status == 1 ?
                                                    '<button class="btn btn-sm btn-success" data-toggle="modal" data-target="#statusModal' +
                                                    value.id + '">Active</button>' :
                                                    '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#statusModal' +
                                                    value.id + '">Inactive</button>';

                                                var editButton = '<a href="/' + value.id +
                                                    '/edit" class="btn btn-sm btn-warning mr-1"><i class="fa fa-edit"></i></a>';
                                                var deleteButton =
                                                    '<button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal' +
                                                    value.id +
                                                    '"><i class="fa fa-trash"></i></button>';
                                                var transactionHistory =
                                                    '<a href="/transaction/history/' + value
                                                    .id +
                                                    '" class="btn btn-sm btn-success">Transaction History</a>';

                                                // Combine wallet data and buttons into one column
                                                var walletColumn = `
                                                    <div class="text-center">
                                                         ${value.wallet} <!-- Wallet data -->
                                                         <div class="d-flex align-items-center justify-content-center mt-2">
                                                             <button class="btn btn-sm btn-success me-1" data-toggle="modal" data-target="#addModal${value.id}">
                                                                 <i class="bi bi-plus-square"></i>
                                                             </button>
                                                             <button class="btn btn-sm btn-danger ml-1" data-toggle="modal" data-target="#deductModal${value.id}">
                                                                 <i class="bi bi-dash-square"></i>
                                                             </button>
                                                         </div>
                                                     </div>
                                                 `;

                                                $('.tdata').append('<tr>' +
                                                    '<td>' + value.id + '</td>' +
                                                    '<td>' + value.role_id + '</td>' +
                                                    '<td>' + value.terminal_id + '</td>' +
                                                    '<td>' + value.password + '</td>' +
                                                    '<td>' + walletColumn + '</td>' +
                                                    // Wallet column with buttons underneath
                                                    '<td>' + value.day_wallet + '</td>' +
                                                    '<td>' + value.today_add_money +
                                                    '</td>' +
                                                    '<td>' + value.created_at + '</td>' +
                                                    '<td>' + statusButton + '</td>' +
                                                    '<td><div class="d-flex">' +
                                                    editButton + deleteButton +
                                                    '</div></td>' +
                                                    '<td>' + transactionHistory + '</td>' +
                                                    '</tr>');
                                            });
                                        }


                                        ,
                                    error: function(xhr, status, error) {
                                        console.log('Error: ' +
                                            error); // Log error if AJAX request fails
                                    }
                                });
                            }

                        });
                        </script>
    @endsection