var current_recalled_invoice = 0;
var current_recalled_invoice_data = null;
var current_recalled_invoice_is_temp=0; 
function holdInvoice(auto) {
    if(auto==1 && inv.getData().length > 0){
        var note = "Auto Hold";
        var holdLocation = "Auto Hold";
        $.ajax({
            type: "post",
            url: "?r=pending_invoices&f=save&p0="+auto,
            data: {
                fullData: {
                    "ID": timestamp,
                    "totalQty": inv.getTotalQtyItems(),
                    "ALlitems": inv.getData(),
                    "Nb": invoicesOnHold_Index,
                    "customer": inv.getcustomer_info()
                },
                CurrentActive: -1,
                note: note,
                location: holdLocation
            },
            dataType: "json",
            success: function (response) {
                
            }
        });
    }else{
        
        if (inv.getData().length > 0) {
       
            invoicesOnHold_Index++;
            var timestamp = Math.floor(Date.now() / 1000);
            $.confirm({
                type: "green",
                title: "Hold Invoice?",
                content: `Note:<br/><textarea class="form-control" id='hold_invoice_note'>${current_recalled_invoice_data && current_recalled_invoice_data.note ? current_recalled_invoice_data.note : ""}</textarea>Location:<input type='text' id="hold_invoice_location" value="${current_recalled_invoice_data && current_recalled_invoice_data.location ? current_recalled_invoice_data.location : ""}" class="form-control">`,
                buttons: {
                    save: {
                        text: "Hold",
                        btnClass: "btn-primary",
                        action: () => {
                            note = $("#hold_invoice_note").val();
                            holdLocation = $("#hold_invoice_location").val();
                            //console.log(inv.getData());
                            $.ajax({
                                type: "post",
                                url: "?r=pending_invoices&f=save&p0="+auto,
                                data: {
                                    fullData: {
                                        "ID": timestamp,
                                        "totalQty": inv.getTotalQtyItems(),
                                        "ALlitems": inv.getData(),
                                        "Nb": invoicesOnHold_Index,
                                        "customer": inv.getcustomer_info()
                                    },
                                    CurrentActive: current_recalled_invoice,
                                    note: note,
                                    location: holdLocation
                                },
                                dataType: "json",
                                success: function (response) {
                                    if (response.success) {
                            
                                        inv.reset();
                                        $("#pay").addClass("disabledPay");
                                        invoicesOnHold_Index++;
                                        inv.deleteCookies();
                                        //additionalData.success();
                                        current_recalled_invoice = 0
                                        current_recalled_invoice_data = null;
                                    }


                                }
                            });
                        }
                    },
                    cancel: {
                        text: "Cancel",
                        btnClass: "btn-secondary",
                        action: () => {
                            // if (additionalData && additionalData.cancel) {
                            // additionalData.cancel();

                            //}
                        }
                    }
                }
            })

        }
    }
    
}
function _recallInvoice() {
    tableRows = "";

    content = `<table class="table table-hover table-striped" style="width:100%" id="recall_options_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Note</th>
                            <th>Location</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Items</th>
                            <th style="width:60px"></th>
                            <th style="width:60px"></th>
                            <th style="width:60px"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>`;
    var content =
        `<div class="modal " id="RecallModel" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"> 
                    <h3 class="modal-title"><i class="icon-invoice"></i> Pending invoices <i class="glyphicon glyphicon-refresh" onclick="_recallInvoice()"></i><i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="removeRecallModal()"></i></h3>
                </div>
                <div class="modal-body">${content}</div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>`;

    $("#RecallModel").modal("hide");
    $("body").append(content);

    $('#recall_options_table').DataTable({
        ajax: {
            url: "?r=pending_invoices&f=getAll",
            type: 'POST',
            error: function (xhr, status, error) {
            },
        }, columnDefs: [
            {
                targets: [6],
                render: function (data, type, row) {
                    return `<button class="btn btn-primary btn-sm" onclick="recallInvoiceNow(${row[0]})" style="width:100%;padding:0px !important;font-size:14px !important;">Load</button>`;
                },
                orderable: false
            },
            {
                targets: [7],
                render: function (data, type, row) {
                    return `<button class="btn btn-danger btn-sm" onclick="deleteHeldItem(${row[0]},true)" style="width:100%;padding:0px !important;font-size:14px !important;">Delete</button>`;
                },
                orderable: false
            },
            {
                targets: [8],
                render: function (data, type, row) {
                    return `<button class="btn btn-info btn-sm" onclick="print_hold_invoice(${row[0]})" style="width:100%;padding:0px !important;font-size:14px !important;">Print</button>`;
                },
                orderable: false
            }
        ], order: [[1, 'desc']],
    });
    $("#RecallModel").centerWH();

    $('#RecallModel').on('show.bs.modal', function (e) { });
    $('#RecallModel').on('hide.bs.modal', function (e) {
        $('#RecallModel').remove();
    });
    $('#RecallModel').modal('show');




}
function deleteHeldItem(item_id, reload_held_items_table = false) {
    $.ajax({
        type: "post",
        url: `?r=pending_invoices&f=get&p0=${item_id}`,
        dataType: "json",
        success: function (response) {

            $.confirm({
                type: "red",
                title: `Delete Pending Invoice?`,
                content: `Are you sure you want to delete the pending invoice with id: ${response.pendingInvoice.id}`,
                buttons: {
                    yes: {
                        btnClass: "btn-danger",
                        text: "Yes, Delete",
                        action: () => {
                            $.ajax({
                                type: "post",
                                url: `?r=pending_invoices&f=delete&p0=${item_id}`,
                                dataType: "json",
                                success: function (response) {
                                    if (reload_held_items_table) {
                                        _recallInvoice();
                                    }
                                }
                            });
                        }
                    }, no: {
                        btnClass: "btn-secondary",
                        text: "No"
                    }
                }
            })
        }
    });

}

function recallInvoiceNow(id) {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    current_recalled_invoice = id;
    inv.clear_customer_display(0);
    inv.reset();
    $.ajax({
        type: "post",
        url: `?r=pending_invoices&f=get&p0=${id}`,
        dataType: "json",
        success: function (response) {
            
            current_recalled_invoice_is_temp = response.pendingInvoice.is_current_temp;
            current_recalled_invoice_data = response.pendingInvoice;
          
             
            inv.setItems(response.pendingInvoice.data.ALlitems);
            if (response.pendingInvoice.data.customer && response.pendingInvoice.data.customer.length > 0) {
                //alert(response.pendingInvoice.data.customer[0].id);
                inv.setcustomer_info(response.pendingInvoice.data.customer);
                inv.showCustomerNot(response.pendingInvoice.data.customer);

            }
            $(".sk-circle-layer").hide();
        }
    });

    var invoicesOnHold_tmp = [];
    $('#RecallModel').modal('hide');
}


function recallInvoice() {
    /*if (inv.getTotalQtyItems())
        holdInvoice({ success: _recallInvoice, cancel: _recallInvoice })
    else {
        _recallInvoice()
    }*/

    _recallInvoice();
}


function deleteItem(item_id_) {
    if ($(".select_p_item").length > 0) {
        if (inv.getTotalItems() == 1 && current_recalled_invoice > 0 && current_recalled_invoice_data==0) {
            $.confirm({
                type: "red",
                title: "Delete held items",
                content: "Are you sure you want to delete the held items?",
                buttons: {
                    yes: {

                        btnClass: "btn-danger",
                        text: "Yes, Delete",
                        action: () => {
                            $.ajax({
                                type: "post",
                                url: `?r=pending_invoices&f=delete&p0=${item_id_}`,
                                dataType: "json",
                                success: function (response) {
                                    var item_id = null;
                                    if (item_id_ > 0) {
                                        item_id = item_id_;
                                    } else {
                                        item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                                    }
                                    //var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                                    if ($(".select_p_item").next().length > 0) {
                                        var current = $(".select_p_item");
                                        $(".select_p_item").next().addClass("select_p_item");
                                        current.removeClass("select_p_item");
                                        current = null;
                                        scrollToSelectedItem();
                                    } else {
                                        if ($(".select_p_item").prev().length > 0) {
                                            var current = $(".select_p_item");
                                            $(".select_p_item").prev().addClass("select_p_item");
                                            current.removeClass("select_p_item");
                                            current = null;
                                            scrollToSelectedItem();
                                        }
                                    }
                                    if (inv != null) {
                                        inv.deleteItem(item_id);

                                    }
                                    current_recalled_invoice = 0
                                    current_recalled_invoice_data=null;
                                }
                            });

                        }
                    }, no: {
                        btnClass: "btn-secondary",
                        text: "No, Reload it",
                        action: () => {
                            recallInvoiceNow(current_recalled_invoice)
                        }
                    }
                }
            });
            
            
        } else {
            setTimeout(function () {
                var item_id = null;
                if (item_id_ > 0) {
                    item_id = item_id_;
                } else {
                    item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                }
                //var item_id = parseInt($(".select_p_item").attr("id").split('_')[1]);
                if ($(".select_p_item").next().length > 0) {
                    var current = $(".select_p_item");
                    $(".select_p_item").next().addClass("select_p_item");
                    current.removeClass("select_p_item");
                    current = null;
                    scrollToSelectedItem();
                } else {
                    if ($(".select_p_item").prev().length > 0) {
                        var current = $(".select_p_item");
                        $(".select_p_item").prev().addClass("select_p_item");
                        current.removeClass("select_p_item");
                        current = null;
                        scrollToSelectedItem();
                    }
                }
                if (inv != null) {
                    inv.deleteItem(item_id);
                }
            }, 50);
        }

    }

}


function print_hold_invoice(hold_invoice_id){
    var width = 500;
                    var height = 600;
                    var left = (screen.width - width) / 2;
                    var top = (screen.height - height) / 2;
        window.open("?r=printing&f=print_hold_invoice&p0=" + hold_invoice_id, '_blank', 'width=' + width + ', height=' + height + ', left=' + left + ', top=' + top);
}
