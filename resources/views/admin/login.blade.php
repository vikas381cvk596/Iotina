@extends('layouts.app', ['bodyclass' => 'bg-dark', 'hidenav' => true])

@section('content')
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form method="post" action="{{ url('/admin/user_login') }}">
          {{ csrf_field() }}
          @include('admin.inc.messages')
          <div class="form-group" >
            <label for="user_name">Email</label>
            <input class="form-control" name="email" id="email" type="text" aria-describedby="emailHelp" placeholder="User Name">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input class="form-control" name="password" id="password" type="password" placeholder="Password">
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox"> Remember Password</label>
            </div>
          </div>
          <!--<a class="btn btn-primary btn-block" type="submit">Loginn</a>-->
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="{{ url('/admin/register') }}">Register an Account</a>
          <a class="d-block small" href="{{ url('/admin/forgot-password') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>

@endsection