@extends('admin.body.adminmaster')
@section('content')

     <div class="container-fluid mt-5">
          <form action="{{route('admin_prediction')}}" method="post">
            @csrf
           <div class="row">
          <div class="col-md-6 equal-width">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="">
                            <div class="row form-group" style=" padding-left:30px;">
                                <span><b style="font-weight:800">Result Announcement Time</b></span>
                                <input type="text" class="form-control" id="result_time" style="  font-size: 16px;color:#333;border:none" name="result_time" value="">
                                </div>                     
                        </div>
                    </div>
                </div>
          </div>
           <div class="col-md-6 equal-width">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="">
                            <div class="row" style=" padding-left:30px;"><h1  id="timer"></h1></div>                     
                        </div>
                    </div>
                </div>
          </div>
          </div>
          <div class="row" style="padding-top: 10px;">
            @for($i = 1; $i <= 12; $i++)
                <div class="card col-md-1 mt-4" style="height:50px;">
                    <h1>{{ $i }}</h1>
                </div>
            @endfor

          </div>
          <div class="row" style="padding-bottom:20px;">
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/1.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/2.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/3.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/4.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/5.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/6.png"></div>
                
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/7.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/8.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/9.png"></div>
                <div class="card col-md-1 mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/10.png"></div>
                <div class="card col-md-1  mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/11.png"></div>
                <div class="card col-md-1 mt-4 " style="height:90px;"><img src="{{env('APP_URL')}}images/12.png"></div>
          </div>
          <div class="row" style="  padding-bottom:20px;" id="amounts-container"></div>
               
           <div class="row ml-4 d-flex" style="margin-bottom: 20px;"> 
                <div class="col-md-2 form-group d-flex">
                 </div>
                 <div class="col-md-3 form-group d-flex">
                     <input type="number" name="number" class="form-control" min="1" max="12" placeholder="Result">
                 </div>
                 <div class="col-md-2 form-group d-flex">
                    <button type="submit" class="form-control btn btn-info"><b>Submit</b></button>
                 </div>
                 <div class="col-md-2 form-group d-flex mt-1">
                    <a href=""> <i class="fa fa-refresh" aria-hidden="true" style="font-size:30px;"></i></a>
                 </div>
                  <div class="col-md-3 form-group d-flex mt-1"></div>
           </div>
           </form>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    function fetchData() {
        fetch('api/fetch')
            .then(response => response.json())
            .then(data => {
                console.log('Fetched data:', data);
                if (data && data.bet_log) {
                    updateBets(data.bet_log,data.result_time);
                } else {
                    console.error('Data format is incorrect or bet_log is missing:', data);
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function updateBets(bet_log,result_time) {
        console.log('Updating Bets:', bet_log);
        var amountdetailHTML = '';
        // var result_time = '<h1>Result Time - '+ result_time + '</h1>';
       
        bet_log.forEach(item => {
            amountdetailHTML += '<div class="card col-md-1 mt-4" style="background-color:#fff;">';
            amountdetailHTML += '<div class="card-body">';
            amountdetailHTML += '<b style="color:black">' + item.amount + '</b>';
            amountdetailHTML += '</div>';
            amountdetailHTML += '</div>';
        });

        $('#amounts-container').html(amountdetailHTML);
        $('#result_time').val(result_time);
    }

    function refreshData() {
        fetchData();
        setInterval(fetchData, 3000); // Refresh every 3 seconds
    }
    
    function page_refresh() {
        location.reload();
    }

    function updateClock() {
        const timerElement = document.getElementById('timer');
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        timerElement.textContent = timeString;
    }

    document.addEventListener('DOMContentLoaded', () => {
        refreshData();
        updateClock();
        setInterval(updateClock, 1000); // Update clock every second
    });
</script>
<script type="text/javascript">    
    setInterval(page_refresh, 300000); // Refresh page every 1 minute
</script>
     </div>
@endsection('content')
