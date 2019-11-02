@extends('layouts.app')

@section('content')

  <div id="wifi_page" class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{ url('/admin') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Wireless Networks</li>
      </ol>
      <!-- Area Chart Example-->
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group" style="text-align: right;">
            <button type="submit" name="btn_add" id="btn_add_wifi" class="btn" style="font-size: 15px;">Add Network</button>
          </div>
        </div>
      </div>
      
      <div id="create_network_block" class="row" style="display: none;">
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-wifi"></i>&nbsp;&nbsp;Create New WiFi Network
            </div>
            
            <div class="row" style="margin-top: 20px;">
              <div class="col-md-12">
                <div style="display: flex; flex-direction: row; align-items: flex-start; justify-content: flex-start;">

                  <!-- Left Section Forms --> 
                  <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding-left: 80px; width: 70%; border-right: 1px solid #e8e8e8" class="left_section">
                    <div style="display: flex; flex-direction: column;" class="form_class form_step_1 active" form-step='form_step_1'>
                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                        <div class="form-group" style="width: 20vw;">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Name</div>
                          <div class='input-group'>
                            <input type="text" id="network_name" name="network_name" class="form-control form-fields" class="form-control" placeholder="Network Name" style="font-size: 14px;"/>
                          </div>
                          <div class='error-text-network-name' style="text-align: left; font-size: 14px; color: #840808; font-weight: 600;"></div>
                        </div> 
                      </div>

                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; width: 30vw;">
                        <div class="form-group" style="width: 30vw;">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">Description</div>
                          <div class='input-group'>
                            <textarea id="network_desc" name="network_desc" class="form-control" class="form-control" placeholder="Description" style="font-size: 14px;"> </textarea>
                          </div>
                        </div>  
                      </div>

                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                        <div class="form-group" style="width: 15vw;">
                          <input type="hidden" id="network_type" name="network_type">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Type</div>
                          <div class="dropdown" style="">
                                <button class="btn btn-default dropdown-toggle" type="button" id="network_type_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: #fff; border: 1px solid #c3c3c3; width: 10vw; color: #696969; width: 15vw; text-align: left;">
                                    Network Type
                                </button>
                                <div class="dropdown-menu" id="network_type_options" aria-labelledby="network_type_dropdown">
                                    <a class="dropdown-item" style="" data-value="PSK">
                                      <span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Pre-Shared Key (PSK)</span><br/><span class="sub_text_dropdown" style="font-size: 12px; font-weight: 400;">Requires users to enter passphrase (that you have defined for the network) to connect</span>
                                    </a>
                                    <a class="dropdown-item" style="" data-value="CaptivePortal">
                                      <span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Captive Portal</span><br/><span class="sub_text_dropdown" style="font-size: 12px; font-weight: 400;">Users are authorized through a captive portal in various methods</span>
                                    </a>
                                </div>
                            </div>        
                        </div> 
                      </div>

                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                        <div class="form-group" style="width: 20vw;">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">VLAN</div>
                          <div class='input-group'>
                            <input type="text" id="network_vlan" name="network_vlan" class="form-control form-fields" class="form-control" placeholder="VLAN" style="font-size: 14px;"/>
                          </div>
                        </div> 
                      </div>
                    </div>
                    <!-- PSK Option Step: Page 1 (PSK Settings) -->
                    <div style="display: none; flex-direction: column;" class="form_class form_step_2" form-step='form_step_2'>
                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                        <div class="form-group" style="width: 20vw;">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Passphrase</div>
                          <div class='input-group'>
                            <input type="text" id="backup_passphrase" name="backup_passphrase" class="form-control form-fields" placeholder="Pre-Shared Key" style="font-size: 14px;"/>
                          </div>
                        </div>  
                      </div>

                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                      
                        <div class="form-group" style="width: 15vw;">
                          <input type="hidden" id="security_protocol" name="security_protocol">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Security Protocol</div>
                          <div class="dropdown sp_dropdown" style="">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="   true" aria-expanded="false" style="font-size: 14px; background-color: #fff; border: 1px solid #c3c3c3; width: 10vw; color: #696969; width: 15vw; text-align: left;">
                              Select
                            </button>
                            <div class="dropdown-menu" id="sp_dropdown_options" aria-labelledby="sp_dropdown">
                              <a class="dropdown-item" style="" data-value="HQ"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">WPA2 (Recommended)</span></a>
                              <a class="dropdown-item" style="" data-value="Warehouse - Fresno"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">WPA</span></a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr/>
                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                      
                        <div class="form-group" style="width: 15vw; display: none;">
                          <input type="hidden" id="passphrase_format" name="passphrase_format" value='0'>
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Passphrase Format</div>
                          <div class="dropdown sp_dropdown" style="">
                            <button class="btn btn-default dropdown-toggle" type="button"id="pf_dropdown" data-toggle="dropdown" aria-haspopup="   true" aria-expanded="false" style="font-size: 14px; background-color: #fff; border: 1px solid #c3c3c3; width: 10vw; color: #696969; width: 15vw; text-align: left;">
                              Select
                            </button>
                            <div class="dropdown-menu" id="pf_dropdown_options" aria-labelledby="pf_dropdown">
                              <a class="dropdown-item" style="" data-value="secured"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Most Secured</span></a>
                              <a class="dropdown-item" style="" data-value="keyboard"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Keyboard-Friendly</span></a>
                              <a class="dropdown-item" style="" data-value="numbers"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Numbers Only</span></a>
                            </div>
                          </div>
                        </div>
                        
                      </div>

                      <div style="display: none; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                      
                        <div class="form-group" style="width: 10vw;">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Passphrase Length</div>
                          <div class='input-group'>
                            <input type="text" id="passphrase_length" name="passphrase_length" class="form-control form-fields" placeholder="Number" style="font-size: 14px;" value='0'/>
                          </div>
                        </div> 
                        
                      </div>

                      <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                      
                        <div class="form-group" style="width: 15vw;">
                          <input type="hidden" id="passphrase_expiry" name="passphrase_expiry">
                          <div style="text-align: left; font-size: 14px; color: #696969; font-weight: 600;">* Passphrase Expiration</div>
                          <div class="dropdown pe_dropdown" style="">
                            <button class="btn btn-default dropdown-toggle" type="button"id="pe_dropdown" data-toggle="dropdown" aria-haspopup="   true" aria-expanded="false" style="font-size: 14px; background-color: #fff; border: 1px solid #c3c3c3; width: 10vw; color: #696969; width: 15vw; text-align: left;">
                              Select
                            </button>
                            <div class="dropdown-menu" id="pe_dropdown_options" aria-labelledby="pe_dropdown">
                              <a class="dropdown-item" style="" data-value="unlimited"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">Unlimited</span></a>
                              <a class="dropdown-item" style="" data-value="1-day"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">1 day</span></a>
                              <a class="dropdown-item" style="" data-value="2-days"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">2 days</span></a>
                              <a class="dropdown-item" style="" data-value="1-week"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">1 week</span></a>
                              <a class="dropdown-item" style="" data-value="2-weeks"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">2 weeks</span></a>
                              <a class="dropdown-item" style="" data-value="1-month"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">1 month</span></a>
                              <a class="dropdown-item" style="" data-value="6-months"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">6 months</span></a>
                              <a class="dropdown-item" style="" data-value="1-year"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">1 year</span></a>
                              <a class="dropdown-item" style="" data-value="2-years"><span class="title_text_dropdown" style="font-size: 14px; font-weight: 600;">2 years</span></a>
                            </div>
                          </div>
                        </div>
                        
                      </div>
                    </div>

                    <!-- Venues Option Step: (Last Step) -->
                    <div style="display: none; flex-direction: column; padding-bottom: 50px;" class="form_class form_step_6" form-step='form_step_6'>   
                      <p style="font-size: 13px; font-weight: 600;">Select venues to activate this network</p>
                      <table id="venues_network_table" class="table table-bordered" id="dataTable" width="45vw" cellspacing="0" style="font-size: 13px;">
                        <thead>
                          <tr>
                            <th>Venue Name</th>
                            <th>Address</th>
                            <th>Networks</th>
                            <th>APs</th>
                            <th>Activate</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                      

                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start; width: 30vw;">
                      <div class="form-group" style="text-align: center;">
                        <button type="submit" name="btn_back" id="btn_back_step" class="btn btn-secondary btn-back-class" style="display: none; margin-top:15px; border-color: #f2f2f2; margin-right: 5px; background-color: #f2f2f2; color: #696969;">Back</button>
                        <button type="submit" name="btn_next" id="btn_next_step" class="btn btn-secondary btn-next-class" style="margin-top:15px; " disabled>Next</button>
                      </div>  
                    </div>

                  </div>

                  <!-- Right Section Progress-Bars --> 
                  <div style="display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start; width: 30%; padding-left: 30px; padding-top: 15px;" id="right_flow_0" class="flow_section">
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">1<div style="position: absolute; height: 40px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Network Settings</span>
                    </div>

                    <div style="height: 50px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">2</div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Venues</span>
                    </div> 
                  </div>

                  <!-- PSK Flow -->
                  <div style="display: none; flex-direction: column; align-items: flex-start; justify-content: flex-start; width: 30%; padding-left: 30px; padding-top: 30px;" id="right_flow_1" class="flow_section">
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div class="circle_step_1" style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">1<div style="position: absolute; height: 40px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span class="title_step_1" style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Network Settings</span>
                    </div>
                    
                    <div style="height: 50px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div class="circle_step_2" style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">2<div style="position: absolute; height: 40px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span class="title_step_2" style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">PSK Settings</span>
                    </div>

                    <div style="height: 50px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">3</div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Venues</span>
                    </div> 
                  </div>

                  <!-- CaptivePortal Flow -->
                  <div style="display: none; flex-direction: column; align-items: flex-start; justify-content: flex-start; width: 30%; padding-left: 30px; padding-top: 30px;" id="right_flow_2" class="flow_section">
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">1<div style="position: absolute; height: 15px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Network Settings</span>
                    </div>
                    
                    <div style="height: 25px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">2<div style="position: absolute; height: 15px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Portal Type</span>
                    </div>

                    <div style="height: 25px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">3<div style="position: absolute; height: 15px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Onboarding</span>
                    </div>

                    <div style="height: 25px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">4<div style="position: absolute; height: 15px; width: 1px; background-color: #b3b3b3; top: 120%"></div></div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Portal Web Page</span>
                    </div>

                    <div style="height: 25px;"></div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: flex-start;">
                      <div style="position: relative; width: 30px; height: 30px; border-radius: 15px; background-color: #b3b3b3; text-align: center; font-size: 12px; display: flex; justify-content: center; align-items: center; color: #fff; font-weight: 600;">5</div>
                      <span style="font-size: 14px; font-weight: 600; padding-left: 10px; color: #696969;">Venues</span>
                    </div> 

                  </div>

                </div>
              </div>
            </div>

            <div id="error_msg_crt" class="row" style="display: none;">
              <div class="col-md-12">
                <p id="error_text" style="font-size: 14px; color: #840808; padding-left: 80px; padding-top: 0px; text-align: left; font-weight: 600;"></p>
              </div>
            </div>
            <div id="success_msg_crt" class="row" style="display: none;">
              <div class="col-md-12">
                <p style="font-size: 14px; color: #636363; padding-left: 80px; padding-top: 0px; text-align: left; font-weight: 600;">Wifi Network successfully created &#10003;</p>
              </div>
            </div>
            
          </div>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i>&nbsp;&nbsp;Networks</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="wifi_table" class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
              <thead>
                <tr>
                  <th>Network Name</th>
                  <th>Description</th>
                  <th>Type</th>
                  <th>Venues</th>
                  <th>APs</th>
                  <th>VLAN</th>
                  <th>No. of Clients</th>
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