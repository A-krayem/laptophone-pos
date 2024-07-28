
var COMPLEXITEMS_EDIT_MODE = 0;
var ciSalesperson = '';
var print_a4_pdf_version = null;
var complex_items_table = null;
var current_date = "today"
var currentSelectedItemsinComplexItem = [];
var generating_invoice = 0;

var categories=[];
var sub_categories=[];

function manual_complex_itemsgenerate_item() {
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
                var complex_item_id = 0;
                $.getJSON("?r=complex_items&f=generate_empty_complex_item", function (data) {
                    complex_item_id = data.new_id;
                    sub_categories=data.subcategories;
                    categories=data.categories;
                }).done(function () {
                    COMPLEXITEMS_EDIT_MODE = 0;
                    create_complex_item(complex_item_id, []);
                });
            }
        });
}

function edit_manual_complex_item(complex_item_id) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON(`?r=complex_items&f=getComplexItem_ItemsDetails&p0=${complex_item_id}`, function (data) {
        _data = data;
        sub_categories=data.subcategories;
        categories=data.categories;
    }).done(function () {
        COMPLEXITEMS_EDIT_MODE = 1;
        create_complex_item(complex_item_id, _data);
    });
}

function cp_category_changed(default_sub_category_id){
    $("#cp_subcategory").empty();
    var sub_categories_options="";
    for(var i=0;i<sub_categories.length;i++){
        if(sub_categories[i].parent==$("#cp_category").val()){
            if(default_sub_category_id==sub_categories[i].id){
                sub_categories_options+="<option selected value='"+sub_categories[i].id+"'>"+sub_categories[i].description+"</option>";
            }else{
                sub_categories_options+="<option value='"+sub_categories[i].id+"'>"+sub_categories[i].description+"</option>";
            }
        }
    }
    $("#cp_subcategory").append(sub_categories_options);
    $("#cp_subcategory").selectpicker("refresh");
    
}

function create_complex_item(complex_item_id, _data) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    
    var categories_options="";
    for(var i=0;i<categories.length;i++){
        categories_options+="<option value='"+categories[i].id+"'>"+categories[i].name+"</option>";
    }

    var modal_name = "modal_create_complex_item_modal__";
    var modal_title = "Compose New Item";
    if (complex_item_id > 0) {
        modal_title = "Edit Composed Item";
    }
    hasPrevious = Object.keys(_data).length > 0
    var content =
        `<div class="modal maxlarge" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                    </div>
                    <input id="complex_item_id__" value="${complex_item_id}" type="hidden" />
                    <div class="modal-body" style="padding-top:2px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:rgb(252 223 83)">
                                <b>Note:</b> When creating a new item composer, the system will automatically generate a corresponding regular item in the inventory associated with the item composer created.
                            </div>        
                        </div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Composed Item Name</label>
                                    <input autocomplete="off" id="complex_item_name"  value="${hasPrevious ? _data.complex_item.name ?? "" : ""}"  name="complex_item_name" class="form-control med_input" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="cp_category">Category</label>
                                    <select id="cp_category" onchange="cp_category_changed(0)" class="form-control med_input" style="width:100%"   name="cp_category">${categories_options}</select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="cp_subcategory">SubCategory</label>
                                    <select id="cp_subcategory" class="form-control med_input" style="width:100%"   name="cp_subcategory"></select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 pr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Barcode</label>
                                    <input autocomplete="off" id="complex_item_barcode"  value="${hasPrevious ? _data.complex_item.barcode ?? "" : ""}"  name="complex_item_barcode" class="form-control med_input" />
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 plr2">
                                <div class="form-group" >
                                    <button class="btn btn-primary" name="ci_barcode_generator" id="ci_barcode_generator" onclick="generate_new_barcode_for_ci()" style="margin-top:30px;">Generate</button>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 pl2" style="display:none">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Composed Item Note</label>
                                    <input autocomplete="off" onchange="update_complex_item_frontEnd(${complex_item_id})" id="complex_item_note" value="${hasPrevious ? _data.complex_item.note ?? "" : ""}" name="complex_item_note" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2 pr2">
                                <div class="form-group">
                                    <label for="code">Cost</label>
                                    <input readonly autocomplete="off" id="complex_item_cost"  value="${hasPrevious ? (_data.complex_item.cost ?? "0") : ""}"  name="complex_item_total" class="form-control med_input cleavesf3" />
                                </div>
                            </div>                            
                            <div class="col-xs-2 plr2">
                                <div class="form-group">
                                    <label for="code">Price</label>
                                    <input readonly autocomplete="off" id="complex_item_total"  value="${hasPrevious ? (_data.complex_item.sub_total ?? "0") : ""}"  name="complex_item_total" class="form-control med_input cleavesf3" />
                                </div>
                            </div>
                            <div class="col-xs-2 plr2">
                                <div class="form-group">
                                    <label for="code">Discount</label>
                                    <input readonly oninput="update_complex_item_frontEnd(${complex_item_id})" autocomplete="off" id="complex_item_discount" value="${hasPrevious ? (_data.complex_item.discount ?? "0") : ""}"  name="complex_item_discount" class="form-control med_input cleavesf3"  />
                                </div>
                            </div>
                            <div class="col-xs-2 plr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Final Price</label>
                                    <input oninput="update_complex_item_frontEnd(${complex_item_id},true)" autocomplete="off" id="total_amount" name="total_amount" class="form-control med_input cleavesf3"  value="${hasPrevious ? (_data.complex_item.total ?? "0") : ""}" />
                                </div>
                            </div>
                            <div class="col-xs-2  pl2">
                            <div class="form-group" style="margin-bottom:5px;">
                                <label for="code">Profit</label>
                                <input autocomplete="off" readonly id="complex_item_profit"  value="${hasPrevious ? (_data.complex_item.profit ?? "0") : ""}" name="complex_item_profit" class="form-control med_input cleavesf3" />
                            </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6  pr2">
                                <div class="form-group" style="margin-bottom:5px;">
                                    <label for="code">Search Item</label>
                                    <select id="search_item" class="form-control med_input" style="width:100%"   name="search_item"></select>
                                </div>
                            </div>
                        </div>    
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <table style="width:100%" id="newcomplexitems_table" class="table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:80px;">Composed Item ID</th>
                                            <th style="width:60px;">Item ID</th>
                                            <th style="width:70px;">Barcode</th>
                                            <th >Description</th>
                                            <th style="width: 150px !important;">Additional Description</th>
                                            <th style="width:70px;text-align:center">Unit Cost</th>
                                            <th style="width:70px;text-align:center">Unit Price</th>
                                            <th style="width:60px;text-align:center">Qty</th>
                                            <th style="width:80px;text-align:center">Total Cost</th>
                                            <th style="width:80px;text-align:center">Total Amount</th>
                                            <th style="width:80px;text-align:center">Profit</th>
                                            <th style="width:30px;text-align:center">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row" style="margin-top:20px">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                        <div style="display:flex;justify-content:end">
                        <button class="btn btn-primary" onclick="update_complex_item(${complex_item_id})">Save </button></div>
                        </div></div>
                    </div>
                </div>
            </div>
        </div>`;
    $("#" + modal_name).remove();
    $("body").append(content);

    $(`#${modal_name}`).on('show.bs.modal', function (e) {

    });
    $(`#${modal_name}`).on('shown.bs.modal', function (e) {
        
        $("#cp_category").selectpicker();
      
        if(typeof _data !== 'undefined' && typeof _data.complex_item !== 'undefined' && typeof _data.complex_item.category_id !== 'undefined' && _data.complex_item.category_id>0){//
            
            $("#cp_category").val(_data.complex_item.category_id);
            $("#cp_category").selectpicker("refresh");
            cp_category_changed(_data.complex_item.subcategory_id);
        }else{
             cp_category_changed(0);
        }

        
        function formatState(state) {
            if (!state.id) {
              return state.text;
            }
            var $state = $(state.element).data('html');
            return $state ? $($state) : state.text;
        }
    
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
            allowClear: true,
            templateResult: formatState,
            templateSelection: formatState,
            escapeMarkup: function(markup) {
              return markup;
            }
        });

        $("#search_item").on("change", () => {
            itemid = $("#search_item").val()
            if (!itemid)
                return
            if (!currentSelectedItemsinComplexItem.includes(parseInt(itemid))) {

                currentSelectedItemsinComplexItem.push(parseInt(itemid));
                manual_add_item_to_complex_items(complex_item_id, itemid);
                $("#search_item").val("").trigger("change")
            } else {
                $("#search_item").val("").trigger("change")
            }

        })

        var table_name = "newcomplexitems_table";
        _cards_table__var = $(`#${table_name}`).DataTable({
            ajax: {
                url: `?r=complex_items&f=get_all_items_in_complex_item&p0=${complex_item_id}`,
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
                { "targets": [4], "searchable": true, "orderable": true, "visible": false },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },//,"className": "dt-center"
                { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                { "targets": [9], "searchable": true, "orderable": true, "visible": true },
                { "targets": [10], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            bSort: false,
            dom: '<"toolbar_new_complex_item">frtip',
            initComplete: function (settings, json) {

                currentSelectedItemsinComplexItem = [];
                if (typeof _data.complex_item_items !== 'undefined') {


                    _data.complex_item_items.forEach(e => {

                        currentSelectedItemsinComplexItem.push(parseInt(e.item_id));
                    })
                }
                cleaves_class(".cleavesf5", 5);
                cleaves_class(".cleavesf3", 3);
                cleaves_class(".cleavesf2", 2);

                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: updateRowsManualComplexItem,
        });

        $(`#${table_name}`).DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
            $('#modal_create_complex_item_modal__ .selected').removeClass("selected");
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
}
function update_complex_item_frontEnd(complex_item_id, basedOnFinalAmmout = false) {
    if (basedOnFinalAmmout) {

        //the user change the final ammount, we change the discount and then apply the update

        var total_amount = 0;
        $(".total_per_item").each(function (i) {
            total_amount += (parseFloat($(this).val().replace(/[^0-9\.\-]/g, '')));
        });
        discount = total_amount - parseFloat($("#total_amount").val().replace(/[^0-9\.\-]/g, ''))

        $("#complex_item_discount").val(discount)

    }
    calculate_ci_total_amount()
}
function update_complex_item(complex_item_id) {

    $.ajax({
        type: "post",
        url: `?r=complex_items&f=updateCI&p0=${complex_item_id}`,
        data: {
            discount: $("#complex_item_discount").val(),
            note: $("#complex_item_note").val(),
            name: $("#complex_item_name").val(),
            barcode: $("#complex_item_barcode").val(),
            category_id: $("#cp_category").val(),
            subcategory_id: $("#cp_subcategory").val()
        },
        dataType: "json",
        success: function (response) {
            
           
            $('#complex_items_table').DataTable().ajax.url(`?r=complex_items&f=getAllComplexItemsDateRange&p0=${$("#cisalesDate").val() ?? ""}&p2=${$("#filter_complexItemStatus").val() ?? ""}&p3=${$("#filter_complexItemSalesperson").val() ?? ""}&p4=${$("#filter_ci_include_items").val() ?? ""}`).load(function () {
                $(".sk-circle-layer").hide();
                setTimeout(function () {
                    //$('#complex_items_table').DataTable().columns.adjust().draw();
                }, 100);
            }, false);
            calculate_ci_total_amount();
             $("#modal_create_complex_item_modal__").modal('hide');
            
             if($('#items_table').length>0){
               
                var table = $('#items_table').DataTable();
                table.ajax.url('?r=items&f=getAllItems&p0='+current_store_id+"&p1="+current_category_id+"&p2="+current_subcategory_id+"&p3="+item_boxex+"&p4="+current_supplier_id+"&p5="+$("#stock_status").val()).load(function () {
                    
                },false);
                
            }
            
        }
    });
}
function manual_add_item_to_complex_items(complex_item_id, item_id) {
    $.getJSON(`?r=complex_items&f=addItemsTocomplexitem_manual&p0=${complex_item_id}&p1=${item_id}`, function (data) {

    }).done(function () {
        var table = $('#newcomplexitems_table').DataTable();
        table.ajax.url(`?r=complex_items&f=get_all_items_in_complex_item&p0=${complex_item_id}`).load(function () {
            calculate_ci_total_amount();
            cleaves_class(".cleavesf3", 3);
            cleaves_class(".cleavesf2", 2);

        }, false);
    });
}


function update_ci_item_data(ci_item_id) {
    /*
     * update Complex item  items in db
     */

    $.ajax({
        type: "post",
        url: `?r=complex_items&f=updateSingleItem&p0=${ci_item_id}`,
        data: {
            description: $(`#ci_item_desc_${ci_item_id}`).val(),
            price: $(`#ci_item_price_${ci_item_id}`).val(),
            qty: $(`#ci_qty_${ci_item_id}`).val()
        },
        dataType: "json",
        success: function (response) {
            $(`#ci_item_desc_${ci_item_id}`).val(response.ci_item.additional_description)
            $(`#ci_item_price_${ci_item_id}`).val(response.ci_item.selling_price)
            $(`#ci_qty_${ci_item_id}`).val(response.ci_item.qty)
            $(`#ci_item_profit_${ci_item_id}`).val(response.ci_item.profit)
            $(`#ci_item_tp_${ci_item_id}`).val(response.ci_item.final_price)
            $(`#ci_item_tc_${ci_item_id}`).val(response.ci_item.final_cost)
            cleaves_class(".cleavesf3", 3);
            cleaves_class(".cleavesf2", 2);

            calculate_ci_total_amount();
        }
    });

}
function generate_new_barcode_for_ci() {
    generateBarcode(0);
    //$('#complex_item_barcode').val(Math.floor(Math.random() * 99999999));
}
function duplicate_complex_item(complex_item_id) {
    swal({
        title: `Duplicate composed item ${complex_item_id}?`,
        text: `Are you sure you want to duplicate composed item with ID: ${complex_item_id}?, click duplicate to continue`,
        type: "success",
        showCancelButton: true,
        confirmButtonClass: "btn-success",
        confirmButtonText: "Yes, Duplicate",
        closeOnConfirm: true
    },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "post",
                    url: `?r=complex_items&f=duplicateCI&p0=${complex_item_id}`,
                    dataType: "json",
                    success: function (response) {
                        edit_manual_complex_item(response.complex_item_id);
                    }
                });
            }
        });


}
function calculate_ci_total_amount() {

    var total_amount = 0;
    var profit_amount = 0
    var cost_amount = 0
    $(".total_per_item").each(function (i) {
        total_amount += (parseFloat($(this).val().replace(/[^0-9\.\-]/g, '')));
    });
    $(".single_item_profit").each(function (i) {
        profit_amount += (parseFloat($(this).val().replace(/[^0-9\.\-]/g, '')));
    })
    $(".cost_per_item").each(function (i) {
        cost_amount += (parseFloat($(this).val().replace(/[^0-9\.\-]/g, '')));
    })
    //alert(total_amount);

    var complex_item_discount = 0;
    if ($("#complex_item_discount").val() != "" && $("#complex_item_discount").val().replace(/[^0-9\.\-]/g, '').length > 0) {
        complex_item_discount = $("#complex_item_discount").val().replace(/[^0-9\.\-]/g, '');
    }

    $("#complex_item_total").val(total_amount);
    $("#complex_item_cost").val(cost_amount);

    $("#complex_item_profit").val(profit_amount - complex_item_discount);
    //$("#total_amount").val(total_amount - complex_item_discount);
    cleaves_class(".cleavesf3", 3);
}

function updateRowsManualComplexItem() {
    var table = $('#newcomplexitems_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        table.cell(index, 11).data(`<i title="Delete" class="glyphicon glyphicon-trash red" onclick="delete_item_from_manual_complex_items('${parseInt(table.cell(index, 0).data())}')" style="font-size:16px;cursor:pointer" ></i>`);
    }
}

function refresh_complex_items_table() {
    $(".sk-circle-layer").show();
    $('#complex_items_table').DataTable().ajax.url(`?r=complex_items&f=getAllComplexItemsDateRange&p0=${$("#cisalesDate").val() ?? ""}&p2=${$("#filter_complexItemStatus").val() ?? ""}&p3=${$("#filter_complexItemSalesperson").val() ?? ""}&p4=${$("#filter_ci_include_items").val() ?? ""}`).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#complex_items_table').DataTable().columns.adjust().draw();
        }, 100);
        //setTimeout(function(){$('#complex_items_table').DataTable().columns.adjust().draw();},1000);
    }, false);
}
function delete_item_from_manual_complex_items(id) {

    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON(`?r=complex_items&f=delete_ci_item&p0=${id}`, function (data) {

    }).done(function () {
        calculate_ci_total_amount();
        if ($("#complex_items_table").length > 0) {
            var table = $('#complex_items_table').DataTable();
            table.ajax.url("?r=complex_items&f=getAllComplexItemsDateRange&p0=" + (current_date ?? "") + "&p1=" + ($("#filter_complexItemStatus").val() ?? "") + "&p2=" + ($("#filter_complexItemSalesperson").val() ?? "") + `&p3=${$("#filter_ci_include_items").val() ?? ""}`).load(function () {
                table.page('last').draw(false);
                $('#complex_items_table').closest('.dataTables_scrollBody').scrollTop($('#complex_items_table').closest('.dataTables_scrollBody')[0].scrollHeight);
                $(".sk-circle-layer").hide();
            }, false);
        }

        var table = $('#newcomplexitems_table').DataTable();
        table.ajax.url("?r=complex_items&f=get_all_items_in_complex_item&p0=" + $("#complex_item_id__").val()).load(function (data) {
            currentSelectedItemsinComplexItem = [];
            data.data.forEach(e => {
                currentSelectedItemsinComplexItem.push(parseInt(e[14]));
            })
            setTimeout(function () { calculate_ci_total_amount(); }, 1000);
            cleaves_class(".cleavesf3", 3);
            cleaves_class(".cleavesf2", 2);
        }, false);

    });
}


function delete_manual_complex_item(id) {
    swal({
        title: "Are you sure?",
        text: `Delete offer package with id ${id}?, click delete to continue`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    },
        function (isConfirm) {
            if (isConfirm) {
                $.getJSON(`?r=complex_items&f=deleteCI&p0=${id}`, function (data) {

                }).done(function () {
                    var table = $('#complex_items_table').DataTable();
                    table.ajax.url("?r=complex_items&f=getAllComplexItemsDateRange&p0=" + (current_date ?? "") + "&p1=" + ($("#filter_complexItemStatus").val() ?? "") + "&p2=" + ($("#filter_complexItemSalesperson").val() ?? "") + `&p3=${$("#filter_ci_include_items").val() ?? ""}`).load(function () {
                        $(".sk-circle-layer").hide();
                        setTimeout(function () {
                            $('#complex_items_table').DataTable().columns.adjust().draw();
                        }, 100);
                    }, false);
                });
            }
        });
}
function initComplexItems() {

    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    COMPLEXITEMS_EDIT_MODE = 0;
    ciSalesperson = '';
    complex_items_table = null;
    current_date = "today"
    $.getJSON("?r=complex_items&f=get_needed_data", function (data) {
        $(".sk-circle-layer").hide();

        ciSalesperson += `<option option value = "0" title = "All Users" > All Users</option > `;
        $.each(data.employees, function (key, val) {
            ciSalesperson += `<option option value = "${val.id}" title = "${val.username ?? ""}" > ${val.username ?? ""}</option > `;
        });


    }).done(function () {
        modal_name = "complex_items"
        modal_title = "<i class='glyphicon glyphicon-briefcase'></i> All Composed items"
        var content =
            `<div div class= "modal" data - backdrop="static" id = "${modal_name}" tabindex = "-1" role = "dialog" aria - labelledby="payment_info__" aria - hidden="true" >
                <div class="modal-dialog" style="margin-top:0!important" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                        </div>

                        <div class="modal-body" style="padding-top:2px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <table id="complex_items_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th style="width: 100px !important;">Composed ID</th>
                                                <th>Name</th>
                                                <th>Barcode</th>
                                                <th>User</th>
                                                <th>Creation date</th>
                                                <th>Items</th>
                                                <th>Sub Total</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                                <th>Profit</th>
                                                <th style="width: 100px !important;">Actions</th>
                                                <th>Deleted</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Package ID</th>
                                                <th>Name</th>
                                                <th>Barcode</th>
                                                <th>User</th>
                                                <th>Creation date</th>
                                                <th>Items</th>
                                                <th>Sub Total</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                                <th>Profit</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> `;
        $("#" + modal_name).remove();
        $("body").append(content);

        $(`#${modal_name}`).modal("show");
        getComplexItems();
    });

}

function getComplexItems() {
    var search_fields = [0, 1, 2, 3, 4, 5, 6, 7, 8];
    var index = 0;
    $('#complex_items_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });

    complex_items_table = $('#complex_items_table').dataTable({
        ajax: "?r=complex_items&f=getAllComplexItemsDateRange&p0=" + (current_date ?? "") + "&p1=" + ($("#filter_complexItemStatus").val() ?? "") + "&p2=" + ($("#filter_complexItemSalesperson").val() ?? "") + `&p3=${$("#filter_ci_include_items").val() ?? ""}`,
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0, 1, 2, 3, 4, 5, 6],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [11],
            "searchable": false,
            "orderable": false,
            "visible": false
        }
        ],
        scrollY: '44vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        dom: '<"toolbarci">frtip',
        initComplete: function (settings) {
            $("#complex_items_table").show();

            var table = $('#complex_items_table').DataTable();



            $("div.toolbarci").html('\n\
                <div class="row" style="margin-top:10px;">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                            <button type="button" class="btn btn-primary" onclick="manual_complex_itemsgenerate_item()" style="width:100%;">Compose new item</button>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-10 col-md-10 col-sm-12 pl2 pr2" >\n\
                        &nbsp;\n\
                    </div>\n\
                </div>\n\
                <div class="row" style="margin-top:10px;">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                            <input id="cisalesDate" class="form-control datepicker" type="text" placeholder="Select date" style="cursor:pointer; width:100%;">\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <select data-width="100%" id="filter_complexItemStatus" class="selectpicker" onchange="refresh_complex_items_table()">\n\
                                <option value="0" title="All Composed Items">All Composed Items</option>\n\
                                <option value="1" title="Deleted">Deleted</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <select data-live-search="true" data-width="100%" id="filter_complexItemSalesperson" class="selectpicker" onchange="refresh_complex_items_table()">\n\
                                ' + ciSalesperson + '\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-4 col-md-4 col-sm-12 pl2" >\n\
                            <select  id="filter_ci_include_items" multiple style="width:100%"  class="form-control" onchange="refresh_complex_items_table()">\n\
                            </select>\n\
                    </div>\n\
                </div>\n\
                ');



            $('.selectpicker').selectpicker();

            $("#filter_ci_include_items").select2({
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
                dropdownParent: $("#complex_items"), allowClear: true
            });

            var defaultStart = moment().startOf('month');
            var end = moment();

            $("#cisalesDate").daterangepicker({
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

            $("#cisalesDate").change(function () {

                refresh_complex_items_table()
            });

            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: updateComplexItemsRows,
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            //$(nRow).addClass(aData[0]);
        },
    });

    $('#complex_items_table').on('page.dt', function () {
        $("#tab_toolbar button.blueB").addClass("disabled");
        updateComplexItemsRows();
    });

    $('#complex_items_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#complex_items_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}


function updateComplexItemsRows() {
    var table = $('#complex_items_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();


        if (table.cell(index, 11).data() == "0") {
            delete_btn = "<i title='Delete' class='glyphicon glyphicon-trash red' style='font-size:18px;cursor:pointer' onclick='delete_manual_complex_item(" + parseInt(table.cell(index, 0).data().split("-")[1]) + ")'></i>";
            duplicate_btn = `<i title='Duplicate' class='glyphicon glyphicon-duplicate ' style='font-size:18px;cursor:pointer' onclick='duplicate_complex_item(${table.cell(index, 0).data().split("-")[1]})'></i>`
            table.cell(index, 10).data('<i title="Edit" class="glyphicon glyphicon-edit" onclick="edit_manual_complex_item(\'' + parseInt(table.cell(index, 0).data().split("-")[1]) + '\')" style="font-size:18px;cursor:pointer" ></i>&nbsp;&nbsp;' + duplicate_btn + '&nbsp;&nbsp;' + delete_btn);
        }
        // if (table.cell(index, 11).data() == "1") {
        //     table.cell(index, 10).data(`<span span class= "redClass" style = "font-weight:700" > DELETED</span > `);
        //     $(table.row(k).node()).addClass("text-danger");
        // }
        // if (table.cell(index, 12).data() == "1") {
        //     $(table.cell(index, 9).node()).addClass("text-danger").css("font-weight", "800")

        // }
        // if (table.cell(index, 13).data() > 0) {
        //     table.cell(index, 10).data(`<span span class= "text-success" style = "font-weight:700" > INV - ${ table.cell(index, 13).data() }</span > `);


        // }
    }
}