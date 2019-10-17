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
          
          $venue_add = '';
          if (all_venues[venue]['venue_description']) {
            $venue_add = all_venues[venue]['venue_address'];
          }

          $venue_desc = '';
          if (all_venues[venue]['venue_description']) {
            $venue_desc = all_venues[venue]['venue_description'];
          }

          $venue_add_notes = '';
          if (all_venues[venue]['venue_description']) {
            $venue_add_notes = all_venues[venue]['venue_address_notes'];
          }

          html_content = html_content+'<tr>';
          html_content = html_content+'<td>'+$venue_name+'<br/>Created On: '+$venue_crt_date+'</td>';
          html_content = html_content+'<td>'+$venue_desc+'</td>';
          html_content = html_content+'<td>'+$venue_add+'</td>';
          html_content = html_content+'<td>'+$venue_add_notes+'</td>';
          //console.log(all_venues[venue]['venue_id']);
          html_content = html_content+'</tr>';
        }

        $('#venues_tab tbody').html(html_content);        
      }
    }); 
  };

  $.fn.generate_venue_table();

} else if (document.getElementById('ap_page')) {

  $("#ap_page").on('click', '.dropdown-item', function() {
    $(this).parents(".dropdown").find('.btn').html($(this).text());
    var venue_name = $(this).attr('data-value');
    $('#venue_id').val(venue_name);
  });

  $("#ap_page").on('click', '#btn_add_ap', function() {
    $('#create_ap_block').fadeIn();
  });

  $("#ap_page").on('click', '.btn_ap', function() {
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


  $.fn.generate_ap_table = function() {
    //alert();
    $.ajax({
      url: "getAllAccessPoints",
      type: "POST",
      data: {
        '_token': window.Laravel.csrfToken
      },
      success: function(all_aps) {
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

          var ap_serial = '';
          if (all_aps[ap]['ap_serial']) {
            ap_serial = all_aps[ap]['ap_serial'];
          }

          var ap_tags = '';
          if (all_aps[ap]['ap_tags']) {
            ap_tags = all_aps[ap]['ap_tags'];
          }

          var ap_status = '';
          if (all_aps[ap]['ap_status']) {
            ap_status = all_aps[ap]['ap_status'];
          }

          var ap_model = '';
          if (all_aps[ap]['ap_model']) {
            ap_model = all_aps[ap]['ap_model'];
          }

          var ap_ip_address = '';
          if (all_aps[ap]['ap_ip_address']) {
            ap_ip_address = all_aps[ap]['ap_ip_address'];
          }

          var ap_mac_address = '';
          if (all_aps[ap]['ap_mac_address']) {
            ap_mac_address = all_aps[ap]['ap_mac_address'];
          }

          var ap_mesh_role = '';
          if (all_aps[ap]['ap_mesh_role']) {
            ap_mesh_role = all_aps[ap]['ap_mesh_role'];
          }

          html_content = html_content+'<tr>';
          html_content = html_content+'<td>'+ap_name+'<br/>Created On: '+ap_crt_date+'</td>';
          html_content = html_content+'<td>'+ap_status+'</td>';
          html_content = html_content+'<td>'+ap_serial+'</td>';
          html_content = html_content+'<td>'+ap_ip_address+'</td>';
          html_content = html_content+'<td>'+ap_mac_address+'</td>';
          html_content = html_content+'<td>'+venue_name+'</td>';
          html_content = html_content+'<td>'+ap_tags+'</td>';
          //console.log(all_venues[venue]['venue_id']);
          html_content = html_content+'</tr>';
        }
        $('#ap_table tbody').html(html_content);       
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
      }
    });
  }
  $.fn.get_all_venues();
}