@extends('layouts.app')

@section('content')

  <div id="venue_page" class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Venues</li>
      </ol>
      <!-- Area Chart Example-->
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group" style="text-align: right;">
            <button type="submit" name="btn_add" id="btn_add_venue" class="btn" style="font-size: 15px;">Add Venue</button>
          </div>
        </div>
      </div>
      
      <div id="create_venue_block" class="row" style="display: none;">
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-globe"></i>&nbsp;&nbsp;Create a Venue
            </div>
            <div id="error_msg_crt" class="row" style="display: none;">
              <div class="col-md-12">
                <p id="error_text" style="font-size: 14px; color: #840808; padding-left: 30px; padding-top: 20px;"></p>
              </div>
            </div>
            <div id="success_msg_crt" class="row" style="display: none;">
              <div class="col-md-12">
                <p style="font-size: 14px; color: #636363; padding-left: 30px; padding-top: 20px;">Venue created successfully &#10003;</p>
              </div>
            </div>
            <div id="create-venue-row" class="row" style="padding: 20px;">
              <div class="col-md-2 text-center">
                <div class="form-group">
                  <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Venue Name</div>
                  <div class='input-group'>
                    <input type="text" id="venue_name" name="venue_name" class="form-control" class="form-control" placeholder="Name" style="font-size: 14px;"/>
                  </div>
                </div>
              </div>
              <div class="col-md-2 text-center">
                <div class="form-group">
                  <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">Description</div>
                  <div class='input-group'>
                    <textarea id="venue_desc" name="venue_desc" class="form-control" class="form-control" placeholder="Description" style="font-size: 14px;"> </textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-3 text-center">
                <div class="form-group">
                  <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Address</div>
                  <div class='input-group'>
                    <input type="text" id="venue_add" name="venue_add" class="form-control" class="form-control" placeholder="Complete Address" style="font-size: 14px;"/>
                  </div>
                </div>
              </div>
              <div class="col-md-3 text-center">
                <div class="form-group">
                  <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Address Notes</div>
                  <div class='input-group'>
                    <input type="text" id="venue_add_notes" name="venue_add_notes" class="form-control" class="form-control" placeholder="Eg. Building, Floor No." style="font-size: 14px;"/>
                  </div>
                </div>
              </div>
              <div class="col-md-2 text-center">
                <div class="form-group">
                    <button type="submit" name="btn_create_venue" value="create" id="btn_create_venue" class="btn btn-secondary btn_venue" style="margin-top:15px">Create Venue</button>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Venues</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="venues_tab" class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Address</th>
                  <th>Address Notes</th>
                  <th>Networks</th>
                  <th>APs</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
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