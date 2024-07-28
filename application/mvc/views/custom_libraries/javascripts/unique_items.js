var currentItemID = 0
var rowsToUpdate = {}
var minQtyNeedsToBeAdded = 0
var itemsAlreadyAdded = {}
var currentForceFill = false
var currentRequiredItems = {}
var itemsNeedUpdate = false
var advancdUniqueItemsTable = false
var warehouse_exist=0;
var is_warehouse=0;
var show_imei_options=0;


function create_unique_items_for_item(itemID, qty, clear = 1) {
    $('.sk-circle').center();
    $('.sk-circle-layer').show();
    currentItemID = itemID;
    $.ajax({
        url: `?r=unique_items&f=createNew&p0=${itemID}&p1=${qty}&p2=${clear}`,
        dataType: 'json',
        success: function (response) {
            warehouse_exist=response.warehouse_exist;
            is_warehouse=response.is_warehouse;
            show_imei_options=response.show_imei_options;
            initUniqueItems(itemID);
        }
    })
}


function add_single_unique_item_with_pi(itemID) {
    currentItemID = itemID;
    if (itemsNeedUpdate) confirmUpdateUniqueItems();
    
    var supplier_id=0;
    if($("#supplier_id").length>0){
        supplier_id=$("#supplier_id").val();
    }
    
    $.ajax({
        url: `?r=unique_items&f=createNew_withPI&p0=${itemID}&p1=${$(
            '#new_unique_items_qty'
        ).val()}&p2=0&p3=${$("#pi_id_for_pic").val()}&p4=${supplier_id}&`,
        dataType: 'json',
        success: function (response) {
            reload_unique_items_table(itemID);
        }
    })
}

function add_single_unique_item(itemID) {
    currentItemID = itemID;
    if (itemsNeedUpdate) confirmUpdateUniqueItems();
    $.ajax({
        url: `?r=unique_items&f=createNew&p0=${itemID}&p1=${$(
            '#new_unique_items_qty'
        ).val()}&p2=0`,
        dataType: 'json',
        success: function (response) {
            reload_unique_items_table(itemID)
        }
    })
}

function initUniqueItems(itemID) {
    $('.sk-circle').center();
    $('.sk-circle-layer').show();
    
    var display="";
    
    if(show_imei_options==0){
        display="display:none;";
    }
    
    COMPLEXITEMS_EDIT_MODE = 0
    ciSalesperson = ''
    complex_items_table = null
    current_date = 'today'
    _data = null
    $.getJSON(`?r=items&f=get_item_by_id__&p0=${itemID}`, function (data) {
        $('.sk-circle-layer').hide()
        _data = data
    }).done(function (data) {
        
        
        modal_name = 'edit_unique_items_of_item'
        itemsNeedUpdate = false
        $(`#${modal_name}`).modal('hide')
        modal_title = `Assign items codes for ${_data[0].description} `
        var content = `<div div class="modal" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true" >
                <div class="modal-dialog" style="margin-top:0!important" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                        </div>

                        <div class="modal-body" style="padding-top:2px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <div style="display:flex;margin-top:10px;margin-bottom:10px;"><input id="new_unique_items_qty" type="number" value="1" style="margin-right:10px;width:100px;${display}" class="form-control"/><button class="btn btn-primary" style="margin-right:40px;${display}" onclick="add_single_unique_item_with_pi(${itemID})"><i class="glyphicon glyphicon-plus " ></i> Add New</button>
                                <p style="margin-right:10px;margin-bottom:0px;${display}">Auto Add Supplier to items with no supplier: </p><select onchange="autoAddSuppliersToUniqueItems()" id="auto_add_supplier_to_new_unique_items" style="margin-left:10px;width:200px;${display}" class="suppliers_select"></select>
                                <p style="margin-left:10px;margin-right:10px;margin-bottom:0px;">Show only not paid</p><input id="notpaid" type="checkbox" onchange="reload_unique_items_table(${itemID})" />
                                </div>
                                <div class="editing-mode" style="display:flex;${display}"><div style="display:flex;margin-right:20px"><p style="margin-right:10px">Multiple Code/Mobile</p><label class="custom-switch"><input type="checkbox" id="input_mode_multiple_single"   checked="" class="custom-switch-input"><span class="custom-switch-label"></span></label></div>
                                
                                <div style="display:flex;${display}"><p style="margin-right:10px">Enable to auto move after Input</p><label class="custom-switch"><input type="checkbox" id="auto_move_after_input"   checked="" class="custom-switch-input"><span class="custom-switch-label"></span></label></div>
                                </div>
                                    <table style="width:100%" id="unique_item_edit_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;">ID</th>
                                                <th style="width:150px;">Code 1</th>
                                                <th style="width:150px;">Code 2</th>
                                                <th style="width:250px">Supplier</th>
                                                <th style="width:250px">customer</th>
                                                <th >Note</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div style="display:flex;align-items:baseline;justify-content:end;margin-top:10px;"><button class="btn btn-primary" style="display:none" id="confirmUpdateUniqueItemsButton" onclick="confirmUpdateUniqueItems()" >SAVE</button>
                                    </div></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div> `
        $(`#${modal_name}`).remove();
        $('body').append(content);

        
        
        $(`#${modal_name}`).on('show.bs.modal', function (e) {
            load_unique_items_datatable(itemID);
            
            
        });
       $(`#${modal_name}`).modal('show');
        
    })
}
function load_unique_items_datatable(itemID) {
    rowsToUpdate = {};
    $(`#unique_item_edit_table`).DataTable({
        ajax: {
            url: `?r=unique_items&f=getAll&p0=${itemID}&p1=0`,
            type: 'POST'
        },
        responsive: true,
        orderCellsTop: true,
        scrollX: true,
        scrollY: '45vh',
        iDisplayLength: 100,
        aoColumnDefs: [
            { targets: [0], searchable: false, orderable: false, visible: true },
            { targets: [1], searchable: false, orderable: false, visible: true },
            { targets: [2], searchable: false, orderable: false, visible: true },
            { targets: [3], searchable: false, orderable: false, visible: true },
            { targets: [4], searchable: false, orderable: false, visible: true },
            { targets: [5], searchable: false, orderable: false, visible: true },
            { targets: [6], searchable: false, orderable: false, visible: true }
        ],
        scrollCollapse: true,
        paging: false,
        bPaginate: false,
        bLengthChange: false,
        bFilter: true,
        bInfo: false,
        bAutoWidth: true,
        bSort: false,
        dom: '<"toolar_unique_items_table">frtip',
        fnDrawCallback: function (settings, json) {
            var table = $('#unique_item_edit_table').DataTable()
            var p = table
                .rows({
                    page: 'current'
                })
                .nodes()
            for (var k = 0; k < p.length; k++) {
                var index = table.row(p[k]).index();
               
                if(table.cell(index, 6).data()!=-1){
                    table
                    .cell(index, 6)
                    .data(
                        `<i title='Delete' class='glyphicon glyphicon-trash red' style='font-size:18px;cursor:pointer' onclick='delete_unique_item(${parseInt(
                            table.cell(index, 0).data()
                        )},${itemID})'></i>`
                    )
                }else{
                    table
                    .cell(index, 6)
                    .data(
                        ''
                    );
                }
                
            }
            cleaves_class('.cleavesf3', 3)
            cleaves_class('.cleavesf2', 2)

            autoAddSuppliersToUniqueItems()
            $('.sk-circle-layer').hide()
            initMoveToNextOnEnter('moveToNextOnEnter')
            initCustomersSelect('.customers_select', $(`#${modal_name}`))
            
            //if(is_warehouse==1){
                initSuppliersSelect('.suppliers_select', $(`#${modal_name}`))
            //}
            
        }
    })
}

function initCustomersSelect(selector, dropdownParent = $('body')) {
    $(selector).select2({
        ajax: {
            url: '?r=customers&f=search',
            data: function (params) {
                var query = {
                    p0: params.term || '',
                    p1: params.page || 1
                }

                // Query parameters will be ?search=[term]&type=public
                return query
            },
            delay: 250,
            dataType: 'json'
        },
        placeholder: 'Select Customer',
        dropdownParent: dropdownParent,
        allowClear: true
    })
}
function initSuppliersSelect(selector, dropdownParent = $('body')) {
    $(selector).select2({
        ajax: {
            url: '?r=suppliers&f=search',
            data: function (params) {
                var query = {
                    p0: params.term || '',
                    p1: params.page || 1
                }

                // Query parameters will be ?search=[term]&type=public
                return query
            },
            delay: 250,
            dataType: 'json'
        },
        placeholder: 'Select Supplier',
        dropdownParent: dropdownParent,
        allowClear: true
    })
}
function autoAddSuppliersToUniqueItems() {
    autoAddSupplierValue = $('#auto_add_supplier_to_new_unique_items').val()
    if (autoAddSupplierValue > 0) {
        supplierName = $('#auto_add_supplier_to_new_unique_items')
            .find(`option[value='${autoAddSupplierValue}']`)
            .html()
        $('.new_unique_item_supplier_select').each((i, e) => {
            if (!e.value || e.value == 0) {
                $(e)
                    .append(
                        `<option value="${autoAddSupplierValue}" selected>${supplierName}</option>`
                    )
                    .trigger('change')
            }
        })
    }
}
function delete_unique_item(uniqueItemID, itemID) {
    $.ajax({
        url: `?r=unique_items&f=deleteItem&p0=${uniqueItemID}`,
        dataType: 'json',
        success: function (response) {
            reload_unique_items_table(itemID)
        }
    })
}
function reload_unique_items_table(itemID) {
    rowsToUpdate = {}

    var isChecked = $('#notpaid').prop('checked')
    isChecked_ = 0
    if (isChecked) {
        isChecked_ = 1
    }

    $('.sk-circle-layer').show()
    $('#unique_item_edit_table')
        .DataTable()
        .ajax.url(`?r=unique_items&f=getAll&p0=${itemID}&p1=${isChecked_}`)
        .load(function () {
            $('.sk-circle-layer').hide()

            $('#complex_items_table').DataTable().columns.adjust().draw()
        }, false)
}
function update_unique_item(itemID) {
    itemsNeedUpdate = true;
    $('#confirmUpdateUniqueItemsButton').show();
    rowsToUpdate[itemID] = {
        code1: $(`#unique_item_code1_${itemID}`).val(),
        code2: $(`#unique_item_code2_${itemID}`).val(),
        supplier_id: $(`#unique_item_supplier_${itemID}`).val(),
        customer_id: $(`#unique_item_customer_${itemID}`).val(),
        note: $(`#unique_item_note_${itemID}`).val()
    };
    
    if($(`#unique_item_code1_${itemID}`).val().length>0){
      check_availibility($(`#unique_item_code1_${itemID}`).val(),itemID,1);  
    }
    
    if($(`#unique_item_code2_${itemID}`).val().length>0){
      check_availibility($(`#unique_item_code2_${itemID}`).val(),itemID,2);  
    }
}

function check_availibility(imei,itemID,imeip){
    var _data=[];
    $.getJSON("?r=unique_items&f=check_availibility&p0="+imei+"&p1="+imeip, function (data) {
        _data=data;
    }).done(function () {
    
        if(_data>0 && imeip==1){
            $(`#unique_item_code1_${itemID}`).addClass("input_border_error");    
        }else if(_data==0 && imeip==1){
            $(`#unique_item_code1_${itemID}`).removeClass("input_border_error");    
        }
        
        if(_data>0 && imeip==2){
            $(`#unique_item_code2_${itemID}`).addClass("input_border_error");
        }else if(_data==0 && imeip==2){
            $(`#unique_item_code2_${itemID}`).removeClass("input_border_error");    
        }
        
    });
}

function confirmUpdateUniqueItems() {
    itemsNeedUpdate = false
    $.ajax({
        type: 'post',
        url: '?r=unique_items&f=update',
        data: { data: rowsToUpdate },
        dataType: 'json',
        async: 'false',
        success: function (response) {
            $('#confirmUpdateUniqueItemsButton').hide()
            rowsToUpdate = {}
        }
    })
}
function initCustomSearchUniqueItems() {
    $.confirm({
        title: 'Search Items Codes',
        content: `<p>Please enter a text to search items codes</p><input id='unique_items_search_text' type='text' class='form-control'/>`,
        type: 'blue',
        buttons: {
            search: {
                btnClass: 'btn-info',
                text: 'Search',
                keys: ['enter'],
                action: () => {
                    if (
                        !$('#unique_items_search_text').val() ||
                        $('#unique_items_search_text').val() == ''
                    ) {
                        $('#unique_items_search_text').css('border', '1px solid red')
                        setTimeout(() => {
                            $('#unique_items_search_text').css('border', '')

                            //Time out to remove the border after specific timeout, to make it user friendly, we can remove this
                        }, 1000)
                        return false
                    } else {
                        uniqueItemsCustomSearchByText($('#unique_items_search_text').val())
                    }
                }
            },
            cancel: {
                btnClass: 'btn-secondary',
                text: 'Close'
            }
        }
    })
    setTimeout(function () {
        $('#unique_items_search_text').focus()
    }, 500)
}

function uniqueItemsCustomSearchByText(term) {
    $.ajax({
        type: 'post',
        url: '?r=unique_items&f=search',
        data: {
            term: term
        },
        dataType: 'json',
        success: function (response) {
            openUniqueItemsSearchResult(response, term)
        }
    })
}

function openUniqueItemsSearchResult(results, term) {
    modal_name = 'searchUniqueItemResultModal'

    $(`#${modal_name}`).modal('hide')
    modal_title = `Code items search for <span id='uniqueItemSearchTerm'>${term}</span> `
    var content = `<div div class="modal" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true" >
            <div class="modal-dialog" style="margin-top:0!important" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                    </div>

                    <div class="modal-body" style="padding-top:2px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                            <div style="display:flex;align-items:baseline;margin-top:10px;margin-bottom:10px">Search: <input type="text" value="${term}" onchange="uniqueItemsCustomSearchByText(this.value)" style="width:auto;margin-left:10px" id="unique_item_search_term_input" class="form-control"/></div>
                            <table style="width:100%"  class="table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:130px;">Creation Date</th>
                                            <th style="width:70px;">Item ID</th>
                                            <th>Item Description</th>
                                            <th style="width:90px">Item Barcode</th>
                                            <th style="width:70px;">Code 1</th>
                                            <th style="width:70px;">Code 2</th>
                                            <th>Supplier</th>
                                            <th>Customer</th>
                                            <th style="width:70px;">Invoice ID</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    ${results.data
            .map(e => {
                return `<tr><td>${e.creation_date
                    }</td><td>${e.item_id}</td><td>${e.item_description ?? ''
                    }</td><td>${e.item_barcode ?? ''
                    }</td><td>${e.code1 ?? ''}</td><td>${e.code2 ?? ''
                    }</td><td>${e.supplier_name ?? ''
                    }</td><td>${e.customer_name ?? ''
                    } </td><td>${e.invoice_id ?? ''
                    }</td><td>${e.note ?? ''}</td></tr>`
            })
            .join('')}
                                    
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> `
    $('#' + modal_name).remove()
    $('body').append(content)

    $(`#${modal_name}`).modal('show')
}
var previousTimeOuts = []
function initMoveToNextOnEnter(className) {
    $(`.${className}`).each((i, element) => {
        $(element).on('keypress', e => {
            if (e.key === 'Enter') {
                orderingAttr = $('#input_mode_multiple_single').prop('checked')
                    ? 'diagonal-ordering'
                    : 'ordering'
                currentOrdering = $(element).attr(`data-${orderingAttr}`)
                nextOrdering = parseInt(currentOrdering) + 1
                if ($(`[data-${orderingAttr}='${nextOrdering}']`).length > 0)
                    $(`[data-${orderingAttr}='${nextOrdering}']`).focus()
            }
        })
        $(element).on('input', e => {
            if ($('#auto_move_after_input').prop('checked')) {
                if (previousTimeOuts.length > 0)
                    previousTimeOuts.forEach(e => {
                        clearTimeout(e)
                    })
                previousTimeOuts.push(
                    setTimeout(() => {
                        orderingAttr = $('#input_mode_multiple_single').prop('checked')
                            ? 'diagonal-ordering'
                            : 'ordering'
                        currentOrdering = $(element).attr(`data-${orderingAttr}`)
                        nextOrdering = parseInt(currentOrdering) + 1
                        if ($(`[data-${orderingAttr}='${nextOrdering}']`).length > 0)
                            $(`[data-${orderingAttr}='${nextOrdering}']`).focus()
                    }, 500)
                )
            }
        })
    })
}

function setCustomerOfUniqueItemsBackup(customer_id, qty) {
    itemsAlreadyAdded = {}
    modal_name = 'searchUniqueItemResultModal'

    minQtyNeedsToBeAdded = qty
    $(`#${modal_name}`).modal('hide')
    modal_title = `Register item code/IMEI to a customer`
    var content = `<div div class="modal" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true" >
            <div class="modal-dialog" style="margin-top:0!important" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${modal_title}</h3>
                    </div>

                    <div class="modal-body" style="padding-top:2px;">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                            <div style="display:flex;align-items:baseline;margin-top:10px;margin-bottom:10px">Enter Code: <input " type="text" value="" onchange="this.style.border=''" style="width:auto;margin-left:10px" id="add_unique_item_to_customer_search" class="form-control"/> <button style="margin-left:10px" class="btn btn-primary" onclick="addUniqueItemToCustomer(${customer_id})">Add</button></div>
                            <table style="width:100%"  class="table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:40px;">ID</th>
                                            <th style="width:70px;">Item ID</th>
                                            <th style="width:150px">Item Description</th>
                                            <th style="width:150px">Item Barcode</th>
                                            <th style="width:150px;">Code 1</th>
                                            <th style="width:150px;">Code 2</th>
                                            <th style="width:250px">Supplier</th>
                                            <th style="width:250px">Customer</th>
                                            <th >Note</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="add_unique_item_to_customer_tablebody">

                                    </tbody>
                                </table>
                                <div style="display:flex;justify-content:end;margin-top:10px"  >
                                    <button class="btn btn-success" style="display:none"  id="save_unique_items_customer_container" onclick="save_unique_items_customers()"><i class="glyphicon glyphicon-save " ></i> Save</button>
                                </div>
                                </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> `
    $('#' + modal_name).remove()
    $('body').append(content)
    $('#add_unique_item_to_customer_search').on('keypress', e => {
        if (e.key === 'Enter') {
            addUniqueItemToCustomer(customer_id)
        }
    })

    $(`#${modal_name}`).modal('show')
}

function addUniqueItemToCustomer_custom(code, cid) {
    $('#add_unique_item_to_customer_search').val(code)
    addUniqueItemToCustomer(cid)
}

function addUniqueItemToCustomer(customer_id) {
    searchCode = $('#add_unique_item_to_customer_search').val()

    $('#add_unique_item_error_container').hide()
    if (searchCode == '' || !searchCode)
        document.getElementById('add_unique_item_to_customer_search').style.border =
            '1px solid red'
    else {
        remainingItems = {}
        Object.keys(currentRequiredItems).forEach(e => {
            remainingItems[e] = currentRequiredItems[e]
            if (itemsAlreadyAdded[e])
                remainingItems[e] = remainingItems[e] - itemsAlreadyAdded[e].length
        })
        $.ajax({
            type: 'post',
            url: '?r=unique_items&f=searchByCode',
            data: {
                code: searchCode,
                customer_id: customer_id,
                remainingItems: remainingItems
            },
            dataType: 'json',
            success: function (response) {
                if (response.success == false) {
                    document.getElementById(
                        'add_unique_item_to_customer_search'
                    ).style.border = '1px solid red'
                } else {
                    $('#add_unique_item_to_customer_search').val('')
                    response.unique_items.forEach(e => {
                        if (
                            Object.keys(itemsAlreadyAdded).includes(e.item_id) &&
                            itemsAlreadyAdded[e.item_id].includes(e.id)
                        ) {
                            $('#add_unique_item_error_container')
                                .html(
                                    'Unique item already added, please enter the code for a different one'
                                )
                                .show()
                            return
                        }
                        if (!currentRequiredItems[e.item_id]) {
                            document.getElementById(
                                'add_unique_item_to_customer_search'
                            ).style.border = '1px solid red'
                            $('#add_unique_item_error_container')
                                .html('Please enter an item from the above required list')
                                .show()
                            return
                        }
                        if (remainingItems[e.item_id] == 0) {
                            document.getElementById(
                                'add_unique_item_to_customer_search'
                            ).style.border = '1px solid red'
                            $('#add_unique_item_error_container')
                                .html('Max number of this item added')
                                .show()
                            return
                        }
                        if (!itemsAlreadyAdded[e.item_id]) itemsAlreadyAdded[e.item_id] = []
                        itemsAlreadyAdded[e.item_id].push(e.id)
                        checkCanSave()
                        $('#add_unique_item_to_customer_tablebody').append(
                            `<tr id="add_unique_item_to_customer_row_${e.id}"><td>${e.item_id
                            }</td><td>${e.item_description ?? ''}</td><td>${e.item_barcode ?? ''
                            }</td><td>${e.code1 ?? ''}</td><td>${e.code2 ?? ''}</td><td>${e.supplier_name ?? ''
                            }</td><td><select class="form-control" id="add_customer_to_unique_item_select_${e.id
                            }">${response.customer.length > 0
                                ? `<option selected value="${response.customer[0].id}">${response.customer[0].name}</option>`
                                : ''
                            }</select></td><td>${e.note ?? ''
                            }</td><td><i title='Delete' class='glyphicon glyphicon-trash red' style='font-size:18px;cursor:pointer' onclick='remove_unique_item_from_client_add(${e.id
                            })'></i></td></tr>`
                        )

                        //<option selected value="${response.customer[0].id}">${response.customer[0].name}</option>
                        $(`#add_customer_to_unique_item_select_${e.id}`).select2({
                            ajax: {
                                url: '?r=customers&f=search',
                                data: function (params) {
                                    var query = {
                                        p0: params.term || '',
                                        p1: params.page || 1
                                    }

                                    // Query parameters will be ?search=[term]&type=public
                                    return query
                                },
                                delay: 250,
                                dataType: 'json'
                            },
                            placeholder: 'Select customer',
                            dropdownParent: $(`#${modal_name}`),
                            allowClear: true
                        })
                    })
                }
            }
        })
    }
}
function remove_unique_item_from_client_add(uniqueItemId) {
    $(`#add_unique_item_to_customer_row_${uniqueItemId}`).remove()
    tmp = itemsAlreadyAdded
    itemsAlreadyAdded = {}
    Object.keys(tmp).forEach(itemID => {
        uniqueItemIds = []
        tmp[itemID].forEach(uid => {
            if (uid != uniqueItemId) uniqueItemIds.push(uid)
        })
        if (uniqueItemIds.length > 0) itemsAlreadyAdded[itemID] = uniqueItemIds
    })
    checkCanSave()
}
function save_unique_items_customers() {
    var invoice_id = $('#code_invoice_id').val()
    uniqueItemsCustomerId = {}
    Object.keys(itemsAlreadyAdded).forEach(itemID => {
        itemsAlreadyAdded[itemID].forEach(e => {
            uniqueItemsCustomerId[e] =
                $(`#add_customer_to_unique_item_select_${e}`).val() ?? 0
        })
    })
    $.ajax({
        type: 'post',
        url: '?r=unique_items&f=updateUniqueItemsCustomerId',
        data: {
            itemsToUpdate: uniqueItemsCustomerId,
            invoice_id: invoice_id
        },
        dataType: 'json',
        success: function (response) {
            $('#searchUniqueItemResultModal').modal('hide')
            $('#searchUniqueItemResultModal').remove()

            if (auto_print !== undefined) {
                if (typeof auto_print !== 'undefined') {
                    swal(
                        {
                            title: 'Do you want to print invoice?',
                            text: '',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonClass: 'btn-danger',
                            confirmButtonText: 'Yes, print it!',
                            closeOnConfirm: true,
                            cancelButtonText: 'Do not print'
                        },
                        function () {
                            inv.print_invoice(invoice_id, 0)
                        }
                    )
                }
            }
        }
    })
}
function checkCanSave() {
    if (currentForceFill && Object.keys(currentRequiredItems).length > 0) {
        canSave = true
        Object.keys(currentRequiredItems).forEach(e => {
            if (
                !itemsAlreadyAdded[e] ||
                itemsAlreadyAdded[e].length != currentRequiredItems[e]
            )
                canSave = false
        })

        Object.keys(itemsAlreadyAdded).forEach(e => {
            if (!currentRequiredItems[e]) canSave = false
        })
        if (canSave) changeSaveAndCloseButtonStatus(1)
        else changeSaveAndCloseButtonStatus(0)
    } else {
        changeSaveAndCloseButtonStatus(1)
    }
}
function changeSaveAndCloseButtonStatus(active) {
    if (active == 1) {
        $('#save_unique_items_customer_container').show()
        $('#close_unique_items_button').show()
    } else {
        $('#save_unique_items_customer_container').hide()
        $('#close_unique_items_button').hide()
    }
}

// function create_unique_items_for_item(itemID, qty, requiredItems = {}, forceFill = false) {
//     clear = 0;
//     currentItemID = itemID
//     currentRequiredItems = requiredItems;
//     currentForceFill = forceFill
//     $.ajax({
//         url: `?r=unique_items&f=createNew&p0=${itemID}&p1=${qty}&p2=${clear}`,
//         dataType: "json",
//         success: function (response) {
//             initUniqueItems(itemID);
//         }
//     });
// }

function show_available_imei(cid) {
    var jsonData = JSON.stringify(current_required_items)
    var content =
        '<div class="modal" data-backdrop="static" id="avimei_Modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Available IMEI<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'avimei_Modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="avimei_table_" class="table table-striped table-bordered" cellspacing="0" >\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 50px !important;">Items ID</th>\n\
                                        <th>Items Description</th>\n\
                                        <th style="width: 80px !important;">IMEI 1</th>\n\
                                        <th style="width: 80px !important;">IMEI 1</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>'

    $('#avimei_Modal').remove()
    $('body').append(content)

    $('#avimei_Modal').on('show.bs.modal', function (e) { })

    $('#avimei_Modal').on('shown.bs.modal', function (e) {
        var avimei_table_ = $('#avimei_table_').dataTable({
            ajax:
                '?r=unique_items&f=show_available_imei&p0=' + jsonData + '&p1=' + cid,
            responsive: true,
            orderCellsTop: true,
            bLengthChange: true,
            iDisplayLength: 1000,
            ordering: false,
            aoColumnDefs: [
                { targets: [0], searchable: true, orderable: false, visible: true },
                { targets: [1], searchable: true, orderable: false, visible: true },
                { targets: [2], searchable: true, orderable: false, visible: true },
                { targets: [3], searchable: true, orderable: false, visible: true }
            ],
            scrollY: '45vh',
            scrollCollapse: true,
            paging: false,
            order: [[0, 'asc']],
            dom: '<"toolbar_crn">frtip',
            initComplete: function (settings) { },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0])
            }
            // fnDrawCallback: updateCNRows_,
        })
    })

    $('#avimei_Modal').on('hide.bs.modal', function (e) {
        $('#avimei_Modal').remove()
    })

    $('#avimei_Modal').modal('show')
}

function ignore_imei_registration(){
    swal({
        title: "Are You Sure",
        html: false ,
        text: 'Close Without Registration IMEI(s)',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Ok",
        cancelButtonText: "Cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data=[];
            $.getJSON("?r=unique_items&f=ignore_imei_registration", function (data) {
                
            }).done(function () {
                $(".sk-circle-layer").hide();
                $("#searchUniqueItemResultModal").modal("hide");
            }).fail(function() {
                
            }).always(function() {

            });
        }
    });
}

var current_required_items = []
function setCustomerOfUniqueItems(
    customer_id,
    requiredItems = {},
    forceFill = false,
    description = '',
    invoice_id
) {
    current_required_items = requiredItems
    currentRequiredItems = requiredItems
    currentForceFill = forceFill
    itemsAlreadyAdded = {}
    modal_name = 'searchUniqueItemResultModal'

    $(`#${modal_name}`).modal('hide')
    modal_title = `Register item code/IMEI to a customer`
    var content = `<div div class="modal " data-backdrop="static" id="${modal_name}"  role="dialog"  aria-labelledby="payment_info__"  daria-hidden="true" >
            <div class="modal-dialog" style="margin-top:0!important" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${modal_title}</h3>
                    </div>
                    
                    <div class="modal-body" style="padding-top:2px;">
                        <input type='hidden' id='code_invoice_id' value=${invoice_id} />
                        <div class="row">
                            <div class="col-lg-12 col-md-12"><p>${description}</p>
                            <div style="display:flex;align-items:baseline;margin-top:10px;margin-bottom:10px">Enter Code: <input " type="text" value="" onchange="this.style.border=''" style="width:auto;margin-left:10px" id="add_unique_item_to_customer_search" class="form-control"/> <button style="margin-left:10px" class="btn btn-primary" onclick="addUniqueItemToCustomer(${customer_id})">Add</button> <button style="margin-left:40px" class="btn btn-primary" onclick="show_available_imei(${customer_id})">Show Available</button>&nbsp;&nbsp;<button style="margin-left:40px" class="btn btn-primary" onclick="ignore_imei_registration()">Ignore IMEI Registration</button></div>
                          <p style="display:none" id="add_unique_item_error_container" class="text-danger">Invalid Item Entered</p>
                            <table style="width:100%"  class="table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="width:70px;">Item ID</th>
                                            <th style="width:150px">Item Description</th>
                                            <th style="width:150px">Item Barcode</th>
                                            <th style="width:150px;">Code 1</th>
                                            <th style="width:150px;">Code 2</th>
                                            <th style="width:250px">Supplier</th>
                                            <th style="width:250px">Customer</th>
                                            <th >Note</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="add_unique_item_to_customer_tablebody">

                                    </tbody>
                                </table>
                                <div style="display:flex;justify-content:end;margin-top:10px"  >
                                    <button class="btn btn-success" style="display:none"  id="save_unique_items_customer_container" onclick="save_unique_items_customers()"><i class="glyphicon glyphicon-save " ></i> Save</button>
                                </div>
                                </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> `
    $('#' + modal_name).remove()
    $('body').append(content)
    $('#add_unique_item_to_customer_search').on('keypress', e => {
        if (e.key === 'Enter') {
            addUniqueItemToCustomer(customer_id)
        }
    });
    
    $(`#${modal_name}`).on('hide.bs.modal', function (e) {
        $(`#${modal_name}`).remove();
    });

    $(`#${modal_name}`).modal('show')
}

function initUniqueItemsAdvancedSearch() {
    modal_name = 'uniqueItemsAdvancedSearch'

    $(`#${modal_name}`).modal('hide')
    modal_title = `View IMEI of items`
    var content = `<div div class="modal " data-backdrop="static" id="${modal_name}"  role="dialog"  aria-labelledby="payment_info__"  daria-hidden="true" >
            <div class="modal-dialog" style="margin-top:0!important" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px" id="close_unique_items_button" class="glyphicon glyphicon-remove" onclick="modal_close('${modal_name}')"></i></h3>
                    </div>
                    
                    <div class="modal-body" style="padding-top:2px;">
                    <div class="row " style="margin:10px 0px;">
                    <div class="col-md-2 col-lg-2"><select id="items_select_advanced" class="form-control form-control-sm items_select" onchange="refresh_advanced_unique_items_source()"  style="width:100%" multiple></select></div>
                    <div class="col-md-2 col-lg-2"><select id="customers_select_advanced" class="form-control form-control-sm customers_select" onchange="refresh_advanced_unique_items_source()" style="width:100%"></select></div>
                    <div class="col-md-2 col-lg-2"><select id="suppliers_select_advanced" class="form-control form-control-sm suppliers_select" onchange="refresh_advanced_unique_items_source()" style="width:100%"></select></div>
                    <div class="col-md-2 col-lg-2"><select id="unique_item_status_advanced" class="form-control form-control-sm"  onchange="refresh_advanced_unique_items_source()"><option value="">All</option><option value="sold">Sold</option><option value="instock">In Stock</option></select></div>
                    <div class="col-md-2 col-lg-2"><select id="unique_items_has_issue" class="form-control form-control-sm"  onchange="refresh_advanced_unique_items_source()"><option value="">All</option><option value="hasIssue">Has Issue</option><option value="noissue">No Issue</option></select></div>
                    <div class="col-md-2 col-lg-2" id="buttons"></div>
                    </div>

                    <table style="width:100%" id="advanced_unique_items_datatable"  class="w-100 table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width:70px;">Item ID</th>
                                    <th>Item <br/>Description</th>
                                    <th style="width:150px">Item <br/>Barcode</th>
                                    <th style="width:150px;">Code 1</th>
                                    <th style="width:150px;">Code 2</th>
                                    <th style="width:120px">Suppliers</th>
                                    <th style="width:250px">Customers</th>
                                    <th style="width:70px">QTY <br/>Sold</th>
                                    <th style="width:80px">IMEI<br/>Available</th>
                                    <th style="width:100px">Stock QTY <br/>Available</th>
                                </tr>
                            </thead>
                            <tbody id="add_unique_item_to_customer_tablebody">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> `
    $('#' + modal_name).remove()
    $('body').append(content)

    initCustomersSelect('.customers_select', $(`#${modal_name}`))
    
    //if(is_warehouse==1){
        initSuppliersSelect('.suppliers_select', $(`#${modal_name}`))
    //}
    
    
    initItemsSearch('.items_select', $(`#${modal_name}`))
    $(`#${modal_name}`).on('hidden.bs.modal', () => {
        $(`#${modal_name}`).remove()
    })
    load_unique_items_datatable_()
    $(`#${modal_name}`).modal('show')
}

function initItemsSearch(selector, dropdownParent = $('body')) {
    $(selector).select2({
        ajax: {
            url: '?r=items&f=search',
            data: function (params) {
                var query = {
                    p0: params.term || '',
                    p1: params.page || 1
                }

                // Query parameters will be ?search=[term]&type=public
                return query
            },
            delay: 250,
            dataType: 'json'
        },
        placeholder: 'Search By Items',
        dropdownParent: dropdownParent,
        allowClear: true
    })
}

function load_unique_items_datatable_() {
    advancdUniqueItemsTable = $(`#advanced_unique_items_datatable`).DataTable({
        ajax: {
            url: `?r=unique_items&f=getAllAdvanced`,
            type: 'POST',
            data: prevData => {
                prevData.customer_id = $('#customers_select_advanced').val()
                prevData.supplier_id = $('#suppliers_select_advanced').val()
                prevData.sold = $('#unique_item_status_advanced').val()
                prevData.itemIDs = $('#items_select_advanced').val()
                prevData.hasIssue = $('#unique_items_has_issue').val()
            }
        },
        responsive: true,
        orderCellsTop: true,
        scrollX: true,
        scrollY: '45vh',
        iDisplayLength: 10,
        aoColumnDefs: [
            { targets: [0], searchable: false, orderable: false, visible: true },
            { targets: [1], searchable: false, orderable: false, visible: true },
            { targets: [2], searchable: false, orderable: false, visible: true },
            { targets: [3], searchable: false, orderable: false, visible: true },
            { targets: [4], searchable: false, orderable: false, visible: true },
            { targets: [5], searchable: false, orderable: false, visible: true },
            { targets: [6], searchable: false, orderable: false, visible: true },
            { targets: [7], searchable: false, orderable: false, visible: true },
            { targets: [8], searchable: false, orderable: false, visible: true }
        ],
        scrollCollapse: true,
        paging: true,
        serverSide: true,
        lengthMenu: [10, 25, 50, 100],
        bLengthChange: false,
        bFilter: true,
        bInfo: false,
        bAutoWidth: true,
        bSort: false,
        rowCallback: function (row, data) {
            if (data[8] != data[9]) row.style.backgroundColor = '#ffa7a7'
            //   if (data['column8'] != 0) {
            //   }
        },
        dom: '<"toolar_advanced_unique_items_table">t<""ip>',
        fnDrawCallback: function (settings, json) {
            var table = $('#unique_item_edit_table').DataTable()
            var p = table
                .rows({
                    page: 'current'
                })
                .nodes()
            for (var k = 0; k < p.length; k++) {
                var index = table.row(p[k]).index()
            }
            cleaves_class('.cleavesf3', 3)
            cleaves_class('.cleavesf2', 2)
        },
        initComplete: function (settings, json) {
            var buttons = new $.fn.dataTable.Buttons(advancdUniqueItemsTable, {
                buttons: [
                    {
                        extend: 'excel',
                        text: 'Export excel',
                        className: 'exportExcel',
                        filename: 'Expenses ',
                        customize: _customizeExcelOptions,
                        exportOptions: {
                            modifier: {
                                page: 'all'
                            },
                            //columns: [ 0,1,2,3,4,5,6,7 ]
                            //format: {
                            //body: function ( data, row, column, node ) {
                            // Strip $ from salary column to make it numeric
                            ///return column === 6 ? data.replace( /[L.L.,]/g, '' ) : data;
                            //}
                            //}
                        }
                    }
                ]
            }).container().appendTo($('#buttons'));
            function _customizeExcelOptions(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                var clR = $('row', sheet);
                //var r1 = Addrow(clR.length+2, [{key:'A',value: "Total Credit Notes"},{key:'B',value: total}]);
                sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML;
                $('row c[r^="A' + (clR.length + 2) + '"]', sheet).attr('s', '48');
                function Addrow(index, data) {
                    var msg = '<row r="' + index + '">'
                    for (var i = 0; i < data.length; i++) {
                        var key = data[i].key;
                        var value = data[i].value;
                        msg += '<c t="inlineStr" r="' + key + index + '">';
                        msg += '<is>';
                        msg += '<t>' + value + '</t>';
                        msg += '</is>';
                        msg += '</c>';
                    }
                    msg += '</row>';
                    return msg;
                }
            }
            $(".sk-circle-layer").hide();
        },

    })
}

function refresh_advanced_unique_items_source() {
    if (advancdUniqueItemsTable) advancdUniqueItemsTable.ajax.reload()
}
