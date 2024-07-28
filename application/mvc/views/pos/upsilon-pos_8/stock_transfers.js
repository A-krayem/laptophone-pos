function stock_transfer(it_id,store_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=all_stores_data&f=prepare_stock_transfer&p0="+it_id, function (data) {
        
        var options_branches="";
        for(var i=0;i<data.stores.length;i++){
            if(store_id==data.stores[i].id){
                options_branches+="<option selected value='"+data.stores[i].id+"'>"+data.stores[i].location+"</option>";
            }else{
                options_branches+="<option value='"+data.stores[i].id+"'>"+data.stores[i].location+"</option>";
            }
        }
        
        var content =
            '<div class="modal" data-backdrop="static" id="add_new_pos_transfer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="pos_transfer_stock_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="trs_item_id" name="trs_item_id" type="hidden" value="'+it_id+'" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title" id="exampleModalLongTitle">Stock Transfer<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_pos_transfer_modal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                               <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">To Branch</label>\n\
                                        <select id="to_branch"  data-live-search="true" name="to_branch" class="selectpicker form-control" >' + options_branches + '</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">Quantity</label>\n\
                                        <input required id="trs_qty"  type="number" name="trs_qty" class="form-control" />\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <div class="row">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    &nbsp;\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">\n\
                                    <button data-dismiss="modal" type="button" class="btn btn-default" style="width:100%">Cancel</button>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" >\n\
                                    <button id="pos_trans_btn" type="submit" class="btn btn-primary" style="width:100%">Submit</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    <form/>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('#add_new_pos_transfer_modal').remove();
        $('body').append(content);

        //submitExpense();

        $('#add_new_pos_transfer_modal').on('shown.bs.modal', function (e) {
            $(".selectpicker").selectpicker();
            submit_pos_transfer("add_new_pos_transfer_modal");
        });
        
        $('#add_new_pos_transfer_modal').on('show.bs.modal', function (e) {
           
        });

        $('#add_new_pos_transfer_modal').on('hide.bs.modal', function (e) {

        });
        $('#add_new_pos_transfer_modal').modal('show');
        
    }).done(function () {
        $(".sk-circle-layer").hide();
    }).fail(function() {
        $(".sk-circle-layer").hide();
    })
    .always(function() {
        $(".sk-circle-layer").hide();
    });
}

function submit_pos_transfer(modal){
    
    $("#pos_transfer_stock_form").on('submit', (function (e) {
        e.preventDefault();
        
        $("#pos_trans_btn").prop("disabled",true);
        $.ajax({
            url: "?r=pos&f=submit_stock_transfer",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $("#pos_trans_btn").prop("disabled",false);
                
                var table = $('#modal_get_all_qty_if_item_table__').DataTable();
                table.ajax.url("?r=all_stores_data&f=get_all_items_qty_in_all_stores&p0="+$("#search_all_store_item_id").val()).load(function () { },false);
                
                //var table = $('#items_search').DataTable();
                //table.ajax.url("?r=pos&f=get_all_items_new_AJAX&p0=0&p1=0&p2=0").load(function () { },false);
                updateAllItems();
                $('#'+modal).modal('hide');
            }
        });
    }));
}