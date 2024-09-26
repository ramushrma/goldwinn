@extends('admin.body.adminmaster')
@section('content')

<!-- Wallet and Add Money section -->
<div class="col-md-12">
    <div class="row margin_top_30">
        <!-- Wallet Section -->
        <div class="col-md-6">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                        <h2>Wallet</h2>
                    </div>
                </div>
                <div class="wallet_section padding_infor_info">
                    <h3>Current Balance: $<span id="wallet-balance">{{$wallet}}</span></h3>
                </div>
            </div>
        </div>

        <!-- Add Money Section -->
        <div class="col-md-6">
            <div class="white_shd full margin_bottom_30">
                <div class="full graph_head">
                    <div class="heading1 margin_0">
                        <h2>Add Money</h2>
                    </div>
                </div>
                <div class="add_money_section padding_infor_info">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addMoneyModal">Add Money</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Money Modal -->
<div class="modal fade" id="addMoneyModal" tabindex="-1" role="dialog" aria-labelledby="addMoneyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMoneyModalLabel">Add Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add_money')}}" method="post">
                @csrf
            <div class="modal-body">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter amount">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="submit" class="btn btn-primary" id="add-money-btn">Add Money</button>
            </div>
             </form>
        </div>
    </div>
</div>

<!-- Table section -->
<div class="col-md-12">
    <div class="white_shd full margin_bottom_30">
        <div class="full graph_head">
            <div class="heading1 margin_0">
                <h2>Responsive Tables</h2>
            </div>
        </div>
        <div class="table_section padding_infor_info">
            <div class="table-responsive-sm">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Serial NO.</th>
                            <th>Money</th>
                            <th>Date And Time</th>
                        </tr>
                    </thead>
                    <tbody id="transactions-table">
                        @if($wallet_history->isNotEmpty())
                        @foreach($wallet_history as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->amount}}</td>
                            <td>{{$item->created_at}}</td>
                        </tr>
                        
                        @endforeach
                        @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endif
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

@endsection
