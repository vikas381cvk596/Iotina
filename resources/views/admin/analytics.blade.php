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
        <!--<div class="form-group" style="display: flex; flex-direction: row; align-items: center; justify-content: flex-end;">
            <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">Time Interval (seconds)&nbsp; &nbsp;&nbsp;</div>
            <input type="text" id="setting_time_interval" name="setting_time_interval" class="form-control" class="form-control" placeholder="300" style="font-size: 14px; max-width: 6vw;"/>
            &nbsp;&nbsp;<button type="submit" name="btn_save_setting" id="btn_save_setting" class="btn btn_save_setting_class" style="font-size: 15px;">Save</button>
        </div>-->

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-chart-line"></i>&nbsp;&nbsp;Connected Clients</div>
        <div class="card-body" id="charts_card">
          <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" class="venue_filter hidden_field" name="venue_filter">
              <div class="dropdown venue_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle venue_filter_dropdown_btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Venue
                </button>
                <div class="dropdown-menu venue_filter_options" aria-labelledby="venue_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" name="ap_filter" class="hidden_field ap_filter">
              <div class="dropdown ap_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle ap_filter_dropdown_btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Access Point
                </button>
                <div class="dropdown-menu ap_filter_options" aria-labelledby="ap_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <div class='input-group'>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                  <div style="font-size: 12px; color: #696969;">Time Interval (minutes)</div>
                  <div style="padding-left: 10px;">
                    <input type="text" class="form-control time_interval_field" class="form-control" placeholder="" style="font-size: 14px; width: 5vw;" value="5"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <div class='input-group'>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                  <div style="font-size: 12px; color: #696969;">Duration (minutes)</div>
                  <div style="padding-left: 10px;">
                    <input type="text" class="form-control duration_field" class="form-control" placeholder="" style="font-size: 14px; width: 5vw;" value="60"/>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          <div id="spin-area-2" style="margin-top: 20px; margin-bottom: 10px;"></div>
          <div id="chart_area">
          </div>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>

      <div class="card mb-3" id="clients_card">
        <div class="card-header">
          <i class="fa fa-exchange-alt"></i>&nbsp;&nbsp;Top Clients By Traffic&nbsp;<span class="clients_count"></span></div>
        <div class="card-body">
          <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" class="venue_filter hidden_field" name="venue_filter">
              <div class="dropdown venue_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle venue_filter_dropdown_btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Venue
                </button>
                <div class="dropdown-menu venue_filter_options" aria-labelledby="venue_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" name="ap_filter" class="hidden_field ap_filter">
              <div class="dropdown ap_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle ap_filter_dropdown_btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Access Point
                </button>
                <div class="dropdown-menu ap_filter_options" aria-labelledby="ap_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <div class='input-group'>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                  <div style="font-size: 12px; color: #696969;">Duration (minutes)</div>
                  <div style="padding-left: 10px;">
                    <input type="text" class="form-control duration_field" class="form-control" placeholder="" style="font-size: 14px; width: 5vw;" value="1440"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <div class='input-group'>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                  <div style="font-size: 12px; color: #696969;">Limit</div>
                  <div style="padding-left: 10px;">
                    <input type="text" class="form-control limit_field" class="form-control" placeholder="" style="font-size: 14px; width: 3vw;" value="10"/>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          <div class="table-responsive">
            <table id="clients_traffic_table" class="table table-bordered" id="dataTable" cellspacing="0" style="font-size: 13px; margin: 0 auto; width: 50vw;">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>MAC Address</th>
                  <th>Tx</th>
                  <th>Rx</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div id="spin-area-2" style="margin-top: 20px; margin-bottom: 10px;"></div>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>

      <div class="card mb-3" id="ap_card">
        <div class="card-header">
          <i class="fa fa-network-wired"></i>&nbsp;&nbsp;Top Clients By Access Points&nbsp;<span class="clients_count"></span></div>
        <div class="card-body">
          <div style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" class="venue_filter hidden_field" name="venue_filter">
              <div class="dropdown venue_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle venue_filter_dropdown_btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Venue
                </button>
                <div class="dropdown-menu venue_filter_options" aria-labelledby="venue_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <input type="hidden" name="ap_filter" class="hidden_field ap_filter">
              <div class="dropdown ap_filter_dropdown" style="width: 10vw;">
                <button class="btn btn-default dropdown-toggle ap_filter_dropdown_btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #f2f2f2; border: 1px solid #c3c3c3; width: 10vw; color: #2b2b2b; width: 10vw; text-align: left;">
                  Access Point
                </button>
                <div class="dropdown-menu ap_filter_options" aria-labelledby="ap_filter_dropdown">
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <div class='input-group'>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                  <div style="font-size: 12px; color: #696969;">Duration (minutes)</div>
                  <div style="padding-left: 10px;">
                    <input type="text" class="form-control duration_field" class="form-control" placeholder="" style="font-size: 14px; width: 5vw;" value="1440"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-right: 20px;">
              <div class='input-group'>
                <div style="display: flex; flex-direction: row; justify-content: center; align-items: center;">
                  <div style="font-size: 12px; color: #696969;">Limit</div>
                  <div style="padding-left: 10px;">
                    <input type="text" class="form-control limit_field" class="form-control" placeholder="" style="font-size: 14px; width: 3vw;" value="10"/>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
          <div class="table-responsive">
            <table id="clients_ap_table" class="table table-bordered" id="dataTable" cellspacing="0" style="font-size: 13px; margin: 0 auto; width: 50vw;">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>MAC Address</th>
                  <th>Tx</th>
                  <th>Rx</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div id="spin-area-3" style="margin-top: 20px; margin-bottom: 10px;"></div>
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