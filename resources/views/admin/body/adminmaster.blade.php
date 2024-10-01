<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Basic Meta Data -->
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

   <!-- Title & Site Metas -->
   <title>Pluto - Responsive Admin Panel</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">

   <!-- Site Icon -->
   <link rel="icon" href="{{ asset('images/fevicon.png') }}" type="image/png" />

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
   <!-- Site CSS -->
   <link rel="stylesheet" href="{{ asset('style.css') }}" />
   <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
   <link rel="stylesheet" href="{{ asset('css/colors.css') }}" />
   <link rel="stylesheet" href="{{ asset('css/bootstrap-select.css') }}" />
   <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}" />
   <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />

   <!-- jQuery -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <!-- Bootstrap JS -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

   <!-- Optional Scripts -->
   <script src="{{ asset('js/popper.min.js') }}"></script>
   <script src="{{ asset('js/animate.js') }}"></script>
   <script src="{{ asset('js/bootstrap-select.js') }}"></script>
   <script src="{{ asset('js/owl.carousel.js') }}"></script>
   <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
   
   <!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
 <!--this icon cdn for font awasom icon-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
 <!--this icon cdn for font awasom icon end-->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


   <style>
      /* Add custom styles here */
      .dataTables_paginate .paginate_button.next,
      .dataTables_paginate .paginate_button.previous,
      .dataTables_paginate .paginate_button {
         display: none !important;
      }
   </style>
   
   <style>
    .dropdown-item.active {
    background-color: #007bff; /* Change to your desired active background color */
    color: white; /* Text color for active item */
}

.dropdown-item {
color: #000; /* Default text color */
}
   </style>
   <style>
 .thead-dark th{
 white-space: nowrap;
 }
 .tbody td{
     text-align:center;
 }
 .tdata td{
 white-space: nowrap;
 }
 .tbody td{
     text-align:center;
 }
   </style>
</head>

<body class="dashboard dashboard_1">
   @if(session('id') === null)
      <script>
         window.location.href = "{{ route('login_page') }}";
      </script>
   @endif

   <!-- Sidebar -->
   @includeIf('admin.body.sidebar')

   <!-- Header -->
   @includeIf('admin.body.header')

   <!-- Flash Message -->
   @includeIf('admin.body.flash-message')

   <!-- Main Content Section -->
   @yield('content')

   <!-- Footer Section (Optional) -->
   <!-- <div class="container-fluid">
      <div class="footer">
         <p>Copyright © 2018 Designed by html.design. All rights reserved.<br><br>
            Distributed By: <a href="https://themewagon.com/">ThemeWagon</a>
         </p>
      </div>
   </div> -->

   <!-- Custom Scripts -->
   @yield('scripts')

   <!-- jQuery Plugins and Custom Scripts -->
   <script>
      var ps = new PerfectScrollbar('#sidebar');
   </script>

   <script src="{{ asset('js/custom.js') }}"></script>
   <script src="{{ asset('js/chart_custom_style1.js') }}"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
