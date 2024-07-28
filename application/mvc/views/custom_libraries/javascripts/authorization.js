function showAuth(){
    var title = "";
    title = "Secure Login - Authorization devices";
    
    var content =
        '<div class="modal medium" data-keyboard="false" data-backdrop="static" id="authModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+title+'<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'authModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="auth_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:120px;">Creation Date</th>\n\
                                        <th style="width:80px;">User ID</th>\n\
                                        <th>Username</th>\n\
                                        <th style="width:100px;">AUTH KEY</th>\n\
                                        <th style="width:90px;">Status</th>\n\
                                        <th style="width:50px;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    
    
    $("#authModal").remove();
    $("body").append(content);
    
    

    $('#authModal').on('shown.bs.modal', function (e) {

        var table_name = "auth_table";
        var new_table__var =null;

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=authorize&f=get_authorized_devices",
                type: 'POST',
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "70vh",
            iDisplayLength: 200,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true,"visible": true },
                { "targets": [1], "searchable": true, "orderable": true,"visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": false, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            bSort:false,
            dom: '<"toolbar_journal">frtip',
            initComplete: function(settings, json) { 
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            //fnDrawCallback: updateRowsManualInvoice,
        });
    });

    $('#authModal').on('hidden.bs.modal', function (e) {
        $("#authModal").remove();
    }); 
    
    $('#authModal').modal('show');
}

function delete_auth(id){
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
           $(".sk-circle-layer").show();
            $.getJSON("?r=authorize&f=delete_auth&p0="+id, function (data) {
                
            }).done(function () {
                var table_details = $("#auth_table").DataTable();
                table_details.ajax.url("?r=authorize&f=get_authorized_devices").load(function () {
                    
                },false);
                $(".sk-circle-layer").hide();
            });
        }
    });
}


function auth_disabled(){

    
    $.confirm({
        title: '<b>Secure Device!</b>',
        content: 'Access limited to authorized devices only.<br/><br/>To activate security features upon logging into the system, <b>please reach out to our support team.</b>',
        buttons: {
            somethingElse: {
                text: 'Close',
                btnClass: 'btn-blue',
                action: function(){
                    
                }
            }
        }
    });
}
