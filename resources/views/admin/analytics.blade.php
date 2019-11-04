@extends('layouts.app')

@section('content')

  <div id="analytics_page" class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ url('/admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Analytics</li>
      </ol>
      <!-- Area Chart Example-->
          <div class="form-group" style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end;">
            <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">Time Interval (seconds)&nbsp; &nbsp;&nbsp;</div>
            <input type="text" id="setting_time_interval" name="setting_time_interval" class="form-control" class="form-control" placeholder="300" style="font-size: 14px; max-width: 6vw;"/>
            &nbsp;&nbsp;<button type="submit" name="btn_save_setting" id="btn_save_setting" class="btn btn_save_setting_class" style="font-size: 15px;">Save</button>
        </div>

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-area-chart"></i> Connected Clients (<span class="interval_time_heading"></span> Seconds Interval)</div>
        <div class="card-body">
          <canvas id="clientTrafficGraph" width="80%" height="20"></canvas>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © EAP 2019</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="{{ url('/admin/logout') }}">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection