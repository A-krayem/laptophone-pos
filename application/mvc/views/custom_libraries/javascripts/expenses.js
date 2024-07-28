var addExpenseFunctionLocked = false;
function addExpense() {
    if(addExpenseFunctionLocked==false){
        addExpenseFunctionLocked = true;
        lockMainPos = true;
        var options = "";
        $.getJSON("?r=expenses&f=gettypes", function (data) {
            var index = 0;

            $.each(data, function (key, val) {
                if (index == 0) {
                    options += "<option selected value=" + val.id + ">" + val.name + "</option>";
                } else {
                    options += "<option value=" + val.id + ">" + val.name + "</option>";
                }
                index++;
            });
        }).done(function () {
            var content =
                '<div class="modal fade" id="add_new_expenses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <form id="add_new_expenses_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-expenses"></i>&nbsp;Add expense</h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">Expense type</label>\n\
                                        <select id="expense_type" name="expense_type" class="selectpicker form-control" >' + options + '</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">Value</label>\n\
                                        <div class="inner-addon left-addon addon_item_icon"><input id="expense_val" name="expense_val" value="0" type="text" class="form-control only_numeric" placeholder="Expense value"></div>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_date">Date</label>\n\
                                        <div class="inner-addon left-addon addon_item_icon"><input id="expense_date" name="expense_date" type="text" class="form-control datepicker"></div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="form-group">\n\
                                <label for="expense_description">Description</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input autocomplete="off" id="expense_description" name="expense_description" type="text" class="form-control" placeholder="Expense description" aria-describedby="basic-addon1"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style=" width: 40%; color: #000; font-size: 20px; font-weight: bold;">Cancel</button>\n\
                            <button id="action_btn" type="submit" class="btn btn-default" style=" width: 40%; color: #000; font-size: 20px; font-weight: bold;">Add</button>\n\
                        </div>\n\
                        <form/>\n\
                    </div>\n\
                </div>\n\
            </div>';

            $('#add_new_expenses').remove();
            $('body').append(content);
            $('.datepicker').datepicker({autoclose:true});
            $(".datepicker").datepicker( "setDate", new Date() );
            $('.selectpicker').selectpicker();
            $(".only_numeric").numeric();
            submitExpense();
            $('#add_new_expenses').modal('toggle');

            $('#add_new_expenses').on('hidden.bs.modal', function (e) {
                lockMainPos = false;
                $('#add_new_expenses').remove();
            });
        })
        .fail(function() {

        })
        .always(function() {
            addExpenseFunctionLocked = false;
        });
    }
}

function submitExpense() {
    var cashBoxTotalReturn = null;
    $("#add_new_expenses_form").on('submit', (function (e) {
        e.preventDefault();
        if($("#expense_val").val()=="") $("#expense_val").val(0);
        if (!emptyInput("expense_description")) {
            $.ajax({
                url: "?r=expenses&f=add_new_expense&p0="+store_id,
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $("#cashboxTotal").html(data.cashBoxTotal);
                    $('#add_new_expenses').modal('hide');
                }
            });
        }
    }));
}

var addQuantityFunctionLocked = false;
function addQuantity(){
    if(addQuantityFunctionLocked==false){
        addQuantityFunctionLocked = true;
        lockMainPos = true;
        var options = "";
        $.getJSON("?r=pos&f=getItemsForPos", function (data) {
            options += "<option value='0'>Select Item</option>";
            $.each(data, function (key, val) {
                options += "<option value=" + val.id + ">" + val.description + "</option>";
            });
        }).done(function () {
            var content =
                '<div class="modal fade" id="add_qty" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
                <div class="modal-dialog" role="document">\n\
                    <div class="modal-content">\n\
                        <form id="add_qty_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title" id="exampleModalLongTitle"><i class="icon-expenses"></i>&nbsp;Add quantity</h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">Items</label>\n\
                                        <select onchange="item_changed()" id="item_id" name="item_id" class="selectpicker form-control" >'+options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">Available quantity</label>\n\
                                        <input autocomplete="off" disabled  id="available_qty" name="available_qty" type="text" class="form-control">\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">\n\
                                    <div class="form-group">\n\
                                        <label for="expense_type">Add quantity</label>\n\
                                        <input autocomplete="off" id="item_add_qty" value="0" name="item_add_qty" type="text" class="form-control">\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="modal-footer">\n\
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style=" width: 40%; color: #000; font-size: 20px; font-weight: bold;">Cancel</button>\n\
                            <button id="action_btn" disabled type="submit" class="btn btn-default" style=" width: 40%; color: #000; font-size: 20px; font-weight: bold;">Add</button>\n\
                        </div>\n\
                        <form/>\n\
                    </div>\n\
                </div>\n\
            </div>';
            $('#add_qty').remove();
            $('body').append(content);
            $('.datepicker').datepicker({autoclose:true});
            $(".datepicker").datepicker( "setDate", new Date() );
            $('.selectpicker').selectpicker();
            $(".only_numeric").numeric();
            submitqty();
            $('#add_qty').modal('toggle');

            $('#add_qty').on('hidden.bs.modal', function (e) {
                lockMainPos = false;
                $('#add_qty').remove();
            });

        }).fail(function() {

        }).always(function() {
            addQuantityFunctionLocked = false;
        });;
    }
}

function item_changed(){
    if( $("#item_id").val() != 0){
        $("#action_btn").removeAttr("disabled");
        $.getJSON("?r=pos&f=getItemsForPosQty&p0="+$("#item_id").val(), function (data) {
            $("#available_qty").val(data[0].quantity);
        }).done(function () {

        });
    }else{
        $("#available_qty").val(0);
        $("#action_btn").attr("disabled","disabled");
    }
}

function submitqty() {
    $("#add_qty_form").on('submit', (function (e) {
        e.preventDefault();
        $("#action_btn").attr("disabled","disabled");
        if($("#item_add_qty").val()=="") $("#item_add_qty").val(0);
        $.ajax({
            url: "?r=pos&f=add_item_qty",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data)
            {
                $("#available_qty").val(parseInt($("#available_qty").val())+parseInt($("#item_add_qty").val()));
                $("#item_add_qty").val(0);
                $("#action_btn").removeAttr("disabled");
            }
        });
    }));
}