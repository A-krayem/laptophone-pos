
var quotation_EDIT_MODE = 0;
var qoSalesperson = '';
var print_a4_pdf_version = null;
var quotations_table = null;
var all_quotations_hide_col = ""
var current_date = "today"
var current_store_id = null;
var currentSelectedItemsInQuotation = [];
var generating_invoice = 0;
var quotation_admin=0;

var changeEventEnabled = true;
var last_quotation_type=0;

var client_result=[];

function manual_quotation_generate_quotation() {
    swal({
        title: "Are you sure?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, create",
        closeOnConfirm: true
    },
        function (isConfirm) {
            if (isConfirm) {
                var quotation_id = 0;
                $.getJSON("?r=quotations&f=generate_empty_quotation", function (data) {
                    quotation_id = data;
                }).done(function () {
                    quotation_EDIT_MODE = 0;
                    create_quotation(quotation_id, []);
                });
            }
        });
}

function edit_manual_quotation(quotation_id) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=quotations&f=getQuotationItemsDetails&p0=" + quotation_id, function (data) {
        _data = data;
    }).done(function () {
        quotation_EDIT_MODE = 1;
        create_quotation(quotation_id, _data);
    });
}

function add_quotation_payment(){
    if($("#quotation_customer_id").val()==0 || $("#quotation_customer_id").val()==null){
        $.dialog({
            title: 'Alert!',
            content: 'You need to choose a customer first!',
        });

    }else{
        add_customer_payment_new($("#quotation_customer_id").val(),mask_clean($("#total_amount").val()),0,$("#quotation_id__").val());
    }
}

function print_last_payment_for_quotation(quotation_id){
    var width = 500;
    var height = 600;
    var left = (screen.width - width) / 2;
    var top = (screen.height - height) / 2;
    window.open(`?r=new_printing&f=print_last_payment_for_quotation&p0=${quotation_id}`, '_blank', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
}

function create_quotation(quotation_id, _data) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    $.getJSON("?r=quotations&f=get_needed_data_for_manual_creation", function (data) {


    }).done(function () {
        var modal_name = "modal_create_quotation_modal__";
        var modal_title = "New quotation";
        if (quotation_id > 0) {
            modal_title = "Edit Quotation";
        }
        hasPrevious = Object.keys(_data).length > 0
        
        var hide_open_gallery="";
        if(quotation_admin==1){
            hide_open_gallery="display:none;";
        }
        
        var content =
            `<div class="modal maxlarge" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="padding-top:5px;padding-bottom:5px;">
                        <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                    </div>
                    <input id="quotation_id__" value="${quotation_id}" type="hidden" />
                    <div class="modal-body" style="padding-top:2px;">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 ">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Client</label>&nbsp;<i onclick="addCustomer(\'add\',[],0)" class="glyphicon glyphicon-plus" style="font-size:18px;cursor:pointer;display:none"></i>
                                  <select id="quotation_customer_id"  class="form-control" style="width:100%;height:34px"  name="quotation_customer"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pl2" style="${hide_open_gallery}">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">&nbsp;</label>
                                    <button id="print_btn_ous" onclick="add_quotation_payment()" type="button" class="btn btn-success" style="width:100%">ADD PAYMENT</button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pl2" style="${hide_open_gallery}">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">&nbsp;</label>
                                    <button id="print_btn_ous" onclick="open_gallery(0)" type="button" class="btn btn-primary" style="width:100%">OPEN GALLERY</button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:5px;">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Quotation Type</label>
                                    <select onchange="update_quotation_type(${quotation_id})" id="quotation_customer_type"  class="form-control" style="width:100%;height:34px" name="quotation_customer_type">
                                        <option value="0">Select Type</option>
                                            <option value="1">Retail</option>
                                            <option value="2">Wholesale</option>
                                            <option value="3">2nd Wholesale</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pl2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Quotation Note</label>
                                    <input autocomplete="off" onchange="" id="quotation_note" value="${hasPrevious ? _data.quotation[0].note ?? "" : ""}" name="quotation_note" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2  pr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Search Item</label>
                                    <select id="search_item" class="form-control med_input" style="width:100%"   name="search_item"></select>
                                </div>
                            </div>
                            <div class="col-xs-2 plr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Total</label>
                                    <input readonly autocomplete="off" id="quotation_total"  value="${hasPrevious ? (_data.quotation[0].sub_total ?? "0") : ""}"  name="quotation_total" class="form-control med_input cleavesf3" />
                                </div>
                            </div>
                            <div class="col-xs-2 plr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Quotation Discount</label>
                                    <input oninput="calculate_total_amount_q()" autocomplete="off" id="quotation_discount" value="${hasPrevious ? (_data.quotation[0].discount ?? "0") : ""}"  name="quotation_discount" class="form-control med_input cleavesf3"  />
                                </div>
                            </div>
                            <div class="col-xs-2 plr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Total Amount</label>
                                    <input readonly autocomplete="off" id="total_amount" name="total_amount" class="form-control med_input cleavesf3"  value="${hasPrevious ? (_data.quotation[0].total ?? "0") : ""}" />
                                </div>
                              
                            </div>
                            <div class="col-xs-2  pl2">
                            <div class="form-group" style="margin-bottom:5px;">
                                <label for="code">Profit</label>
                                <input autocomplete="off" readonly id="quotation_profit"  value="${hasPrevious ? (_data.quotation[0].profit ?? "0") : ""}" name="quotation_profit" class="form-control med_input cleavesf3" />
                            </div>
                            </div>
                            <div class="col-xs-2  pl2">
                            <div class="form-group" style="margin-bottom:5px;">
                                <label for="code">Valid Till</label>
                                <input autocomplete="off" id="quotation_expiery_date" value="" name="quotation_expiery_date" class="form-control med_input" />
                            </div>
                            </div>
                            <div class="col-xs-2  pl2 d-none" style="display:none" >
                                <label for="quo_rate" style="width:100%">Rate</label>
                                <div class="input-group">
                                    <span class="input-group-addon" id="ex_b_r_l" style="width:40px;"><b>1 USD </b>= </span>
                                        <input type="text" class="form-control cleavesf3" name="quo_rate" id="quo_rate" value="${hasPrevious ? (_data.quotation[0].rate ?? "0") : ""}" placeholder="" style="padding-left:5px;padding-right:5px;font-weight:bold;" />
                                    <span class="input-group-addon" style="width:40px;"><b>LBP</b></span>
                                </div> 
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table style="width:100%" id="newquotation_table" class="table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:80px;">quotation Item ID</th>
                                            <th style="width:60px;">ID</th>
                                            <th style="width:80px;">Code</th>
                                            <th style="width:70px;">Barcode</th>
                                            <th >Description</th>
                                            <th style="width: 150px !important;">Additional Description</th>
                                            <th style="width:70px;text-align:center">Price</th>
                                            <th style="width:50px;text-align:center">Discount</th>
                                            <th style="width:50px;text-align:center">TAX</th>
                                            <th style="width:90px;text-align:center">Final Price</th>
                                            <th style="width:60px;text-align:center">Qty</th>
                                            <th style="width:60px;text-align:center">In Stock</th>
                                            <th style="width:80px;text-align:center">Total</th>
                                            <th style="width:80px;text-align:center">Profit</th>
                                            <th style="width:30px;text-align:center">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group" style="margin-top:5px;">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group" style="margin-top:5px;">
                                    <button id="print_btn_ous" onclick="print_last_payment_for_quotation(${quotation_id})" type="button" class="btn btn-info" style="width:100%">PRINT LAST PAYMENT ON POS 58mm</button>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group" style="margin-top:5px;">
                                    <button onclick="save_manual_quotation(${quotation_id})" type="button" class="btn btn-primary" style="width:100%">SAVE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        $("#" + modal_name).modal('hide');
        $("body").append(content);

        $(`#${modal_name}`).on('show.bs.modal', function (e) {

        });
        var currentDate = new Date();
        $("#quotation_expiery_date").datepicker({
            autoclose: true,
            dateFormat: 'yy-mm-dd',
            beforeShowDay: function (date) {
                if (date.toDateString() === currentDate.toDateString()) {
                    return [true, "bg-info", "Today"];
                }
                return [true, ""];
            }
        });
        if (hasPrevious) {
            if (_data.quotation[0].expiery_date && _data.quotation[0].expiery_date.split(" ")[0] != "0000-00-00")
                $("#quotation_expiery_date").datepicker("setDate", _data.quotation[0].expiery_date.split(" ")[0]);
        } else {
        }
        $(`#${modal_name}`).on('shown.bs.modal', function (e) {
            
            if(_data.length==0){
                $("#quotation_customer_type").val(0);
            }else{
               $("#quotation_customer_type").val(_data.quotation[0].quotation_type); 
            }
            
            $("#quotation_customer_type").select2();
            
            $("#quotation_customer_id").select2({
                ajax: {
                    url: '?r=customers&f=search',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    delay: 250,
                    dataType: 'json',
                    processResults: function (data) {
                        client_result=data;
                        return data;
                    }
                },
                placeholder: "Select Client",
                dropdownParent: $(`#${modal_name}`), allowClear: true
            });
            $('#quotation_customer_id').on('change', function () {
                var selectedValue = $('#quotation_customer_id').val();
                console.log("results");
                console.log(client_result);
                for(var i=0;i<client_result.results.length;i++){
                    if(client_result.results[i].id==selectedValue){
                        if(client_result.results[i].type>0){
                            $("#quotation_customer_type").val(client_result.results[i].type).trigger('change');
                        }
                        
                    }
                    
                }
                //var selectedInfo = client_result.find(item => item.id == selectedValue);
                //alert(selectedInfo.name);
            });
            
            
            $("#search_item").select2({
                ajax: {
                    url: '?r=items&f=search',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    delay: 250,
                    dataType: 'json'
                },
                placeholder: "Search by barcode, description",
                dropdownParent: $(`#${modal_name}`),
                allowClear: true
            });

            $("#search_item").on("change", () => {
                if($("#quotation_customer_type").val()==0){
                    $.dialog({
                        title: 'Alert!',
                        content: 'You need to choose quotation type first!',
                    });
                    return;
                }
                   
                itemid = $("#search_item").val()
                if (!itemid)
                    return
                if (!currentSelectedItemsInQuotation.includes(parseInt(itemid))) {

                    currentSelectedItemsInQuotation.push(parseInt(itemid));
                    manual_quotation_add_item_to_quotation(quotation_id, itemid);
                    $("#search_item").val("").trigger("change");
                } else {
                    $("#search_item").val("").trigger("change");
                }

            })

            var table_name = "newquotation_table";
            _cards_table__var = $(`#${table_name}`).DataTable({
                ajax: {
                    url: "?r=quotations&f=get_all_item_in_quotation&p0=" + quotation_id,
                    type: 'POST',
                    error: function (xhr, status, error) {
                    },
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "45vh",
                iDisplayLength: 100,
                aoColumnDefs: [
                    { "targets": [0], "searchable": false, "orderable": true, "visible": false },
                    { "targets": [1], "searchable": false, "orderable": true, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": false },
                    { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [7], "searchable": true, "orderable": true, "visible": true },//,"className": "dt-center"
                    { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [9], "searchable": true, "orderable": true, "visible": true },
                ],
                scrollCollapse: true,
                paging: false,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bAutoWidth: true,
                bSort: false,
                dom: '<"toolbar_new_quotation">frtip',
                initComplete: function (settings, json) {

                    currentSelectedItemsInQuotation = [];
                    if (typeof _data.quotation_items !== 'undefined') {

                        $('#recurring_id').selectpicker('val', _data.quotation[0].recurring);
                        if (_data.customer.length > 0) {
                            $("#quotation_customer_id").append(`<option value="${_data.customer[0].id}">${_data.customer[0].name ?? ''} ${_data.customer[0].middle_name ?? ''} ${_data.customer[0].last_name ?? ''}</option>`);
                            
                        }
                        _data.quotation_items.forEach(e => {

                            currentSelectedItemsInQuotation.push(parseInt(e.item_id));
                        })
                    }

                    cleaves_class(".cleavesf3", 3);
                    cleaves_class(".cleavesf2", 2);


                    check_if_free();
                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                fnDrawCallback: updateRowsManualquotation,
            });

            $(`#${table_name}`).DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
                $('#modal_create_quotation_modal__ .selected').removeClass("selected");
                $(this).addClass('selected');
            });


            $(`#${table_name}`).on('click', 'td', function () {
                if ($(this).index() == 4 || $(this).index() == 5) {
                    //return false;
                }
            });


            if (_data.currency_counnt == 1) {
                $("#rt_container").hide();
            }
            
          
        });

        $(`#${modal_name}`).on('hide.bs.modal', function (e) {
            if (e.namespace == "bs.modal") {
                $("#" + modal_name).remove();
            }
        });

        $(`#${modal_name}`).modal('show');
    });
}

function check_if_free(){
    var table = $('#newquotation_table').DataTable();
    table.rows().every(function() {
        var data = this.data();
        var cells = this.nodes().to$().find('td');
        var it_id=parseFloat($(cells[0]).html().split('-')[1]);
        
        if($(".itfp_"+it_id).val()==0){
            $(cells[0]).css('color', 'red');
            $(cells[1]).css('color', 'red');
            $(cells[2]).css('color', 'red');
            $(cells[3]).css('color', 'red');

            
            $(".itfp_"+it_id).css('color', 'red');
        }else{
            $(cells[0]).css('color', 'black');
            $(cells[1]).css('color', 'black');
            $(cells[2]).css('color', 'black');
            $(cells[3]).css('color', 'black');

            
             $(".itfp_"+it_id).css('color', 'black');
            
        }
    });
}

function update_quotation_type(quotation_id){
    if (changeEventEnabled) {
        
        if($("#quotation_customer_type").val()==0){
            changeEventEnabled=false;
            $.dialog({
                title: 'Alert!',
                content: 'This type is not allowed!',
            });

            $("#quotation_customer_type").val(last_quotation_type).trigger("change");
            changeEventEnabled=true;
            return;
        }

        $.getJSON("?r=quotations&f=update_quotation_type&p0=" + quotation_id + "&p1=" + $("#quotation_customer_type").val(), function (data) {

        }).done(function () {
            last_quotation_type=$("#quotation_customer_type").val();
        });
    }
    
}

function manual_quotation_add_item_to_quotation(quotation_id, item_id) {
    $.getJSON("?r=quotations&f=addItemsToQuotation_manual&p0=" + quotation_id + "&p1=" + item_id + "&p2=" + $("#quotation_customer_id").val(), function (data) {

    }).done(function () {
        var table = $('#newquotation_table').DataTable();
        table.ajax.url("?r=quotations&f=get_all_item_in_quotation&p0=" + quotation_id).load(function () {
            calculate_total_amount_q();
            cleaves_class(".cleavesf3", 3);
            cleaves_class(".cleavesf2", 2);

            if ($("#quotation_customer_id").val() > 0) {
                $(".history_prices").show();
            }
            
            check_if_free();
        }, false);
    });
}


function update_total_quotation(id, quotation_id) {
    /*
     * update quotation items in db
     */
    
   

    var _data = [];
    $.getJSON("?r=quotations&f=save_manual_quotation_items&p0=" + id + "&p1=" + $("#inv_it_price_" + id).val().replace(/[^0-9\.\-]/g, '') + "&p2=" + $("#inv_it_dis_" + id).val().replace(/[^0-9\.\-]/g, '') + "&p3=" + $("#mivat_" + id).val() + "&p4=" + $("#inv_it_qty_" + id).val() + "&p5=" + $("#quotation_note").val() + "&p6=" + $("#quo_rate").val().replace(/[^0-9\.\-]/g, ''), function (data) {
        _data = data;
    }).done(function (_data) {
        var price_per_unit = 0;
        if ($("#inv_it_price_" + id).val().replace(/[^0-9\.\-]/g, '').length > 0) {
            price_per_unit = $("#inv_it_price_" + id).val().replace(/[^0-9\.\-]/g, '');
        }
        $("#quo_it_profit_" + id).val(_data[0].profit);
        var discount_per_unit = 0;
        if ($("#inv_it_dis_" + id).val().replace(/[^0-9\.\-]/g, '').length > 0) {
            discount_per_unit = $("#inv_it_dis_" + id).val().replace(/[^0-9\.\-]/g, '');
        }

        var vat = 1;
        if ($("#mivat_" + id).val() == "1") {
            vat = parseFloat(_data[0].vat_value);

        }
        $(`#qty_in_store_${id}`).val(_data[0].qty_in_store);

        var pertmp = (1 - parseFloat(discount_per_unit) / 100);
        var total_price = 0;
        if (vat == 0) {
            total_price = price_per_unit * (Math.round(pertmp * 1000) / 1000) * $("#inv_it_qty_" + id).val();
            $("#fp_" + id).val(price_per_unit * (Math.round(pertmp * 1000) / 1000));
        } else {
            total_price = price_per_unit * (Math.round(pertmp * 1000) / 1000) * vat * $("#inv_it_qty_" + id).val();
            $("#fp_" + id).val(price_per_unit * (Math.round(pertmp * 1000) / 1000) * vat);
        }


        //$("#fp_"+id).val(price_per_unit*(Math.round(pertmp * 1000) / 1000)*vat);
        $("#inv_it_tp_" + id).val(total_price);


        cleaves_class(".cleavesf3", 3);
        cleaves_class(".cleavesf2", 2);

        calculate_total_amount_q();
        
        check_if_free();

    });

}

function calculate_total_amount_q() {

    var total_amount = 0;
    var profit_amount = 0
    $(".total_per_item").each(function (i) {
        total_amount += (parseFloat($(this).val().replace(/[^0-9\.\-]/g, '')));
    });
    $(".single_item_profit").each(function (i) {
        profit_amount += (parseFloat($(this).val().replace(/[^0-9\.\-]/g, '')));
    })
    //alert(total_amount);

    var quotation_discount = 0;
    if ($("#quotation_discount").val() != "" && $("#quotation_discount").val().replace(/[^0-9\.\-]/g, '').length > 0) {
        quotation_discount = $("#quotation_discount").val().replace(/[^0-9\.\-]/g, '');
    }

    $("#quotation_total").val(total_amount);

    $("#quotation_profit").val(profit_amount - quotation_discount);
    $("#total_amount").val(total_amount - quotation_discount);
    cleaves_class(".cleavesf3", 3);
}

function updateRowsManualquotation() {
    var table = $('#newquotation_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        table.cell(index, 14).data(`<i title="Delete" class="glyphicon glyphicon-trash red" onclick="delete_item_from_manual_quotation('${parseInt(table.cell(index, 0).data())}')" style="font-size:16px;cursor:pointer" ></i>`);
    }
}

function update_ad_item_description(quotation_item_id) {
    $.getJSON("?r=quotations&f=update_add_item_description&p0=" + quotation_item_id + "&p1=" + $("#addesc_" + quotation_item_id).val(), function (data) {

    }).done(function () {

    });
}

function refresh_quotations_table() {
    $(".sk-circle-layer").show();
    $('#quotations_table').DataTable().ajax.url(`?r=quotations&f=getAllQuotationsDateRange&p0=${$("#store_list").val() ?? ""}&p1=${$("#qosalesDate").val() ?? ""}&p2=${$("#filter_qoofficial").val() ?? ""}&p3=${$("#filter_qoSalesperson").val() ?? ""}&p4=${$("#filter_qoCustomer").val() ?? ""}&p5=${$("#filter_qo_include_items").val() ?? ""}`).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#quotations_table').DataTable().columns.adjust().draw();
        }, 100);
        //setTimeout(function(){$('#quotations_table').DataTable().columns.adjust().draw();},1000);
    }, false);
}
function delete_item_from_manual_quotation(id) {

    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=quotations&f=delete_item_from_manual_quotation&p0=" + id, function (data) {

    }).done(function () {
        if ($("#quotations_table").length > 0) {
            var table = $('#quotations_table').DataTable();
            table.ajax.url("?r=quotations&f=getAllQuotationsDateRange&p0=" + (current_store_id ?? "") + "&p1=" + (current_date ?? "") + "&p2=" + ($("#filter_qoofficial").val() ?? "") + "&p3=" + ($("#filter_qoSalesperson").val() ?? "") + `&p4=${$("#filter_qoCustomer").val() ?? ""}&p5=${$("#filter_qo_include_items").val() ?? ""}`).load(function () {
                table.page('last').draw(false);
                $('#quotations_table').closest('.dataTables_scrollBody').scrollTop($('#quotations_table').closest('.dataTables_scrollBody')[0].scrollHeight);
                $(".sk-circle-layer").hide();
            }, false);
        }

        var table = $('#newquotation_table').DataTable();
        table.ajax.url("?r=quotations&f=get_all_item_in_quotation&p0=" + $("#quotation_id__").val()).load(function (data) {
            currentSelectedItemsInQuotation = [];
            data.data.forEach(e => {
                currentSelectedItemsInQuotation.push(parseInt(e[14]));
            })
            setTimeout(function () { calculate_total_amount_q(); }, 1000);
            cleaves_class(".cleavesf3", 3);
            cleaves_class(".cleavesf2", 2);
        }, false);
        if ($("#customers_statement_table").length > 0) {
            var table = $('#customers_statement_table').DataTable();
            table.ajax.url("?r=customers&f=get_customer_statement&p0=" + $("#customers_list").val()).load(function () {
                $(".sk-circle-layer").hide();
            }, false);
        }

    });
}

function save_manual_quotation(quotation_id) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    var change_date_chk = 0;
    if ($("#change_date_chk").is(":checked")) {
        change_date_chk = 1;
    }

    $.getJSON(`?r=quotations&f=save_manual_quotation&p0=${quotation_id}&p1=${$("#quotation_customer_id").val()}&p2=${$("#quotation_discount").val().replace(/[^0-9\.\-]/g, '')}&p3=${$("#quotation_note").val()}&p4=${$("#quo_rate").val().replace(/[^0-9\.\-]/g, '')}&p5=${$("#quotation_expiery_date").val()}`, function (data) {

    }).done(function () {

        if ($("#quick_quotations_table").length > 0) {
            var table = $('#quick_quotations_table').DataTable();
            table.ajax.url("?r=quick_display&f=get_all_quotations_quick&p0=" + $("#quick_daterange").val() + "&p1=" + $("#quick_status").val()).load(function () {
                $("." + pad_quotation(quotation_id)).addClass("selected");
                $(".sk-circle-layer").hide();
            }, false);
        }

        if ($("#quotations_table").length > 0) {

            var table = $('#quotations_table').DataTable();
            table.ajax.url("?r=quotations&f=getAllQuotationsDateRange&p0=" + (current_store_id ?? "") + "&p1=" + (current_date ?? "") + "&p2=" + ($("#filter_qoofficial").val() ?? "") + "&p3=" + ($("#filter_qoSalesperson").val() ?? "") + `&p4=${$("#filter_qoCustomer").val() ?? ""}&p5=${$("#filter_qo_include_items").val() ?? ""}`).load(function () {
                table.page('last').draw(false);
                if (quotation_EDIT_MODE == "0") {
                    $('#quotations_table').closest('.dataTables_scrollBody').scrollTop($('#quotations_table').closest('.dataTables_scrollBody')[0].scrollHeight);
                }

                setTimeout(function () {
                    $('#quotations_table').DataTable().columns.adjust().draw();
                }, 100);

                $(".sk-circle-layer").hide();
            }, false);

        }



        if ($("#cutomer_quotation_table").length > 0) {
            var table = $('#cutomer_quotation_table').DataTable();
            table.ajax.url("?r=quotations&f=getquotationsMustPay&p0=" + current_store_id).load(function () {
                $("." + pad_quotation(quotation_id)).addClass("selected");
                $(".sk-circle-layer").hide();
            }, false);
        }

        if ($("#cutomer_quotation_table___").length > 0) {
            var table = $('#cutomer_quotation_table___').DataTable();
            table.ajax.url("?r=quotations&f=getquotationsOfCustomers&p0=0").load(function () {
                $("." + pad_quotation(quotation_id)).addClass("selected");
                $(".sk-circle-layer").hide();
            }, false);
        }

        $("#modal_create_quotation_modal__").modal("hide");
        $(".sk-circle-layer").hide();
    });
}

function delete_manual_quotation(id) {
    swal({
        title: "Are you sure?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },
        function (isConfirm) {
            if (isConfirm) {
                $(".sk-circle").center();
                $(".sk-circle-layer").show();
                $.getJSON("?r=quotations&f=delete_quotation&p0=" + id, function (data) {

                }).done(function () {
                    var table = $('#quotations_table').DataTable();
                    table.ajax.url("?r=quotations&f=getAllQuotationsDateRange&p0=" + (current_store_id ?? "") + "&p1=" + (current_date ?? "") + "&p2=" + ($("#filter_qoofficial").val() ?? "") + "&p3=" + ($("#filter_qoSalesperson").val() ?? "") + `&p4=${$("#filter_qoCustomer").val() ?? ""}&p5=${$("#filter_qo_include_items").val() ?? ""}`).load(function () {
                        $(".sk-circle-layer").hide();
                        setTimeout(function () {
                            $('#quotations_table').DataTable().columns.adjust().draw();
                        }, 100);
                    }, false);
                });
            }
        });
}


function initQuotations(admin) {
    
    if (!navigator.onLine) {
            //swal("Check your internet connection");
            //return;
        }
        
    quotation_admin=admin;
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    quotation_EDIT_MODE = 0;
    qoSalesperson = '';
    print_a4_pdf_version = null;
    quotations_table = null;
    all_quotations_hide_col = ""
    current_date = "today"
    current_store_id = null;
    _all_customers = [];
    $.getJSON("?r=quotations&f=get_needed_data", function (data) {
        $(".sk-circle-layer").hide();
        all_currencies = [];
        $.each(data.currencies, function (key, val) {
            all_currencies.push({
                id: val.id,
                name: val.name,
                symbole: val.symbole,
                system_default: val.system_default,
                rate_to_system_default: val.rate_to_system_default
            });

            print_a4_pdf_version = data.print_a4_pdf_version;
        });
        
       
        //if(data.employees.lenght>1){
            qoSalesperson += '<option selected value="0" title="All Users">All Users</option>';
        //}
        
        
        $.each(data.employees, function (key, val) {
            qoSalesperson += '<option value="' + val.id + '" title="' + val.username + '">' + val.username + '</option>';
        });
        _all_customers = data.customers

        all_quotations_hide_col = data.all_quotations_hide_col.split(',');

    }).done(function () {
        modal_name = "quotationsModal"
        modal_title = "<i class='icon-invoice'></i> All Quotations"
        var content =
            `<div class="modal" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">
                <div class="modal-dialog" style="margin-top:0!important" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                        </div>
                    
                        <div class="modal-body" style="padding-top:2px;">
                        <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <table id="quotations_table" class="table table-striped table-bordered" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px !important;">QUO. ID</th>
                                        <th style="width: 75px !important;">Customer ID</th>
                                        <th>Client</th>
                                        <th>User</th>
                                        <th style="width: 100px !important;">Date</th>
                                        <th style="width: 70px !important;" >Sub Total</th>
                                        <th style="width: 50px !important;">Discount</th>
                                        <th style="width: 50px !important;">Vat</th>
                                        <th style="width: 70px !important;">Total</th>
                                        <th style="width: 90px !important;">Valid Till</th>
                                        <th style="width: 90px !important;">Type</th>
                                        <th style="width: 100px !important;">Actions</th>
                                        <th>Deleted</th>
                                        <th>Expiery Date Passed</th>
                                        <th>Invoice ID</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>quotation ID</th>
                                        <th>Customer ID</th>
                                        <th>Customer Name</th>
                                        <th>User</th>
                                        <th>Quotation date</th>
                                        <th>Sub Total</th>
                                        <th>Discount</th>
                                        <th>Vat</th>
                                        <th>Total</th>
                                        <th ></th>
                                        <th >Type</th>
                                        <th></th>
                                        <th>Deleted</th>
                                        <th>Expiery Date Passed</th>
                                        <th >Invoice ID</th>
                                        <th>Role</th>
                                    </tr>
                                </tfoot>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                        </div>
                   
                    </div>
                </div>
            </div>`;
        $("#" + modal_name).remove();
        $("body").append(content);

        $(`#${modal_name}`).modal("show");
        getquotationsOfCustomer(0);
    }).fail(function() {
        swal("Check your internet connection");
    }).always(function() {
        $(".sk-circle-layer").hide();
        
    });;; ;;

}

function getquotationsOfCustomer() {
    var search_fields = [0, 1, 2, 3, 4, 5, 6, 7, 8,9,10];
    var index = 0;
    $('#quotations_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });

    quotations_table = $('#quotations_table').dataTable({
        ajax: "?r=quotations&f=getAllQuotationsDateRange&p0=" + (current_store_id ?? "") + "&p1=" + (current_date ?? "") + "&p2=3&p3=" + ($("#filter_qoSalesperson").val() ?? "") + `&p4=${$("#filter_qoCustomer").val() ?? ""}&p5=${$("#filter_qo_include_items").val() ?? ""}`,
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0],
            "searchable": true,
            "orderable": true,
            "visible": true
        },
        {
            "targets": [1],
            "searchable": true,
            "orderable": true,
            "visible": false
        },
        {
            "targets": [2],
            "searchable": true,
            "orderable": true,
            "visible": true
        },
        {
            "targets": [3],
            "searchable": true,
            "orderable": true,
            "visible": true
        },
        {
            "targets": [4],
            "searchable": true,
            "orderable": true,
            "visible": true
        },
        {
            "targets": [5],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [6],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [7],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [8],
            "searchable": false,
            "orderable": true,
            "visible": true
        }, {
            "targets": [9],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [10],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [11],
            "searchable": false,
            "orderable": false,
            "visible": true
        }, {
            "targets": [12],
            "searchable": false,
            "orderable": false,
            "visible": false
        }, {
            "targets": [13],
            "searchable": false,
            "orderable": false,
            "visible": false
        }, {
            "targets": [14],
            "searchable": false,
            "orderable": false,
            "visible": false
        }, {
            "targets": [15],
            "searchable": false,
            "orderable": false,
            "visible": false
        }
        ],
        scrollY: '44vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        dom: '<"toolbarqo">frtip',
        initComplete: function (settings) {
            $("#quotations_table").show();

            var table = $('#quotations_table').DataTable();



            $("div.toolbarqo").html('\n\
                <div class="row" style="margin-top:10px;">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                            <button type="button" class="btn btn-primary" onclick="manual_quotation_generate_quotation()" style="width:100%;">Create Quotation</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-10 col-md-10 col-sm-12 pl2 pr2" >\n\
                        &nbsp;\n\
                    </div>\n\
                </div>\n\
                <div class="row" style="margin-top:10px;">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                            <input id="qosalesDate" class="form-control datepicker" type="text" placeholder="Select date" style="cursor:pointer; width:100%;">\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <select data-width="100%" id="filter_qoofficial" class="selectpicker" onchange="refresh_quotations_table()">\n\
                                <option value="0" title="All Quotations">All Quotations</option>\n\
                                <option value="1" title="Deleted">Deleted</option>\n\
                                <option value="2" title="Expired">Expired</option>\n\
                                <option value="3" title="Pending" selected>Pending</option>\n\
                                <option value="4" title="Converted">Converted</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <select data-live-search="true" data-width="100%" id="filter_qoSalesperson" class="selectpicker" onchange="refresh_quotations_table()">\n\
                                ' + qoSalesperson + '\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                            <select  id="filter_qoCustomer" style="width:100%"  class="form-control" onchange="refresh_quotations_table()">\n\
                            </select>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-sm-12 pl2" >\n\
                            <select  id="filter_qo_include_items" multiple style="width:100%"  class="form-control" onchange="refresh_quotations_table()">\n\
                            </select>\n\
                    </div>\n\
                </div>\n\
                ');



            $('.selectpicker').selectpicker();
            $("#filter_qoCustomer").select2({
                ajax: {
                    url: '?r=customers&f=search',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    delay: 250,
                    dataType: 'json'
                },
                placeholder: "All Clients",
                dropdownParent: $("#quotationsModal"), allowClear: true
            });
            $("#filter_qo_include_items").select2({
                ajax: {
                    url: '?r=items&f=search',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    delay: 250,
                    dataType: 'json'
                },
                placeholder: "Search By Items",
                dropdownParent: $("#quotationsModal"), allowClear: true
            });

            var defaultStart = moment().startOf('month');
            var end = moment();

            $("#qosalesDate").daterangepicker({
                //dateLimit:{month:12},
                startDate: defaultStart,
                endDate: end,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            $("#qosalesDate").change(function () {

                refresh_quotations_table()
            });

            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: updateQoRows,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //$(nRow).addClass(aData[0]);
        },
    });

    $('#quotations_table').on('page.dt', function () {
        $("#tab_toolbar button.blueB").addClass("disabled");
        updateQoRows();
    });

    $('#quotations_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#quotations_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}
function generate_invoice_from_quotation(quotation_id) {
    if (generating_invoice == 1)
        return;
    generating_invoice = 1;
    swal({
        title: `Are you sure you want to convert quotation: ${quotation_id} to an invoice?`,
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Yes, Convert",
        closeOnConfirm: true
    },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "post",
                    url: `?r=quotations&f=generateInvoiceFromQuotation&p0=${quotation_id}`,
                    dataType: "json",
                    success: function (response) {
                        refresh_quotations_table();
                        generating_invoice = 0;
                    }
                });
            } else {

                console.log("not generating anymore");
                generating_invoice = 0;
            }
        });

}


function updateQoRows() {
    var table = $('#quotations_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();

        if (table.cell(index, 12).data() == "0") {
            gen_inv_btn = ` <i class="icon-invoice" title="Convert to invocie" style="font-size:18px;cursor:pointer" onclick="generate_invoice_from_quotation(${parseInt(table.cell(index, 0).data().split("-")[1])})"></i>`;
            delete_btn = "&nbsp;<i title='Delete' class='glyphicon glyphicon-trash red' style='font-size:18px;cursor:pointer' onclick='delete_manual_quotation(" + parseInt(table.cell(index, 0).data().split("-")[1]) + ")'></i>";
            if (print_a4_pdf_version && print_a4_pdf_version == 1) {

                table.cell(index, 11).data('<i title="print" class="glyphicon glyphicon-print" onclick="open_quotation_to_print(\'' + parseInt(table.cell(index, 0).data().split("-")[1]) + '\')" style="font-size:18px;cursor:pointer" ></i>&nbsp;&nbsp;<i title="Edit" class="glyphicon glyphicon-edit" onclick="edit_manual_quotation(\'' + parseInt(table.cell(index, 0).data().split("-")[1]) + '\')" style="font-size:18px;cursor:pointer" ></i>' + delete_btn + gen_inv_btn);
            } else {
                // OLD print
                // <i title="print" class="glyphicon glyphicon-print" onclick="print_sheet(\'' + parseInt(table.cell(index, 0).data().split("-")[1]) + '\')" style="font-size:18px;cursor:pointer" ></i>
                table.cell(index, 11).data('<i title="Edit" class="glyphicon glyphicon-edit" onclick="edit_manual_quotation(\'' + parseInt(table.cell(index, 0).data().split("-")[1]) + '\')" style="font-size:18px;cursor:pointer" ></i> ' + ' <i title="print" class="glyphicon glyphicon-print" onclick="open_quotation_to_print(\'' + parseInt(table.cell(index, 0).data().split("-")[1]) + '\')" style="font-size:18px;cursor:pointer" ></i>' + delete_btn + gen_inv_btn);
            }
        }
        if (table.cell(index, 12).data() == "1") {
            table.cell(index, 11).data(`<span class="redClass" style="font-weight:700">DELETED</span>`);
            $(table.row(k).node()).addClass("text-danger");
        }
        if (table.cell(index, 13).data() == "1") {
            $(table.cell(index, 9).node()).addClass("text-danger").css("font-weight", "800")

        }
        if (table.cell(index, 14).data() > 0) {
            if(table.cell(index, 15).data()==1){
                table.cell(index, 11).data(`<span class="text-success" style="font-weight:700; cursor:pointer" onclick="edit_manual_invoice(${table.cell(index, 14).data()})">INV-${table.cell(index, 14).data()}</span>`);
            }else{
                table.cell(index, 11).data(`<span class="text-success" style="font-weight:700">INV-${table.cell(index, 13).data()}</span>`);
            }

        }
        // var delete_btn = "";
        // if (table.cell(index, 17).data() == "0") {
        // }


        // if (table.cell(index, 17).data() == "1") {
        //     table.cell(index, 16).data("");
        // }
    }
}
function open_quotation_to_print(quotation_id) {
    window.open(`?r=new_printing&f=print_quotation&p0=${quotation_id}`, "_blank")
}


