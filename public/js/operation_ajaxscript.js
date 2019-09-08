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