function add_item(){
    var content =
        '<div class="modal" data-backdrop="static" id="add_new_items_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="add_new_item_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title" id="exampleModalLongTitle"><i class="glyphicon glyphicon-briefcase"></i>&nbsp;Add Item</h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_supplier">Supplier</label>\n\
                                    <input id="item_supplier" name="item_supplier" value="" type="text" class="form-control" placeholder="Supplier">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_supplier_phone">Phone</label>\n\
                                    <input id="item_supplier_phone" name="item_supplier_phone" value="" type="text" class="form-control" placeholder="Phone">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_description">Description</label>\n\
                                    <input required id="item_description" name="item_description" value="" type="text" class="form-control" placeholder="Description">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_cost">Cost</label>\n\
                                    <input required id="item_cost" name="item_cost" value="" type="number" class="form-control only_numeric" placeholder="Buying Cost">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_price">Price</label>\n\
                                    <input required id="item_price" name="item_price" value="" type="number" class="form-control" placeholder="Selling Price">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row for_mobile">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_code1">IMEI 1</label>\n\
                                    <input required id="item_code1" name="item_code1" value="" type="text" class="form-control" placeholder="IMEI 1">\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_code2">IMEI 2</label>\n\
                                    <input id="item_code2" name="item_code2" value="" type="text" class="form-control" placeholder="IMEI 2">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_description">Barcode</label>\n\
                                    <input required id="item_barcode" name="item_barcode" value="" type="text" class="form-control" placeholder="Barcode">\n\
                                </div>\n\
                            </div>                            \n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                <div class="form-group">\n\
                                    <label for="item_code1">Quantity</label>\n\
                                    <input required id="item_qty" name="item_qty" value="" type="number" class="form-control" placeholder="Quantity">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-danger" data-dismiss="modal" style="">Cancel</button>\n\
                        <button id="action_btn" type="submit" class="btn btn-primary" style="">Add</button>\n\
                    </div>\n\
                <form/>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_items_modal').remove();
    $('body').append(content);
    
    
    if(mobile_shop==0){
        $(".for_mobile").remove();
    }else{
        $("#item_supplier").attr("required", true);
        $("#item_supplier_phone").attr("required", true);
    }
    

    $('.selectpicker').selectpicker();
    $(".only_numeric").numeric();
    
    
    submitItem();


    $('#add_new_items_modal').on('show.bs.modal', function (e) {
        $(".sk-circle-layer").hide();
    });
    
    $('#add_new_items_modal').on('shown.bs.modal', function (e) {
        $("#item_supplier").focus();
    });

    $('#add_new_items_modal').on('hide.bs.modal', function (e) {
        $('#add_new_items_modal').remove();
        
    });
    $('#add_new_items_modal').modal('show');
}


function submitItem() {
    $("#add_new_item_form").on('submit', (function (e) {
        e.preventDefault();
        
        
        if (!navigator.onLine) {
            //swal("Check your internet connection");
            //return;
        }
    
        $.ajax({
            url: "?r=pos&f=add_new_item_pos",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $('#add_new_items_modal').modal('hide');
            }
        });
    }));
}

