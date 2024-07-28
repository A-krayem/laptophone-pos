function _edit_discount(id){
    var discount_data = [];
    $.getJSON("?r=discounts&f=get_discount&p0="+id, function (data) {
        discount_data = data;
    }).done(function () {
        _add_discount('edit',discount_data);
    });
}


function _add_discount(action,data){
    var index = 0;
    var parents_categories = "";
    $.getJSON("?r=items&f=get_needed_data", function (data) {
        index = 0;
        $.each(data.parents_categories, function (key, val) {
            if (index == 0) {
                parents_categories += "<option selected value=" + val.id + ">" + val.name + "</option>";
            } else {
                parents_categories += "<option value=" + val.id + ">" + val.name + "</option>";
            }
            index++;
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_discountModal" tabindex="-1" role="dialog">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_discount_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title"><i class="glyphicon glyphicon-chevron-down"></i>&nbsp;Discounts<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_discountModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                    <label for="discount_name">Discount name</label>\n\
                                    <input autocomplete="off" name="discount_name" id="discount_name" onchange="" class="col-md-2 form-control" type="text" placeholder="Discount name">\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:20px;">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <label for="category_id">Category</label>\n\
                                    <select data-live-search="true" id="category_id" name="category_id" onchange="category_changed(-1)" class="selectpicker form-control" style="width:100%">' + parents_categories + '</select>\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <label for="sub_category_id">Sub-Category</label>\n\
                                    <select data-live-search="true" id="sub_category_id" name="sub_category_id" class="selectpicker form-control" style="width:100%"></select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:20px;">\n\
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n\
                                    <label for="start_date">Start Date</label>\n\
                                    <input name="start_date" id="start_date" class="col-md-2 form-control datepicker" type="text" placeholder="" style="cursor:pointer">\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n\
                                    <label for="end_date">End Date</label>\n\
                                    <input name="end_date" id="end_date" class="col-md-2 form-control datepicker" type="text" placeholder="" style="cursor:pointer">\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">\n\
                                    <label for="end_date">Value (%)</label>\n\
                                    <input value="10" name="discount_value" id="discount_value" class="col-md-2 form-control only_numeric" type="text" placeholder="">\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="submit_id" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#add_discountModal").remove();
        $("body").append(content);
        $("#add_discountModal").centerWH();
        
        if(action=="add"){
            $('#start_date').datetimepicker({
                defaultDate: new Date(),
                format: 'YYYY-MM-DD HH:mm:ss'
            });

            $('#end_date').datetimepicker({
                defaultDate: new Date(),
                format: 'YYYY-MM-DD HH:mm:ss'
            });
            
            $('.datepicker').datetimepicker().on('changeDate', function(ev) {

            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
        }
        
        $('.selectpicker').selectpicker();

        $(".only_numeric").numeric({ negative : false});

        $("#discount_value").keyup(function() {
            if($("#discount_value").val()>100){
                $("#discount_value").val(100);
            }
        });

        submitNewDiscount(action);
        $('#add_discountModal').on('show.bs.modal', function (e) {
            if(action=="add"){
                category_changed(-1);
            }else if(action=="edit"){
                $("#id_to_edit").val(data[0].id);
                $("#discount_name").val(data[0].discount_name);
                $('#category_id').selectpicker('val', data[0].category_parent_id);
                category_changed(data[0].category_id);
                
                $('#start_date').datetimepicker({
                    defaultDate: data[0].start_date,
                    format: 'YYYY-MM-DD HH:mm:ss'
                });

                $('#end_date').datetimepicker({
                    defaultDate: data[0].end_date,
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
                
                $('.datepicker').datetimepicker().on('changeDate', function(ev) {

                }).on('hide show', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                });

                $('#discount_value').val(data[0].discount_value);
                $('#submit_id').html("Update");
            }  
        });
        $('#add_discountModal').on('hide.bs.modal', function (e) {
            $("#add_discountModal").remove();
        });
        $('#add_discountModal').modal('show');
    });
}


function _edit_discount_group(id){
    var discount_data = [];
    $.getJSON("?r=discounts&f=get_discount&p0="+id, function (data) {
        discount_data = data;
    }).done(function () {
        _add_discount_by_group('edit',discount_data);
    });
}

function never_end_changed(){
    if ($("#never_end").is(':checked')) {
        $("#start_date").prop('readonly', true);
        $("#end_date").prop('readonly', true);
    } else {
        $("#start_date").prop('readonly', false);
        $("#end_date").prop('readonly', false);
    }
}

function _add_discount_by_group(action,data){
    var index = 0;
    var all_groups_options = "";
    $.getJSON("?r=items&f=get_needed_data", function (data) {
                index = 0;
        $.each(data.all_groups, function (key, val) {
            if (index == 0) {
                all_groups_options += "<option selected value=" + val.item_group + ">" + val.description + "</option>";
            } else {
                all_groups_options += "<option value=" + val.item_group + ">" + val.description + "</option>";
            }
            index++;
        });
    }).done(function () {
        var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_discount_groupModal" role="dialog">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="add_discount_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" type="hidden" value="0" />\n\
                        <div class="modal-header"> \n\
                            <h3 class="modal-title"><i class="glyphicon glyphicon-chevron-down"></i>&nbsp;Discount By Group<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_discount_groupModal\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body">\n\
                            <div class="row">\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <label for="discount_name">Discount name</label>\n\
                                    <input autocomplete="off" name="discount_name" id="discount_name" onchange="" class="col-md-2 form-control" type="text" placeholder="Discount name">\n\
                                </div>\n\
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                    <label for="category_id">Group</label>\n\
                                    <select data-live-search="true" id="group_id" name="group_id" onchange="discount_group_changed()" class="selectpicker form-control" style="width:100%">' + all_groups_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:20px;">\n\
                                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">\n\
                                    <label for="start_date">Never End</label>\n\
                                    <br/><input style="width:50px;height:25px;" type="checkbox" onchange="never_end_changed()" name="never_end" id="never_end"  />\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <label for="start_date">Start Date</label>\n\
                                    <input name="start_date" id="start_date" class="col-md-2 form-control datepicker" type="text" placeholder="" style="cursor:pointer">\n\
                                </div>\n\
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">\n\
                                    <label for="end_date">End Date</label>\n\
                                    <input name="end_date" id="end_date" class="col-md-2 form-control datepicker" type="text" placeholder="" style="cursor:pointer">\n\
                                </div>\n\
                            </div>\n\
                            <div class="row" style="margin-top:20px;">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <label for="end_date">Value (%)</label>\n\
                                    <input readonly value="0" name="discount_value" id="discount_value" class="col-md-2 form-control only_numeric" type="text" placeholder="">\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <label for="d_final_price">Final Price</label>\n\
                                    <input value="" oninput="discount_new_price_changed()" name="n_final_price" id="n_final_price" class="col-md-2 form-control only_numeric" type="text" placeholder="">\n\
                                    <input value="" name="o_final_price" id="o_final_price" class="col-md-2 form-control only_numeric" type="hidden" placeholder="">\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <label for="print_br_gp">Print barcode</label>\n\
                                    <br/><input style="width:50px;height:25px;" type="checkbox" name="print_br_gp" id="print_br_gp" value="" />\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <button id="submit_id" type="submit" class="btn btn-primary">Add</button>\n\
                    </div>\n\
                    </form>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#add_discount_groupModal").modal('hide');
        $("body").append(content);
        $("#add_discount_groupModal").centerWH();
        
        if(action=="add"){
            $('#start_date').datetimepicker({
                defaultDate: new Date(),
                format: 'YYYY-MM-DD HH:mm:ss'
            });

            $('#end_date').datetimepicker({
                defaultDate: new Date(),
                format: 'YYYY-MM-DD HH:mm:ss'
            });
            
            $('.datepicker').datetimepicker().on('changeDate', function(ev) {

            }).on('hide show', function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
        }
        
        $('.selectpicker').selectpicker();

        $(".only_numeric").numeric({ negative : false});

        $("#discount_value").keyup(function() {
            if($("#discount_value").val()>100){
                $("#discount_value").val(100);
            }
        });

        submitNewDiscount_group(action);
        $('#add_discount_groupModal').on('show.bs.modal', function (e) {
            if(action=="add"){
                discount_group_changed();
                //category_changed(-1);
            }else if(action=="edit"){
                $("#id_to_edit").val(data[0].id);
                $("#discount_name").val(data[0].discount_name);
                $('#group_id').selectpicker('val', data[0].group_id);
                
                $('#start_date').datetimepicker({
                    defaultDate: data[0].start_date,
                    format: 'YYYY-MM-DD HH:mm:ss'
                });

                $('#end_date').datetimepicker({
                    defaultDate: data[0].end_date,
                    format: 'YYYY-MM-DD HH:mm:ss'
                });
                
                $('.datepicker').datetimepicker().on('changeDate', function(ev) {

                }).on('hide show', function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                });

                $('#discount_value').val(data[0].discount_value);
                
                if(data[0].never_end==1){
                    $('#never_end').prop('checked', true);
                    $('#start_date').prop('readonly', true);
                    $('#end_date').prop('readonly', true);
                }
                
                discount_group_changed();
                
                $('#submit_id').html("Update");
            }  
        });
        $('#add_discount_groupModal').on('hide.bs.modal', function (e) {
            $("#add_discount_groupModal").remove();
        });
        $('#add_discount_groupModal').modal('show');
    });
}

function discount_new_price_changed(){
    $("#discount_value").val((1-$("#n_final_price").val()/$("#o_final_price").val())*100);
}

function discount_group_changed(){
    $.getJSON("?r=items&f=get_item_by_id&p0="+$("#group_id").val(), function (data) {
        if($("#discount_value").val()>0){
            $("#n_final_price").val(parseFloat(data[0].selling_price)*(1-parseFloat($("#discount_value").val())/100));
        }else{
            $("#n_final_price").val(parseFloat(data[0].selling_price));
        }
        
        $("#o_final_price").val(parseFloat(data[0].selling_price));
    }).done(function () {
        
    });
}

function category_changed(type){
    var categories = "";
    $.getJSON("?r=items&f=get_needed_data", function (data) {
        categories += "<option selected value='0'>All</option>";
        $.each(data.categories, function (key, val) {
            if(val.parent == $("#category_id").val()){
                categories += "<option value=" + val.id + ">" + val.description + "</option>";
            }
        });
    }).done(function () {
        $("#sub_category_id").html("");
        $("#sub_category_id").append(categories);
        $("#sub_category_id").selectpicker('refresh');
        if(type>=0){
            $('#sub_category_id').selectpicker('val', type);
        }
    });
}


function submitNewDiscount_group(action) {
    $("#add_discount_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=discounts&f=add_new_discount_group",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    var print_is_checked = $('#print_br_gp').prop('checked');
                    var current_group = $('#group_id').val();
                        
                    $('#add_discount_groupModal').modal('hide');
                    var table = $('#discounts_table').DataTable();
                    table.ajax.url("?r=discounts&f=getAllDiscounts_bygroups&p0="+current_store_id).load(function () {
                        if(action=="add"){
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                        }else{
                             table.row('.' + padDiscount(data[0]), {page: 'current'}).select();
                        }
                        $(".sk-circle-layer").hide();
                        
                        
                    },false);
                    
                    if (print_is_checked) {
                        print_barcode_group(current_group);
                    }
                    
                }
        });
    }));
}

function submitNewDiscount(action) {
    $("#add_discount_form").on('submit', (function (e) {
        e.preventDefault();
        $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=discounts&f=add_new_discount",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function (data)
                {
                    $('#add_discountModal').modal('hide');
                    var table = $('#discounts_table').DataTable();
                    table.ajax.url("?r=discounts&f=getAllDiscounts&p0="+current_store_id).load(function () {
                        if(action=="add"){
                            table.page('last').draw(false);
                            table.row(':last', {page: 'current'}).select();
                        }else{
                             table.row('.' + padDiscount(data[0]), {page: 'current'}).select();
                        }
                        $(".sk-circle-layer").hide();
                    },false);
                }
        });
    }));
}