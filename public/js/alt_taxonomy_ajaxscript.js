
$(document).ready(function(){

    //get base URL *********************
    var url = $('#url').val();
    var term_table;
    var checked_terms_set = false;
    //display modal form for creating new product *********************
    $('#btn-add').click(function(e){

        $('#btn-save').val("add");
        $('#frmProducts').trigger("reset");
        $('#myModal').modal('show');
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
                $('#alt_taxonomy_name').val(data.alt_taxonomy_name);
                $('#alt_taxonomy_vocabulary').val(data.alt_taxonomy_vocabulary);
                //if(data.alt_taxonomy_vocabulary != "")
                $('#btn-save').val("update");
                $('#myModal').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //display modal form for open_term_modal ***************************
    $(document).on('click','.open_term_modal',function(e){
        checked_terms_set = false;
        var alt_taxonomy_id = $(this).val();    
        $("#alt_taxonomy_id").val(alt_taxonomy_id);
        // Populate Data in Edit Modal Form
        $.ajax({
            type: "GET",
            url: url + '/terms/' + alt_taxonomy_id,
            success: function (data) {
                console.log(data);
                var selected_ids = data.terms;
                
                console.log(data.terms);
                var html = '<h2>'+ data.alt_taxonomy_name +'</h2>';
                html += '<table id="term_tb" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">'
                html += '<thead>'
                html += '<tr>'
                html += '<th class="text-center"></th>'
                html += '<th class="text-center">Name</th>'            
                html += '<th class="text-center">Parent Name</th>'            
                html += '</tr>'
                html += '</thead>'
                html += '</table>'
                $('#open_term_modal').modal('show');
                $('#list_tb_open_term').html(html);
                term_table = $('#term_tb').DataTable({
                    // 'ajax': 'https://api.myjson.com/bins/1us28',
                    'ajax': url + '/all_terms',
                    "columnDefs":[
                        {
                            'targets': 0,
                            'checkboxes': {
                               'selectRow': true
                            },
                            'createdCell':  function (td, cellData, rowData, row, col){
                                if(selected_ids.indexOf(cellData) > -1){
                                    this.api().cell(td).checkboxes.select();
                                }
                            },
                            'orderDataType': 'dom-checkbox',
                        }
                    ],                    
                    'select': {
                        'style': 'multi'
                    },
                    "order": [[ 0, "desc" ]]
                });
                $(':checkbox').on('change', function(e) {
                    var row = $(this).closest('tr');
                    var hmc = row.find(':checkbox:checked').length;
                    var kluj = parseInt(hmc);
                    row.find('td.counter').text(kluj);
                    term_table.row(row).invalidate('dom');
                  }); 
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $( "#form-open-terms button[type='submit']" ).click(function(e) {
        if (!checked_terms_set) {
            e.preventDefault();
            var checked_terms = term_table.column(0).checkboxes.selected();
            $('#checked_terms').val(checked_terms.join(","));
            checked_terms_set = true;
            $(this).trigger('click');
        }
    })



    //create new product / update existing product ***************************
    $( "#frmProducts" ).submit(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })

        e.preventDefault(); 
        var formData = {
            alt_taxonomy_name: $('#alt_taxonomy_name').val(),
            alt_taxonomy_vocabulary: $('#alt_taxonomy_vocabulary').val(),
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