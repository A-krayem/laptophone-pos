function show_pictures(id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_items_images_table";
    var modal_name = "modal_all_all_items_images____";
    var modal_title = "Upload Images <small>(1200 x 630 px is recommended and max size 600kb)</small>";
    
    var content =
    '<div class="modal medium" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <b>Disk size:</b> <span id="current_disk"></span> <b>of</b> <span id="total_disk"></span>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row" style="margin-top:20px;">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:80px;">ID</th>\n\
                                        <th >Item</th>\n\
                                        <th style="width:120px;">Creation Date</th>\n\
                                        <th style="width:100px;">Show</th>\n\
                                        <th style="width:50px;"></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var =null;
        
        var search_fields = [];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=images&f=show_images&p0="+id,
                type: 'POST',
                dataSrc: function (json) {
                    $("#current_disk").html(json.current_pictures_storage);
                    $("#total_disk").html(json.max_pictures_storage);

                    return json.data;
                },
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "55vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": false, "visible":  false },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true,"className": "dt-center" },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_allimages">frtip',
            initComplete: function(settings, json) {
                 $("div.toolbar_allimages").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-6 col-md-6 col-xs-6" style="padding-left:15px;padding-right:5px;">\n\
                            <form id="itemsimages_form" action="" method="post" enctype="multipart/form-data" >\n\
                                <input type="hidden" value="'+id+'" name="it_upload" id="it_upload" />\n\
                                <span class="control-fileupload">\n\
                                    <input onchange="uploadimages()" type="file" id="itemsimages" name="itemsimages[]" multiple accept="image/x-png,image/gif,image/jpeg">\n\
                                </span>\n\
                            </form>\n\
                        </div>\n\
                    </div>\n\
                    ');  
                
                    submit_allimages_form();
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setImageuploadsOptions,
        });
        
        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('#modal_all_items_images_table .selected').removeClass("selected");
            $(this).addClass('selected');
        });
        
        $('#'+table_name).on('click', 'td', function () {
            //if ($(this).index() == 3) {
                //return false;
            //}
        });
        
        $('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable(this.value,that.index(),100,table_name);
            } );
        } );
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function setImageuploadsOptions(){
    var table = $('#modal_all_items_images_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 4).data('<i class="glyphicon glyphicon-trash redandsize" title="Delete" onclick="delete_image(\''+parseInt(table.cell(index, 0).data())+'\')" style="cursor:pointer"></i>');
    }
}

function delete_image(id){
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        closeOnConfirm: true,
        cancelButtonText: "Cancel",
    },
    function(isConfirm){
        if(isConfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show(); 
            $.getJSON("?r=images&f=delete_item_images&p0="+id, function (data) {

            }).done(function () {
                var table = $('#modal_all_items_images_table').DataTable();
                table.ajax.url("?r=images&f=show_images&p0="+$("#it_upload").val()).load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            });
        }
    });   
}

function uploadimages(){
    $("#itemsimages_form").submit();
}

function submit_allimages_form(){
    $("#itemsimages_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle").center();
        $(".sk-circle-layer").show(); 
        $.ajax({
            url: "?r=images&f=uploadimages&p0="+$("#it_upload").val(),
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                if(data==0){
                    alert("You have exceeded the maximum allowed disk space.");
                }
                var table = $('#modal_all_items_images_table').DataTable();
                table.ajax.url("?r=images&f=show_images&p0="+$("#it_upload").val()).load(function () {
                    $(".sk-circle-layer").hide();
                }, false);
            }
        });
    }));
}
