<!DOCTYPE html>
<html lang="en">
   <head>
      <!-- basic -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- mobile metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="viewport" content="initial-scale=1, maximum-scale=1">
      <!-- site metas -->
      <title>Pluto - Responsive Bootstrap Admin Panel Templates</title>
      <meta name="keywords" content="">
      <meta name="description" content="">
      <meta name="author" content="">
      <!-- site icon -->
      <link rel="icon" href="images/fevicon.png" type="image/png" />
      <!-- bootstrap css -->
      <link rel="stylesheet" href="css/bootstrap.min.css" />
      <!-- site css -->
      <link rel="stylesheet" href="style.css" />
      <!-- responsive css -->
      <link rel="stylesheet" href="css/responsive.css" />
      <!-- color css -->
      <link rel="stylesheet" href="css/colors.css" />
      <!-- select bootstrap -->
      <link rel="stylesheet" href="css/bootstrap-select.css" />
      <!-- scrollbar css -->
      <link rel="stylesheet" href="css/perfect-scrollbar.css" />
      <!-- custom css -->
      <link rel="stylesheet" href="css/custom.css" />
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
   </head>
  
       	<style>
		.dataTables_paginate .paginate_button.next,
		.dataTables_paginate .paginate_button.previous,
		.dataTables_paginate .paginate_button {
			display: none !important;
		}
	</style>
	
   <body class="dashboard dashboard_1">


       @if(session('id') === null)
        <script>
            window.location.href = "{{route('login_page')}}";
        </script>
       @endif
	  
    @includeIf('admin.body.sidebar')
	
    
    @includeIf('admin.body.header') 
	   @includeIf('admin.body.flash-message')  
	<!--@includeIf('admin.body.flash-message')-->
    @yield('content')
     
        <!--<div class="container-fluid">-->
        <!--             <div class="footer">-->
        <!--                <p>Copyright © 2018 Designed by html.design. All rights reserved.<br><br>-->
        <!--                   Distributed By: <a href="https://themewagon.com/">ThemeWagon</a>-->
        <!--                </p>-->
        <!--             </div>-->
        <!--          </div>-->
               </div>
               <!-- end dashboard inner -->
            </div>
         </div>
      </div>
       
      <!-- jQuery -->
      @yield('scripts')
      <script src="js/jquery.min.js"></script>
      <script src="js/popper.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <!-- wow animation -->
      <script src="js/animate.js"></script>
      <!-- select country -->
      <script src="js/bootstrap-select.js"></script>
      <!-- owl carousel -->
      <script src="js/owl.carousel.js"></script> 
      <!-- chart js -->
      <script src="js/Chart.min.js"></script>
      <script src="js/Chart.bundle.min.js"></script>
      <script src="js/utils.js"></script>
      <script src="js/analyser.js"></script>
      <!-- nice scrollbar -->
      <script src="js/perfect-scrollbar.min.js"></script>
      <script>
         var ps = new PerfectScrollbar('#sidebar');
         
      </script>
      
      <!-- custom js -->
      <script src="js/custom.js"></script>
      <script src="js/chart_custom_style1.js"></script>
   </body>
</html>
      