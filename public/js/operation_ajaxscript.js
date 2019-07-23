$(document).ready(function(){
  var original_facet="";
  $('.buttonNext').click(function(){

  var method =$( "#method option:selected" ).text();
  var facet =$( "#facet option:selected" ).text();
  var id = document.getElementById('status').value;

    if(method == 'Checklist'){
      $("div#csv_form").hide();
      $("div#checklist_form").show();
      if(facet == 'Taxonomy'){
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })

        if(original_facet == facet){
          var url = 'meta_filter/'+id;
        }
        else{
          var url = '/taxonomy_filter';
        }

        console.log(url);
        $.ajax({
          type: 'POST',
          url: url.toLowerCase(),
          success: function(data){
              $('#list_tb').html(data);
          }
        });
      }
      if(facet == 'Postal_code'){


        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })

        if(original_facet == facet)
          var url = 'meta_filter/'+id;
        else
          var url = '/postal_code_filter';

        $.ajax({
          type: 'POST',
          url: url.toLowerCase(),
          success: function(data){
              $('#list_tb').html(data);
          }
        });
      }
    }
    else{
      $("div#checklist_form").hide();
      $("div#csv_form").show();
    }

  });

  $('.edit-meta').click(function(){

    var id = $(this).val();
    $("#status").val(id);

    id = $(this).parent().parent().children().eq(0);
    operation = $(this).parent().parent().children().eq(1).html();
    original_facet = $(this).parent().parent().children().eq(2).html();
    method = $(this).parent().parent().children().eq(3).html();

    $("div#operation select").val(operation);
    $("div#facet select").val(original_facet);
    $("div#method select").val(method);

  });

  $('.delete-meta').click(function(){

    var id = $(this).val();
    $("#id").val(id);

  });

});