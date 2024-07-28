var CUSTOMERS_PHONE_FORMAT = "";
var CUSTOMERS_IDENTITY_FORMAT = "";

function delete_picture(id,customer_id){           
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
            $.getJSON("?r=customers&f=delete_cheque_picture&p0="+id, function (data) {
                var upload_picture_section = '<div class="form-group">\n\
                    <label for="supplier_id_for_cheque">Picture</label>\n\
                    <input type="hidden" value="'+customer_id+'" class="form-control"  id="customer_id_for_cheque" name="customer_id_for_cheque">\n\
                    <input accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="cheque_picture" name="cheque_picture">\n\
                </div>';
                $("#pic_upload").empty();
                $("#pic_upload").append(upload_picture_section);
            }).done(function () {
                $(".sk-circle-layer").hide();
            }); 
        }
    });

}

function check_identity_duplicate(){
    
    if($("#id_to_edit").val()==0){
        $("#action_btn").hide();
        $.getJSON("?r=customers&f=check_identity&p0="+$("#id_nb").val().replace(/ /g,''), function (data) {
            if(data.nb>0){
                $("#block_id").val(1);
                //alert("Identity Exist");
                $("#id_nb").addClass("error");
            }else{
                $("#block_id").val(0);
                $("#id_nb").removeClass("error");
                $("#action_btn").show();
            }
        }).done(function () {

        });
    }
}

function addCustomerPaymentDetails(customer_id,id_int,info,page){
    var payment_options = "";
    var banks_options = "";
    var all_currencies_data = [];
    var currencies_options = "";
    var number_of_decimal_points = 0;
    
    var currenciesl=0;
    
    var currency_default = 0;
    

    var upload_picture_section = '<div class="form-group">\n\
        <label for="supplier_id_for_cheque">Picture</label>\n\
        <input type="hidden" value="'+customer_id+'" class="form-control"  id="customer_id_for_cheque" name="customer_id_for_cheque">\n\
        <input accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="cheque_picture" name="cheque_picture">\n\
    </div>';
    if(info.length>0 && info[0].picture!=null){
        upload_picture_section = "<div class='row'>\n\
            <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>\n\
                <div class='form-group'>\n\
                    <label for='delivery_date'>Picture <a href='"+info[0].picture+"' data-toggle='lightbox' id='open-image' data-title='&nbsp;' data-footer='&nbsp;'>Show</a></label>\n\
                    <button id='delete_pi_pic' onclick='delete_picture("+id_int+","+customer_id+")' type='button' class='btn btn-danger' style='width:100%'>Delete</button>\n\
                </div>\n\
            </div>\n\
        </div>";
    }

    $.getJSON("?r=settings_info&f=get_info", function (data) {
        $.each(data.payments_method, function (key, val) {
            payment_options+="<option value='"+val.id+"' title='"+val.method_name+"'>"+val.method_name+"</option>";
        });
        $.each(data.banks, function (key, val) {
            banks_options+="<option value='"+val.id+"' title='"+val.name+"'>"+val.name+"</option>";
        });
        $.each(data.currencies, function (key, val) {
            currencies_options+="<option value='"+val.id+"' title='"+val.name+" ("+val.symbole+")'>"+val.name+" ("+val.symbole+")</option>";
        });
        $.each(data.currencies, function (key, val) {
            if(val.system_default==1){
                currency_default=val.id;
            }
            all_currencies_data.push({id:val.id,name:val.name,symbole:val.symbole,system_default:val.system_default,rate_to_system_default:val.rate_to_system_default});
        });
        currenciesl=data.currencies.length;
        number_of_decimal_points = data.settings[0].number_of_decimal_points;
    }).done(function () {
        var ddata = [];
        
        
        $.getJSON("?r=customers&f=getCustomerInfoById&p0="+customer_id, function (data) {
            ddata = data;
        }).done(function () {
            var full_name = ddata[0].name;
            if(ddata[0].middle_name!==null){
                full_name += ddata[0].middle_name;
            }
            if(ddata[0].last_name!==null){
                full_name += ddata[0].last_name;
            }
            
            var content =
                '<div class="modal medium-plus" data-backdrop="static" id="addPaymentCustomerModal" tabindex="-1" role="dialog">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <form id="add_customer_payment_form" action="" method="post" enctype="multipart/form-data" >\n\
                            <input id="customer_id" name="customer_id" type="hidden" value="'+customer_id+'" />\n\
                            <input id="id_to_edit" name="id_to_edit" type="hidden" value="'+id_int+'" />\n\
                            <div class="modal-header"> \n\
                                <h3 class="modal-title">Add Customer payment<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="closeModal(\'addPaymentCustomerModal\')"></i></h3>\n\
                            </div>\n\
                            <div class="modal-body">\n\
                                <div class="row">\n\
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:40px;">\n\
                                        <span class="label label-info" style="font-size:20px;">'+full_name+'</span>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_date">Invoice Date</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="creation_date" name="creation_date" type="text" class="form-control datepicker med_input"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pr2">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_method">Payment Method</label>\n\
                                            <select onchange="payment_method_supplier_changed()" id="payment_method" name="payment_method" class="selectpicker form-control med_input" >'+payment_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plpr2">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_value">Payment Value</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_value" name="payment_value" value="0" type="text" class="form-control med_input"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plpr2">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_date">Value Date</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_date" name="payment_date" type="text" class="form-control datepicker med_input"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 plpr2">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_currency">Currency</label>\n\
                                            <select data-live-search="true" id="payment_currency" name="payment_currency" class="selectpicker form-control med_input" >'+currencies_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plpr2" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Rate</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="" name="" type="text" class="form-control med_input" placeholder="Rate" ></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pl2" id="currency_rate_container">\n\
                                        <div class="form-group">\n\
                                            <label for="inv_rate" style="width:100%">Rate</label>\n\
                                            <div class="input-group">\n\
                                                <span class="input-group-addon" style="width:40px;"><b>1 USD </b>= </span>\n\
                                                    <input type="text" class="form-control cleavesf3" name="currency_rate" id="currency_rate" value="1500" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />\n\
                                                <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>\n\
                                            </div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pr2 bank_input credit_card_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="bank_source">Bank</label>&nbsp;&nbsp;<span onclick="addBank(\'supplier_payment\')" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add new bank</span>\n\
                                            <select data-live-search="true" id="bank_source" name="bank_source" class="selectpicker form-control" >'+banks_options+'</select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plpr2 bank_input credit_card_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Reference Number</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="reference" name="reference" type="text" class="form-control med_input" placeholder="Reference"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plpr2 bank_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Owner</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_owner" name="payment_owner" type="text" class="form-control med_input" placeholder="Owner"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 plpr2 credit_card_input" style="display:none">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Voucher Number</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="voucher_nb" name="voucher_nb" type="text" class="form-control med_input" placeholder="Voucher"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 plpr2 bank_input credit_card_input" style="display:none" id="pic_upload">\n\
                                        '+upload_picture_section+'\n\
                                    </div>\n\
                                </div>\n\
                                <div class="row">\n\
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                        <div class="form-group">\n\
                                            <label for="payment_note">Note</label>\n\
                                            <div class="inner-addon"><input autocomplete="off" id="payment_note" name="payment_note" type="text" class="form-control" placeholder="Note"></div>\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="modal-footer">\n\
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">\n\
                                    <div class="form-group">\n\
                                    &nbsp;\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%;color: #000;">Cancel</button>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <button id="sbm_btn" id="action_btn" type="submit" class="btn btn-primary" style="width:100%; color: #fff;">Add</button>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </form>\n\
                    </div>\n\
                </div>\n\
            </div>';
            $("#addPaymentCustomerModal").remove();
            $("body").append(content);
             
            //if(id_int==0){
                 submit_customer_payment(page,customer_id);
            //}
            
            $('#addPaymentCustomerModal').on('hide.bs.modal', function (e) {
                $("#addPaymentCustomerModal").remove();
            });

            $('#addPaymentCustomerModal').on('show.bs.modal', function (e) {   

            });

            $('#addPaymentCustomerModal').on('shown.bs.modal', function (e) {

                $('#open-image').click(function (e) {
                    e.preventDefault();
                    $(this).ekkoLightbox();
                });


                $('.selectpicker').selectpicker();
                
                $('#payment_currency').selectpicker("val",currency_default);
                

                $('#addPaymentCustomerModal .datepicker').datepicker({autoclose:true,format: 'yyyy-mm-dd'});
                $('#addPaymentCustomerModal .datepicker').datepicker( "setDate", new Date() );
                $('#addPaymentCustomerModal .datepicker').datepicker().on('changeDate', function(ev) {

                }).on('hide show', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                });

                //format_input_number(0,"#payment_value",number_of_decimal_points,0);
                
                //format_input_number(0,"#currency_rate",2,0);

                //update_rate();
                
                if(info.length>0){
                    
                    $("#payment_method").selectpicker('val', info[0].payment_method);
                    $("#payment_method").prop("disabled","disabled");
                    payment_method_supplier_changed();

                    //format_input_number(info[0].balance,"#payment_value",2,0);
                    $("#payment_value").val(parseFloat(info[0].balance));
                    $("#payment_value").prop("disabled","disabled");

                    $('#payment_date').datepicker( "setDate", info[0].value_date.split(" ")[0]);
                    $("#payment_date").prop("disabled","disabled");

                    $("#payment_currency").selectpicker('val', info[0].currency_id);
                    $("#payment_currency").prop("disabled","disabled");
                    
                   
                    $("#currency_rate").val(parseFloat(info[0].usd_to_lbp));
                    if(parseFloat(info[0].usd_to_lbp)==0){
                        $("#currency_rate_container").hide();
                    }
                    $("#currency_rate").prop("disabled","disabled");
                        
                    //update_rate();

                    $("#currency_rate").prop("disabled","disabled");

                    $("#payment_note").val(info[0].note);
                    $("#payment_note").prop("disabled","disabled");

                    $("#bank_source").selectpicker('val', info[0].bank_id);
                    $("#bank_source").prop("disabled","disabled");

                    $("#reference").val(info[0].reference_nb);
                    $("#reference").prop("disabled","disabled");

                    $("#payment_owner").val(info[0].owner);
                    $("#payment_owner").prop("disabled","disabled");

                    $("#voucher_nb").val(info[0].voucher);
                    $("#voucher_nb").prop("disabled","disabled");

                    $("#sbm_btn").html('Update pictures');
                }
                
                if(currenciesl==1){
                        $("#currency_rate_container").hide();
                    }
                    
                cleaves_id("payment_value",5);
                cleaves_id("currency_rate",5);
                $(".sk-circle-layer").hide();
            });

            $('#addPaymentCustomerModal').modal('show');
        });
                
        
    });
}

function submit_customer_payment(page,customer_id){
    $("#add_customer_payment_form").on('submit', (function (e) {
        e.preventDefault();
        $("#payment_value").val($("#payment_value").val().replace(/,/g , ''));
        $("#currency_rate").val($("#currency_rate").val().replace(/,/g , ''));
        $(".sk-circle-layer").show();
        $.ajax({
            url: "?r=customers&f=add_customer_payment",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                if(page=="customer_statement"){   
                    var table = $('#customers_statement_table').DataTable();
                    table.ajax.url("?r=customers&f=get_customer_statement&p0="+$("#customers_list").val()).load(function () {
                        table.page('last').draw(false);
                        table.row(':last', {page: 'current'}).select();
                        $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                        $('#addPaymentCustomerModal').modal('hide');
                        $(".sk-circle-layer").hide();
                    }, false);  
                }
                if(page=="customers"){
                    var table = $('#customers_table').DataTable();
                    table.ajax.url("?r=customers&f=getAllCustomers&p0="+$("#all_remain").val()).load(function () {
                        table.row('.' + pad_customer(customer_id), {page: 'current'}).select();
                        $('#addPaymentCustomerModal').modal('hide');
                        $(".sk-circle-layer").hide();
                    }, false);  
                }
                if(page=="pos"){
                    $('#addPaymentCustomerModal').modal('hide');
                    $(".sk-circle-layer").hide();
                    if($("#payments_of_customer").length>0){
                        customer_changed_pos();
                    }
                }
                if(page=="invoicesadmin"){
                    $('#addPaymentCustomerModal').modal('hide');
                    $(".sk-circle-layer").hide();
                    refresh_invoices_table();
                }
                
                if(page=="invoicesmustpay"){
                    $('#addPaymentCustomerModal').modal('hide');
                    $(".sk-circle-layer").hide();
                    var table = $('#cutomer_invoice_table').DataTable();
                    table.ajax.url("?r=invoice&f=getInvoicesMustPay&p0="+current_store_id).load(function () {
                        $(".sk-circle-layer").hide();
                    }, false);
                }
                
            }
        });
    }));
}

function delete_customer_id(id){
    swal({
        title: "Delete customer!!!",
        text: "Are you sure that you want to delete this customer?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete now",
        cancelButtonText: "Cancel",
        closeOnConfirm: true
      },
    function(isconfirm){
        if(isconfirm){
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            $.getJSON("?r=customers&f=delete_customer_&p0=" + id, function (data) {
                
            }).done(function () {
                var table = $('#customers_search').DataTable();
                table.ajax.url("?r=pos&f=get_all_customers&p0=1").load(function () {
                    
                },false);
                $(".sk-circle-layer").hide();
            });
        }else{

        }
    });
    
}

function updateRows_edit_customers(){
    var table = $('#customers_search').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        
        if(CUSTOMERS_PHONE_FORMAT!="-1"){
            //$("#ph_"+parseInt(table.cell(index, 0).data().split('-')[1])).mask(CUSTOMERS_PHONE_FORMAT);
        }else{
            $("#ph_"+parseInt(table.cell(index, 0).data().split('-')[1]));
        }
        
        
        //$("#idnbb_"+parseInt(table.cell(index, 0).data().split('-')[1])).mask(CUSTOMERS_IDENTITY_FORMAT);
        
        var more = "";
        if(typeof enable_advanced_customer_info != undefined && enable_advanced_customer_info==1){
            more = '<i class="glyphicon glyphicon-print shortcut" title="Print Identities" onclick="print_identities_customer_id(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\')";></i>&nbsp;<i class="glyphicon icon-pdf shortcut" title="PDF Identities 1" onclick="print_identities_1_customer_id(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\')";></i>&nbsp;<i class="glyphicon icon-pdf shortcut" title="PDF Identities 2" onclick="print_identities_2_customer_id(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\')";></i>&nbsp;';
        }
        
        var delete_cus = "";
        if(typeof enable_delete_customer_on_pos != undefined && enable_delete_customer_on_pos==1){
            delete_cus = '<i class="glyphicon glyphicon-trash shortcut" title="Delete" onclick="delete_customer_id(\''+parseInt(table.cell(index, 0).data().split('-')[1])+'\')";></i>';
        }
        
        
        table.cell(index, 7).data('<i class="glyphicon glyphicon-edit shortcut" title="Edit" onclick="editCustomer(\''+table.cell(index, 0).data()+'\')";></i>&nbsp;'+more+''+delete_cus);
    }
}
            
function _show_all_customers(type){
    if (!navigator.onLine) {
        //swal("Check your internet connection");
        //return;
    }
    var url = "?r=pos&f=get_all_customers&p0=0";
    var title = "All Wholesale Clients";
    if(type==1){
        url = "?r=pos&f=get_all_customers&p0=1";
        title = "All Clients";
    }
    var adv_info = "12";
    var adv_info_section = "0";
    var adv_info_section_3 = "0";
    var adv_display = "none;";
    var col_disp = false;
    var col_hide = true;
    var idt_width = 200;
    if(typeof enable_advanced_customer_info != undefined && enable_advanced_customer_info==1){
        adv_info = "5";
        adv_info_section = "2";
        adv_info_section_3 = "5";
        var adv_display = "block;";
        col_disp = true;
        col_hide = false;
    }
    
    
    if(typeof advanced_customer_info_img_width != undefined){
        idt_width = advanced_customer_info_img_width;
    }
    
    
    lockMainPos = true;
    var content =
    '<div class="modal" data-backdrop="static" id="customersModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <input id="type_clients" name="type_clients" type="hidden" value="'+type+'" />\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>'+title+'<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'customersModal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-'+adv_info+' col-md-'+adv_info+' col-sm-'+adv_info+' col-xs-12" style="padding-right:5px;">\n\
                            <table id="customers_search" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 80px !important;">Ref.</th>\n\
                                        <th>Client Name</th>\n\
                                        <th style="width: 100px !important;">Balance</th>\n\
                                        <th style="width: 100px !important;">Address</th>\n\
                                        <th style="width: 60px !important;">phone</th>\n\
                                        <th style="width: 95px !important;">Identity nb</th>\n\
                                        <th style="width: 60px !important;">Type</th>\n\
                                        <th style="width: 60px !important;"></th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Ref.</th>\n\
                                        <th>Client Name</th>\n\
                                        <th>Balance</th>\n\
                                        <th>Address</th>\n\
                                        <th>phone</th>\n\
                                        <th>Identity Number</th>\n\
                                        <th>Type</th>\n\
                                        <th></th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                            <div id="user_customers_log_details"></div>\n\
                        </div>\n\
                        <div class="col-lg-'+adv_info_section+' col-md-'+adv_info_section+' col-sm-'+adv_info_section+' col-xs-12" style="padding-top:30px;padding-left:2px;padding-right:2px;display:'+adv_display+'">\n\
                            <span class="customer_info_title">ID Type: </span><span class="customer_info_value" id="i_id_type"></span><br/>\n\
                            <span class="customer_info_title">First Name: </span><span class="customer_info_value" id="i_f_name"></span><br/>\n\
                            <span class="customer_info_title">Middle Name: </span><span class="customer_info_value" id="i_m_name"></span><br/>\n\
                            <span class="customer_info_title">Last Name: </span><span class="customer_info_value" id="i_l_name"></span><br/>\n\
                            <span class="customer_info_title">Address: </span><span class="customer_info_value" id="i_addr_inf"></span><br/>\n\
                            <span class="customer_info_title">D.O.B.: </span><span class="customer_info_value" id="i_dob"></span><br/>\n\
                            <span class="customer_info_title">Phone: </span><span class="customer_info_value" id="i_ph"></span><br/>\n\
                            <span class="customer_info_title">ID Number: </span><span class="customer_info_value" id="i_id_nb"></span><br/>\n\
                            <span class="customer_info_title">C.O.B: </span><span class="customer_info_value" id="i_cob"></span><br/>\n\
                            <span class="customer_info_title">C.O.I.: </span><span class="customer_info_value" id="i_coi"></span><br/>\n\
                            <span class="customer_info_title">Expiry: </span><span class="customer_info_value" id="i_exp"></span><br/>\n\
                        </div>\n\
                        <div class="col-lg-'+adv_info_section_3+' col-md-'+adv_info_section_3+' col-sm-'+adv_info_section_3+' col-xs-12" style="padding-top:30px;display:'+adv_display+';text-align:center">\n\
                            <span id="expired_text" class="expired_text" style="display:none">EXPIRED</span>\n\
                            <img width="'+idt_width+'px" id="imgsrc_1" src="" />\n\
                            <img width="'+idt_width+'px" id="imgsrc_2" src="" style="margin-top:10px;" />\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#customersModal").remove();
    $("body").append(content);
    $('#customersModal').on('show.bs.modal', function (e) {
        var items_search = null;
        var search_fields = [0,1,2,3,4,5,6];
        var index = 0;
        $('#customers_search tfoot th').each( function () {
            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                var phone_format = "";
                var identity_format = "";
                if(index==3){
                    phone_format = "phone_format";
                }
                if(index==4){
                    identity_format = "identity_format";
                }
                $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm '+phone_format+' '+identity_format+'" type="text" placeholder=" '+title+'" /></div>' );
                index++;
                
                if(CUSTOMERS_PHONE_FORMAT!="-1"){
                    //$(".phone_format").mask(CUSTOMERS_PHONE_FORMAT);
                }
                
                //$(".identity_format").mask(CUSTOMERS_IDENTITY_FORMAT);
            }
        });
               
        items_search = $('#customers_search').DataTable({
            ajax: {
                url: url,
                deferRender: true,
                type: 'POST',
                dataSrc: function (json) {
                    return json.data;
                },
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            orderCellsTop: true,
            iDisplayLength: 50,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": false },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": false },
                { "targets": [6], "searchable": false, "orderable": false, "visible": true },
                { "targets": [7], "searchable": false, "orderable": false, "visible": true, "className": "dt-center" },
            ],
            scrollCollapse: true,
            scrollY: '44vh',
            paging: true,
            dom: '<"toolbar_clients">frtip',
            initComplete: function(settings, json) {
                items_search.cell( ':eq(0)' ).focus();
                $('#items_search tfoot input:eq(1)').focus();
                
                var add_classic_customer="";
                if(typeof enable_advanced_customer_info != undefined && enable_advanced_customer_info==1){
                    add_classic_customer='\n\
                    <div class="col-lg-2 col-md-2 col-xs-12">\n\
                        <div id="tab_toolbar" class="btn-group tab_toolbar" role="group" aria-label="">\n\
                            <button onclick="addCustomer(\'add\',[],1)" type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus">Add New Identity</i></button>\n\
                        </div>\n\
                    </div>\n\
                        ';
                }else{
                    
                     add_classic_customer='\n\
                    <div class="col-lg-2 col-md-2 col-xs-12">\n\
                            <div id="tab_toolbar" class="btn-group tab_toolbar" role="group" aria-label="">\n\
                                <button onclick="addCustomer(\'add\',[],0)" type="button" class="btn btn-primary "><i class="glyphicon glyphicon-plus"></i>Add Client</button>\n\
                            </div>\n\
                        </div>\n\
                        ';
                }
                
                $("div.toolbar_clients").html('<div class="row">\n\
                        '+add_classic_customer+'\n\
                    </div>\n\
                    ');
                
                if(typeof enable_advanced_customer_info != undefined && enable_advanced_customer_info==1){
                    $('#customers_search').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                        $('.selected').removeClass("selected");
                        $(this).addClass('selected');
                        var dt = $('#customers_search').DataTable();
                        var id = dt.row(this).data()[0];
                        update_cus_info(parseInt(id.split('-')[1]));
                        
                        
                    });
                }
                
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: updateRows_edit_customers,
        });

        $('#customers_search').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
            //if($("#type_clients").val()==0){
                var sdata = items_search.row('.selected', 0).data();
            
                inv.setCustomerId(parseInt(sdata[0].split("-")[1]));
                $('#customersModal').modal('hide');
            //}
        });


        
        $('#customers_search').DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });

        
        $('#customers_search').on( 'page.dt', function () {
            $("#customers_search .selected").removeClass("selected");
        } );

        $('#customers_search').on('key.dt', function(e, datatable, key, cell, originalEvent){
            if(key === 13){
                var sdata = items_search.row('.selected', 0).data();
                add_to_invoive(parseInt(sdata[0].split("-")[1]));
            }
        });
        
        $('#customers_search').DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                items_search.keys.disable();
                if ( that.search() !== this.value ) {
                    that.search( this.value ).draw();
                }
                items_search.keys.enable();
            } );
        } );
    });
    
    $('#customersModal').on('shown.bs.modal', function (e) {

    });
    $('#customersModal').on('hide.bs.modal', function (e) {
        lockMainPos = false;
        $("#customersModal").remove();
    });
    $('#customersModal').modal('show');
}

function getAreas(_data){
    $("#area").empty();
    var country_val = $("#country").val();
    if(_data.length>0){
        country_val = _data[0].country_id;
    }
    
    $.getJSON("?r=countries&f=get_area_by_country_id&p0="+country_val, function (data) {
        $.each(data, function (key, val) {
            if(_data.length>0){
                if(val.id==_data[0].area_id){
                    $("#area").append("<option selected value=" + val.id + ">" + val.name + "</option>");
                }else{
                    $("#area").append("<option value=" + val.id + ">" + val.name + "</option>");
                }
            }else{
                $("#area").append("<option value=" + val.id + ">" + val.name + "</option>");
            }
            
            
        });
        $("#area").selectpicker("refresh");
        getDistricts(_data);
    });
}

function getDistricts(_data){
    $("#district").empty();
    var area_val = $("#area").val();
    if(_data.length>0){
        area_val = _data[0].area_id;
    }
  
    $.getJSON("?r=countries&f=get_districts_by_area_id&p0="+area_val, function (data) {
        $.each(data, function (key, val) {
            if(_data.length>0){
                if(val.id==_data[0].district_id){
                    $("#district").append("<option selected value=" + val.id + ">" + val.name + "</option>");
                }else{
                    $("#district").append("<option value=" + val.id + ">" + val.name + "</option>");
                }
                
            }else{
                $("#district").append("<option value=" + val.id + ">" + val.name + "</option>");
            }
            
        });
        $("#district").selectpicker("refresh");
        getCities(_data);
    });
}

function getCities(data_){
    $("#city").empty();
    
    var district_val = $("#district").val();
    if(data_.length>0){
        district_val = data_[0].district_id;
    }
    
    $.getJSON("?r=countries&f=get_cities_by_district_id&p0="+district_val, function (data) {
        $.each(data, function (key, val) {
            if(data_.length>0){
                if(val.id==data_[0].city_id){
                    $("#city").append("<option selected value=" + val.id + ">" + val.name + "</option>");
                }else{
                    $("#city").append("<option value=" + val.id + ">" + val.name + "</option>");
                }
            }else{
                $("#city").append("<option value=" + val.id + ">" + val.name + "</option>");
            }
            
        });
        $("#city").selectpicker("refresh");
        
    });
}


function readURL(input,img_id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#'+img_id)
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function add_new_city(){
    var content =
        '<div style="z-index:99999999999" class="modal" data-backdrop="static" data-keyboard="false" id="add_new_city_modal" role="dialog">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_city_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new city</h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                        <label for="cat_desc">City in <span id="city_for_country"></span></label>\n\
                        <input id="city_name" name="city_name" type="text" class="form-control" placeholder="Area Name">\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_city_modal').remove();
    $('body').append(content);

    submitNewCity();

    $('#add_new_city_modal').on('show.bs.modal', function (e) {
        $("#city_for_country").html($("#district option:selected").text());
    });

    $('#add_new_city_modal').on('shown.bs.modal', function (e) {
        setTimeout(function(){
            $("#city_name").focus();
        },300);
    });

    $('#add_new_city_modal').on('hide.bs.modal', function (e) {
        $('#add_new_city_modal').remove();
    });

    $('#add_new_city_modal').modal('show');
 
}

function submitNewCity(){
    $("#add_new_city_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("city_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=countries&f=add_new_city&p0="+$("#district").val(),
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#add_new_city_modal').modal('hide');
                    $("#city").append("<option value='"+data.id+"'>"+data.name+"</option>");
                    $("#city").selectpicker('refresh');
                    $("#city").selectpicker('val', data.id);
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}


function add_new_district(){
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_district_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_district_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new district</h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                        <label for="district_name">District in <span id="district_for_country"></span></label>\n\
                        <input id="district_name" name="district_name" type="text" class="form-control" placeholder="Area Name">\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_district_modal').remove();
    $('body').append(content);

    submitNewDistrict();

    $('#add_new_district_modal').on('show.bs.modal', function (e) {
        $("#district_for_country").html($("#area option:selected").text());
    });

    $('#add_new_district_modal').on('shown.bs.modal', function (e) {
        setTimeout(function(){
            $("#district_name").focus();
        },300);
    });

    $('#add_new_district_modal').on('hide.bs.modal', function (e) {
        $('#add_new_district_modal').remove();
    });

    $('#add_new_district_modal').modal('show');
 
}

function submitNewDistrict(){
    $("#add_new_district_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("district_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=countries&f=add_new_district&p0="+$("#area").val(),
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#add_new_district_modal').modal('hide');
                    $("#district").append("<option value='"+data.id+"'>"+data.name+"</option>");
                    $("#district").selectpicker('refresh');
                    $("#district").selectpicker('val', data.id);
                    getCities([]);
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}

function add_new_area(){
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_area_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_area_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new area</h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="form-group">\n\
                        <label for="cat_desc">Area in <span id="area_for_country"></span></label>\n\
                        <input id="area_name" name="area_name" type="text" class="form-control" placeholder="Area Name">\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_area_modal').remove();
    $('body').append(content);

    submitNewArea();

    $('#add_new_area_modal').on('show.bs.modal', function (e) {
        $("#area_for_country").html($("#country option:selected").text());
    });

    $('#add_new_area_modal').on('shown.bs.modal', function (e) {
        setTimeout(function(){
            $("#area_name").focus();
        },300);
    });

    $('#add_new_area_modal').on('hide.bs.modal', function (e) {
        $('#add_new_area_modal').remove();
    });

    $('#add_new_area_modal').modal('show');
 
}

function submitNewArea(){
    $("#add_new_area_form").on('submit', (function (e) {
        e.preventDefault();
        if (!emptyInput("area_name")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=countries&f=add_new_area&p0="+$("#country").val(),
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#add_new_area_modal').modal('hide');
                    $("#area").append("<option value='"+data.id+"'>"+data.name+"</option>");
                    $("#area").selectpicker('refresh');
                    $("#area").selectpicker('val', data.id);
                    getDistricts([]);
                    $(".sk-circle-layer").hide();
                }
            });
        }
    }));
}



function identity_changed(){
    for(var i=0;i<global_identities_types_info.length;i++){
        if($("#id_type").val()!=null && $("#id_type").val()!="" && global_identities_types_info[i].id == $("#id_type").val()){
            if(global_identities_types_info[i].expired_required==0){
                $("#id_expiry").val("");
                $("#id_expiry_section").hide();
            }else{
                $("#id_expiry_section").show();
            }
        }
    }
}


$.fn.focusNextInputField = function() {
    return this.each(function() {
        var fields = $(this).parents('form:eq(0),body').find('button,input,textarea,select');
        var index = fields.index( this );
        if ( index > -1 && ( index + 1 ) < fields.length ) {
            fields.eq( index + 1 ).focus().select();
        }
        return false;
    });
};

function convert_to_identity(){
    $(".sk-circle").center();
        $(".sk-circle-layer").show();
    var id_int = $("#id_to_edit").val();
    $('#add_new_customer').modal('toggle');
    setTimeout(function(){
        
               
        var data_ = [];

        $.getJSON("?r=customers&f=getCustomersById&p0=" + id_int, function (data) {
            
            data_.push({id_int:id_int,name:data[0].name,middle_name:data[0].middle_name,last_name:data[0].last_name,address:data[0].address,phone:data[0].phone,customer_type:data[0].customer_type,starting_balance:data[0].starting_balance,mof:data[0].mof,discount:data[0].discount,country_id:data[0].country_id,area_id:data[0].area_id,district_id:data[0].district_id,city_id:data[0].city_id,dob:data[0].dob,id_type:data[0].id_type,id_expiry:data[0].id_expiry,id_nb:data[0].id_nb,cob:data[0].cob,identity_pic_1:data[0].identity_pic_1,identity_pic_2:data[0].identity_pic_2,coi:data[0].coi,note:data[0].note,email:data[0].email,company:data[0].company,created_by:data[0].created_by});
        }).done(function () {
            addCustomer('up',data_,0);
        });
    },500);
    
}

var global_identities_types_info = [];
var tmp_0bject = null;
function addCustomer(action,data,override_identities) {
    
    var customer_options = "";
     var vendors_options = "";
    var suppliers_options = "";
    var customer_countries = "";
    var identities_types_options = "";
    var country_default_id = 0;
    var enable_advanced_customer_info = 0;
    var advanced_customer_info_img_width = 0;
    var mandatory_field = "";
    var mandatory_field_sign = "";
    var customer_areas = "";
    var display = "display:none;";
    var divide_start = "";
    var divide_end = "";
 
    var convert_to_advanced = false;
    
    var dflt_loc = [];
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=customers&f=get_customers_types", function (data) {
        if(data.enable_advanced_customer_info == "1" ){
             convert_to_advanced = true;
        }
        if(override_identities==1){
            data.enable_advanced_customer_info = 0;
        }
        
        $.each(data.customers_types, function (key, val) {
            customer_options += "<option value=" + val.id + ">" + val.name + "</option>";
        });
        
        $.each(data.vendors, function (key, val) {
            vendors_options += "<option value=" + val.id + ">" + val.username + "</option>";
        });
        
        suppliers_options += "<option value='0'>None</option>";
        $.each(data.suppliers, function (key, val) {
            suppliers_options += "<option value=" + val.id + ">" + val.name + "</option>";
        });
        
        customer_countries += "<option value='0'>Select Country</option>";
        $.each(data.countries, function (key, val) {
            var selected_c = "";
            if(val.default_selection==1){
                country_default_id = val.id;
                selected_c = "selected";
            }
            customer_countries += "<option "+selected_c+" value=" + val.id + ">" + val.country_name + "</option>";
        });
        
        dflt_loc = data.default_city_location;
        
        $.each(data.areas, function (key, val) {
            if(val.country_id==country_default_id){
                customer_areas += "<option value=" + val.id + ">" + val.name + "</option>";
            }
        });
        
        global_identities_types_info = [];
        $.each(data.id_types, function (key, val) {
            global_identities_types_info.push({id:val.id,name:val.name,expired_required:val.expired_required});
            identities_types_options += "<option value='"+val.id+"'>"+val.name+"</option>";
        });
        
        enable_advanced_customer_info = data.enable_advanced_customer_info;

        
        
        advanced_customer_info_img_width = data.advanced_customer_info_img_width;
        CUSTOMERS_PHONE_FORMAT = data.phone_number_format;
        CUSTOMERS_IDENTITY_FORMAT = data.identity_number_format;
        
        if(data.enable_advanced_customer_info == "1" ){
            
           
            
            mandatory_field = "required";
            mandatory_field_sign = "<span class='mandatory_field_sign'>*</span>";
            
            display = "display:block;";
            divide_start = '<div class="row"><div class="col-lg-7 col-md-7 col-sm-7">';
            
            divide_end =    '</div>\n\
                                <div class="col-lg-5 col-md-5 col-sm-3">\n\
                                    <div class="row">\n\
                                        <div class="col-lg-12 col-md-12 col-sm-12">\n\
                                            <img style="width:'+advanced_customer_info_img_width+'px" id="img_1" src="#" alt="" />\n\
                                            <img style="width:'+advanced_customer_info_img_width+'px" id="img_2" src="#" alt="" style="margin-top:10px;" />\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>';
  
        }else{
            display = "display:none;";
        }
        
    }).done(function () {
        var convert_to_advanced_html ="";
        if(convert_to_advanced){
            convert_to_advanced_html ="<span style='font-weight:bold;cursor:pointer' onclick='convert_to_identity()'>Convert to IDENTITY</span>";
        }
        
        var content =
            '<div class="modal" data-backdrop="static" id="add_new_customer" role="dialog" aria-hidden="true" style="z-index:99999999">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_new_customer_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                    <input id="block_id" name="block_id" type="hidden" value="0" />\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add new client '+convert_to_advanced_html+'</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        '+divide_start+'\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="customer_name">First Name '+mandatory_field_sign+'</label>\n\
                                    <input '+mandatory_field+' autocomplete="off" id="customer_name" name="customer_name" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="middle_name">Middle Name</label>\n\
                                    <input autocomplete="off" id="middle_name" name="middle_name" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="last_name">Last Name '+mandatory_field_sign+'</label>\n\
                                    <input '+mandatory_field+' autocomplete="off" id="last_name" name="last_name" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="last_name">Email</label>\n\
                                    <input autocomplete="off" id="email" name="email" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="dob">Date Of Birth '+mandatory_field_sign+'</label>\n\
                                    <input '+mandatory_field+' autocomplete="off" id="dob" name="dob" type="text" class="form-control big_and_bold" placeholder="dd-mm-yyyy">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="last_name">Company</label>\n\
                                    <input autocomplete="off" id="company" name="company" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="connected_to_supplier">Connected to Supplier (Same STMT)</label>\n\
                                    <div class="form-group">\n\
                                        <select data-live-search="true" id="connected_to_supplier" name="connected_to_supplier" class="selectpicker">' + suppliers_options + '</select>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3">\n\
                                <div class="form-group">\n\
                                    <label for="cus_code">Code</label>\n\
                                    <input autocomplete="off" id="cus_code" name="cus_code" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="'+display+';background-color:beige">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="country">Country&nbsp;</label>\n\
                                    <select onchange="getAreas([])" data-live-search="true" id="country" name="country" class="selectpicker">' + customer_countries + '</select>\n\
                                </div>\n\
                            </div>\n\
                           <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="area">Area&nbsp;<span onclick="add_new_area()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add New</span></label>\n\
                                    <select data-live-search="true" onchange="getDistricts([])" id="area" name="area" class="selectpicker">' + customer_areas + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="district">District&nbsp;<span onclick="add_new_district()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add New</span></label>\n\
                                    <select onchange="getCities([])" data-live-search="true" id="district" name="district" class="selectpicker"></select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="city">City&nbsp;<span onclick="add_new_city()" style="cursor:pointer; font-size:14px; font-weight:bold;color:#286090">Add New</span></label>\n\
                                    <select data-live-search="true" id="city" name="city" class="selectpicker"></select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" >\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="customer_address">Address</label>\n\
                                    <input autocomplete="off" id="customer_address" name="customer_address" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="customer_phone">Phones '+mandatory_field_sign+'</label>\n\
                                    <input '+mandatory_field+' autocomplete="off" id="customer_phone" name="customer_phone" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6">\n\
                                <div class="form-group">\n\
                                    <label for="note">Note</label>\n\
                                    <input autocomplete="off" id="note" name="note" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="'+display+';background-color:beige">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 pr2">\n\
                                <label for="id_type">Identity Type</label>\n\
                                <div class="form-group">\n\
                                    <select onchange="identity_changed()" data-live-search="true" id="id_type" name="id_type" class="selectpicker">' + identities_types_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 plpr2" >\n\
                                <div class="form-group" id="id_expiry_section">\n\
                                    <label for="id_expiry">Expiry Date '+mandatory_field_sign+'</label>\n\
                                    <input autocomplete="off" id="id_expiry" name="id_expiry" type="text" class="form-control big_and_bold" placeholder="dd-mm-yyy">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3 plpr2">\n\
                                <div class="form-group">\n\
                                    <label for="id_expiry">Identity Number '+mandatory_field_sign+'</label>\n\
                                    <input oninput="check_identity_duplicate()"  '+mandatory_field+' autocomplete="off" id="id_nb" name="id_nb" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 plpr2">\n\
                                <div class="form-group">\n\
                                    <label for="cob">Country Of Birth</label>\n\
                                    <select data-live-search="true" id="cob" name="cob" class="selectpicker">' + customer_countries + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 plpr2">\n\
                                <div class="form-group">\n\
                                    <label for="cob">Country Of Issue</label>\n\
                                    <select data-live-search="true" id="coi" name="coi" class="selectpicker">' + customer_countries + '</select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="customer_starting_balance">Starting Balance</label>\n\
                                    <input autocomplete="off" id="customer_starting_balance" value="0" name="customer_starting_balance" type="text" class="form-control big_and_bold only_numeric" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                           <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="customer_discount">Standard Discount %</label>\n\
                                    <input autocomplete="off" id="customer_discount" value="0" name="customer_discount" type="text" class="form-control big_and_bold only_numeric" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="customer_mof">MOF</label>\n\
                                    <input autocomplete="off" id="customer_mof" value="" name="customer_mof" type="text" class="form-control big_and_bold" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <label for="customer_type">Type</label>\n\
                                <div class="form-group">\n\
                                    <select id="customer_type" name="customer_type" class="selectpicker">' + customer_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12" style="'+display+';background-color:beige">\n\
                                <div class="form-group" style="margin-top:5px;margin-bottom:5px;">\n\
                                    <input onchange="readURL(this,\'img_1\');" accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="identity_1" name="identity_1" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-12 col-md-12 col-sm-12" style="'+display+';background-color:beige">\n\
                                <div class="form-group" style="margin-top:5px;margin-bottom:5px;">\n\
                                    <input onchange="readURL(this,\'img_2\');" accept=".png,.jpg,.jpeg" type="file" value="" class="form-control"  id="identity_2" name="identity_2" />\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2" style="'+display+'">\n\
                                <div class="form-group" style="margin-top:5px;">\n\
                                    <button style="display:none" id="print_identities_id" onclick="print_identities()" type="button" class="btn btn-dark">Print Identities</button>\n\
                                </div>\n\
                            </div>\n\
                            \n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="area">Area</label>\n\
                                    <input autocomplete="off" id="a_area" name="a_area" type="text" class="form-control " placeholder="Area">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="city">City</label>\n\
                                    <input autocomplete="off" id="a_city" name="a_city" type="text" class="form-control " placeholder="City">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="street">Street</label>\n\
                                    <input autocomplete="off" id="a_street" name="a_street" type="text" class="form-control " placeholder="Street">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="building">Building</label>\n\
                                    <input autocomplete="off" id="a_building" name="a_building" type="text" class="form-control " placeholder="Building">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="floor">Floor</label>\n\
                                    <input autocomplete="off" id="a_floor" name="a_floor" type="text" class="form-control " placeholder="Floor">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3">\n\
                                <div class="form-group">\n\
                                    <label for="street">Note</label>\n\
                                    <input autocomplete="off" id="a_cnote" name="a_cnote" type="cnote" class="form-control " placeholder="Note">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-3 col-md-3 col-sm-3" id="vendor_container">\n\
                                <label for="customer_type">Vendors</label>\n\
                                <div class="form-group">\n\
                                    <select id="vendor_id" name="vendor_id" class="selectpicker">' + vendors_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        '+divide_end+'\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#add_new_customer').remove();
        $('body').append(content);

        submitCustomer(action,enable_advanced_customer_info);

        $('.selectpicker').selectpicker({selectOnTab:true});
        

        $('.selectpicker').on('changed.bs.select', function(event){
            
            tmp_0bject = this;
            setTimeout(function(){
                $(tmp_0bject).focusNextInputField();
            },100);
            
        });
        
        $(".only_numeric").numeric({ negative : false});

        $('#add_new_customer').on('show.bs.modal', function (e) {

        });
        
        $('#add_new_customer').on('hidden.bs.modal', function (e) {
            $('#add_new_customer').remove();
        });

        $('#add_new_customer').on('shown.bs.modal', function (e) {
            $("#customer_name").focus();
            
            //getfirstname_for_type_head("customer_name");
            //getmiddlename_for_type_head("middle_name");
            //getlastname_for_type_head("last_name");
            
            if(data.length>0 && data[0].city_id>0){
                getDistricts([]);
            }
            
            if(data.length==0){
                if(dflt_loc.length>0){
                    var ddt = [{"area_id":dflt_loc[0].area_id,"district_id":dflt_loc[0].district_id,"city_id":dflt_loc[0].city_id}];
                    $("#area").val(dflt_loc[0].area_id);
                    $('#area').selectpicker("refresh");
                    getDistricts(ddt);
                }else{
                    getDistricts([]);
                }
            }
            
            
            $('#dob').datepicker({autoclose:true,format: 'dd-mm-yyyy'});
            $('#dob').datepicker().on('changeDate', function(ev) {
                
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            
            $('#dob').mask('AA-AA-AAAA');
            
            if(CUSTOMERS_PHONE_FORMAT!="-1"){
                $('#customer_phone').mask(CUSTOMERS_PHONE_FORMAT);
            }
            

            
            $('#id_expiry').datepicker({autoclose:true,format: 'dd-mm-yyyy'});
            $('#id_expiry').datepicker().on('changeDate', function(ev) {
                
                if($("#id_expiry").val().length>0 && $("#id_type").val()!=null && $("#id_type").val()!=""){
                    var seleted_date=$("#id_expiry").val().split("-");
                    var newDate=seleted_date[1]+"/"+seleted_date[0]+"/"+seleted_date[2];
                    
                    var d = new Date();
                    var m_ = d.getMonth();
                    var d_ = d.getMonth()+1;
           
                    
                    var y_ = d.getFullYear();
                    
                    //alert(parseInt(m_)+"="+parseInt(seleted_date[0]));
                    //alert(parseInt(d_)+"="+parseInt(seleted_date[1]));
                    //alert(y_+"="+seleted_date[2]);
                    
                    if(parseInt(m_)==parseInt(seleted_date[0]) && parseInt(d_)==parseInt(seleted_date[1]) && y_==seleted_date[2]){
                        
                    }else{
                       if( new Date(newDate).getTime()<Date.now()){
                            $("#id_expiry").val("");
                            alert("Identity Expired");
                            $("#id_expiry").focus();
                        }  
                    }
                    
                   
                }
            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
            $('#id_expiry').mask('AA-AA-AAAA');
            
            $('#id_nb').mask(CUSTOMERS_IDENTITY_FORMAT);
    
            if(data.length>0){
                $("#action_btn").text('Update');
                $("#id_to_edit").val(data[0].id_int);
                $("#customer_name").val(data[0].name);
                $("#company").val(data[0].company);
                
             
                $("#cus_code").val(data[0].code);
                
                $("#email").val(data[0].email);
                
                $("#print_identities_id").show();
                
           
                if(data[0].dob!=null && data[0].dob!="0000-00-00 00:00:00"){
                    $('#dob').datepicker( "setDate", data[0].dob.split(' ')[0]);
                }
                
                
                if(data[0].id_expiry!=null && data[0].id_expiry!="0000-00-00 00:00:00"){
                    $('#id_expiry').datepicker( "setDate", data[0].id_expiry.split(' ')[0] );
                }
                
                if(data[0].id_nb!=null){
                    
                    $("#id_nb").val(data[0].id_nb);
                    $("#id_nb").trigger('input');
                }
                
                if(data[0].id_type!=0){
                    $("#id_type").val(data[0].id_type);
                    $('#id_type').selectpicker("refresh");
                }
                
                
                if(data[0].identity_pic_1!="0"){
                    $("#img_1").attr("src",data[0].identity_pic_1);
                }
                if(data[0].identity_pic_2!="0"){
                    $("#img_2").attr("src",data[0].identity_pic_2);
                }
                
                
                $("#cob").val(data[0].cob);
                $('#cob').selectpicker("refresh");
                
                $("#coi").val(data[0].coi);
                $('#coi').selectpicker("refresh");
                
                
                
                $("#middle_name").val(data[0].middle_name);
                $("#last_name").val(data[0].last_name);

                $("#customer_mof").val(data[0].mof);
                $("#customer_address").val(data[0].address);
                
             
                $("#a_area").val(data[0].address_area);
                $("#a_city").val(data[0].address_city);
                $("#a_street").val(data[0].address_street);
                $("#a_floor").val(data[0].address_floor);
                $("#a_cnote").val(data[0].address_note);
                $("#a_building").val(data[0].address_building);
                
                $("#customer_phone").val(data[0].phone);
                $("#customer_phone").trigger('input');
                
                $("#note").val(data[0].note);

                $("#customer_discount").val(parseFloat(data[0].discount));

                $("#customer_starting_balance").val(parseFloat(data[0].starting_balance));

                $("#customer_type").val(data[0].customer_type);
                $('#customer_type').selectpicker("refresh");
                
                $("#vendor_id").val(data[0].created_by);
                $('#vendor_id').selectpicker("refresh");
                
                $("#connected_to_supplier").val(data[0].connected_to_supplier);
                $('#connected_to_supplier').selectpicker("refresh");
                
                
                if(enable_advanced_customer_info == "1"){
                    var data_ = [ {"country_id":data[0].country_id,"area_id":data[0].area_id,"district_id":data[0].district_id,"city_id":data[0].city_id} ];
                    
                    if(data[0].city_id==0){
                        $('#country').selectpicker('val', 0);
                        $('#area').empty();
                        $("#area").selectpicker("refresh");
                    }else{
                        getAreas(data_);
                    }
                    

                }
            }else{
                $("#vendor_container").hide();
            }
            identity_changed();
            $(".sk-circle-layer").hide();
        });

        $('#add_new_parent_cat').on('hide.bs.modal', function (e) {
            $('#add_new_customer').remove();
        });

        $('#add_new_customer').modal('toggle');
    });
}

function submitCustomer(action,enable_advanced_customer_info) {
    $("#add_new_customer_form").on('submit', (function (e) {
        e.preventDefault();
        
        if(enable_advanced_customer_info=="1"){
            if(!$("#id_expiry").is(":hidden")){
                if (emptyInput("id_expiry")) {
                    return;
                }
            }
            
        }
        
        if($("#block_id").val()==1){
            alert("Cannot submit, Identity exists");
            return;
        }
        
        if (!emptyInput("customer_name")) {
            
            $(".sk-circle-layer").show();
            $('#id_nb').unmask();
            $('#customer_phone').unmask();
            
            $.ajax({
                url: "?r=customers&f=add_new_customer",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    if(data.ph_error==1){
                        $(".sk-circle-layer").hide();
                        alert("Phone number already exist");
                        if(CUSTOMERS_PHONE_FORMAT!="-1"){
                            $('#customer_phone').mask(CUSTOMERS_PHONE_FORMAT);
                            $('#customer_phone').trigger("input");
                        }
                        return;
                    }

                    if($('#customers_table').length>0){
                        var table = $('#customers_table').DataTable();
                        table.ajax.url('?r=customers&f=getAllCustomers&p0=0').load(function () { 
                           if(action == "up"){
                                table.row('.' + pad_customer(data.id), {page: 'current'}).select();
                            }else{
                                table.page('last').draw(false);
                                table.row(':last', {page: 'current'}).select();
                            }
                            $(".sk-circle-layer").hide();
                            $('#add_new_customer').modal('hide');
                            
                        },false);
                    }else if($('#customers_search').length>0){
                        var table = $('#customers_search').DataTable();
                        var url = "?r=pos&f=get_all_customers&p0=0";
                        if($("#type_clients").val()=="1"){
                            url = "?r=pos&f=get_all_customers&p0=1";
                        }
                        if($("#type_clients").val()=="0"){
                            
                        }else{
                            
                        }
                        table.ajax.url(url).load(function () {
                            if(action == "up"){
                                $("."+pad_customer(data.id)).addClass('selected');
                                //table.row('.' + pad_customer(data.id), {page: 'current'}).addClass('selected');
                            }else{
                                $("#customers_search").DataTable().page('last').draw('page');
                                //$('#customers_search tr:last').addClass('selected');
                                var idx = table.row(":last").index();
                                $(table.row(idx).node()).addClass('selected');
                                
                                $(".dataTables_scrollBody").scrollTop($('.dataTables_scrollBody')[0].scrollHeight);
                                //table.page('last').draw(false);
                                //table.row(':last', {page: 'current'}).select();
                            }
                        },false);
                        $(".sk-circle-layer").hide();
                        $('#add_new_customer').modal('hide');
                    }
                   
                    if($('#delivery_items_table').length>0){
                        $("#delivery_items_table .selectpicker").each(function( index ) {
                            $(this).append("<option value='"+data.id+"'>"+$("#customer_name").val()+"</option>");
                            $(this).selectpicker('refresh');
                            $(".sk-circle-layer").hide();
                            $('#add_new_customer').modal('hide');
                        });
                    }
                    
                    if($("#garage_card_modal").length>0){
                     
                        $("#customers_list").append("<option value='"+data.id+"'>"+$("#customer_name").val()+" "+$("#middle_name").val()+" "+$("#last_name").val()+"</option>");
                        
                        
                        $("#customers_list").val(data.id);
                        
                        $("#customers_list").selectpicker('refresh');
                        $(".sk-circle-layer").hide();
                        $('#add_new_customer').modal('hide');
                    } 
                    
                    
                    if($("#invoices_table").length>0){
                     
                        $("#invoice_customer").val($("#customer_name").val()+" "+$("#middle_name").val()+" "+$("#last_name").val());
                        $("#invoice_customer_id").val(data.id);
                        
                        $(".sk-circle-layer").hide();
                        $('#add_new_customer').modal('hide');
                    } 
                    
                    if($("#users_customers_logs_table").length>0){
                        
                        var table = $('#users_customers_logs_table').DataTable();
                        table.ajax.url("?r=logs&f=get_user_customers_logs&p0="+$("#datefilter").val()+"&p1="+$("#users_list").val()+"&p2=0&p3=0").load(function () {
                            $(".sk-circle-layer").hide();
                            $('#add_new_customer').modal('hide');
                        }, false);
                
                        
                    }
                    
                    // from POS
                    if($("#payment_info").length>0){
                        //$('#customer_name_payment').typeahead('setVal',data.id);
                        $('#customer_id').val(data.id);
                        
                        $('#customer_name_payment').val($("#customer_name").val());
                        $('#customer_middle_payment').val($("#middle_name").val());
                        $('#customer_last_payment').val($("#last_name").val());
                        
                        $('#customer_phone').val($("#customer_phone").val());
                        $('#customer_address').val($("#customer_address").val());
                        
                        $(".sk-circle-layer").hide();
                        $('#add_new_customer').modal('hide');
                    }
                }
            });
        }
    }));
}

function editCustomer_new(id_int){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();       
    var data_ = [];
    var data_r = [];
    
    $.getJSON("?r=customers&f=getCustomersById&p0=" + id_int, function (data) {
        data_r=data;
    }).done(function () {
     
        data_.push({id_int:id_int,name:data_r[0].name,middle_name:data_r[0].middle_name,last_name:data_r[0].last_name,address:data_r[0].address,phone:data_r[0].phone,customer_type:data_r[0].customer_type,starting_balance:data_r[0].starting_balance,mof:data_r[0].mof,discount:data_r[0].discount,country_id:data_r[0].country_id,area_id:data_r[0].area_id,district_id:data_r[0].district_id,city_id:data_r[0].city_id,dob:data_r[0].dob,id_type:data_r[0].id_type,id_expiry:data_r[0].id_expiry,id_nb:data_r[0].id_nb,cob:data_r[0].cob,identity_pic_1:data_r[0].identity_pic_1,identity_pic_2:data_r[0].identity_pic_2,coi:data_r[0].coi,note:data_r[0].note,address_area:data_r[0].address_area,address_city:data_r[0].address_city,address_street:data_r[0].address_street,address_floor:data_r[0].address_floor,address_note:data_r[0].address_note,address_building:data_r[0].address_building,email:data_r[0].email,company:data_r[0].company,connected_to_supplier:data_r[0].connected_to_supplier,code:data_r[0].code,created_by:data_r[0].created_by});
        
        if(data_[0].id_nb==null || data_[0].id_nb.length==0){
            addCustomer('up',data_,1);
        }else{
            addCustomer('up',data_,0);
        }
        
    });
}

function editCustomer(id){
    var id_int = parseInt(id.split('-')[1]);
    $(".sk-circle").center();
    $(".sk-circle-layer").show();       
    var data_ = [];
    var data_r = [];
    
    $.getJSON("?r=customers&f=getCustomersById&p0=" + id_int, function (data) {
        data_r=data;
    }).done(function () {
     
        data_.push({id_int:id_int,name:data_r[0].name,middle_name:data_r[0].middle_name,last_name:data_r[0].last_name,address:data_r[0].address,phone:data_r[0].phone,customer_type:data_r[0].customer_type,starting_balance:data_r[0].starting_balance,mof:data_r[0].mof,discount:data_r[0].discount,country_id:data_r[0].country_id,area_id:data_r[0].area_id,district_id:data_r[0].district_id,city_id:data_r[0].city_id,dob:data_r[0].dob,id_type:data_r[0].id_type,id_expiry:data_r[0].id_expiry,id_nb:data_r[0].id_nb,cob:data_r[0].cob,identity_pic_1:data_r[0].identity_pic_1,identity_pic_2:data_r[0].identity_pic_2,coi:data_r[0].coi,note:data_r[0].note,address_area:data_r[0].address_area,address_city:data_r[0].address_city,address_street:data_r[0].address_street,address_floor:data_r[0].address_floor,address_note:data_r[0].address_note,address_building:data_r[0].address_building,email:data_r[0].email,company:data_r[0].company,connected_to_supplier:data_r[0].connected_to_supplier,code:data_r[0].code,created_by:data_r[0].created_by});
        
        if(data_[0].id_nb==null || data_[0].id_nb.length==0){
            addCustomer('up',data_,1);
        }else{
            addCustomer('up',data_,0);
        }
        
    });
}

function update_cus_info(customer_id){
    $.getJSON("?r=customers&f=getCustomersById&p0=" + customer_id, function (data) {
        $("#i_f_name").html(data[0].name.toUpperCase());
        $("#i_m_name").html(data[0].middle_name.toUpperCase());
        $("#i_l_name").html(data[0].last_name.toUpperCase());
        $("#i_id_type").html(data[0].id_type_name.toUpperCase());
        $("#i_dob").html(data[0].dob.toUpperCase());
        
        $("#i_ph").empty();
        $("#i_ph").html("<span id='fph'>"+data[0].phone+"<span>");
        
        if(CUSTOMERS_PHONE_FORMAT!="-1"){
            $("#fph").mask(CUSTOMERS_PHONE_FORMAT);
        }
        
        
        $("#i_id_nb").empty();
        $("#i_id_nb").html("<span id='fnb'>"+data[0].id_nb.toUpperCase()+"<span>");
        $("#fnb").mask(CUSTOMERS_IDENTITY_FORMAT);
        
        
        $("#i_cob").html(data[0].cob_name.toUpperCase());
        $("#i_coi").html(data[0].coi_name.toUpperCase());
   
        $("#i_addr_inf").html(data[0].city_name+" "+data[0].address);
        
        if(data[0].identity_pic_1==0){
            $("#imgsrc_1").hide();
        }else{
            if(data[0].identity_pic_1!=-1){
                $("#imgsrc_1").show();
                $("#imgsrc_1").attr("src",data[0].identity_pic_1);
            }else{
                $("#imgsrc_1").hide();
            }
        }
        
        if(data[0].identity_pic_2==0){
            $("#imgsrc_2").hide();
        }else{
            if(data[0].identity_pic_2!=-1){
                $("#imgsrc_2").show();
                $("#imgsrc_2").attr("src",data[0].identity_pic_2);
            }else{
                $("#imgsrc_2").hide();
            }
        }
        
        if(data[0].expired==1){
            $("#expired_text").show();
        }else{
            $("#expired_text").hide();
        }
        
        
        if(data[0].id_expiry!=null){
            $("#i_exp").html(data[0].id_expiry.split(' ')[0]);
        }else{
            $("#i_exp").html("");
        }
        
        if(data[0].dob!=null){
            $("#i_dob").html(data[0].dob.split(' ')[0]);
        }
        
    }).done(function () {
        
        $.getJSON("?r=logs&f=get_user_customers_logs_by_id&p0="+customer_id, function (data) {
            $("#user_customers_log_details").html("");
            $("#user_customers_log_details").append(data.customer_info);
            $.each(data.logs, function (key, val) {
                $("#user_customers_log_details").append(val.description);
            });
        }).done(function () {
            
        }); 
        
    });
}