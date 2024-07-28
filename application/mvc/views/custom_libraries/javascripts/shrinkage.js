function add_shrinkage(id, data) {
    stores_options = "";
    for (var i = 0; i < all_stores.length; i++) {
        sel = "";
        if (i == 0) sel = "selected";
        stores_options += "<option " + sel + " value=" + all_stores[i].id + ">" + all_stores[i].name + "</option>";
    };
    
    var title="Add Shrinkage";
    if(id>0){
        title="Edit Shrinkage";
    }
    
    var btn="Add";
    if(id>0){
        btn="Update";
    }
    
    var content =
        '<div class="modal" data-backdrop="static" id="shrinkage_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <form id="shrinkage_form" action="" method="post" enctype="multipart/form-data" >\n\
                <input id="id_to_edit" name="id_to_edit" type="hidden" value="' + id + '" />\n\
                <div class="modal-content">\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+title+'<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="shrinkage_close()"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-xs-4">\n\
                                <div class="form-group">\n\
                                    <label for="stores_list">Store Name</label>\n\
                                    <select id="stores_list" name="stores_list" class="selectpicker form-control" style="width:100%">' + stores_options + '</select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-xs-8">\n\
                                <div class="form-group">\n\
                                    <label for="shrinkage_description">Description</label>\n\
                                    <input id="shrinkage_description" value="" name="shrinkage_description" type="text" class="form-control" placeholder=""/>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                        <a onclick="$(this).closest(\'form\').submit()" type="submit" class="btn btn-primary">'+btn+'</a>\n\
                    </div>\n\
                </div>\n\
            </form>\n\
        </div>\n\
    </div>';
    $("#shrinkage_modal").remove();
    $("body").append(content);

    $("#shrinkage_modal").centerWH();

    $('#shrinkage_modal').on('show.bs.modal', function(e) {});

    $('#shrinkage_modal').on('shown.bs.modal', function(e) {
        $('#stores_list').selectpicker();
        if (data.length > 0) {
            $("#shrinkage_description").val(data[0].description);
            $("#stores_list").val(data[0].store_id);
            $("#stores_list").selectpicker('refresh');
        }
    });

    $('#shrinkage_modal').on('hide.bs.modal', function(e) {
        $('#shrinkage_modal').remove();
    });

    submitShrinkAge(data);

    $('#shrinkage_modal').modal('show');
}


function shrinking(id) {
    var content =
        '<div class="modal" data-backdrop="static" id="shrinking_modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                                <h3 class="modal-title">Compare Stock<i style="float:right;font-size:30px; cursor:pointer" class="glyphicon glyphicon-remove" onclick="shrinking_close()"></i></h3>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-body" style="padding-bottom:0px !important;">\n\
                        <div class="row">\n\
                            <table id="shrinking_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 70px !important;">Shrinkage</th>\n\
                                        <th style="width: 35px !important;">Item</th>\n\
                                        <th style="width: 75px !important;">Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width: 60px !important;">Color</th>\n\
                                        <th style="width: 60px !important;">Size</th>\n\
                                        <th style="width: 60px !important;">Qty</th>\n\
                                        <th style="width: 90px !important;">New Stock</th>\n\
                                        <th style="width: 120px !important;">Checked Date</th>\n\
                                        <th style="width: 100px !important;">Average Cost/U</th>\n\
                                        <th style="width: 100px !important;">Total Lost Cost</th>\n\
                                        <th style="width: 70px !important;">Scanner Qty</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>ShrinkageShrinkage Id</th>\n\
                                        <th>Item</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Description</th>\n\
                                         <th>Color</th>\n\
                                        <th>Size</th>\n\
                                        <th>Qty</th>\n\
                                        <th>New Stock</th>\n\
                                        <th>Checked Date</th>\n\
                                        <th>Average Cost/U</th>\n\
                                        <th>Total Lost Cost</th>\n\
                                        <th>Scanner Qty</th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
    $("#shrinking_modal").remove();
    $("body").append(content);

    $("#shrinking_modal").centerWH();

    $('#shrinking_modal').on('show.bs.modal', function(e) {});

    $('#shrinking_modal').on('shown.bs.modal', function(e) {
        $(".sk-circle-layer").show();
        prepare_table_shrinking(id);
    });

    $('#shrinking_modal').on('hide.bs.modal', function(e) {
        $('#shrinking_modal').remove();
    });

    $('#shrinking_modal').modal('show');
}

var shrinkin_id_ = 0;

function submit_excel_to_shrinkage__(){
    var formData = new FormData();
    formData.append('excelfile', $('#excelfile')[0].files[0]);
    formData.append('id', shrinkin_id_);

    $("#excelfile_btn").prop("disabled", true);
    
    $(".sk-circle-layer").show();
      
    $.ajax({
        url: "?r=shrinkage&f=upload_excel_file",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function (data) {
            $(".sk-circle-layer").hide();
            $("#excelfile_btn").prop("disabled", false);
            
            $("#excelfile").val('');
            
            if(data.error>0){
               
            }else{
                
            }
            
            update_shrinking_table(shrinkin_id_);
        },
        error: function (xhr, ajaxOptions, thrownError) {

        }
    });
}



function prepare_table_shrinking(id) {
    shrinkin_id_ = id;
    var search_fields = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,10,11];
    var index = 0;
    $('#shrinking_table tfoot th').each(function() {

        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input id="idf_' + index + '" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="' + title + '" /></div>');
            index++;
        }
    });

    var shrinkage_table = $('#shrinking_table').dataTable({
        ajax: "?r=shrinkage&f=getAllShrinkagesDetails_New&p0=" + id + "&p1=0&p2=0&p3=0&p4=1",
        responsive: true,
        orderCellsTop: true,
        bLengthChange: true,
        iDisplayLength: 50,
        aoColumnDefs: [
            { "targets": [0], "searchable": true, "orderable": true, "visible": true },
            { "targets": [1], "searchable": true, "orderable": true, "visible": true },
            { "targets": [2], "searchable": true, "orderable": true, "visible": true },
            { "targets": [3], "searchable": true, "orderable": true, "visible": true },
            { "targets": [4], "searchable": true, "orderable": true, "visible": true },
            { "targets": [5], "searchable": true, "orderable": true, "visible": true },
            { "targets": [6], "searchable": true, "orderable": true, "visible": true },
            { "targets": [7], "searchable": true, "orderable": false, "visible": true },
            { "targets": [8], "searchable": true, "orderable": false, "visible": true },
            { "targets": [9], "searchable": true, "orderable": false, "visible": true },
            { "targets": [10], "searchable": true, "orderable": false, "visible": true },
            { "targets": [11], "searchable": true, "orderable": true, "visible": true }
        ],
        scrollY: '45vh',
        scrollCollapse: true,
        paging: true,
        order: [
            [0, "asc"]
        ],
        dom: '<"toolbar_sh">frtip',
        initComplete: function(settings) {
            //var table = $('#shrinkage_table').DataTable();
            //table.row(':eq(0)', { page: 'current' }).select();



            var stores_option = "";
            for (var i = 0; i < all_stores.length; i++) {
                stores_option += '<option value=' + all_stores[i].id + ' title="' + all_stores[i].name + '">' + all_stores[i].name + '</option>';
            }

            var suppliers_options = "";
            suppliers_options += '<option value=0 title="All Suppliers">All Sppliers</option>';
            for (var i = 0; i < all_suppliers.length; i++) {
                suppliers_options += '<option value=' + all_suppliers[i].id + ' title="' + all_suppliers[i].name + '">' + all_suppliers[i].name + '</option>';
            }

            var categories_parents_options = "";
            categories_parents_options += '<option value=0 title="All Categories">All Categories</option>';
            for (var i = 0; i < categories_parents.length; i++) {
                categories_parents_options += '<option value=' + categories_parents[i].id + ' title="' + categories_parents[i].name + '">' + categories_parents[i].name + '</option>';
            }

            var categories_options = "";
            categories_options += '<option selected value=0 title="All Sub-Categories">All Sub-Categories</option>';
            for (var i = 0; i < categories.length; i++) {
                categories_options += '<option value=' + categories[i].id + ' title="' + categories[i].name + '">' + categories[i].name + '</option>';
            }

            $("div.toolbar_sh").html('\n\
                <div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        <b>Total Lost: </b><span id="total_lost"></span>\n\
                    </div>\n\
                    <div class="col-lg-6 col-md-6 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        &nbsp;\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="form-group">\n\
                            <input type="file" class="form-control-file" id="excelfile" onchange="submit_excel_to_shrinkage__()">\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-right:5px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                            <select data-live-search="true" data-width="100%" id="suppliers_list" class="selectpicker" onchange="suppliers_list_changed(' + id + ')">\n\
                                ' + suppliers_options + '\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                             <select data-live-search="true" data-width="100%" id="categories_list" class="selectpicker" onchange="categories_list_changed(' + id + ')">\n\
                                 ' + categories_parents_options + '\n\
                             </select>\n\
                         </div>\n\
                     </div>\n\
                     <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                             <select multiple data-live-search="true" data-width="100%" id="subcategories_list" class="selectpicker" onchange="subcategories_list_changed(' + id + ')">\n\
                                 ' + categories_options + '\n\
                             </select>\n\
                         </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:15px;padding-right:5px;">\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100% !important;" onchange="group_changed(' + id + ')">\n\
                            <select data-width="100%" id="group_list" class="selectpicker">\n\
                                <option value="0" title="All Items">All Items (New Stock)</option>\n\
                                <option value="1" title="New Stock changed">New Stock changed</option>\n\
                                <option value="2" title="New Stock not changed">New Stock not changed</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-xs-12" style="padding-left:5px;padding-right:5px;">\n\
                         <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                             <select data-live-search="true" data-width="100%" id="shrinkage_qty_details" class="selectpicker" onchange="shrinkage_qty_details_changed(' + id + ')">\n\
                             <option value="0">All Items ()</option>\n\
                             <option value="1" selected>Only Differrent (QTY vs SQTY)</option>\n\
                             <option value="2">Only Different (QTY vs SQTY) & QTY > 0 </option>\n\
                             </select>\n\
                         </div>\n\
                    </div>\n\
                    \n\
                </div>\n\
                ');


            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [{
                    extend: 'excel',
                    text: 'Export excel',
                    className: 'exportExcel',
                    filename: 'Shrinkage',
                    customize: _customizeExcelOptions,
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function(data, row, column, node) {
                                // Strip $ from salary column to make it numeric
                                return column === 7 ? $("#id_" + parseInt(table.cell(row, 0).data().split('-')[1])).val() : data; //table.cell(row,0).data().split('-')[1]
                            }
                        }
                    }
                }]

            }).container().appendTo($('#buttons'));

            function _customizeExcelOptions(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                var clR = $('row', sheet);
                var r1 = Addrow(clR.length + 2, [{ key: 'A', value: "Total Lost" }, { key: 'B', value: $("#total_lost").html() }]);
                //var r2 = Addrow(clR.length+3, [{key:'A',value: "Total profit"},{key:'B',value: $("#total_profit").html()}]);
                //var r3 = Addrow(clR.length+4, [{key:'A',value: "Total Expenses"},{key:'B',value: $("#total_expenses").html()}]);
                //var r4 = Addrow(clR.length+5, [{key:'A',value: "Total Invoices Discounts"},{key:'B',value: $("#tm_discount").html()}]);
                //var r5 = Addrow(clR.length+6, [{key:'A',value: "Total Credit Notes"},{key:'B',value: $("#total_credit_notes").html()}]);
                sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;

                $('row c[r^="A' + (clR.length + 2) + '"]', sheet).attr('s', '48');
                //$('row c[r^="A'+(clR.length+3)+'"]', sheet).attr('s', '48');
                //$('row c[r^="A'+(clR.length+4)+'"]', sheet).attr('s', '48');
                //$('row c[r^="A'+(clR.length+5)+'"]', sheet).attr('s', '48');
                //$('row c[r^="A'+(clR.length+6)+'"]', sheet).attr('s', '48');

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

            $('.selectpicker').selectpicker();




            $("div.toolbar1").html('\n\
                <div class="row" style="margin-top:5px">\n\
                </div>\n\
                ');

            update_total_lost(shrinkin_id_);

            $(".sk-circle-layer").hide();
        },
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $(nRow).addClass(aData[0]);
        },
        fnDrawCallback: updateRows_,
    });

    $('#shrinking_table tbody').on('mouseenter', 'tr', function() {
        $(".selected_highlight").removeClass("selected_highlight");
        $(this).addClass("selected_highlight");
    });


    $('#shrinking_table').DataTable().columns().every(function() {
        var that = this;
        $('input', this.footer()).on('keyup change', function() {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#shrinking_table').on('page.dt', function() {



        //update_shrinking_table(shrinkin_id_);
        //updateRows_();
    });

    var table = $('#shrinking_table').DataTable();
    $('#shrinking_table tbody').on('dblclick', 'td', function() {
        // var rowIdx = table.cell( this ).index().row;
        //var colIdx = table.cell( this ).index().column;
        //table.cell(this).data('<input value="'+table.cell(this).data()+'" class="form-control input-sm" id="\'id_'+parseInt(table.cell(index, 0).data().split("-")[1])+'\'" type="text">');
    });


}


function group_changed(id) {
    update_shrinking_table(id);
}

function update_total_lost(id) {
    $.getJSON("?r=shrinkage&f=get_shrinkage_info&p0=" + id, function(data) {
        $("#total_lost").html(data.total_lost);
    }).done(function() {

    });
}

function categories_list_changed(id) {
    current_category_id = $("#categories_list").val();
    update_subcategories();
}

function subcategories_list_changed(id) {
    current_subcategory_id = $("#subcategories_list").val();
    update_shrinking_table(id);
}

function suppliers_list_changed(id) {
    current_supplier_id = $("#suppliers_list").val();
    update_shrinking_table(id);
}

function shrinkage_qty_details_changed(id) {
    // current_qty_id_info = $("#shrinkage_qty_details").val();
    update_shrinking_table(id);
}

function exclude_ietms_in_shrinkage(data) {
    var inp_val = "";
    for (var i = 0; i < data.length; i++) {
        if (i < data.length - 1) {
            inp_val += data[i].barcode + ",";
        } else {
            inp_val += data[i].barcode;
        }
    }



    $('body').append('<form id="exclude_ietms_in_shrinkage_form"><input type="hidden" name="id" id="id" value="' + shrinkin_id_ + '"><input type="hidden" name="barcodes_input" id="barcodes_input" value="' + inp_val + '"></form>');
    $("#exclude_ietms_in_shrinkage_form").on('submit', (function(e) {
        e.preventDefault();
        $(".sk-circle-layer").show();
        $.ajax({
            url: "?r=shrinkage&f=exclude_items_in_shrinkage",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                $(".sk-circle-layer").hide();
                $("#exclude_ietms_in_shrinkage_form").remove();
                update_shrinking_table(shrinkin_id_);
            }
        });
    }));
    $("#exclude_ietms_in_shrinkage_form").submit();

}

function hiligth() {
    var table = $('#shrinking_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        if (parseInt($('#id_' + parseInt(table.cell(index, 0).data().split("-")[1])).val()) != parseInt(table.cell(index, 4).data())) {
            $('#id_' + parseInt(table.cell(index, 0).data().split("-")[1])).css("background-color", "#f9cfc2");
        } else {
            //var row = table.row(p[k]).node();
            //$(row).addClass('tohide');
        }
    }
}


function update_shrinking_table(id) {
    var table = $('#shrinking_table').DataTable();
    $(".sk-circle-layer").show();
    table.ajax.url("?r=shrinkage&f=getAllShrinkagesDetails_New&p0=" + id + "&p1=" + $("#group_list").val() + "&p2=" + $("#suppliers_list").val() + "&p3=" + $("#subcategories_list").val() + "&p4=" + $("#shrinkage_qty_details").val()).load(function() {
        if (global_excel_barcode.length > 0) {
            for (var i = 0; i < global_excel_barcode.length; i++) {
                $(".brc_" + global_excel_barcode[i].barcode).val(0);
            }
            for (var i = 0; i < global_excel_barcode.length; i++) {
                $(".brc_" + global_excel_barcode[i].barcode).val(parseInt($(".brc_" + global_excel_barcode[i].barcode).val()) + 1);
            }
        }
        hiligth();
        $(".sk-circle-layer").hide();
    }, false);
}

function hide_rows_not_in_the_list_of_barcodes() {
    var barcodes = [];
    for (var i = 0; i < global_excel_barcode.length; i++) {
        barcodes.push(global_excel_barcode[i].barcode);
    }

    var table = $('#shrinking_table').DataTable();
    var p_ = table.rows().nodes();
    for (var k = 0; k < p_.length; k++) {
        if (jQuery.inArray(table.cell(k, 2).data(), barcodes) !== -1) {

        } else {
            table.row(p_[k]).remove().draw();
        }
    }

}

function updateRows_() {
    var table = $('#shrinking_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        if ($("#id_" + parseInt(table.cell(index, 0).data().split("-")[1])).length == 0) {
            if (table.cell(index, 11).data() > 0) {
                table.cell(index, 7).data('<input value="' + table.cell(index, 11).data() + '" class="form-control input-sm only_numeric brc_' + table.cell(index, 2).data() + '" style="width:48%;" id="id_' + parseInt(table.cell(index, 0).data().split("-")[1]) + '" type="text">&nbsp;<button onclick="change_qty_sh(' + parseInt(table.cell(index, 0).data().split("-")[1]) + ',' + index + ')" type="button" class="btn btn-xs btn-xxs btn-info" style="width:48%">Set</button>');
            } else {
                table.cell(index, 7).data('<input value="' + table.cell(index, 7).data() + '" class="form-control input-sm only_numeric brc_' + table.cell(index, 2).data() + '" style="width:48%;" id="id_' + parseInt(table.cell(index, 0).data().split("-")[1]) + '" type="text">&nbsp;<button onclick="change_qty_sh(' + parseInt(table.cell(index, 0).data().split("-")[1]) + ',' + index + ')" type="button" class="btn btn-xs btn-xxs btn-info" style="width:48%">Set</button>');
            }
        }
    }
    hiligth();
    $("#shrinking_table .only_numeric").numeric({ negative: true });
}

function change_qty_sh(id, index) {

    $.getJSON("?r=shrinkage&f=change_qty_sh&p0=" + id + "&p1=" + $("#id_" + id).val(), function(data) {
        var table = $('#shrinking_table').DataTable();
        table.cell(index, 8).data(data[0].checked_date);
        table.cell(index, 9).data(decimal_round(data[0].avg_cost, 5));
        table.cell(index, 10).data(data[0].total_cost);

        update_total_lost(shrinkin_id_);
    }).done(function() {

    });
}

function submitShrinkAge(data_) {
    $("#shrinkage_form").on('submit', (function(e) {
        e.preventDefault();
        if (!emptyInput("shrinkage_description")) {
            $(".sk-circle-layer").show();
            $.ajax({
                url: "?r=shrinkage&f=add_new_shrinkage",
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    var table = $('#shrinkage_table').DataTable();
                    table.ajax.url("?r=shrinkage&f=getAllShrinkages").load(function() {
                        if (data_.length > 0) {
                            table.row('.' + pad_shkrinkage(data_[0].id), { page: 'current' }).select();
                        } else {
                            table.page('last').draw(false);
                            table.row(':last', { page: 'current' }).select();

                            var sdata = table.row('.selected', 0).data();

                        }
                        $(".sk-circle-layer").hide();
                        $('#shrinkage_modal').modal('hide');
                    }, false);

                }
            });
        }
    }));
}

function shrinkage_close() {
    $('#shrinkage_modal').modal('toggle');
}

function shrinking_close() {
    $('#shrinking_modal').modal('toggle');
    var table = $('#shrinkage_table').DataTable();
    $(".sk-circle-layer").show();
    table.ajax.url("?r=shrinkage&f=getAllShrinkages").load(function() {
        $(".tab_toolbar button.blueB").addClass("disabled");
        $(".sk-circle-layer").hide();
    }, false);
}


function ExportToTable() {
    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
    /*Checks whether the file is a valid excel file*/
    if (regex.test($("#excelfile").val().toLowerCase())) {
        var xlsxflag = false; /*Flag for checking whether excel is .xls format or .xlsx format*/
        if ($("#excelfile").val().toLowerCase().indexOf(".xlsx") > 0) {
            xlsxflag = true;
        }
        /*Checks whether the browser supports HTML5*/
        if (typeof(FileReader) != "undefined") {
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = e.target.result;
                /*Converts the excel data in to object*/
                if (xlsxflag) {
                    var workbook = XLSX.read(data, { type: 'binary' });
                } else {
                    var workbook = XLS.read(data, { type: 'binary' });
                }
                /*Gets all the sheetnames of excel in to a variable*/
                var sheet_name_list = workbook.SheetNames;

                var cnt = 0; /*This is used for restricting the script to consider only first sheet of excel*/
                sheet_name_list.forEach(function(y) { /*Iterate through all sheets*/
                    /*Convert the cell value to Json*/
                    if (xlsxflag) {
                        var exceljson = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                    } else {
                        var exceljson = XLS.utils.sheet_to_row_object_array(workbook.Sheets[y]);
                    }
                    if (exceljson.length > 0 && cnt == 0) {
                        //BindTable(exceljson, '#exceltable');  
                        fetchExcel(exceljson);
                        cnt++;
                    }
                });
                $('#exceltable').show();
            }
            if (xlsxflag) { /*If excel file is .xlsx extension than creates a Array Buffer from excel*/
                reader.readAsArrayBuffer($("#excelfile")[0].files[0]);
            } else {
                reader.readAsBinaryString($("#excelfile")[0].files[0]);
            }
        } else {
            alert("Sorry! Your browser does not support HTML5!");
        }
    } else {
        alert("Please upload a valid Excel file!");
    }
}


var global_excel_barcode = [];

function fetchExcel(jsondata) {
    global_excel_barcode = jsondata;
    exclude_ietms_in_shrinkage(global_excel_barcode);
    //update_shrinking_table(shrinkin_id_);


    /*hide_rows_not_in_the_list_of_barcodes();
    
    for (var i = 0; i < global_excel_barcode.length; i++) {
        $(".brc_"+global_excel_barcode[i].barcode).val(0);
    }
    for (var i = 0; i < global_excel_barcode.length; i++) { 
        $(".brc_"+global_excel_barcode[i].barcode).val(parseInt($(".brc_"+global_excel_barcode[i].barcode).val())+1);
    }*/
}

function BindTable(jsondata, tableid) { /*Function used to convert the JSON array to Html Table*/
    var columns = BindTableHeader(jsondata, tableid); /*Gets all the column headings of Excel*/
    for (var i = 0; i < jsondata.length; i++) {
        var row$ = $('<tr/>');
        for (var colIndex = 0; colIndex < columns.length; colIndex++) {
            var cellValue = jsondata[i][columns[colIndex]];
            if (cellValue == null)
                cellValue = "";
            row$.append($('<td/>').html(cellValue));
        }
        $(tableid).append(row$);
    }
}

function BindTableHeader(jsondata, tableid) { /*Function used to get all column names from JSON and bind the html table header*/
    var columnSet = [];
    var headerTr$ = $('<tr/>');
    for (var i = 0; i < jsondata.length; i++) {
        var rowHash = jsondata[i];
        for (var key in rowHash) {
            if (rowHash.hasOwnProperty(key)) {
                if ($.inArray(key, columnSet) == -1) { /*Adding each unique column names to a variable array*/
                    columnSet.push(key);
                    headerTr$.append($('<th/>').html(key));
                }
            }
        }
    }
    $(tableid).append(headerTr$);
    return columnSet;
}