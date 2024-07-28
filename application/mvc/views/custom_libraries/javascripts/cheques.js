function show_due_cheques(with_data){
    var content =
    '<div class="modal" data-backdrop="static" id="due_chequesModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Pending/Due Cheque(s)<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'due_chequesModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="due_cheques_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 100px !important;">Id</th>\n\
                                        <th style="width: 100px !important;">Reference</th>\n\
                                        <th style="width: 100px !important;">Owner</th>\n\
                                        <th style="width: 100px !important;">Owner type</th>\n\
                                        <th style="width: 100px !important;">Bank</th>\n\
                                        <th>Value Date</th>\n\
                                        <th style="width: 100px !important;">Cheque Value</th>\n\
                                        <th style="width: 70px !important;">Currency</th>\n\
                                        <th style="width: 50px !important;">Image</th>\n\
                                        <th style="width: 20px !important;"></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Id</th>\n\
                                        <th>Reference</th>\n\
                                        <th>Owner</th>\n\
                                        <th>Owner type</th>\n\
                                        <th>Bank</th>\n\
                                        <th>Value Date</th>\n\
                                        <th>Cheque Value</th>\n\
                                        <th>Currency</th>\n\
                                        <th></th>\n\
                                        <th></th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#due_chequesModal").remove();
    $("body").append(content);
    $('#due_chequesModal').on('show.bs.modal', function (e) {

    });

    $('#due_chequesModal').on('shown.bs.modal', function (e) {
        var chq_table = null;
        var search_fields = [0,1,2,3,4,5,6,7];
        var index = 0;
        $('#due_cheques_table tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                index++;
            }
        });
                    
        chq_table = $('#due_cheques_table').DataTable({
            ajax: {
                url: "?r=dashboard&f=get_due_cheques&p0=1",
                type: 'POST',
                error:function(xhr,status,error) {
                   
                },
            },
            orderCellsTop: true,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                { "targets": [8], "searchable": true, "orderable": false, "visible": true },
                { "targets": [9], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
               //chq_table.cell( ':eq(0)' ).focus();
                //$('#due_cheques_table tfoot input:eq(0)').focus();
                        
            },
            fnDrawCallback: updateRows_cheques,
        });
    });
    $('#due_chequesModal').on('hide.bs.modal', function (e) {
        $("#due_chequesModal").remove();
    });
    $('#due_chequesModal').modal('show');
}

function updateRows_cheques(){
    var table = $('#due_cheques_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index, 9).data("<button onclick='cheque_done(\""+table.cell(index, 0).data()+"\")' type='button' class='btn btn-xs btn-info' style='width:100%; font-size:13px;'><b>Done</b></button>");
    }
}

function cheque_done(payment_id){
    
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    },
    function(isConfirm){
       payment_type = payment_id.split('_');
       if(isConfirm){
            if(payment_type[0]=="s"){
                $(".sk-circle-layer").show();
                $.getJSON("?r=suppliers&f=payment_done&p0="+payment_id, function (data) {
                    
                }).done(function () {
                    var table = $('#due_cheques_table').DataTable();
                    table.ajax.url("?r=dashboard&f=get_due_cheques&p0=1").load(function () {
                        $(".sk-circle-layer").hide();
                    },false);
                    
                    $('.notifyjs-wrapper').trigger('notify-hide');
                    getGlobalInfo();
                    show_due_invoice_notification();
                    show_due_cheques_notification();
                    
                    
                });
            }
            if(payment_type[0]=="c"){
                $(".sk-circle-layer").show();
                $.getJSON("?r=customers&f=payment_done&p0="+payment_id, function (data) {

                }).done(function () {
                    var table = $('#due_cheques_table').DataTable();
                    table.ajax.url("?r=dashboard&f=get_due_cheques&p0=1").load(function () {
                        $(".sk-circle-layer").hide();
                    },false);
                    
                    $('.notifyjs-wrapper').trigger('notify-hide');
                    getGlobalInfo();
                    show_due_invoice_notification();
                    show_due_cheques_notification();
            
                });
            }
            
            
       }
    }); 
}