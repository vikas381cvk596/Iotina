@extends('layouts.app')

@section('content')

  <div id="users_page" class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-users"></i>&nbsp;&nbsp;Clients Connected&nbsp;<span class="clients_count"></span></div>
        <div class="card-body">
          <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" id="venue_filter" name="venue_filter"  class="hidden_field">
              <div class="dropdown venue_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle" type="button" id="venue_filter_dropdown_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Venue
                </button>
                <div class="dropdown-menu" id="venue_filter_options" aria-labelledby="venue_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" id="ap_filter" name="ap_filter"  class="hidden_field">
              <div class="dropdown ap_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle" type="button" id="ap_filter_dropdown_btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Access Point
                </button>
                <div class="dropdown-menu" id="ap_filter_options" aria-labelledby="ap_filter_dropdown">
                </div>
              </div>
            </div>
          </div>
                

          <div class="table-responsive">
            <table id="users_table" class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
              <thead>
                <tr>
                  <th>IP Address</th>
                  <th>Venue</th>
                  <th>AP</th>
                  <th>Radio Frequency</th>
                  <th>Bytes Sent</th>
                  <th>Bytes Received</th>
                  <th>Signal Strength</th>
                  <th>Last Connected</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div id="spin-area" style="margin-top: 10px; margin-bottom: 10px;"></div>
        </div>
        <!--<div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div> -->
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