$(document).ready(function(){
  var original_facet="";
  $('.buttonNext').click(function(){

  var method =$( "#method option:selected" ).text();
  var facet =$( "#facet option:selected" ).text();
  var id = document.getElementById('status').value;    

  });

  $('.edit-meta').click(function(){

    var id = $(this).val();
    $("#status").val(id);

    operation = $(this).parent().parent().children().eq(1).html();
    original_facet = $(this).parent().parent().children().eq(2).html();
    method = $(this).parent().parent().children().eq(3).html();

    $("input#operation").val(operation);
    $("input#facet").val(original_facet);
    $("input#method").val(method);
    if(original_facet == 'Taxonomy'){
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })

        var url = 'meta_filter/'+id;

        console.log(url);
        $.ajax({
          type: 'POST',
          url: url.toLowerCase(),
          success: function(data){
              $('#list_tb_edit').html(data);
          }
        });
    }

    if(original_facet == 'Postal_code'){
        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })

        var url = 'meta_filter/'+id;

        $.ajax({
          type: 'POST',
          url: url.toLowerCase(),
          success: function(data){
              $('#list_tb_edit').html(data);
          }
        });
    }

  });

  $('.delete-meta').click(function(){

    var id = $(this).val();
    $("#id").val(id);

  });

});