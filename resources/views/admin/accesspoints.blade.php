@extends('layouts.app')

@section('content')

  <div id="ap_page" class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ url('/admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Access Points</li>
      </ol>
      <!-- Area Chart Example-->
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group" style="text-align: right;">
            <button type="submit" name="btn_add" id="btn_add_ap" class="btn" style="font-size: 15px;">Add AP</button>
          </div>
        </div>
      </div>
      
      <div id="create_ap_block" class="row" style="display: none;">
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-network-wired"></i>&nbsp;&nbsp;Create AP
            </div>
            
            <div class="row" style="margin-top: 20px;">
              <div class="col-md-12">
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                  <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; width: 30vw;">
                    <div class="form-group" style="width: 15vw;">
                      <input type="hidden" id="venue_id" name="venue_id">
                      <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Venue</div>
                      <div class="dropdown venue_name" style="">
                        <button class="btn btn-default dropdown-toggle" type="button"id="venue_dropdown" data-toggle="dropdown" aria-haspopup="   true" aria-expanded="false" style="font-size: 14px; background-color: #fff; border: 1px solid #c3c3c3; width: 10vw; color: #696969; width: 15vw; text-align: left;">
                          Select Venue
                        </button>
                        <div class="dropdown-menu" id="venue_dropdown_options" aria-labelledby="venue_dropdown">
                          
                        </div>
                      </div>
                          
                    </div>  
                    <div class="form-group" style="padding-left: 20px;width: 15vw;">
                      <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* AP Name</div>
                      <div class='input-group'>
                        <input type="text" id="ap_name" name="ap_name" class="form-control" class="form-control" placeholder="Name" style="font-size: 14px;"/>
                      </div>
                    </div>
                  </div>
                  <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; width: 30vw;">
                    <div class="form-group" style="width: 30vw;">
                      <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">Description</div>
                      <div class='input-group'>
                        <textarea id="ap_desc" name="ap_desc" class="form-control" class="form-control" placeholder="Description" style="font-size: 14px;"> </textarea>
                      </div>
                    </div>  
                  </div>
                  <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                      
                    <div class="form-group" style="width: 20vw;">
                      <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">Tags</div>
                      <div class='input-group'>
                        <input type="text" id="ap_tags" name="ap_tags" class="form-control" class="form-control" placeholder="Add a tag" style="font-size: 14px;"/>
                      </div>
                    </div>
                  </div>
                  <div style="display: flex; flex-direction: row; align-items: flex-start; justify-content: flex-start; width: 30vw;">
                    <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; width: 16vw;">
                      <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Identifier</div>
                      <div class="dropdown" style="">
                        <input type="hidden" id="ap_identifier" name="ap_identifier" class="form-control" />
                        <button class="btn btn-default dropdown-toggle" type="button" id="ap_identifier_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #fff; border: 1px solid #c3c3c3; width: 10vw; color: #696969; width: 15vw; text-align: left;">
                            Select
                        </button>
                        <div class="dropdown-menu" id="ap_identifier_options" aria-labelledby="ap_identifier_dropdown">
                            <a class="dropdown-item" style="" data-value="Serial Number">
                              <span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Serial Number</span>
                            </a>
                            <a class="dropdown-item" style="" data-value="MAC Address">
                              <span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">MAC Address</span>
                            </a>
                        </div>
                      </div>
                    </div>
                    <div class="form-group" style="padding-left: 20px;width: 15vw;">
                      <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600; opacity: 0;">A</div>
                      <div class='input-group'>
                        <input type="text" id="ap_serial" name="ap_serial" class="form-control" class="form-control" placeholder="Serial" style="font-size: 14px; display: none;"/>
                      </div>
                    </div>
                  </div>
                  <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; width: 30vw;">
                    <div class="form-group" style="width: 20vw; text-align: center;">
                      <button type="submit" name="btn_create_ap" value="create" id="btn_create_ap" class="btn btn-secondary btn_ap" style="margin-top:15px">Create Access Point</button>
                    </div>  
                  </div>
                  
                </div>
              </div>
            </div>

            <div id="error_msg_crt" class="row" style="display: none;">
              <div class="col-md-12">
                <p id="error_text" style="font-size: 14px; color: #840808; padding-left: 30px; padding-top: 0px; text-align: center;font-weight: 600;"></p>
              </div>
            </div>
            <div id="success_msg_crt" class="row" style="display: none;">
              <div class="col-md-12">
                <p style="font-size: 14px; color: #636363; padding-left: 30px; padding-top: 0px; text-align: center; font-weight: 600;">AP created successfully &#10003;</p>
              </div>
            </div>
            
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i>&nbsp;&nbsp;Access Points</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="ap_table" class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
              <thead>
                <tr>
                  <th>AP</th>
                  <th>Status</th>
                  <th>Serial</th>
                  <th>IP Address</th>
                  <th>MAC Address</th>
                  <th>Venue</th>
                  <th>No. of Clients</th>
                  <th>Tags</th>
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