
@extends('admin.body.adminmaster')
@section('content')

<div class="col-md-12 margin_top_30">
                           <div class="white_shd full margin_bottom_30">
                              <div class="full graph_head">
                                 <div class="heading1 margin_0">
                                    <h2>Responsive Tables</h2>
                                 </div>
                              </div>
                              <div class="table_section padding_infor_info">
                                              <form id="filterForm">
                <div class="row" style="backgorund-color: #343a40; padding: 10px; border-radius: 5px; ">
                    
                    <div class="col-md-1 ">
                        <!--<input type="text" class="btn btn-primary" onclick="filterTable()">Filter</button>-->
                        <h4 class="city">Filter:</h4>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <!--<label for="result_time">Result Time</label>-->
                            <input type="text" class="form-control" id="result_time" placeholder="Enter result time">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <!--<label for="barcode_number">Barcode Number</label>-->
                            <input type="text" class="form-control" id="barcode_number" placeholder="Enter barcode number">
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <button type="button" class="btn btn-primary" onclick="filterTable()">Filter</button>
                        <button type="button" class="btn btn-secondary" onclick="restoreFilter()">Reset Filter</button>
                    </div>
                </div>
            
            </form>
             
                <div class="table-responsive-sm">
                     <table id="example" class="table table-striped" style="width:250%">
                    <thead>
    <tr>
        <th>Sr.Num.</th>
        <th>Result Time</th>
        <th>Quantity</th>
        <th>Total Points</th>
        <th>Win Points</th>
        <th>Bet Details</th>
        <th>Game Name</th>
        <th>Terminal ID</th>
        <th>Status</th>
        <th>Order ID</th>
        <th>Barcode Number</th>
        <th>Created At</th>
    </tr>
</thead>
                        <tbody>
                            @php
                                $card_info = [
                                    '1' => 'JC',
                                    '2' => 'JD',
                                    '3' => 'JS',
                                    '4' => 'JH',
                                    '5' => 'QC',
                                    '6' => 'QD',
                                    '7' => 'QS',
                                    '8' => 'QH',
                                    '9' => 'KC',
                                    '10' => 'KD',
                                    '11' => 'KS',
                                    '12' => 'KH'
                                ];
                            @endphp
                            @foreach($results as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->result_time }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->total_points }}</td>
                                    <td>{{ $item->win_points }}</td>
                                    <td>
                                        @php
                                            $details = json_decode($item->bet_details);
                                            $bet_details_string = '';
                                        @endphp
                                        @foreach($details as $value)
                                            @php
                                                $points = $value->points;
                                                $card_number = $value->card_number;
                        
                                                if (array_key_exists($card_number, $card_info)) {
                                                    $card_name = $card_info[$card_number];
                                                    $bet_details_string .= $card_name . ':' . $points . ',';
                                                }
                                            @endphp
                                        @endforeach
                                        {{ rtrim($bet_details_string, ',') }}
                                    </td>
                                    <td>{{ $item->game_name }}</td>
                                    <td>{{ $item->treminal_id }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->order_id }}</td>
                                    <td>{{ $item->barcode_number }}</td>
                                    <td>{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                                    </table>
                                    
                                    <nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ $results->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $results->url(1) }}" aria-label="First">
                <span aria-hidden="true">&laquo;&laquo;</span>
            </a>
        </li>
        <li class="page-item {{ $results->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $results->previousPageUrl() }}" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        @php
            $half_total_links = floor(9 / 2);
            $from = $results->currentPage() - $half_total_links;
            $to = $results->currentPage() + $half_total_links;

            if ($results->currentPage() < $half_total_links) {
                $to += $half_total_links - $results->currentPage();
            }

            if ($results->lastPage() - $results->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($results->lastPage() - $results->currentPage()) - 1;
            }
        @endphp

        @for ($i = $from; $i <= $to; $i++)
            @if ($i > 0 && $i <= $results->lastPage())
                <li class="page-item {{ $results->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $results->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor

        <li class="page-item {{ $results->hasMorePages() ? '' : 'disabled' }}">
            <a class="page-link" href="{{ $results->nextPageUrl() }}" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <li class="page-item {{ $results->currentPage() == $results->lastPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $results->url($results->lastPage()) }}" aria-label="Last">
                <span aria-hidden="true">&raquo;&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
</div>
                                    
                               
                              </div>
                           </div>
</div>
@endsection