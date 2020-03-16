
var spinner = false;

$.fn.spin_on = function(element_id) {
    var opts = {
      lines: 8, // The number of lines to draw
      length: 3, // The length of each line
      width: 3, // The line thickness
      radius: 4, // The radius of the inner circle
      scale: 1 // Scales overall size of the spinner
      // corners: 1, // Corner roundness (0..1)
      // color: '#ffffff', // CSS color or array of colors
      // fadeColor: 'transparent', // CSS color or array of colors
      // speed: 1, // Rounds per second
      // rotate: 0, // The rotation offset
      // animation: 'spinner-line-fade-quick', // The CSS animation name for the lines
      // direction: 1, // 1: clockwise, -1: counterclockwise
      // zIndex: 2e9, // The z-index (defaults to 2000000000)
      // className: 'spinner', // The CSS class to assign to the spinner
      // top: '50%', // Top position relative to parent
      // left: '50%', // Left position relative to parent
      // shadow: '0 0 1px transparent', // Box-shadow for the lines
      // position: 'absolute' // Element positioning
    };
    console.log('spin called');
    if (!spinner) {
        spinner = new Spinner(opts).spin(document.getElementById(element_id));
    }

    spinner.spin(document.getElementById(element_id));
    // $(".page-content > div").css('opacity', '0.75');
};

$.fn.spin_off = function() {
    if (spinner) {
        spinner.stop();
    }

    // $(".page-content > div").css('opacity', '1.0');
}

if (document.getElementById('venue_page'))
{
  
  $("#venue_page").on('click', '#btn_add_venue', function() {
    $('#create_venue_block').fadeIn();
  });

  $("#venue_page").on('click', '.btn_venue', function() {
    var venue_name = $('#venue_name').val();
    var venue_desc = $('#venue_desc').val();
    var venue_add = $('#venue_add').val();
    var venue_add_notes = $('#venue_add_notes').val();

    if (venue_name == '' || venue_add == '') {
      $('#error_msg_crt').show();
      $('#error_text').html('Fields marked with * are mandatory');
      $('#success_msg_crt').hide();
    } else {
      $.ajax({
        url: "createVenue",
        type: "POST",
        data: {
          venue_name: venue_name,
          venue_desc: venue_desc,
          venue_add: venue_add,
          venue_add_notes: venue_add_notes,
          '_token': window.Laravel.csrfToken
        },
        success: function(result) {
          //alert(result);
          if (result == 'venue_name_error') {
            $('#error_msg_crt').show();
            $('#error_text').html('Error: Venue name '+venue_name+' already exists');
            $('#success_msg_crt').hide();
          } else {
            $('#error_msg_crt').hide();
            $('#success_msg_crt').show();

            $.fn.generate_venue_table();
          }
        }
      });   
    }
  });


  $.fn.generate_venue_table = function() {
    $.fn.spin_on('spin-area');
    $('#venues_table tbody').html(''); 
    $.ajax({
      url: "getAllVenues",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_venues) {
        var html_content = '';
        for (var venue in all_venues) {
          var venue_name = all_venues[venue]['venue_name'];
          var venue_crt_date = all_venues[venue]['created_at'];
          
          var venue_add = '';
          if (all_venues[venue]['venue_address']) {
            venue_add = all_venues[venue]['venue_address'];
          }

          var venue_desc = '';
          if (all_venues[venue]['venue_description']) {
            venue_desc = all_venues[venue]['venue_description'];
          }

          var venue_add_notes = '';
          if (all_venues[venue]['venue_address_notes']) {
            venue_add_notes = all_venues[venue]['venue_address_notes'];
          }

          var network_count = '0';
          if (all_venues[venue]['network_count']) {
            network_count = all_venues[venue]['network_count'];
          }

          var ap_count = '0';
          if (all_venues[venue]['ap_count']) {
            ap_count = all_venues[venue]['ap_count'];
          }

          var client_count = '0';
          if (all_venues[venue]['client_count']) {
            client_count = all_venues[venue]['client_count'];
          }

          html_content = html_content+'<tr>';
          html_content = html_content+'<input type="hidden" class="row_venue_id" value='+venue+'>';
          html_content = html_content+'<input type="hidden" class="row_venue_name" value='+venue_name+'>';
          html_content = html_content+'<input type="hidden" class="row_venue_desc" value='+venue_desc+'>';
          html_content = html_content+'<input type="hidden" class="row_venue_add" value='+venue_add+'>';
          html_content = html_content+'<input type="hidden" class="row_venue_add_notes" value='+venue_add_notes+'>';
          html_content = html_content+'<td>'+venue_name+'<br/>Created On: '+venue_crt_date+'</td>';
          html_content = html_content+'<td>'+venue_desc+'</td>';
          html_content = html_content+'<td>'+venue_add+'</td>';
          html_content = html_content+'<td>'+venue_add_notes+'</td>';
          html_content = html_content+'<td>'+network_count+'</td>';
          html_content = html_content+'<td>'+ap_count+'</td>';
          html_content = html_content+'<td>'+client_count+'</td>';

          var icons_html = '<span class="edit_venue_record"><i class="far fa-edit" style="font-size:13px; color: #696969;"></i></span><span class="del_venue_record" style="padding-left: 10px;"><i class="far fa-trash-alt" style="font-size:13px; color: #696969;"></i></span>';
          html_content = html_content+'<td>'+icons_html+'</td>';

          //console.log(all_venues[venue]['venue_id']);
          html_content = html_content+'</tr>';
        }
        $.fn.spin_off('spin-area');
        $('#venues_table tbody').html(html_content);        
      }
    }); 
  };

  $.fn.generate_venue_table();

  $("#venue_page").on('click', '.del_venue_record', function() {
    var confirm_check = confirm("Do you want to delete this venue ?");

    if (!confirm_check) {
      return false;
    }

    var venue_id = $(this).closest('tr').find('.row_venue_id').val();
    // console.log(venue_id);
    $.ajax({
      url: "deleteVenue",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken,
        venue_id: venue_id
      },
      success: function(response) {
        // console.log(response);
        $.fn.generate_venue_table();
      }
    });
  });

  $("#venue_page").on('click', '.edit_venue_record', function() {
    $('#error_msg_edit_save').hide();
    $('#success_msg_edit_save').hide();
    $('#venue_page .edit_venue_block').fadeIn();
    var venue_id = $(this).closest('tr').find('.row_venue_id').val();
    var venue_name = $(this).closest('tr').find('.row_venue_name').val();
    var venue_desc = $(this).closest('tr').find('.row_venue_desc').val();
    var venue_add = $(this).closest('tr').find('.row_venue_add').val();
    var venue_add_notes = $(this).closest('tr').find('.row_venue_add_notes').val();

    $('html, body').animate({
      scrollTop: $("#venue_page .edit_venue_block").offset().top-60
    }, 300);

    $('#venue_page .venue_id_edit_block').val(venue_id);
    $('#venue_name_edit').val(venue_name);
    $('#venue_desc_edit').val(venue_desc);
    $('#venue_add_edit').val(venue_add);
    $('#venue_add_notes_edit').val(venue_add_notes);
  });

  $("#venue_page").on('click', '.btn_venue_cancel', function() {
    $('#venue_page .edit_venue_block').fadeOut();
    $.fn.reset_venue_edit_block();
  });

  $.fn.reset_venue_edit_block = function() {
    $('#venue_name_edit').val('');
    $('#venue_desc_edit').val('');
    $('#venue_add_edit').val('');
    $('#venue_add_notes_edit').val('');
    $('#error_msg_edit_save').hide();
    $('#success_msg_edit_save').hide();
  }

  $("#venue_page").on('click', '.btn_venue_edit', function() {
    var venue_id = $('#venue_page .venue_id_edit_block').val();
    var venue_name = $('#venue_name_edit').val();
    var venue_desc = $('#venue_desc_edit').val();
    var venue_add = $('#venue_add_edit').val();
    var venue_add_notes = $('#venue_add_notes_edit').val();

    // console.log(venue_id+"::"+venue_name+"::"+venue_desc+"::"+venue_add+"::"+venue_add_notes);

    if (venue_id == '' || venue_name == '' || venue_add == '') {
      // console.log('error');
      $('#error_msg_edit_save').show();
      $('#error_text_edit_save').html('Fields marked with * are mandatory');
      $('#success_msg_edit_save').hide();
    } else {
      // console.log('calling ajax');
      $.ajax({
        url: "updateVenue",
        type: "POST",
        data: {
          venue_id: venue_id,
          venue_name: venue_name,
          venue_desc: venue_desc,
          venue_add: venue_add,
          venue_add_notes: venue_add_notes,
          '_token': window.Laravel.csrfToken
        },
        success: function(result) {
          // console.log(result);
          // console.log('testing');
          if (result == 'venue_name_duplicate') {
            $('#error_msg_edit_save').show();
            $('#error_text_edit_save').html('Error: Venue name '+venue_name+' already exists');
            $('#success_msg_edit_save').hide();
          } else {
            // console.log('error occ');
            $('#error_msg_edit_save').hide();
            $('#success_msg_edit_save').show();

            $.fn.generate_venue_table();
          }
        },
        error: function(err) {
          // console.log(err);
        }
      });   
    }
  });

} else if (document.getElementById('ap_page')) {

  $("#ap_page").on('click', '#venue_dropdown_options .dropdown-item', function() {
    $(this).parents(".dropdown").find('.btn').html($(this).text());
    var venue_id = $(this).attr('data-value');
    $('#venue_id').val(venue_id);
  });

  $("#ap_page").on('click', '#ap_identifier_options .dropdown-item', function() {
    $(this).parents(".dropdown").find('.btn').html($(this).text());
    var ap_identifier = $(this).attr('data-value');
    $('#ap_identifier').val(ap_identifier);

    $('#ap_serial').fadeIn();  
    if (ap_identifier == "Serial Number") {
      $('#ap_serial').attr('placeholder','Serial Number');
    } else if (ap_identifier == "MAC Address") {
      $('#ap_serial').attr('placeholder','MAC Address');
    } 
  });

  $("#ap_page").on('click', '#venue_dropdown_options_edit .dropdown-item', function() {
    $(this).parents(".dropdown").find('.btn').html($(this).text());
    var venue_id = $(this).attr('data-value');
    $('#venue_id_edit').val(venue_id);
  });

  $("#ap_page").on('click', '#ap_identifier_options_edit .dropdown-item', function() {
    $(this).parents(".dropdown").find('.btn').html($(this).text());
    var ap_identifier = $(this).attr('data-value');
    $('#ap_identifier_edit').val(ap_identifier);

    $('#ap_serial_edit').fadeIn();  
    if (ap_identifier == "Serial Number") {
      $('#ap_serial').attr('placeholder','Serial Number');
    } else if (ap_identifier == "MAC Address") {
      $('#ap_serial').attr('placeholder','MAC Address');
    } 
  });

  $("#ap_page").on('click', '#btn_add_ap', function() {
    $('#create_ap_block').fadeIn();
  });

  $("#ap_page").on('click', '.btn_ap', function() {
    var venue_id = $('#venue_id').val();
    var ap_name = $('#ap_name').val();
    var ap_desc = $('#ap_desc').val();
    var ap_identifier = $('#ap_identifier').val();
    var ap_serial = $('#ap_serial').val();
    var ap_tags = $('#ap_tags').val();
    //alert(venue_id+"::"+ap_name+"::"+ap_serial);
    if (venue_id == '' || ap_name == '' || ap_identifier == '' || ap_serial == '') {
      $('#error_msg_crt').show();
      $('#error_text').html('Fields marked with * are mandatory');
      $('#success_msg_crt').hide();
    } else {
      $.ajax({
        url: "createAccessPoint",
        type: "POST",
        data: {
          venue_id: venue_id,
          ap_name: ap_name,
          ap_desc: ap_desc,
          ap_identifier: ap_identifier,
          ap_serial: ap_serial,
          ap_tags: ap_tags,
          '_token': window.Laravel.csrfToken
        },
        success: function(result) {
          //alert(result);
          //alert(result);
          var ap_output = JSON.parse(result);
          if (ap_output.status == 'success') {
            $('#error_msg_crt').hide();
            $('#success_msg_crt').show();

            $.fn.generate_ap_table();
          } else {
            $('#error_msg_crt').show();
            $('#error_text').html('Some unexpected error occured.');
            $('#success_msg_crt').hide();
          }
        }
      });   
    }
  });


  $.fn.generate_ap_table = function() {
    $('#ap_table tbody').html('');
    $.fn.spin_on('spin-area');
    //alert();
    $.ajax({
      url: "getAllAccessPoints",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_aps) {
        // console.log(all_aps);
        //console.log(all_aps);
        var html_content = '';
        for (var ap in all_aps) {
          var venue_name = all_aps[ap]['venue_name'];
          var ap_crt_date = all_aps[ap]['created_at'];
          
          var ap_name = '';
          if (all_aps[ap]['ap_name']) {
            ap_name = all_aps[ap]['ap_name'];
          }

          var ap_description = '';
          if (all_aps[ap]['ap_description']) {
            ap_description = all_aps[ap]['ap_description'];
          }

          var ap_identifier = '';
          if (all_aps[ap]['ap_identifier']) {
            ap_identifier = all_aps[ap]['ap_identifier'];
          }

          var ap_serial = '';
          if (all_aps[ap]['ap_serial']) {
            ap_serial = all_aps[ap]['ap_serial'];
          }

          var ap_mac_address = '';
          if (all_aps[ap]['ap_mac_address']) {
            ap_mac_address = all_aps[ap]['ap_mac_address'];
          }

          /*if (ap_identifier == "MAC Address") {
            ap_mac_address = ap_serial;
            ap_serial = '';

          }*/

          var ap_tags = '';
          if (all_aps[ap]['ap_tags']) {
            ap_tags = all_aps[ap]['ap_tags'];
          }

          var ap_status = 'Not Yet Connected';
          if (all_aps[ap]['ap_status']) {
            if (all_aps[ap]['ap_status'] == "not_yet_connected") {
              ap_status = "Not Yet Connected";
            } else if (all_aps[ap]['ap_status'] == "connected") {
              ap_status = "Connected";
            } else if (all_aps[ap]['ap_status'] == "disconnected") {
              ap_status = "Disconnected";
            } else {
              ap_status = all_aps[ap]['ap_status'];
            }
          }

          var ap_model = '';
          if (all_aps[ap]['ap_model']) {
            ap_model = all_aps[ap]['ap_model'];
          }

          var ap_ip_address = '';
          if (all_aps[ap]['ap_ip_address']) {
            ap_ip_address = all_aps[ap]['ap_ip_address'];
          }

          var ap_mesh_role = '';
          if (all_aps[ap]['ap_mesh_role']) {
            ap_mesh_role = all_aps[ap]['ap_mesh_role'];
          }

          var client_count = '0';
          if (all_aps[ap]['client_count']) {
            client_count = all_aps[ap]['client_count'];
          }

          html_content = html_content+'<tr>';
          html_content = html_content+'<input type="hidden" class="row_ap_id" value='+ap+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_name" value='+ap_name+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_desc" value='+ap_description+'>';
          // html_content = html_content+'<input type="hidden" class="row_ap_status" value='+ap_status+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_serial" value='+ap_serial+'>';
          // html_content = html_content+'<input type="hidden" class="row_ap_ip_address" value='+ap_ip_address+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_mac_address" value='+ap_mac_address+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_venue_name" value='+venue_name+'>';
          // html_content = html_content+'<input type="hidden" class="row_ap_client_count" value='+client_count+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_tags" value='+ap_tags+'>';
          html_content = html_content+'<input type="hidden" class="row_ap_identifier" value="'+ap_identifier+'">';
          
          html_content = html_content+'<td>'+ap_name+'<br/>Created On: '+ap_crt_date+'</td>';
          html_content = html_content+'<td>'+ap_status+'</td>';
          html_content = html_content+'<td>'+ap_serial+'</td>';
          html_content = html_content+'<td>'+ap_ip_address+'</td>';
          html_content = html_content+'<td>'+ap_mac_address+'</td>';
          html_content = html_content+'<td>'+venue_name+'</td>';
          html_content = html_content+'<td>'+client_count+'</td>';
          html_content = html_content+'<td>'+ap_tags+'</td>';

          var icons_html = '<span class="edit_ap_record"><i class="far fa-edit" style="font-size:13px; color: #696969;"></i></span><span class="del_ap_record" style="padding-left: 10px;"><i class="far fa-trash-alt" style="font-size:13px; color: #696969;"></i></span>';
          html_content = html_content+'<td>'+icons_html+'</td>';
          //console.log(all_venues[venue]['venue_id']);
          html_content = html_content+'</tr>';
        }
        $('#ap_table tbody').html(html_content);   
        $.fn.spin_off('spin-area');    
      }
    }); 
  };
  $.fn.generate_ap_table();

  $.fn.get_all_venues = function() {
    $.ajax({
      url: "getAllVenues",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_venues) {
        var html_content = '';
        //alert(all_venues);
        for (var venue in all_venues) {
          var venue_name = all_venues[venue]['venue_name'];
          var venue_id = all_venues[venue]['venue_id'];

          html_content = html_content+'<a class="dropdown-item" data-value="'+venue_id+'">'+venue_name+'</a>';
        }

        $("#venue_dropdown_options").html(html_content);
        $("#venue_dropdown_options_edit").html(html_content);
      }
    });
  }
  $.fn.get_all_venues();

  $("#ap_page").on('click', '.edit_ap_record', function() {
    $('#error_msg_edit_ap').hide();
    $('#success_msg_edit_ap').hide();
    $('#ap_page .edit_ap_block').fadeIn();
    var ap_id = $(this).closest('tr').find('.row_ap_id').val();
    var ap_name = $(this).closest('tr').find('.row_ap_name').val();
    var ap_desc = $(this).closest('tr').find('.row_ap_desc').val();
    // var ap_status = $(this).closest('tr').find('.row_ap_status').val();
    var ap_serial = $(this).closest('tr').find('.row_ap_serial').val();
    // var ap_ip_address = $(this).closest('tr').find('.row_ap_ip_address').val();
    var ap_mac_address = $(this).closest('tr').find('.row_ap_mac_address').val();
    var venue_name = $(this).closest('tr').find('.row_ap_venue_name').val();
    // var client_count = $(this).closest('tr').find('.row_ap_client_count').val();
    var ap_tags = $(this).closest('tr').find('.row_ap_tags').val();
    var ap_identifier = $(this).closest('tr').find('.row_ap_identifier').val();
    // console.log(ap_identifier);

    $('html, body').animate({
      scrollTop: $("#ap_page .edit_ap_block").offset().top-60
    }, 300);

    $('#ap_page .ap_id_edit_block').val(ap_id);

    $('#venue_dropdown_edit').html(venue_name);
    $("#venue_dropdown_options_edit a").each(function() {
      // console.log($(this).text()+":::"+venue_name);
      if ($(this).text() == venue_name) {
        $('#venue_id_edit').val($(this).attr('data-value'));
      }
    });

    $('#ap_name_edit').val(ap_name);
    $('#ap_desc_edit').val(ap_desc);
    $('#ap_tags_edit').val(ap_tags);

    $('#ap_identifier_edit').val(ap_identifier);
    $('#ap_identifier_dropdown_edit').html(ap_identifier);

    // console.log(ap_identifier);
    if (ap_identifier == "Serial Number") {
      $('#ap_serial_edit').val(ap_serial);
      // console.log('yesy');  
    } else {
      $('#ap_serial_edit').val(ap_mac_address);
    }
    // $('#venue_name_edit').val(venue_name);
    // $('#venue_desc_edit').val(venue_desc);
    // $('#venue_add_edit').val(venue_add);
    // $('#venue_add_notes_edit').val(venue_add_notes);
  });

  $("#ap_page").on('click', '.btn_ap_cancel', function() {
    $('#ap_page .edit_ap_block').fadeOut();
    $.fn.reset_ap_edit_block();
  });

  $.fn.reset_ap_edit_block = function() {
    $('#ap_page .ap_id_edit_block').val('');
    $('#ap_name_edit').val('');
    $('#ap_desc_edit').val('');
    $('#ap_tags_edit').val('');
    $('#ap_identifier_edit').val('');
    $('#ap_identifier_dropdown_edit').html('');
    $('#ap_serial_edit').val('');
    $('#venue_dropdown_edit').html('');
    $('#venue_id_edit').val('');
  }

  $("#ap_page").on('click', '#btn_edit_ap', function() {
    var ap_id = $('#ap_page .ap_id_edit_block').val();
    var venue_id = $('#venue_id_edit').val();
    var ap_name = $('#ap_name_edit').val();
    var ap_desc = $('#ap_desc_edit').val();
    var ap_serial = $('#ap_serial_edit').val();
    var ap_tags = $('#ap_tags_edit').val();
    var ap_identifier = $('#ap_identifier_edit').val();


    // console.log(ap_id+"::"+ap_name+"::"+venue_id+"::"+ap_serial+"::"+ap_tags+"::"+ap_identifier);

    if (ap_id == '' || ap_name == '' || venue_id == '' || ap_identifier == '' || ap_serial == '') {
      $('#error_msg_edit_ap').show();
      $('#error_text_ap').html('Fields marked with * are mandatory');
      $('#success_msg_edit_ap').hide();
    } else {
      // console.log('calling ajax');
      $.ajax({
        url: "updateAccessPoint",
        type: "POST",
        data: {
          ap_id: ap_id,
          ap_name: ap_name,
          ap_desc: ap_desc,
          venue_id: venue_id,
          ap_serial: ap_serial,
          ap_tags: ap_tags,
          ap_identifier: ap_identifier,
          '_token': window.Laravel.csrfToken
        },
        success: function(result) {
          // console.log(result);
          
          if (result == 'ap_not_found') {
            $('#error_msg_edit_ap').show();
            $('#error_text_ap').html('Error: Access Point '+ap_name+' not found');
            $('#success_msg_edit_ap').hide();
          } else {
            $('#error_msg_edit_ap').hide();
            $('#success_msg_edit_ap').show();
            //$('#ap_page .edit_ap_block').fadeOut();
            // $.fn.reset_ap_edit_block();
            $.fn.generate_ap_table();
          }
        },
        error: function(err) {
          // console.log(err);
        }
      });   
    }
  });

  $("#ap_page").on('click', '.del_ap_record', function() {
    var confirm_check = confirm("Do you want to delete this access point ?");

    if (!confirm_check) {
      return false;
    }

    var ap_id = $(this).closest('tr').find('.row_ap_id').val();
    // console.log(venue_id);
    $.ajax({
      url: "delAccessPoint",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken,
        ap_id: ap_id
      },
      success: function(response) {
        // console.log(response);
        $.fn.generate_ap_table();
      }
    });
  });
} else if (document.getElementById('wifi_page')) {
  
  $.fn.toggle_flow_section = function(flow_class) {
    $('.flow_section').hide();
    $('#'+flow_class).show();    
  }

  $.fn.create_network = function() {
    var network_venues = [];
    $('.venue_checkbox').each(function() {
      if ($(this).is(':checked')) {
        console.log('ID::::'+$(this).closest('tr').find('.venue_id').val());
        network_venues.push($(this).closest('tr').find('.venue_id').val());
      }       
    });

    var networkData = {
      'network_name': $('#network_name').val(),
      'network_desc': $('#network_desc').val(),
      'network_type': $('#network_type').val(),
      'network_vlan': $('#network_vlan').val(),
      'security_protocol': $('#security_protocol').val(),
      'passphrase_format': $('#passphrase_format').val(),
      'passphrase_expiry': $('#passphrase_expiry').val(),
      'backup_passphrase': $('#backup_passphrase').val(),
      'passphrase_length': $('#passphrase_length').val(),
      'network_venues': JSON.stringify(network_venues),
    };
    
    $.ajax({
      url: "createNetwork",
      type: "POST",
      data: {
        networkData: JSON.stringify(networkData),
        '_token': window.Laravel.csrfToken
      },
      success: function(result) {
        //alert(result);
        var wifi_output = JSON.parse(result);
        if (wifi_output.status == 'success') {
          $('#error_msg_crt').hide();
          $('#success_msg_crt').show();
          $.fn.generate_wifi_table();
        } else {
          $('#error_msg_crt').show();
          $('#error_text').html('Some unexpected error occured.');
          $('#success_msg_crt').hide();
        }
      }
    });
  }
  //$.fn.create_network();

  $.fn.navigate = function(this_object) {
    var navigate_step_form = $(this_object).attr('link');

    if (navigate_step_form == 'form_submit') {
      $.fn.create_network();
    } else {

      $('.form_class').hide();
      $('.form_class').removeClass('active');

      
      $('.'+navigate_step_form).show();
      $('.'+navigate_step_form).addClass('active');
        //ced853
      if (navigate_step_form == 'form_step_1') {
        $('#btn_back_step').hide();
        $('#btn_next_step').html('Next');
        if ($('#network_type' == "PSK")) {
          $('#btn_next_step').attr('link','form_step_2');
        }
        else if ($('#network_type' == "CaptivePortal")) {
          $('#btn_next_step').attr('link','form_step_3');
        }
        $('.circle_step_1').css('background-color','#b3b3b3');
        $('.title_step_1').css('color','#696969');
        
        $('.circle_step_2').css('background-color','#b3b3b3');
        $('.title_step_2').css('color','#696969');
      } else if (navigate_step_form == 'form_step_2') {
        $('.circle_step_1').css('background-color','#ced853');
        $('.title_step_1').css('color','#ced853');
        
        $('.circle_step_2').css('background-color','#b3b3b3');
        $('.title_step_2').css('color','#696969');
        
        $('#btn_back_step').show();
        $('#btn_back_step').attr('link', 'form_step_1');
        $('#btn_next_step').attr('link', 'form_step_6');
        $('#btn_next_step').html('Next');
      } else if (navigate_step_form == 'form_step_6') {
        $('.circle_step_1').css('background-color','#ced853');
        $('.title_step_1').css('color','#ced853');
        
        $('.circle_step_2').css('background-color','#ced853');
        $('.title_step_2').css('color','#ced853');
        
        $('#btn_back_step').show();
        $('#btn_back_step').attr('link', 'form_step_2');
        $('#btn_next_step').attr('link', 'form_submit');
        $('#btn_next_step').html('Create');
      }
      $.fn.reset_btn_states();
    }
  }

  $.fn.reset_btn_states = function() {
    $('.form_class').each(function() {
      if ($(this).hasClass('active')) {
        if ($(this).attr('form-step') == 'form_step_1') {
          if ($('#network_type').val() && $('#network_name').val()) {
            $('#btn_next_step').addClass('active');
            $('#btn_next_step').removeAttr('disabled');
          } else {
            $('#btn_next_step').removeClass('active');
            $('#btn_next_step').attr('disabled', 'disabled');
          }
        } else if ($(this).attr('form-step') == 'form_step_2') {
          if ($('#security_protocol').val() && $('#passphrase_format').val() && $('#passphrase_expiry').val() && $('#backup_passphrase').val() && $('#passphrase_length').val()) {
            $('#btn_next_step').addClass('active');
            $('#btn_next_step').removeAttr('disabled');
          } else {
            $('#btn_next_step').removeClass('active');
            $('#btn_next_step').attr('disabled', 'disabled');
          }
        }
      }
    });
    
    /*if (form_step == 'form_step_1') {
      
    }*/
  }

  $(".left_section").on('change', '.form-fields', function() {
    $.fn.reset_btn_states();
  });

  $(".dropdown").on('click', '.dropdown-item', function() {
    $(this).parents(".dropdown").find('.btn').html($(this).find('.title_text_dropdown').text());
  });

  $("#network_type_options").on('click', '.dropdown-item', function() {
    //$(this).parents(".dropdown").find('.btn').html($(this).find('.title_text_dropdown').text());
    var network_type = $(this).attr('data-value');
    $('#network_type').val(network_type);
    
    if (network_type == 'PSK') {
      $.fn.toggle_flow_section('right_flow_1');
      $('#btn_next_step').attr('link','form_step_2');
    } else if (network_type == 'CaptivePortal') {
      $.fn.toggle_flow_section('right_flow_2');
      $('#btn_next_step').attr('link','form_step_3');
    }

    $.fn.reset_btn_states();
  });

  $("#sp_dropdown_options").on('click', '.dropdown-item', function() {
    var security_protocol = $(this).attr('data-value');
    $('#security_protocol').val(security_protocol);    

    $.fn.reset_btn_states();
  });

  $("#pf_dropdown_options").on('click', '.dropdown-item', function() {
    var passphrase_format = $(this).attr('data-value');
    $('#passphrase_format').val(passphrase_format);    

    $.fn.reset_btn_states();
  });

  $("#pe_dropdown_options").on('click', '.dropdown-item', function() {
    var passphrase_expiry = $(this).attr('data-value');
    $('#passphrase_expiry').val(passphrase_expiry);    

    $.fn.reset_btn_states();
  });

  $("#wifi_page").on('click', '#btn_next_step', function() {
    if ($(this).hasClass('active')) {
      $.fn.navigate(this);
    }
  });

  $("#wifi_page").on('click', '#btn_back_step', function() {
    $.fn.navigate(this);
  });

  $("#wifi_page").on('click', '#btn_add_wifi', function() {
    $('#create_network_block').show();
  });

  $.fn.venues_network_table = function() {
    $.ajax({
      url: "getAllVenues",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_venues) {
        //alert(all_venues);
        var html_content = '';
        for (var venue in all_venues) {
          var venue_name = all_venues[venue]['venue_name'];
          var venue_id = all_venues[venue]['venue_id'];

          var venue_add = '';
          if (all_venues[venue]['venue_address']) {
            venue_add = all_venues[venue]['venue_address'];
          }

          var network_count = '0';
          if (all_venues[venue]['network_count']) {
            network_count = all_venues[venue]['network_count'];
          }

          var ap_count = '0';
          if (all_venues[venue]['ap_count']) {
            ap_count = all_venues[venue]['ap_count'];
          }

          html_content = html_content+'<tr>';
          html_content = html_content+'<input type="hidden" class="venue_id" value="'+venue_id+'">';
          html_content = html_content+'<td align="center" style="border-color: #f2f2f2;">'+venue_name+'</td>';
          html_content = html_content+'<td align="center" style="border-color: #f2f2f2;">'+venue_add+'</td>';
          html_content = html_content+'<td align="center" style="border-color: #f2f2f2;">'+network_count+'</td>';
          html_content = html_content+'<td align="center" style="border-color: #f2f2f2;">'+ap_count+'</td>';
          html_content = html_content+'<td align="center" style="border-color: #f2f2f2;"><input type="checkbox" class="venue_checkbox"></td>';
          //console.log(all_venues[venue]['venue_id']);
          html_content = html_content+'</tr>';
        }

        $('#venues_network_table tbody').html(html_content);        
      }
    });
  }
  $.fn.venues_network_table();
  
  $("#wifi_page").on('click', '.btn_wifi', function() {
    var venue_id = $('#venue_id').val();
    var ap_name = $('#ap_name').val();
    var ap_desc = $('#ap_desc').val();
    var ap_serial = $('#ap_serial').val();
    var ap_tags = $('#ap_tags').val();
    //alert(venue_id+"::"+ap_name+"::"+ap_serial);
    if (venue_id == '' || ap_name == '' || ap_serial == '') {
      $('#error_msg_crt').show();
      $('#error_text').html('Fields marked with * are mandatory');
      $('#success_msg_crt').hide();
    } else {
      $.ajax({
        url: "createAccessPoint",
        type: "POST",
        data: {
          venue_id: venue_id,
          ap_name: ap_name,
          ap_desc: ap_desc,
          ap_serial: ap_serial,
          ap_tags: ap_tags,
          '_token': window.Laravel.csrfToken
        },
        success: function(result) {
          //alert(result);
          //alert(result);
          if (result == 'success') {
            $('#error_msg_crt').hide();
            $('#success_msg_crt').show();

            $.fn.generate_ap_table();
          } else {
            $('#error_msg_crt').show();
            $('#error_text').html('Some unexpected error occured.');
            $('#success_msg_crt').hide();
          }
        }
      });   
    }
  });

  $("#network_name").on('change', function() {
    if ($(this).val()) {
      $('.error-text-network-name').html('');
      $.ajax({
        url: "duplicateNetworkName",
        type: "POST",
        data: {
          'network_name': $(this).val(),
          '_token': window.Laravel.csrfToken
        },
        success: function(status) {
          console.log(status);
          if (status == 'duplicate') {
            $('#network_name').val('');
            $('.error-text-network-name').html('* Network name must be unique');
          } else {
              $('.error-text-network-name').html('');
          }
          $.fn.reset_btn_states();
        }
      });       
    }
  });

  $.fn.generate_wifi_table = function() {
    //alert();
    $.fn.spin_on('spin-area');
    $.ajax({
      url: "getAllWifiNetworks",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_networks) {
        //alert(all_networks);
        console.log(all_networks);
        var html_content = '';
        for (var network in all_networks) {

          var network_name = '';
          if (all_networks[network]['network_name']) {
            network_name = all_networks[network]['network_name'];
          }

          var network_desc = '';
          if (all_networks[network]['network_description']) {
            network_desc = all_networks[network]['network_description'];
          }
          
          var network_type = '';
          if (all_networks[network]['network_type']) {
            network_type = all_networks[network]['network_type'];
          }

          var network_vlan = '';
          if (all_networks[network]['network_vlan']) {
            network_vlan = all_networks[network]['network_vlan'];
          }
          
          var backup_phrase = '';
          if (all_networks[network]['backup_phrase']) {
            backup_phrase = all_networks[network]['backup_phrase'];
          }

          var count_venue = '';
          if (all_networks[network]['count_venue']) {
            count_venue = all_networks[network]['count_venue'];
          }

          var count_ap = '';
          if (all_networks[network]['count_ap']) {
            count_ap = all_networks[network]['count_ap'];
          }

          var client_count = '0';
          if (all_networks[network]['client_count']) {
            client_count = all_networks[network]['client_count'];
          }
          
          html_content = html_content+'<tr>';
          html_content = html_content+'<input type="hidden" class="row_network_id" value='+network+'>';
          html_content = html_content+'<input type="hidden" class="row_network_name" value='+network_name+'>';
          html_content = html_content+'<input type="hidden" class="row_network_desc" value='+network_desc+'>';
          html_content = html_content+'<input type="hidden" class="row_network_type" value='+network_type+'>';
          html_content = html_content+'<input type="hidden" class="row_network_vlan" value='+network_vlan+'>';
          
          html_content = html_content+'<td>'+network_name+'</td>';
          html_content = html_content+'<td>'+network_desc+'</td>';
          html_content = html_content+'<td>'+network_type+'</td>';
          html_content = html_content+'<td>'+count_venue+'</td>';
          html_content = html_content+'<td>'+count_ap+'</td>';
          html_content = html_content+'<td>'+network_vlan+'</td>';
          html_content = html_content+'<td>'+client_count+'</td>';

          var icons_html = '<span class="edit_wifi_record"><i class="far fa-edit" style="font-size:13px; color: #696969;"></i></span><span class="del_wifi_record" style="padding-left: 10px;"><i class="far fa-trash-alt" style="font-size:13px; color: #696969;"></i></span>';
          html_content = html_content+'<td>'+icons_html+'</td>';
          html_content = html_content+'</tr>';
        }
        $.fn.spin_off('spin-area');
        $('#wifi_table tbody').html(html_content);       
      }
    }); 
  };
  $.fn.generate_wifi_table();

  $.fn.get_all_networks = function() {
    $.ajax({
      url: "getAllVenues",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_venues) {
        var html_content = '';
        //alert(all_venues);
        for (var venue in all_venues) {
          var venue_name = all_venues[venue]['venue_name'];
          var venue_id = all_venues[venue]['venue_id'];

          html_content = html_content+'<a class="dropdown-item" data-value="'+venue_id+'">'+venue_name+'</a>';
        }

        $("#venue_dropdown_options").html(html_content);
      }
    });
  }

  $("#wifi_page").on('click', '.del_wifi_record', function() {
    var confirm_check = confirm("Do you want to delete this network ?");

    if (!confirm_check) {
      return false;
    }

    var network_id = $(this).closest('tr').find('.row_network_id').val();
    // console.log(venue_id);
    $.ajax({
      url: "delWifiNetwork",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken,
        network_id: network_id
      },
      success: function(response) {
        console.log(response);
        $.fn.generate_wifi_table();
      }
    });
  });

  //$.fn.get_all_networks();
}

if (document.getElementById('users_page'))
{
  $.fn.get_collections_data = function() {
    $.ajax({
      url: "getCollectionsData",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(sta_data) {
        var sta_data = JSON.parse(sta_data);
        console.log(sta_data);
        var html_content = '';
        $('.clients_count').html('('+sta_data['count']+')');
        for (var field in sta_data['sta_data']) {
          var sta_fields = sta_data['sta_data'][field];
          var timestamp = '';
          //var timestamp = sta_fields['timestamp']['$numberLong'];
          var sta_id = sta_fields['sta_id'];
          var sta_id = sta_fields['sta_id'];
          var ip_address = sta_fields['IPV4Address'];
          var venue_id = sta_fields['venue_id'];
          var ap_id = sta_fields['ap_id'];
          var radio_frequency = sta_fields['radio_frequency'];
          var bytes_sent = sta_fields['BytesSent'];
          var bytes_received = sta_fields['BytesReceived'];
          var signal_strength = sta_fields['SignalStrength'];
          var last_connected = sta_fields['LastConnectTime'];

          html_content = html_content+'<tr>';
          html_content = html_content+'<input type="hidden" class="sta_id" value="'+sta_id+'">';
          html_content = html_content+'<td>'+ip_address+'</td>';
          html_content = html_content+'<td>'+venue_id+'</td>';
          html_content = html_content+'<td>'+ap_id+'</td>';
          html_content = html_content+'<td>'+radio_frequency+'</td>';
          html_content = html_content+'<td>'+bytes_sent+'</td>';
          html_content = html_content+'<td>'+bytes_received+'</td>';
          html_content = html_content+'<td>'+signal_strength+'</td>';
          html_content = html_content+'<td>'+last_connected+'</td>';
          //console.log(all_venues[venue]['venue_id']);
          html_content = html_content+'</tr>';
          
        }
        $('#users_table tbody').html(html_content);
      }
    });
  }
  $.fn.get_collections_data();
} else if (document.getElementById('analytics_page'))
{
  $("#analytics_page").on('click', '.btn_save_setting_class', function() {
    var setting_time_interval = $("#setting_time_interval").val();
    //alert(setting_time_interval);

    $.ajax({
      url: "setTimeInterval",
      type: "POST",
      data: {
        'setting_time_interval': setting_time_interval,
        '_token': window.Laravel.csrfToken
      },
      success: function(data) {
        $("#setting_time_interval").val(data);
      }
    });
    
  });
  
  $.fn.get_time_interval_setting = function() {
    //var setting_time_interval = $("#setting_time_interval").val();

    $.ajax({
      url: "getTimeInterval",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(data) {
        $("#setting_time_interval").val(data);
        if (data != '') {
          $(".interval_time_heading").html(data);
        } else {
          $(".interval_time_heading").html('300');
        }
      }
    });
  }
  $.fn.get_time_interval_setting();

  $.fn.get_clients_graph_data = function() {
    $.ajax({
      url: "getClientsTrafficGraphData",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(graph_data) {
        console.log(graph_data);
        var graph_data = JSON.parse(graph_data);
        var dataPointsCount = graph_data['count_datapoints'];
        console.log(dataPointsCount);
        var dataPoints = graph_data['clients_count'];
        console.log(dataPoints);

        var setting_time_interval = graph_data['setting_time_interval'];
        //console.log(graph_data);
        var maxDataPoint = Math.max.apply(null, dataPoints);
        maxDataPoint = maxDataPoint*(3/2);
        var digits_cnt = digits_count(maxDataPoint);
        //maxDataPoint = parseInt(maxDataPoint / 10, 10) + 1 * 10;
        if (digits_cnt == 2) {
          maxDataPoint = Math.ceil(maxDataPoint / 10) * 10;
        } else if (digits_cnt == 3) {
          maxDataPoint = Math.ceil(maxDataPoint / 100) * 100;
        } else if (digits_cnt == 4) {
          maxDataPoint = Math.ceil(maxDataPoint / 1000) * 1000;
        }

        var dataPointsTime = [];
        var today = new Date();
        var current_time = today.getHours() + ":" + today.getMinutes();
        var interval = parseInt(setting_time_interval);
        
        for (i=0; i<dataPointsCount; i++) {
          dataPointsTime[i] = current_time;
          today.setMinutes(today.getMinutes() - interval);
          current_time = today.getHours() + ":" + today.getMinutes();
        }

        //dataPoints.reverse();
        dataPointsTime.reverse();
        console.log(dataPointsTime);
        /*var temp_today = new Date();
        temp_today.setMinutes(today.getMinutes() - 5);*/
        

        var ctx2 = document.getElementById("clientTrafficGraph");
        var myLineChart = new Chart(ctx2, {
          type: 'line',
          data: {
            labels: dataPointsTime,
            datasets: [{
              label: "Sessions",
              lineTension: 0.3,
              backgroundColor: "rgba(2,117,216,0.2)",
              borderColor: "rgba(2,117,216,1)",
              pointRadius: 5,
              pointBackgroundColor: "rgba(2,117,216,1)",
              pointBorderColor: "rgba(255,255,255,0.8)",
              pointHoverRadius: 5,
              pointHoverBackgroundColor: "rgba(2,117,216,1)",
              pointHitRadius: 20,
              pointBorderWidth: 2,
              data: dataPoints,
            }],
          },
          options: {
            scales: {
              xAxes: [{
                time: {
                  unit: 'date'
                },
                gridLines: {
                  display: false
                },
                ticks: {
                  maxTicksLimit: dataPointsCount
                }
              }],
              yAxes: [{
                ticks: {
                  min: 0,
                  max: maxDataPoint,
                  maxTicksLimit: 5
                },
                gridLines: {
                  color: "rgba(0, 0, 0, .125)",
                }
              }],
            },
            legend: {
              display: false
            }
          }
        });
      }
    });
  }
  $.fn.get_clients_graph_data();
} else if (document.getElementById('dashboard_page'))
{
  $.fn.get_live_dashboard_data = function() {
    $.ajax({
      url: "getDashboardData",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(dashboard_data) {
        console.log(dashboard_data);
        var dashboard_data_raw = JSON.parse(dashboard_data);
        //console.log(dashboard_data_raw.org_id);
        $('.venue_count').html(dashboard_data_raw.venue_count);
        $('.ap_count').html(dashboard_data_raw.ap_count);
        $('.network_count').html(dashboard_data_raw.network_count);
        $('.clients_count').html(dashboard_data_raw.clients_count);
                
      }
    });
  }

  $.fn.get_clients_graph_data = function() {
    $.ajax({
      url: "getClientsTrafficGraphData",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(graph_data) {
        console.log("original_data_mongo"+graph_data);
        var graph_data = JSON.parse(graph_data);
        //var finalArray = graph_data['finalArray'];
        //console.log("data_final_array::"+finalArray);
        var dataPointsCount = graph_data['count_datapoints'];
        //console.log(dataPointsCount);
        var dataPoints = graph_data['clients_count'];
        console.log(dataPoints);
        //console.log(graph_data);
        var setting_time_interval = graph_data['setting_time_interval'];
        
        var maxDataPoint = Math.max.apply(null, dataPoints);
        maxDataPoint = maxDataPoint*(3/2);
        var digits_cnt = digits_count(maxDataPoint);
        //maxDataPoint = parseInt(maxDataPoint / 10, 10) + 1 * 10;
        if (digits_cnt == 2) {
          maxDataPoint = Math.ceil(maxDataPoint / 10) * 10;
        } else if (digits_cnt == 3) {
          maxDataPoint = Math.ceil(maxDataPoint / 100) * 100;
        } else if (digits_cnt == 4) {
          maxDataPoint = Math.ceil(maxDataPoint / 1000) * 1000;
        }

        /*var dataPointsTime = [];
        var today = new Date();
        var current_time = today.getHours() + ":" + today.getMinutes();
        var interval = parseInt(setting_time_interval);

        for (i=0; i<dataPointsCount; i++) {
          dataPointsTime[i] = current_time;
          today.setMinutes(today.getMinutes() - interval);
          current_time = today.getHours() + ":" + today.getMinutes();
        }
        
        dataPointsTime.reverse();*/
        
        /*var temp_today = new Date();
        temp_today.setMinutes(today.getMinutes() - 5);*/
        /*var dataPointsTime = [];
        var dataPointsTimeArray = JSON.parse(graph_data['time_intervals']);
        console.log(dataPointsTimeArray);
        dataPointsTimeArray.forEach(function (item, index) {
          console.log(item, index);
        });*/
        var dataPointsTime = graph_data['time_intervals'];
        console.log(dataPointsTime);
        
        /*dataPointsTimeArray.forEach(function(value, index) {
            dataPointsTime[0] = value;
        });
        console.log(dataPointsTime);*/
        
        var ctx2 = document.getElementById("clientTrafficGraph");
        var myLineChart = new Chart(ctx2, {
          type: 'line',
          data: {
            labels: dataPointsTime,
            datasets: [{
              label: "Sessions",
              lineTension: 0.3,
              backgroundColor: "rgba(2,117,216,0.2)",
              borderColor: "rgba(2,117,216,1)",
              pointRadius: 5,
              pointBackgroundColor: "rgba(2,117,216,1)",
              pointBorderColor: "rgba(255,255,255,0.8)",
              pointHoverRadius: 5,
              pointHoverBackgroundColor: "rgba(2,117,216,1)",
              pointHitRadius: 20,
              pointBorderWidth: 2,
              data: dataPoints,
            }],
          },
          options: {
            scales: {
              xAxes: [{
                time: {
                  unit: 'date'
                },
                gridLines: {
                  display: false
                },
                ticks: {
                  maxTicksLimit: dataPointsCount
                }
              }],
              yAxes: [{
                ticks: {
                  min: 0,
                  max: maxDataPoint,
                  maxTicksLimit: 5
                },
                gridLines: {
                  color: "rgba(0, 0, 0, .125)",
                }
              }],
            },
            legend: {
              display: false
            }
          }
        });
      }
    });
  }
  $.fn.get_clients_graph_data();
  $.fn.get_live_dashboard_data();

  $.fn.get_time_interval_setting = function() {
    //var setting_time_interval = $("#setting_time_interval").val();

    $.ajax({
      url: "getTimeInterval",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(data) {
        if (data != '') {
          $(".interval_time_heading").html(data);
        } else {
          $(".interval_time_heading").html('300');
        }
      }
    });
  }
  $.fn.get_time_interval_setting();

}

if (document.getElementById('test_page'))
{
  console.log(JSON.parse($('#results_hidden').val()));
}

function digits_count(n) {
  var count = 0;
  if (n >= 1) ++count;

  while (n / 10 >= 1) {
    n /= 10;
    ++count;
  }

  return count;
}