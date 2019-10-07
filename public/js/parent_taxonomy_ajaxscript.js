
$(document).ready(function(){

    //get base URL *********************
    var url = $('#url').val();
    //display modal form for creating new product *********************
    $('#btn-add').click(function(e){

        $('#btn-save').val("add");
        $('#frmProducts').trigger("reset");
        $('#myModal').modal('show');
        
        $('#btn-save').on('click', function() {
            var data = {
                parent_taxonomy_name: $('#parent_taxonomy_name').val(),
                parent_taxonomy_name: $('#parent_taxonomy_vocabulary').val()
            }

            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (data) {
                    console.log(data);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
    });



    //display modal form for product EDIT ***************************
    $(document).on('click','.open_modal',function(e){

        var id = $(this).val();

        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function (data) {
                // console.log(data);
                $('#id').val(data.id);
                $('#parent_taxonomy_name').val(data.parent_taxonomy_name);
                $('#parent_taxonomy_vocabulary').val(data.parent_taxonomy_vocabulary);
                //if(data.parent_taxonomy_vocabulary != "")
                $('#btn-save').val("update");
                $('#myModal').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //display modal form for open_taxonomy_modal ***************************
    $(document).on('click','.open_taxonomy_modal',function(e){

        var parent_taxonomy_id = $(this).val();    
        $("#parent_taxonomy_id").val(parent_taxonomy_id);
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: url + '/taxonomies/' + parent_taxonomy_id,
            success: function (data) {
                console.log(data);
                var selected_ids = [];
                for (var i = 0; i < data.taxonomies.length; i ++) {
                    selected_ids.push(data.taxonomies[i].id);
                }
                var html = '<h2>'+ data.parent_taxonomy_name +'</h2>';
                html += '<table id="taxonomy_tb" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">'
                html += '<thead>'
                html += '<tr>'
                html += '<th class="text-center"></th>'
                html += '<th class="text-center">Taxonomy Name (Name of Child Taxonomy)</th>'            
                // html += '<th class="text-center">Parent Name</th>'
                // html += '<th class="text-center">Category ID</th>'
                html += '</tr>'
                html += '</thead>'
                html += '<tbody>'
                for (var i = 0; i < data.all_taxonomies.length; i ++) {
                    var taxonomy_id = data.all_taxonomies[i].id
                    html += '<tr>'
                    html += '<td class="text-center">'
                    var checkbox = '<input type="checkbox" name="checked_taxonomies[]" value="'+taxonomy_id+'" '+ (selected_ids.indexOf(taxonomy_id) > -1 ? 'checked >' : '>');
                    html += checkbox
                    html += '</td>'
                    html += '<td class="text-center">'+data.all_taxonomies[i].taxonomy_name+'</td>'
                    // html += '<td class="text-center">'+data.all_taxonomies[i].taxonomy_parent_name+'</td>'                  
                    // html += '<td class="text-center">'+data.all_taxonomies[i].category_id+'</td>'               
                    html += '</tr>'
                }
                        
                html += '</tbody>'
                html += '</table>'
                $('#open_taxonomy_modal').modal('show');
                $('#list_tb_open_taxonomy').html(html);
                $('#taxonomy_tb').DataTable({
                    "columnDefs":[
                    {
                        "targets": 0,
                        "orderDataType": "dom-checkbox"
                    }],
                    "order": [[ 0, "desc" ]]
                });
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });



    //create new product / update existing product ***************************
    $( "#frmProducts" ).submit(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        e.preventDefault(); 
        var formData = {
            parent_taxonomy_name: $('#parent_taxonomy_name').val(),
            parent_taxonomy_vocabulary: $('#parent_taxonomy_vocabulary').val(),
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btn-save').val();
        var type = "POST"; //for creating new resource
        var id = $('#id').val();
        var my_url = url;
        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + id;
        }

        // console.log(formData);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                // console.log(data);
                // var product = '<tr id="project' + data.id + '"><td class="text-center">' + data.project_projectid + '</td><td class="text-center">' + data.project_managingagency + '</td>';
                // product += '<td class="text-center"><button class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill open_modal" title="Edit details" value="' + data.bodystyleid + '"><i class="la la-edit"></i></button>';
                // product += ' <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-product" title="Delete" value="' + data.bodystyleid + '"><i class="la la-trash"></i></button></td></tr>';
                
                if (state == "add"){ //if user added a new record
                    window.location.reload(); 
                   // $('.alert.alert-success.alert-dismissible.fade.show').hide(5000);
                }else{ //if user updated an existing record
                    $('#frmProducts').trigger("reset");
                    $('#myModal').modal('hide');
                    window.location.reload(); 
                    // $("#project" + project_id).replaceWith( product );
                   // $('.m-portlet.m-portlet--mobile').before(edit_alert);
                    //$('.alert.alert-brand.alert-dismissible.fade.show').hide(5000);
                }
                
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

     //display modal form for product Delete ***************************
    $(document).on('click','.delete-product',function(){
        var id = $(this).val();
       
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: url + '/' + id,
            success: function (data) {
                // console.log(data);
                $('#product_id').val(data.id);
                $('#btn-delete').val("delete");
                $('#deleteModal').modal('show');

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //delete product and remove it from TABLE list ***************************
    $(document).on('click','#btn-delete',function(){
        var product_id = $('#product_id').val();
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: "DELETE",
            url: url + '/' + product_id,
            success: function (data) {
                // console.log(data);
                window.location.reload(); 
               // $('.show').hide(5000);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
    
});