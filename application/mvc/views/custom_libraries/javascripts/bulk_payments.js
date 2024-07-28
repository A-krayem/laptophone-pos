
function bulk_payment_for_delivery(){
    var modal_name = "modal_bul_pay_delivery____";
    
    var content =
    '<div class="modal" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="bulkpayments_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input type="hidden" value="0" id="bulk_payment_step" name="bulk_payment_step" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">BULK PAYMENTS<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <lable for="del_ids" style="font-size:16px;">Delivery references separated by a comma and slash (code/amount), as illustrated in the example: 3541/2, 1554/1, 1236/8</label>\n\
                                    <textarea id="del_ids" name="del_ids" class="form-control" type="text" placeholder="" /></textarea>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    &nbsp;\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">\n\
                                <div class="form-group">\n\
                                    <button onclick="submit_f(0)" id="validate_bp" style="width:100%" type="button" class="btn btn-primary">Validate</button>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="display:none" id="submit_bp_container">\n\
                                <div class="form-group">\n\
                                    <button onclick="submit_f(1)" id="submit_bp" style="width:100%" type="button" class="btn btn-success" >Submit</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row extra_containers" style="margin-top:10px;display:none">\n\
                            <div class="col-md-3 col-sm-3">\n\
                                <div class="panel panel-success" style="margin-bottom:5px;">\n\
                                    <div class="panel-heading" style="padding:5px;">\n\
                                        <b class="announcement-heading dollar" id="del_total_invoices"></b>\n\
                                        <p class="announcement-text text-200">Total Invoices</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-md-3 col-sm-3">\n\
                                <div class="panel panel-success" style="margin-bottom:5px;">\n\
                                    <div class="panel-heading" style="padding:5px;">\n\
                                        <b class="announcement-heading dollar" id="del_total_amount"></b>\n\
                                        <p class="announcement-text text-200">Total Amount</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-md-3 col-sm-3">\n\
                                <div class="panel panel-danger" style="margin-bottom:5px;">\n\
                                    <div class="panel-heading" style="padding:5px;">\n\
                                        <b class="announcement-heading dollar" id="del_invoices_not_exists"></b>\n\
                                        <p class="announcement-text text-200">Not Exist</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-md-3 col-sm-3">\n\
                                <div class="panel panel-danger" style="margin-bottom:5px;">\n\
                                    <div class="panel-heading" style="padding:5px;">\n\
                                        <b class="announcement-heading dollar" id="del_invoices_already_paid"></b>\n\
                                        <p class="announcement-text text-200">Already Paid</p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row not_exist_section " style="display:none">\n\
                            <div class="col-md-12 col-sm-12"  >\n\
                               <div class="panel panel-danger" style="margin-bottom:5px;">\n\
                                    <div class="panel-heading" style="padding:5px;">\n\
                                        <b class="announcement-heading dollar" >Not Exist:</b>\n\
                                        <p class="announcement-text text-200 selectable-text" id="not_exist_detials"></p>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row extra_containers" style="display:none">\n\
                            <div class="col-md-12 col-sm-12">\n\
                                <table style="width:100%" id="invoices_result_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:50px;">ID</th>\n\
                                            <th style="width:70px;">Reference</th>\n\
                                            <th>Customer</th>\n\
                                            <th style="width:80px;">Amount</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).modal('hide');
    $("body").append(content);
    
    submitbulk_payment_form(modal_name);
    
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        $("#del_ids").focus();
        
        $('#invoices_result_table').DataTable({});
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function submit_f(step){
    $("#bulk_payment_step").val(step);
    $("#bulkpayments_form").submit();
}

function submitbulk_payment_form(modalname){
    
    $("#bulkpayments_form").on('submit', (function (e) {
        e.preventDefault();
        
        $("#not_exist_detials").html("");
        
        if($("#bulk_payment_step").val()==1){
            $.confirm({
                title: "Are you sure",
                content: "Submit all payments",
                buttons: {
                    Yes: {
                        btnClass: "btn-primary",
                        text: "Yes, SUBMIT",
                        action: () => {
                            $("#submit_bp").prop("disabled",true);
                            $.ajax({
                                url: "?r=deliveries&f=submitbulk_payment",
                                type: "POST",
                                data: new FormData(this),
                                contentType: false,
                                cache: false,
                                processData: false,
                                dataType: 'json',
                                success: function (data)
                                {
                                    $("#submit_bp").prop("disabled",false);
                                    $('#modal_bul_pay_delivery____').modal('hide');
                                    refresh_delivery_table();
                                }
                            });                 
                        }
                    }, 
                    cancel: {
                        btnClass: "btn-secondary",
                        text: "Cancel",
                        action: () => {
                            
                        }
                    }
                }
            });
        }else{
            var old_txt_btn = $("#validate_bp").html();
            $("#validate_bp").html("In progress");
            $.ajax({
                url: "?r=deliveries&f=submitbulk_payment",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $("#validate_bp").html(old_txt_btn);
                    $("#del_total_invoices").html(data.total_invoices);
                    $("#del_total_amount").html(data.total_amount);
                    $("#del_invoices_not_exists").html(data.invoices_not_exists);
                    $("#del_invoices_already_paid").html(data.invoices_already_paid);
                    
                    if(data.invoices_not_exists_details.toString().length>0){
                        $(".not_exist_section").show();
                        $("#not_exist_detials").html(data.invoices_not_exists_details);
                    }else{
                        $(".not_exist_section").hide();
                    }
                    
                    
                    var table = $('#invoices_result_table').DataTable();
                    // New data to be set
                    var newData = data.data;

                    // Clear existing data
                    table.clear();

                    // Add new data
                    table.rows.add(newData);

                    // Redraw the DataTable
                    table.draw();
                    
                    $("#submit_bp_container").show();
                    $("#validate_bp").html("Validate Again");
                    $(".extra_containers").show();
                    
                }
            });
        }
        
        
            
        
    }));
}