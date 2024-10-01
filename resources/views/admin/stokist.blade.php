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
                    </div>
                  <div class="float-right">
                     <div class="form-group" style="width: 100%;"> <!-- width ko apni pasand ke hisaab se adjust kar sakte ho -->
                         <form method="GET" action="{{ route('stokistlist') }}" id="roleFilterForm">
                             <div class="form-row d-flex">
                                 <!-- Select Role Dropdown -->
                                 <div class="col">
                                     <select class="form-control" id="roleSelect" name="role_id" onchange="document.getElementById('roleFilterForm').submit()">
                                       <option value="" {{ $role_id === null ? 'selected' : '' }}>All Users</option>
                                         @if($roles->role_id == 1)
                                           <option value="2" {{ $role_id == 2 ? 'selected' : '' }}>Stockist</option>
                                           <option value="3" {{ $role_id == 3 ? 'selected' : '' }}>Sub Stockist</option>
                                           <option value="4" {{ $role_id == 4 ? 'selected' : '' }}>Users</option>
                                         @elseif($roles->role_id == 2)
                                           <option value="3" {{ $role_id == 3 ? 'selected' : '' }}>Sub Stockist</option>
                                           <option value="4" {{ $role_id == 4 ? 'selected' : '' }}>Users</option>
                                         @elseif($roles->role_id == 3)
                                           <option value="4" {{ $role_id == 4 ? 'selected' : '' }}>Users</option>
                                         @endif
                                   </select>
                 
                                 </div>
                 
                                 <!-- Search Terminal ID Input -->
                                 <div class="col">
                                     <input type="text" name="search" class="form-control" placeholder="Search KeyName..." value="{{ request('search') }}" />
                                 </div>
                 
                                 <!-- Search Button -->
                                 <div class="col-auto">
                                     <button type="submit" class="btn btn-primary">Search</button>
                                 </div>
                 
                                 <!-- Reset Button -->
                                  @if (request('role_id') || request('search'))
                                    <div class="col-auto">
                                     <a href="{{ route('stokistlist') }}" class="btn btn-secondary">Reset</a>
                                 </div>
                                  @endif
                             </div>
                         </form>
                     </div>
                </div>
                </div>
                <div class="table_section padding_infor_info">
                    <div class="table-responsive"> <!-- Changed class to table-responsive for better responsiveness -->
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
                                                <button class="btn btn-sm btn-success me-1" data-toggle="modal" data-target="#addModal{{ $admin->id }}">
                                                    <i class="bi bi-plus-square"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger ml-1" data-toggle="modal" data-target="#deductModal{{ $admin->id }}">
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
                                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#statusModal{{ $admin->id }}">Active</button>
                                        @else
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#statusModal{{ $admin->id }}">Inactive</button>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                             <a href="{{$admin->id}}/edit" class="btn btn-sm btn-warning mr-1"><i class="fa fa-edit"></i></a>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $admin->id }}"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('transaction.history', ['id' => $admin->id]) }}" class="btn btn-sm btn-success">Transaction History</a>
                                    </td>
                                </tr>

                                <!-- Status Modal -->
                                <div class="modal fade" id="statusModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="statusModalLabel{{ $admin->id }}">{{ $admin->status == 1 ? 'Inactive' : 'Activate' }} User</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to {{ $admin->status == 1 ? 'Incative' : 'active' }} this user?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admins.updateStatus', $admin->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $admin->status == 1 ? 0 : 1 }}">
                                                    <button type="submit" class="btn btn-primary">{{ $admin->status == 1 ? 'Inactive' : 'Activate' }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $admin->id }}">Delete User</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this user?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Modal (Dummy) -->
                                <div class="modal fade" id="editModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $admin->id }}">Edit User</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Add your edit form here -->
                                                <p>Form to edit user details will go here.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--addwalletmodal-->
                                <div class="modal fade" id="addModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="addModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addModalLabel{{ $admin->id }}">Add Wallet Amount</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer">
                                                <form id="operationForm" action="{{ route('wallet', ['id' => $admin->id]) }}" method="POST">
                                                    @csrf
                                                    <label id="modalLabel" for="modalAmount">Amount</label>
                                                    <input type="number" name="amount" id="modalAmount" class="form-control" required step="0.01">
                                                     <input type="hidden" name="operation" value="add">
                                                      <input type="hidden" name="authid" value="{{$authid}}">
                                                    </div>
                                                    <div class="modal-footer">
                                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                       <button type="submit" class="btn btn-success" id="modalSubmitBtn">Submit</button>
                                                   </div>
                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                   
                                
                                <!--addwalletmodal-->
                                 <!--deductwalletmodal-->
                                <div class="modal fade" id="deductModal{{ $admin->id }}" tabindex="-1" role="dialog" aria-labelledby="deductModalLabel{{ $admin->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deductModalLabel{{ $admin->id }}">Deduct Wallet Amount</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer">
                                                <form id="operationForm" action="{{ route('wallet', ['id' => $admin->id]) }}" method="POST">
                                                    @csrf
                                                    <label id="modalLabel" for="modalAmount">Amount</label>
                                                    <input type="number" name="amount" id="modalAmount" class="form-control" required step="0.01">
                                                     <input type="hidden" name="operation" value="deduct">
                                                     <input type="hidden" name="authid" value="{{$authid}}">
                                                    </div>
                                                    <div class="modal-footer">
                                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                       <button type="submit" class="btn btn-success" id="modalSubmitBtn">Submit</button>
                                                  </div>
                                                </form>
                                            </div>
                                            </div>
                                        </div>
                                <!--deductwalletmodal-->
                               @empty
                                <tr>
                                    <td colspan="12"><marquee behavior="alternate" direction=""><sapn style="color:red;">!!</span> <span style="color:black;">No  user found</span> <span style="color:red;">!!</span></marquee></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="d-flex float-right mt-3"> 
    {{ $admins->appends(['role_id' => $role_id])->links('admin.custom') }} 
</div>
</div>
@endsection

@section('scripts')

@endsection
