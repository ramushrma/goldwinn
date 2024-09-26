@extends('admin.body.adminmaster')
 @section('content')
    
	
   <body class="inner_page login">
      <div class="full_container">
         <div class="container">
            <div class="center verticle_center full_height">
               <div class="login_section">
                  <div class="logo_login">
                     <div class="center">
                        <img width="210" src="images/logo/logo.png" alt="#" />
                     </div>
                  </div>
                  <div class="login_form">
                     <form action="{{route('update_password')}}" method="post">
                         @csrf
                        <fieldset>
                           <div class="field">
                              <label class="label_field">Terminal ID </label>
                              <input type="number" name="terminal_id" placeholder="Enter Terminal ID" />
                           </div>
                           <div class="field">
                              <label class="label_field">Old Password</label>
                              <input type="password" name="password" placeholder="Enter Old Password" />
                           </div>
                            <div class="field">
                              <label class="label_field">New Password</label>
                              <input type="password" name="new_password" placeholder="Enter New Password" />
                           </div>
                           <div class="field margin_0">
                              <label class="label_field hidden">hidden label</label>
                              <button  type="submit" name="submit" class="main_bt">Change Password</button>
                           </div>
                        </fieldset>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
@endsection
      