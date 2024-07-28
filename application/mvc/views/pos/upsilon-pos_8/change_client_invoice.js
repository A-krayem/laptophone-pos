function change_client_invoice(invoice_id){
    var modal_name="change_client_invoice_modal";
    var modal_title="Change Client";
    
    var p_options="<option value='0'>SELECT TO CHANGE</option><option value='1'>CASH</option><option value='2'>DEBT</option>";
    
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="closeModal(\'change_client_invoice_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div style="font-size:20px;">Payment</div>\n\
                            <select data-width="100%" id="payment_options" class="selectpicker" onchange="update_payment_of_invoice('+invoice_id+')">\n\
                                ' + p_options + '\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row" style="margin-top:20px !important;">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div style="font-size:20px;">Search Client by ID, Name or Phone </div>\n\
                            <input type="text" class="form-control" id="client_sr" name="client_sr" />\n\
                            <ul id="client_sr-list" style="padding-left:15px !important; font-size:18px;"></ul>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    
    $('#'+modal_name).modal('hide');
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {
        
        $("#payment_options").selectpicker();
        
        $('#client_sr').keyup(function(){
            var query = $(this).val();
            if(query != ''){
                $.ajax({
                    url: '?r=pos&f=search_client&p0='+invoice_id,
                    method: 'POST',
                    data: {query:query},
                    success: function(data){
                        $('#client_sr-list').fadeIn();
                        $('#client_sr-list').html(data);
                    }
                });
            }
        });
        
    
    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#client_sr-list").remove();
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}


function update_payment_of_invoice(invoice_id){
    $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var _data=[];
        $.getJSON("?r=pos&f=update_payment_of_invoice&p0="+invoice_id+"&p1="+$("#payment_options").val(), function (data) {
            _data=data;
        }).done(function () { 
            $(".sk-circle-layer").hide();
            if(_data==0){
                invoices_date_changed();
                swal("Changed");
            }
            
            if(_data==1){
                swal("You must set the client first");
            }
            

        }).fail(function() {
            //logged_out_warning();
        }).always(function() {

        });
}


function update_inv_cl(client_id,invoice_id){
    $(".sk-circle").center();
        $(".sk-circle-layer").show();
        var _data=[];
        $.getJSON("?r=pos&f=change_client_invoice&p0="+invoice_id+"&p1="+client_id, function (data) {
            _data=data;
        }).done(function () {   
            invoices_date_changed();
            
            //$("#change_client_invoice_modal").modal("hide");
            swal("Changed");

        }).fail(function() {
            //logged_out_warning();
        }).always(function() {

        });
}