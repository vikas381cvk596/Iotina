@extends('layouts.app', ['bodyclass' => 'bg-dark', 'hidenav' => true])

@section('content')

  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Register an Account</div>
      <div class="card-body">
        <form method="post" action="{{ url('/admin/registerUser') }}">
          {{ csrf_field() }}
           @include('admin.inc.messages')            
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-6">
                <label for="name">Name</label>
                <input class="form-control" name="user_name" id="user_name" type="text" aria-describedby="nameHelp" placeholder="Full Name">
              </div>
            </div><br/>
            <div class="form-row">
              <div class="col-md-6">
                <label for="email">Email</label>
                <input class="form-control" name="user_email" id="user_email" type="text" aria-describedby="emailHelp" placeholder="Email">
              </div>
              <div class="col-md-6">
                <label for="org">Organisation</label>
                <input class="form-control" name="user_organisation" id="user_organisation" type="text" aria-describedby="orgHelp" placeholder="Organisation Name">
              </div>
            </div><br/>
            <div class="form-row">
              <div class="col-md-6">
                <label for="exampleInputPassword1">Password</label>
                <input class="form-control" name="password" id="password" type="password" placeholder="Password">
              </div>
              <div class="col-md-6">
                <label for="exampleConfirmPassword">Confirm password</label>
                <input class="form-control" name="confirm_password" id="confirm_password" type="password" placeholder="Confirm password">
              </div>
            </div>
          </div>
          <!--<a class="btn btn-primary btn-block" href="{{ url('/admin/register') }}">Register</a>-->
          <button type="submit" class="btn btn-primary btn-block" style="width: 200px; margin: 0 auto;">Register</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="{{ url('/admin/login') }}">Login Page</a>
          <a class="d-block small" href="{{ url('/admin/forgot-password') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>

@endsection