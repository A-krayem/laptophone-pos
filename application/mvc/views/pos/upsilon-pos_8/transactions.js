function add_new_transaction(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var transfer_to_options="";
    var _data=[];
    $.getJSON("?r=transactions&f=get_info", function (data) {
        _data = data;
        transfer_to_options+="<option value='0'>Select Cashbox</option>";
        for(var i=0;i<_data.opened.length;i++){
            transfer_to_options+="<option value='"+_data.opened[i].id+"'>"+_data.opened[i].username+"</option>";
        }
    }).done(function () { 
        $(".sk-circle-layer").hide();
        var content =
        '<div class="modal medium" data-backdrop="static"  id="tra_modal" role="dialog" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="casbox_transactions_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Cashbox Transaction<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'tra_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row" >\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="transaction_type" >Transaction Type</label>\n\
                                    <select class="form-control" id="transaction_type" name="transaction_type" onchange="transfer_type_changed()">\n\
                                          <option value="0">Select Transaction Type</option>\n\
                                          <option value="1">CASH IN</option>\n\
                                          <option value="2">CASH OUT</option>\n\
                                    </select>\n\
                                </div>\n\
                                <div class="form-group" id="transfer_to_container" style="display:none">\n\
                                    <label for="transaction_to">Tranfer To Cashbox</label>\n\
                                    <select class="form-control" id="transaction_to" name="transaction_to">'+transfer_to_options+'</select>\n\
                                </div>\n\
                                <div class="form-group">\n\
                                    <label for="amount_usd">Amount USD</label>\n\
                                    <input style="font-size:18px; font-weight:bold;" type="text" class="form-control" id="amount_usd" name="amount_usd" placeholder="">\n\
                                </div>\n\
                                <div class="form-group">\n\
                                    <label for="amount_lbp">Amount LBP</label>\n\
                                    <input style="font-size:18px; font-weight:bold;" type="text" class="form-control" id="amount_lbp" name="amount_lbp" placeholder="">\n\
                                </div>\n\
                                <div class="form-group">\n\
                                    <label for="transaction_note">Note</label>\n\
                                    <textarea class="form-control" id="transaction_note" name="transaction_note" rows="3"></textarea>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <button type="submit" class="btn btn-primary">Submit</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
       
        $('#tra_modal').modal('hide');
        
        $("body").append(content);
        $('#tra_modal').on('show.bs.modal', function (e) {
            
        });

        $('#tra_modal').on('shown.bs.modal', function (e) { 
           casbox_transactions_form('tra_modal');
           
           cleaves_id("amount_usd",0);
           cleaves_id("amount_lbp",0);
        });

        $('#tra_modal').on('hide.bs.modal', function (e) {
            $("#tra_modal").remove();
        });
        $('#tra_modal').modal('show');
    }).fail(function() { $(".sk-circle-layer").hide(); })
        .always(function() { $(".sk-circle-layer").hide(); });;
    
    
}


function transfer_type_changed(){
    /* Transfers */
    if($("#transaction_type").val()==3){
        $("#transfer_to_container").show();
    }else{
        $("#transfer_to_container").hide();
    }
}

function casbox_transactions_form(modalname){
    $("#casbox_transactions_form").on('submit', (function (e) {
        e.preventDefault();
        
        if($("#cashtype").val()==0){
            alert("Select Cash Type First");
            return;
        }
        
        if($("#transaction_type").val()==3 && $("#transaction_to").val()==0){
            alert("Select Transfer To Cashbox");
            return;
        }
        
        if( ($("#amount_usd").val()==0 || $("#amount_usd").val()=="") && ($("#amount_lbp").val()==0 || $("#amount_lbp").val()=="")){
            alert("Missing Amount");
            return;
        }
        
        
  
        if($("#transaction_type").val()==0){
            alert("Select Transaction Type First");
            return;
        }
       
        $('#amount_usd').val($('#amount_usd').val().replace(/,/g , ''));
        $('#amount_lbp').val($('#amount_lbp').val().replace(/,/g , ''));

        
        $(".sk-circle").center();
        $(".sk-circle-layer").show();
    
        $.ajax({
            url: "?r=transactions&f=add_new_transaction",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $('#amount_usd').val("");
                $('#amount_lbp').val("");
                $(".sk-circle-layer").hide();
                
            
                $('#drep_table__').DataTable().ajax.url("?r=cashinout&f=get_full_report_table&p0=0&p1=0").load(function () {
                    
                }, false);
    
                $('#tra_modal').modal('hide');
            }
        });
    }));
}


function delete_transaction(transaction_id){
    swal({
        title: "Are you sure?",
        html: false ,
        text: '',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            var _data = [];
            $.getJSON("?r=transactions&f=delete_transaction&p0="+transaction_id, function (data) {
                _data = data;
            }).done(function () {
                $('#drep_table__').DataTable().ajax.url("?r=cashinout&f=get_full_report_table&p0=0&p1=0").load(function () {
                    
                }, false);
            });
        }
    });
}