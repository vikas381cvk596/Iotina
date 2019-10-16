
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
        /*if (result == "error_1") {
          alert("Error: Duplicate record.");
          return false;
        }

        if (result == "1") {
          //$(current_obj).closest('.Rtable-row').find('button').attr("disabled", "disabled");
          $(current_obj).closest('.Rtable-row').find('input').attr("disabled", "disabled");    
        }

        $buttons_html = ''
          +'<div class="col-md-12 text-center">'
            +'<button type="button" class="btn edit_btn btn-sm" value="edit">Edit</button>'
          +'</div>'
          +'<div class="col-md-12 text-center" style="margin-top: 5px;">'
            +'<button type="submit" name="update-btn" class="btn delete_btn btn-sm" value="delete" >Delete</button>'
          +'</div>';

        $(current_obj).closest('.Rtable-row').find(".button-row").html($buttons_html);
        $(current_obj).closest('.Rtable-row').removeClass('row_enable');    
        */
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
        $venue_name = all_venues[venue]['venue_name'];
        $venue_crt_date = all_venues[venue]['created_at'];
        
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
      /*if (result == 'venue_name_error') {
        $('#error_msg_crt').show();
        $('#error_text').html('Error: Venue name '+venue_name+' already exists');
        $('#success_msg_crt').hide();
      } else {
        $('#error_msg_crt').hide();
        $('#success_msg_crt').show();

        $.fn.generate_venue_table();
      }*/
      
    }
  }); 
};

$.fn.generate_venue_table();