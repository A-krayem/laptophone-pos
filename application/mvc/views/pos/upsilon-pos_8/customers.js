function update_difference_inv(){
    //set_current_cash_var(2);
    var vl=parseFloat($("#payment_value").val());

    if(vl>=0){
        $("#difference_inv").html(-mask_clean($("#payment_value").val()));
    }else{
        $("#difference_inv").html(mask_clean($("#payment_value").val()));
    }
    
    cash_changed_usd($("#cash_usd"));
    
    cleaves_id("r_cash_lbp_action",0);
    cleaves_id("r_cash_usd_action",5);
}
function add_customer_payment_new(default_customer_id,default_amount,invoice_id,quotation_id){
    var content =
    '<div class="modal small" data-backdrop="static"  id="cp_modal" role="dialog" >\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Customer Payment<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'cp_modal\')"></i></h3>\n\
                </div>\n\
                <form id="add_customer_payment_form" action="" method="post" enctype="multipart/form-data" >\n\
                <div class="modal-body">\n\
                    <input id="customer_id" name="customer_id" type="hidden" value="" />\n\
                    <input  name="invoice_id" type="hidden" value="'+invoice_id+'" />\n\
                    <input  name="quotation_id" type="hidden" value="'+quotation_id+'" />\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="" />\n\
                    <input id="payment_method" name="payment_method" type="hidden" value="1" />\n\
                    <div class="row " style="margin-top:10px;">\n\
                        <span id="difference_inv" style="display:none"></span>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-left:18px;">\n\
                            <div class="form-group">\n\
                                <label for="payment_value">Payment Value</label>\n\
                                <div class="inner-addon"><input oninput="update_difference_inv()" autocomplete="off" id="payment_value" name="payment_value" value="0" type="text" class="form-control med_input">\n\</div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pr2">\n\
                            <div class="form-group">\n\
                                <label for="payment_date">Payment Date</label>\n\
                                <div class="inner-addon"><input autocomplete="off" id="creation_date" name="creation_date" type="text" class="form-control datepicker med_input"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left:18px;">\n\
                            <div class="form-group">\n\
                                <label for="payment_note">Note</label>\n\
                                <div class="inner-addon"><input autocomplete="off" id="payment_note" name="payment_note" type="text" class="form-control"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row " style="margin-top:10px;">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cinfo_lg" id="cash_d_container">\n\
                            <div id="cash_info_container" style="padding-left:2px;">\n\
                                <div class="panel panel-default">\n\
                                    <div class="panel-heading" style="padding-top:5px;padding-bottom:5px;"><b>Cash Info</b></div>\n\
                                    <div class="panel-body" style="padding:10px;">\n\
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <label for="cash_usd">IN USD </label><span id="to_return_c_usd" style="float:right">&nbsp;&nbsp;&nbsp;</span>\n\
                                                <input onkeyup="cash_changed_usd(this)" autocomplete="off" id="cash_usd" name="cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <label for="cash_usd">OUT USD</label>\n\
                                                <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_usd" name="r_cash_usd" type="text" class="form-control med_input" placeholder="">\n\
                                                <input onkeyup="r_cash_usd_action_changed(this)" autocomplete="off" id="r_cash_usd_action" name="r_cash_usd_action" type="text" class="form-control med_input" placeholder="">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <label for="cash_usd">IN LBP </label><span id="to_return_c_lbp" style="float:right">&nbsp;&nbsp;&nbsp;</span>\n\
                                                <input onkeyup="cash_changed_lbp(this)"  autocomplete="off" id="cash_lbp" name="cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                            </div>\n\
                                        </div>\n\
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:2px;padding-right:2px;">\n\
                                            <div class="form-group" style="margin-bottom:3px;">\n\
                                                <label for="cash_usd">OUT LBP</label>\n\
                                                <input style="display:none" readonly onkeyup="" autocomplete="off" id="r_cash_lbp" name="r_cash_lbp" type="text" class="form-control med_input" placeholder="">\n\
                                                <input onkeyup="r_cash_lbp_action_changed(this)" autocomplete="off" id="r_cash_lbp_action" name="r_cash_lbp_action" type="text" class="form-control med_input" placeholder="">\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <div class="row" style="margin-top:20px;">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <button type="submit" class="btn btn-primary" style="width:100%">SUBMIT</button>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#cp_modal').modal('hide');
    $("body").append(content);
    $('#cp_modal').on('show.bs.modal', function (e) {

    });

    $('#cp_modal').on('shown.bs.modal', function (e) {
        
        if(default_customer_id==0){
            $("#customer_id").val($("#customer_id_payment").val());
        }else{
            $("#customer_id").val(default_customer_id);
        }
        
        $("#id_to_edit").val(0);
        
        
        $('#creation_date').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
        $('#creation_date').datepicker( "setDate", new Date() );
        $('#creation_date').datepicker().on('changeDate', function(ev) {

        }).on('hide show', function(event) {
            event.preventDefault();
            event.stopPropagation();
        });
        
        
        
        set_current_cash_var(2);
        cleaves_id("cash_lbp",0);
        cleaves_id("cash_usd",5);
        cleaves_id("payment_value",5);
        
        if(default_amount>0){
            $("#payment_value").val(default_amount);
            update_difference_inv();
        }
        
        submit_customer_payment_new();
        
        $(".sk-circle-layer").hide();   
    });

    $('#cp_modal').on('hide.bs.modal', function (e) {
        $("#cp_modal").remove();
    });
    $('#cp_modal').modal('show');
}

function submit_customer_payment_new(){
    $("#add_customer_payment_form").on('submit', (function (e) {
        e.preventDefault();
        $("#payment_value").val($("#payment_value").val().replace(/,/g , ''));
        $(".sk-circle-layer").show();
        $.ajax({
            url: "?r=customers&f=add_customer_payment_new",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $('#cp_modal').modal('hide');
                $(".sk-circle-layer").hide();
                
                if($("#payments_of_customer").length>0){
                        customer_changed_pos();
                    }
            }
        });
    }));
}



