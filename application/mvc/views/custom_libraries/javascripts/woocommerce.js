var woo_item_categories = [];
var woo_pos_parent_categories = [];
var woo_woocommerce_parent_categories = [];

function show_woocommerce_items_info() {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();

    modal_name = "woocommerce_items_modal";
    modal_title = "<i class='glyphicon glyphicon-briefcase'></i>&nbsp;Woocommerce";

    var content =
        `<div class= "modal" data-backdrop="static" id="${modal_name}" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" >
    <div class="modal-dialog" role="document">\n\
        <div class="modal-content">\n\
            <div class="modal-header" style="padding-top:5px !important;padding-bottom:5px !important;"> \n\
            <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
            </div>\n\
            <div class="modal-body">\n\
                <div id="woocommerce-tabs">\n\
                    <ul class="nav nav-pills">\n\
                        <li class="active">\n\
                            <a  href="#woo_pos_catgories_tab" data-toggle="tab">Pos Sub-Categories</a>\n\
                        </li>\n\
                        <li class="">\n\
                            <a  href="#woo_categories_tab" data-toggle="tab">Woocommerce  Sub-Categories</a>\n\
                        </li>\n\
                        <li class="">\n\
                            <a  href="#woo_items_tab" data-toggle="tab">Simple Items</a>\n\
                        </li>\n\
                        <li class="">\n\
                        <a  href="#woo_variable_items_tab" data-toggle="tab">Variable Items</a>\n\
                    </li>\n\
                    </ul>\n\
                    <div class="tab-content" style="padding-top:5px !important;">\n\
                        <div class="tab-pane active" id="woo_pos_catgories_tab">\n\
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <h4 style="margin-bottom:1px !important;margin-top:3px !important;"></h4>
                                    <table id="woo_pos_catgories_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Sub-Category</th>
                                            <th>Category</th>
                                            <th>Is Sync</th>
                                            <th style="width: 30px !important;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                            <th>Sub-Category</th>
                                            <th>Category</th>
                                            <th></th>
                                            <th style="width:  30px !important;"></th>
                                            </tr>
                                        </tfoot>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>\n\
                        <div class="tab-pane " id="woo_categories_tab">\n\
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <h4 style="margin-bottom:1px !important;margin-top:3px !important;"></h4>
                                    <table id="woocommerce_categories_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Category ID</th>
                                            <th>Sub-Category</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                            <th style="width: 80px !important;">Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                            <th>Category ID</th>
                                            <th>Sub-Category</th>
                                            <th>Description</th>
                                            <th>Category</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>\n\
                        
                        <div class="tab-pane " id="woo_items_tab">\n\
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <h4 style="margin-bottom:1px !important;margin-top:3px !important;"></h4>
                                    <table id="woocommerce_items_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th style="width:70px;">Item ID</th>
                                                <th style="width:70px;">Code</th>
                                                <th>Description</th>
                                                <th style="width:70px;">Barcode</th>
                                                <th style="width:70px;">Color</th>
                                                <th style="width:70px;">Size</th>
                                                <th>Is Sync</th>
                                                <th style="width: 180px !important;">Actions</th>                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Item ID</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Barcode</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Is Sync</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>\n\
                        <div class="tab-pane " id="woo_variable_items_tab">\n\
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                <h4 style="margin-bottom:1px !important;margin-top:3px !important;"></h4>
                                    <table id="woocommerce_variable_items_table" class="table table-striped table-bordered" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th style="width:70px;">Item ID</th>
                                                <th style="width:70px;">Code</th>
                                                <th>Description</th>
                                                <th style="width:70px;">Barcode</th>
                                                <th style="width:70px;">Color</th>
                                                <th style="width:70px;">Size</th>
                                                <th>Is Sync</th>
                                                <th style="width: 180px !important;">Actions</th>                                            </tr>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Item ID</th>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Barcode</th>
                                                <th>Color</th>
                                                <th>Size</th>
                                                <th>Is Sync</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
            <form/>\n\
        </div>\n\
    </div>\n\
</div> `;

    $("#" + modal_name).remove();
    $("body").append(content);

    $('#' + modal_name).on('shown.bs.modal', function (e) {

        $(".sk-circle-layer").hide();
        var _data = [];
        $.getJSON("?r=categories&f=getCategories", function (data) {
            _data = data
            $.each(_data.cat, function (key, val) {
                woo_item_categories.push({ id: val.id, description: val.description, parent: val.parent });
            });
            woo_pos_parent_categories = (_data.catp);

        });



        get_pos_categories_info_table();

        $('#woocommerce-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href");
            if (target == "#woo_pos_catgories_tab") {
                if ($.fn.DataTable.isDataTable('#woo_pos_catgories_table')) {
                    $('#woo_pos_catgories_table').DataTable().ajax.reload();

                } else {
                    get_pos_categories_info_table();
                }

            }
            if (target == "#woo_items_tab") {
                if ($.fn.DataTable.isDataTable('#woocommerce_items_table')) {
                    $('#woocommerce_items_table').DataTable().ajax.reload();

                } else {
                    get_woo_items_info_table();
                }

            }
            if (target == "#woo_categories_tab") {
                load_woocommerce_parent_categories_filter();
                load_woocommerce_categories_table();
                change_parent_category_btn_text();

            }

            if (target == "#woo_variable_items_tab") {
                if ($.fn.DataTable.isDataTable('#woocommerce_variable_items_table')) {
                    $('#woocommerce_variable_items_table').DataTable().ajax.reload();

                } else {
                    get_woo_variable_items_info_table();
                }

            }

        });

    });

    $('#' + modal_name).on('hide.bs.modal', function (e) {
        $("#" + modal_name).remove();
    });

    $('#' + modal_name).modal('show');



}

/*  WOOCOMMERCE ITEMS */
function get_woo_items_info_table() {

    var search_fields = [0, 1, 2, 3, 4, 5, 6];
    var index = 0;
    $('#woocommerce_items_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });



    $('#woocommerce_items_table').dataTable({
        ajax: "?r=woocommerce&f=get_all_items_info&p0=" + ($("#filter_search_woo_items").val() ?? "") + "&p1=" + ($("#woo_item_parent_categories").val() ?? "0") + "&p2=" + ($("#woo_item_categories").val() ?? "0") + "&p3=0",
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0, 1, 2, 3, 4],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [6],
            "searchable": false,
            "orderable": false,
            "visible": false
        }, {
            "targets": [7],
            "searchable": true,
            "orderable": false,
            "visible": true
        }
        ],
        scrollY: '42vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        bInfo: true,
        bSort: true,
        dom: '<"toolbar_woocommerce_items_table">frtip',
        initComplete: function (settings) {
            $('#woocommerce_items_table').show();

            $("#woo_item_parent_categories").empty();
            $("#woo_item_categories").empty();


            $("div.toolbar_woocommerce_items_table").html('\n\
            <div class="row" style="margin-top:5px;">\n\
                <div class="col-lg-2 col-md-2 col-sm-12 pl10 " >\n\
                    <button class="btn btn-primary" onclick="sync_all_items(0)" style="width:100%">Sync All Items</button>\n\
                </div>\n\
                <div class="col-lg-4 col-md-4 col-sm-12 pl10 " >\n\
                    <select  id="filter_search_woo_items" multiple style="width:100%"  class="form-control" onchange="refresh_woocommerce_items_table();">\n\
                    </select>\n\
                </div>\n\
                <div class="col-lg-3 col-md-3 col-sm-12 pl2" >\n\
                    <select  id="woo_item_parent_categories"  style="cursor:pointer;width:100%"  class="form-control" onchange="woo_categories_list_changed();" >\n\
                    </select>\n\
                </div>\n\
                <div class="col-lg-3 col-md-3 col-sm-12 pl2" >\n\
                    <select  id="woo_item_categories"  style="cursor:pointer;width:100%"  class="form-control" onchange="refresh_woocommerce_items_table();" >\n\
                    </select>\n\
                </div>\n\
            </div>\n\
            ');


            var _data = [];
            var woo_categories_parents_options = "";
            var woo_categories_options = "";

            $.getJSON("?r=categories&f=getCategories", function (data) {
                _data = data

            }).done(function () {

                woo_categories_parents_options += "<option value='0'>All Categories</option>";
                woo_categories_options += "<option value='0'>All Sub-Categories</option>";

                $.each(_data.catp, function (key, val) {
                    woo_categories_parents_options += "<option value=" + val.id + ">" + val.name + "</option>";
                });
                $("#woo_item_parent_categories").append(woo_categories_parents_options);

                $.each(_data.cat, function (key, val) {
                    woo_categories_options += "<option value=" + val.id + ">" + val.description + "</option>";
                });
                $("#woo_item_categories").append(woo_categories_options);
            });



            $("#filter_search_woo_items").select2({
                ajax: {
                    url: '?r=woocommerce&f=search',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1,
                            p2: params.is_variable || 0

                        }
                        return query;
                    },
                    delay: 250,
                    dataType: 'json'
                },
                placeholder: "Search by items",
                allowClear: true
            });



            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: function () {
            woocommerce_items_table_callback();

        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
    });

    $('#woocommerce_items_table').on('page.dt', function () {
        woocommerce_items_table_callback();
    });

    $('#woocommerce_items_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#woocommerce_items_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function woocommerce_items_table_callback() {

    var table = $('#woocommerce_items_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {

        var index = table.row(p[k]).index();
        var item_action_btn2="";
        if (table.cell(index, 6).data() == 0) {
            var item_action_btn = '<button  title="Add Item" class="btn btn-primary btn-xs" onclick="sync_item(' + table.cell(index, 0).data() + ',0,0)"><i class="glyphicon glyphicon-plus" style="float: left;padding-top:1px;"></i> &nbsp; Add</button>';
            var item_action_btn2 = '<button  title="Add Item" class="btn btn-primary btn-xs" onclick="sync_item(' + table.cell(index, 0).data() + ',0,1)"><i class="glyphicon glyphicon-plus" style="float: left;padding-top:1px;"></i> &nbsp; Add with Quantity</button>';

        } else {
            var item_action_btn = '<button  title="Delete Item"  class="btn btn-danger btn-xs" onclick="delete_woo_item(' + table.cell(index, 0).data() + ',0)"><i class="glyphicon glyphicon-trash" style="float: left;padding-top:1px;"></i> &nbsp; Delete</button>';
        }

        table.cell(index, 7).data(item_action_btn+"&nbsp;&nbsp;"+item_action_btn2);


    }


}



function woo_categories_list_changed() {
    $('#woo_item_categories').empty();
    var woo_categories_options = '<option value="0" >All Sub-Categories</option>';
    $.each(woo_item_categories, function (key, val) {
        if ($("#woo_item_parent_categories").val() == val.parent && $("#woo_item_parent_categories").val() != 0) {
            woo_categories_options += "<option value=" + val.id + ">" + val.description + "</option>";
        }
        if ($("#woo_item_parent_categories").val() == 0) {
            woo_categories_options += "<option value=" + val.id + ">" + val.description + "</option>";
        }
    });
    $("#woo_item_categories").append(woo_categories_options);
    refresh_woocommerce_items_table();
}

function sync_all_items(is_variable_item) {
    swal({
        title: "Are you sure?",
        html: true,
        text: '<p class="text-primary">Proceed with syncing all items to Woocommerce website by selecting Yes</p>',
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=sync_all_items&p0=`+is_variable_item, function (data) {
                    _data = data;
                }).done(function () {
                    if (_data.error == 0) {
                        if (is_variable_item == 0) {
                            refresh_woocommerce_items_table();

                        } else {
                            refresh_woocommerce_variable_items_table();

                        }
                    } else {
                        $(".sk-circle-layer").hide();
                        alert(_data.msg);
                    }
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        }

    );
}
function sync_item(item_id, is_variable_item,sync_item_quantity) {

    swal({
        title: "Are you sure?",
        html: true,
        text: `<p class="text-primary">Add Item ID ${item_id} into Woocommerce?, click yes to continue </p>`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=sync_itemid&p0=${item_id}&p1=true&p2=${is_variable_item}&p3=${sync_item_quantity}`, function (data) {
                    _data = data;
                }).done(function () {
                    if (_data.error == 0) {
                        if (is_variable_item == 0) {
                            refresh_woocommerce_items_table();

                        } else {
                            refresh_woocommerce_variable_items_table();

                        }

                    } else {
                        $(".sk-circle-layer").hide();
                        alert(_data.msg);
                    }

                });
            } else {
                $(".sk-circle-layer").hide();

            }
        }

    );
}


function delete_woo_item(item_id, is_variable_item) {
 
    swal({
        title: "Are you sure?",
        text: `Delete Item ID ${item_id} from Woocommerce?, click delete to continue`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=delete_itemid&p0=${item_id}&p1=${is_variable_item}`, function (data) {
                    _data = data
                }).done(function () {
                    if (_data.error == 0) {
                        if (is_variable_item == 0) {
                            refresh_woocommerce_items_table();

                        } else {
                            refresh_woocommerce_variable_items_table();

                        }
                    } else {
                        $(".sk-circle-layer").hide();
                        alert(_data.msg);
                    }
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        });
}

function refresh_woocommerce_items_table() {
    $(".sk-circle-layer").show();
    $('#woocommerce_items_table').DataTable().ajax.url("?r=woocommerce&f=get_all_items_info&p0=" + ($("#filter_search_woo_items").val() ?? "") + "&p1=" + ($("#woo_item_parent_categories").val() ?? "0") + "&p2=" + ($("#woo_item_categories").val() ?? "0") + "&p3=0").load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#woocommerce_items_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);

}


/* END WOOCOMMERCE ITEMS */

/* POS SUB-Categories  */

function get_pos_categories_info_table() {

    var search_fields = [0, 1, 2];
    var index = 0;
    $('#woo_pos_catgories_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });



    $('#woo_pos_catgories_table').dataTable({
        ajax: "?r=woocommerce&f=get_all_pos_categories&p0=" + ($("#woo_pos_p_categories").val() ?? "0"),
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0, 1, 2],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [3],
            "visible": true
        }
        ],
        scrollY: '42vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        bInfo: true,
        bSort: true,
        dom: '<"toolbar_woo_pos_catgories_table">frtip',
        initComplete: function (settings) {

            var woo_pos_parent_catgories_options = "";
            for (var i = 0; i < woo_pos_parent_categories.length; i++) {
                woo_pos_parent_catgories_options += "<option value='" + woo_pos_parent_categories[i].id + "'>" + woo_pos_parent_categories[i].name + "</option>";
            }

            $("div.toolbar_woo_pos_catgories_table").html('\n\
            <div class="row" style="margin-top:5px;">\n\
            <div class="col-lg-2 col-md-2 col-sm-12 pl10 " >\n\
                <button class="btn btn-primary" onclick="sync_all_categories()" style="width:100%">Sync All</button>\n\
            </div>\n\
            <div class="col-lg-3 col-md-3 col-sm-12 pl2" >\n\
                <select  id="woo_pos_p_categories"  style="cursor:pointer;width:100%"  class="form-control" onchange="refresh_woo_pos_categories_table();" >\n\
                <option value="0">All Categories</option>'+ woo_pos_parent_catgories_options + '</select>\n\
            </div>\n\
        </div>\n\
        </div>\n\
            ');




            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: function () {
            woo_pos_catgories_table_callback();

        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
    });

    $('#woo_pos_catgories_table').on('page.dt', function () {
        woo_pos_catgories_table_callback();
    });

    $('#woo_pos_catgories_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#woo_pos_catgories_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function woo_pos_catgories_table_callback() {

    var table = $('#woo_pos_catgories_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        var cat_action_btn = '<button  title="Sync Category" class="btn btn-primary btn-xs" onclick="sync_pos_catgory(' + table.cell(index, 0).data() + ')"><i class="glyphicon glyphicon-plus" style="float: left;padding-top:1px;"></i> &nbsp; Sync</button>';
        table.cell(index, 4).data(cat_action_btn);

    }
}


function refresh_woo_pos_categories_table() {
    $(".sk-circle-layer").show();
    $('#woo_pos_catgories_table').DataTable().ajax.url("?r=woocommerce&f=get_all_pos_categories&p0=" + ($("#woo_pos_p_categories").val() ?? "0")).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#woo_pos_catgories_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);

}


function sync_pos_catgory(category_id) {

    swal({
        title: "Are you sure?",
        html: true,
        text: `<p class="text-primary">Sync Sub-Category ID ${category_id} into Woocommerce?, click yes to continue </p>`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=sync_categoryid&p0=${category_id}&p1=true`, function (data) {
                    _data = data;
                }).done(function () {
                    if (_data.error == 0) {
                        refresh_woo_pos_categories_table();
                    } else {
                        $(".sk-circle-layer").hide();
                        alert(_data.msg);
                    }

                });
            } else {
                $(".sk-circle-layer").hide();

            }
        }

    );
}


function sync_all_categories() {
    swal({
        title: "Are you sure?",
        html: true,
        text: '<p class="text-primary">Proceed with syncing all Sub-Categories to Woocommerce website by selecting Yes</p>',
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON("?r=woocommerce&f=sync_all_categories&p0=" + ($("#woo_pos_p_categories").val() ?? "0"), function (data) {
                    _data = data;
                }).done(function () {
                    if (_data.error == 0) {
                        refresh_woo_pos_categories_table();
                    } else {
                        $(".sk-circle-layer").hide();
                        alert(_data.msg);
                    }
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        }

    );
}

/* END POS SUB-Categories  */


/* WOOCOMMERCE SUB-Categories  */


function get_woocommerce_categories_info_table() {

    var search_fields = [0, 2, 3, 4];
    var index = 0;
    $('#woocommerce_categories_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });



    $('#woocommerce_categories_table').dataTable({
        ajax: "?r=woocommerce&f=get_all_woocommerce_categories&p0=" + ($("#woo_woocommerce_p_categories").val() ?? "-1"),
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0, 2, 3, 4],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [1],
            "visible": false
        }
        ],
        scrollY: '42vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        bInfo: true,
        bSort: true,
        dom: '<"toolbar_woocommerce_categories_table">frtip',
        initComplete: function (settings) {
            var woo_woocommerce_p_categories_options = "";
            for (var i = 0; i < woo_woocommerce_parent_categories.length; i++) {
                woo_woocommerce_p_categories_options += "<option value='" + woo_woocommerce_parent_categories[i].id + "'>" + woo_woocommerce_parent_categories[i].name + "</option>";
            }

            $("div.toolbar_woocommerce_categories_table").html('\n\
            <div class="row" style="margin-top:5px;">\n\
            <div class="col-lg-2 col-md-2 col-sm-12 pl10 " >\n\
                <button class="btn btn-primary" onclick="sync_woocommerce_category_parents()" style="width:100%">Sync Woocommerce Info</button>\n\
            </div>\n\
            <div class="col-lg-3 col-md-3 col-sm-12 pl2" >\n\
                <select  id="woo_woocommerce_p_categories"  style="cursor:pointer;width:100%"  class="form-control" onchange="refresh_woo_woocommerce_categories_table();change_parent_category_btn_text();" >\n\
                <option value="-1">All Categories</option>'+ woo_woocommerce_p_categories_options + '</select>\n\
            </div>\n\
            <div class="col-lg-3 col-md-3  pl2" >\n\
            <button  style="cursor:pointer;"  class="form-control ml4 btn btn-primary " onclick="add_edit_woocommerce_parent_category();" id="woo_add_cat_btn" >Add Category </button>\n\
            <button   style="cursor:pointer;"  class="form-control ml4 btn btn-primary" onclick="add_edit_woocommerce_category('+ ($("#woo_woocommerce_p_categories").val() ?? "-1") + ',0);" >Add Sub-Category </button>\n\
            <button   style="cursor:pointer;"  class="form-control ml4 btn btn-danger" onclick="delete_woocommerce_parent_category();" >Delete Category </button>\n\
            </div> \n\
        </div>\n\
        </div>\n\
            ');




            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: function () {
            woocommerce_categories_table_callback();

        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
    });

    $('#woocommerce_categories_table').on('page.dt', function () {
        woocommerce_categories_table_callback();
    });

    $('#woocommerce_categories_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#woocommerce_categories_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function woocommerce_categories_table_callback() {

    var table = $('#woocommerce_categories_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        var cat_action_btn = '&nbsp;&nbsp;<button  title="Delete Category" class="btn btn-danger btn-xs" onclick="delete_woo_category(' + table.cell(index, 0).data() + ')"> &nbsp; Delete</button>';
        table.cell(index, 5).data(cat_action_btn);
        var edit_action_btn = '<button  title="Edit Category" class="btn btn-primary btn-xs" onclick="add_edit_woocommerce_category(' + table.cell(index, 1).data() + ',' + table.cell(index, 0).data() + ')">&nbsp; Edit</button>';
        table.cell(index, 5).data(edit_action_btn + cat_action_btn);

    }
}


function refresh_woo_woocommerce_categories_table() {
    $(".sk-circle-layer").show();
    $('#woocommerce_categories_table').DataTable().ajax.url("?r=woocommerce&f=get_all_woocommerce_categories&p0=" + ($("#woo_woocommerce_p_categories").val() ?? "-1")).load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#woocommerce_categories_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);

}




function delete_woo_category(category_id) {

    swal({
        title: "Are you sure?",
        text: `Delete Category ID ${category_id} from Woocommerce?, click delete to continue`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=delete_woo_categoryid&p0=${category_id}`, function (data) {
                    _data = data
                }).done(function () {
                    if (_data.error == 0) {
                        refresh_woo_woocommerce_categories_table();
                    } else {
                        $(".sk-circle-layer").hide();
                        alert(_data.msg);
                    }
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        });
}




function load_woocommerce_categories_table() {
    if ($.fn.DataTable.isDataTable('#woocommerce_categories_table')) {
        // $('#woocommerce_categories_table').DataTable().ajax.reload();
        $('#woocommerce_categories_table').DataTable().ajax.url("?r=woocommerce&f=get_all_woocommerce_categories&p0=" + ($("#woo_woocommerce_p_categories").val() ?? "-1")).load(function () {
            setTimeout(function () {
                $('#woocommerce_categories_table').DataTable().columns.adjust().draw();
            }, 100);
        }, false);
    } else {
        get_woocommerce_categories_info_table();
    }
}

function load_woocommerce_parent_categories_filter() {
    $("#woo_woocommerce_p_categories").empty();
    woo_woocommerce_parent_categories = [];
    var _data = [];
    $.getJSON("?r=woocommerce&f=get_woocommerce_parent_categories&p0=true", function (data) {
        _data = data;
        woo_woocommerce_parent_categories = (_data);
    }).done(function () {
        var woo_woocommerce_p_categories_options = "<option value='-1'>All Categories</option>";
        for (var i = 0; i < _data.length; i++) {
            woo_woocommerce_p_categories_options += "<option value='" + _data[i].id + "'>" + _data[i].name + "</option>";
        }
        $("#woo_woocommerce_p_categories").html(woo_woocommerce_p_categories_options);
    });


}

function add_edit_woocommerce_category(woo_parent_cat_id, woo_cat_id) {



    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var id_to_edit = 0;
    var modal_title = "Add New Sub-Category";
    var cat_name = "";
    var cat_desc = "";
    var woo_parent_cat_select_disabled = "";
    var new_cat_btn_txt = "Add";
    if (woo_cat_id > 0) {
        new_cat_btn_txt = "Update";
        var woo_parent_cat_select_disabled = "disabled";
        var table = $('#woocommerce_categories_table').DataTable();
        var p = table.rows({
            page: 'current'
        }).nodes();

        for (var k = 0; k < p.length; k++) {
            var index = table.row(p[k]).index();
            if (table.cell(index, 0).data() == woo_cat_id) {
                var cat_name = table.cell(index, 2).data();
                var cat_desc = table.cell(index, 3).data();
            }
        }

    } else {
        woo_parent_cat_id = ($("#woo_woocommerce_p_categories").val() ?? "-1");
    }

    var woo_woocommerce_p_categories_options = "";
    for (var i = 0; i < woo_woocommerce_parent_categories.length; i++) {
        var parent_cat_selected = ""
        if (woo_parent_cat_id == woo_woocommerce_parent_categories[i].id) {
            parent_cat_selected = "selected";
        }
        woo_woocommerce_p_categories_options += "<option " + parent_cat_selected + " value='" + woo_woocommerce_parent_categories[i].id + "'>" + woo_woocommerce_parent_categories[i].name + "</option>";
    }

    if (woo_cat_id > 0) {
        id_to_edit = woo_cat_id;
        var modal_title = "Edit Sub-Category";
    }
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_woo_category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog modal-sm" role="document">\n\
            <div class="modal-content">\n\
                \n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id=""><i class="glyphicon glyphicon-plus"></i>&nbsp;'+ modal_title + '<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_woo_category_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label >Category</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><select '+ woo_parent_cat_select_disabled + ' id="woo_p_cat_id" name="woo_p_cat_id" class="form-control" placeholder="">' + woo_woocommerce_p_categories_options + '</select></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label>Name</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="woo_cat_name" name="woo_cat_name" type="text" class="form-control" value="'+ cat_name + '"></div>\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label  >Description</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><textarea id="woo_cat_desc" name="woo_cat_desc" class="form-control" placeholder="">'+ cat_desc + '</textarea></div>\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="submit_new_woo_category" onclick="submit_new_woo_category('+ woo_cat_id + ')" type="submit" class="btn btn-primary">' + new_cat_btn_txt + '</button>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_woo_category_modal').remove();
    $('body').append(content);

    $('#add_new_woo_category_modal').on('show.bs.modal', function (e) {
        $(".sk-circle-layer").hide();

    });



    $('#add_new_woo_category_modal').on('hide.bs.modal', function (e) {
        $("#add_new_woo_category_modal").remove();
    });

    $('#add_new_woo_category_modal').modal('show');
}


function submit_new_woo_category(woo_cat_id) {
    var inputs_is_filled = true;
    $(".input_border_error").removeClass('input_border_error');
    // if ($("#woo_cat_desc").val() == "") {
    //     $("#woo_cat_desc").addClass('input_border_error');
    //     inputs_is_filled = false;
    // }
    if ($("#woo_cat_name").val() == "") {
        $("#woo_cat_name").addClass('input_border_error');
        inputs_is_filled = false;
    }


    if (inputs_is_filled) {
        $(".sk-circle-layer").show();
        var formData = new FormData();

        formData.append("p_cat_id", $("#woo_p_cat_id").val());
        formData.append("desc", $("#woo_cat_desc").val());
        formData.append("name", $("#woo_cat_name").val());
        formData.append("category_id", woo_cat_id);
        $.ajax({
            url: "?r=woocommerce&f=add_new_woo_category",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                $(".sk-circle-layer").hide();
                $('#add_new_woo_category_modal').modal('hide');

                if (data.error == 0) {
                    load_woocommerce_categories_table();
                } else {
                    alert("Sub-Category was not added!"); // change it
                }





            }

        });
    }


}



//


function add_edit_woocommerce_parent_category() {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var modal_title = "Add New Category";
    var parent_cat_name = "";
    var parent_cat_desc = "";

    woo_parent_cat_id = ($("#woo_woocommerce_p_categories").val() ?? "-1");
    var woo_p_cat_array = [];
    for (var i = 0; i < woo_woocommerce_parent_categories.length; i++) {
        woo_p_cat_array[woo_woocommerce_parent_categories[i].id] = woo_woocommerce_parent_categories[i];
    }


    var new_p_cat_btn_txt = "Add";
    if (woo_parent_cat_id > 0) {
        var modal_title = "Edit Category ID " + woo_parent_cat_id
        new_p_cat_btn_txt = "Update";
        parent_cat_name = woo_p_cat_array[woo_parent_cat_id].name;
        parent_cat_desc = woo_p_cat_array[woo_parent_cat_id].description;
    }
    var content =
        '<div class="modal" data-backdrop="static" data-keyboard="false" id="add_new_woo_parent_category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
        <div class="modal-dialog modal-sm" role="document">\n\
            <div class="modal-content">\n\
                \n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" id=""><i class="glyphicon glyphicon-plus"></i>&nbsp;'+ modal_title + '<i style="float:right;font-size:30px" class="glyphicon glyphicon-remove" onclick="closeModal(\'add_new_woo_parent_category_modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label>Name</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><input id="woo_p_cat_name" name="woo_p_cat_name" type="text" class="form-control" value="'+ parent_cat_name + '"></div>\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <div class="form-group">\n\
                                <label>Description</label>\n\
                                <div class="inner-addon left-addon addon_item_icon"><textarea id="woo_p_cat_desc" name="woo_p_cat_desc" class="form-control" placeholder="">'+ parent_cat_desc + '</textarea></div>\n\
                                </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
                <div class="modal-footer">\n\
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>\n\
                    <button id="submit_new_woo_parent_category" onclick="submit_new_woo_parent_category('+ woo_parent_cat_id + ')" type="submit" class="btn btn-primary">' + new_p_cat_btn_txt + '</button>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';

    $('#add_new_woo_parent_category_modal').remove();
    $('body').append(content);

    $('#add_new_woo_parent_category_modal').on('show.bs.modal', function (e) {
        $(".sk-circle-layer").hide();

    });
    $('#add_new_woo_parent_category_modal').on('hide.bs.modal', function (e) {
        $("#add_new_woo_parent_category_modal").remove();
    });

    $('#add_new_woo_parent_category_modal').modal('show');
}

//submit_new_woo_parent_category




function submit_new_woo_parent_category(woo_p_cat_id) {
    var inputs_is_filled = true;
    $(".input_border_error").removeClass('input_border_error');
    // if ($("#woo_p_cat_desc").val() == "") {
    //     $("#woo_p_cat_desc").addClass('input_border_error');
    //     inputs_is_filled = false;
    // }
    if ($("#woo_p_cat_name").val() == "") {
        $("#woo_p_cat_name").addClass('input_border_error');
        inputs_is_filled = false;
    }


    if (inputs_is_filled) {
        $(".sk-circle-layer").show();
        var formData = new FormData();

        formData.append("desc", $("#woo_p_cat_desc").val());
        formData.append("name", $("#woo_p_cat_name").val());
        formData.append("p_cat_id", woo_p_cat_id);
        $.ajax({
            url: "?r=woocommerce&f=add_new_woo_parent_category",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function (_data) {
                $(".sk-circle-layer").hide();
                if (_data.error == 0) {
                    $('#add_new_woo_parent_category_modal').modal('hide');
                    var woo_woocommerce_p_categories_options = "<option value='-1'>All Categories</option>";
                    for (var i = 0; i < _data.parent_categories.length; i++) {
                        var parent_cat_selected = "";
                        if (woo_p_cat_id == _data.parent_categories[i].id) {
                            parent_cat_selected = "selected";
                        }
                        woo_woocommerce_p_categories_options += "<option " + parent_cat_selected + " value='" + _data.parent_categories[i].id + "'>" + _data.parent_categories[i].name + "</option>";
                    }
                    woo_woocommerce_parent_categories = _data.parent_categories;
                    $("#woo_woocommerce_p_categories").empty();
                    $("#woo_woocommerce_p_categories").html(woo_woocommerce_p_categories_options);
                    load_woocommerce_categories_table();

                } else {
                    alert("Category was not added!"); // change it
                }





            }

        });
    }


}



function delete_woocommerce_parent_category() {
    var woo_parent_cat_id = ($("#woo_woocommerce_p_categories").val() ?? "-1");

    if (woo_parent_cat_id == -1) {
        var delete_text = "Delete all categories? Please be aware that all sub-categories will be reassigned as the main category and items below these categories will be merged to Uncategorized Category, click delete to continue";

    } else {
        var delete_text = "Delete all categories under Category ID " + woo_parent_cat_id + "? Note that all sub-categories will be set as the main category, click delete to continue";
    }
    swal({
        title: "Are you sure?",
        text: ` ${delete_text}`,
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Delete",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=delete_parent_category&p0=${woo_parent_cat_id}`, function (data) {
                    _data = data
                }).done(function () {
                    $(".sk-circle-layer").hide();
                    console.log(_data.parent_categories);
                    if (_data.error == 0) {

                        var woo_woocommerce_p_categories_options = "<option value='-1'>All Categories</option>";
                        for (var i = 0; i < _data.parent_categories.length; i++) {

                            woo_woocommerce_p_categories_options += "<option value='" + _data.parent_categories[i].id + "'>" + _data.parent_categories[i].name + "</option>";
                        }
                        woo_woocommerce_parent_categories = _data.parent_categories;
                        $("#woo_woocommerce_p_categories").empty();
                        $("#woo_woocommerce_p_categories").html(woo_woocommerce_p_categories_options);
                        load_woocommerce_categories_table();

                    } else {
                        alert(_data.msg);
                    }
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        });
}



function sync_woocommerce_category_parents() {

    swal({
        title: "Are you sure?",
        html: true,
        text: '<p class="text-primary">Proceed with syncing all info from Woocommerce website by selecting Yes</p>',
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-primary",
        confirmButtonText: "Yes",
        closeOnConfirm: true
    },
        function (isConfirm) {
            $(".sk-circle").center();
            $(".sk-circle-layer").show();
            var _data = [];
            if (isConfirm) {
                $.getJSON(`?r=woocommerce&f=get_woocommerce_category_and_parents`, function (data) {
                    _data = data;
                }).done(function () {
                    $(".sk-circle-layer").hide();
                    if (_data.error == 0) {
                        var woo_woocommerce_p_categories_options = "<option value='-1'>All Categories</option>";
                        for (var i = 0; i < _data.parent_categories.length; i++) {
                            woo_woocommerce_p_categories_options += "<option value='" + _data.parent_categories[i].id + "'>" + _data.parent_categories[i].name + "</option>";
                        }
                        woo_woocommerce_parent_categories = _data.parent_categories;

                        $("#woo_woocommerce_p_categories").empty();
                        $("#woo_woocommerce_p_categories").html(woo_woocommerce_p_categories_options);
                        load_woocommerce_categories_table();


                    } else {
                        alert(_data.msg);
                    }
                });
            } else {
                $(".sk-circle-layer").hide();

            }
        }

    );
}



function change_parent_category_btn_text() {
    var p_cat_val = ($("#woo_woocommerce_p_categories").val() ?? "-1");
    if (p_cat_val > 0) {
        $("#woo_add_cat_btn").html("Edit Category");
    } else {
        $("#woo_add_cat_btn").html("Add Category");

    }
}



/*WOOCOMMERCE ITEMS VARIABLE */
function get_woo_variable_items_info_table() {

    var search_fields = [0, 1, 2, 3, 4, 5, 6];
    var index = 0;
    $('#woocommerce_variable_items_table tfoot th').each(function () {
        if (jQuery.inArray(index, search_fields) !== -1) {
            var title = $(this).text();
            $(this).html('<div class="inner-addon left-addon"><input  style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" ' + title + '" /></div>');
        }
        index++;
    });



    $('#woocommerce_variable_items_table').dataTable({
        ajax: "?r=woocommerce&f=get_all_items_info&p0=" + ($("#filter_search_woo_variable_items").val() ?? "") + "&p1=" + ($("#woo_v_item_parent_categories").val() ?? "0") + "&p2=" + ($("#woo_v_item_categories").val() ?? "0") + "&p3=1",
        orderCellsTop: true,
        aoColumnDefs: [{
            "targets": [0, 1, 2, 3, 4],
            "searchable": true,
            "orderable": true,
            "visible": true
        }, {
            "targets": [6],
            "searchable": false,
            "orderable": false,
            "visible": false
        }, {
            "targets": [7],
            "searchable": true,
            "orderable": false,
            "visible": true
        }
        ],
        scrollY: '42vh',
        iDisplayLength: 100,
        scrollCollapse: true,
        paging: true,
        bInfo: true,
        bSort: true,
        dom: '<"toolbar_woocommerce_variable_items_table">frtip',
        initComplete: function (settings) {
            $('#woocommerce_variable_items_table').show();

            $("#woo_v_item_parent_categories").empty();
            $("#woo_v_item_categories").empty();


            $("div.toolbar_woocommerce_variable_items_table").html('\n\
            <div class="row" style="margin-top:5px;">\n\
                <div class="col-lg-2 col-md-2 col-sm-12 pl10 " >\n\
                    <button class="btn btn-primary" onclick="sync_all_items(1)" style="width:100%">Sync All Items</button>\n\
                </div>\n\
                <div class="col-lg-4 col-md-4 col-sm-12 pl10 " >\n\
                    <select  id="filter_search_woo_variable_items" multiple style="width:100%"  class="form-control" onchange="refresh_woocommerce_variable_items_table();">\n\
                    </select>\n\
                </div>\n\
                <div class="col-lg-3 col-md-3 col-sm-12 pl2" >\n\
                    <select  id="woo_v_item_parent_categories"  style="cursor:pointer;width:100%"  class="form-control" onchange="woo_v_categories_list_changed();" >\n\
                    </select>\n\
                </div>\n\
                <div class="col-lg-3 col-md-3 col-sm-12 pl2" >\n\
                    <select  id="woo_v_item_categories"  style="cursor:pointer;width:100%"  class="form-control" onchange="refresh_woocommerce_variable_items_table();" >\n\
                    </select>\n\
                </div>\n\
            </div>\n\
            ');


            var _data = [];
            var woo_categories_parents_options = "";
            var woo_categories_options = "";

            $.getJSON("?r=categories&f=getCategories", function (data) {
                _data = data

            }).done(function () {

                woo_categories_parents_options += "<option value='0'>All Categories</option>";
                woo_categories_options += "<option value='0'>All Sub-Categories</option>";

                $.each(_data.catp, function (key, val) {
                    woo_categories_parents_options += "<option value=" + val.id + ">" + val.name + "</option>";
                });
                $("#woo_v_item_parent_categories").append(woo_categories_parents_options);

                $.each(_data.cat, function (key, val) {
                    woo_categories_options += "<option value=" + val.id + ">" + val.description + "</option>";
                });
                $("#woo_v_item_categories").append(woo_categories_options);
            });



            $("#filter_search_woo_variable_items").select2({
                ajax: {
                    url: '?r=woocommerce&f=search',
                    data: function (params) {
                        var query = {
                            p0: params.term || "",
                            p1: params.page || 1,
                            p2: params.is_variable || 1

                        }
                        return query;
                    },
                    delay: 250,
                    dataType: 'json'
                },
                placeholder: "Search by items",
                allowClear: true
            });



            $(".sk-circle-layer").hide();
        },
        fnDrawCallback: function () {
            woocommerce_variable_items_table_callback();

        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
        },
    });

    $('#woocommerce_variable_items_table').on('page.dt', function () {
        woocommerce_variable_items_table_callback();
    });

    $('#woocommerce_variable_items_table').DataTable().columns().every(function () {
        var that = this;
        $('input', this.footer()).on('keyup change', function () {
            if (that.search() !== this.value) {
                that.search(this.value).draw();
            }
        });
    });

    $('#woocommerce_variable_items_table').DataTable().on('mousedown', "tr", function (e, dt, type, indexes) {
        $('.selected').removeClass("selected");
        $(this).addClass('selected');
    });

}

function woocommerce_variable_items_table_callback() {

    var table = $('#woocommerce_variable_items_table').DataTable();
    var p = table.rows({
        page: 'current'
    }).nodes();
    for (var k = 0; k < p.length; k++) {

        var index = table.row(p[k]).index();
        var item_action_btn2="";
        if (table.cell(index, 6).data() == 0) {
            var item_action_btn = '<button  title="Add Item" class="btn btn-primary btn-xs" onclick="sync_item(' + table.cell(index, 0).data() + ',1,0)"><i class="glyphicon glyphicon-plus" style="float: left;padding-top:1px;"></i> &nbsp; Add</button>';
            var item_action_btn2 = '<button  title="Add Item" class="btn btn-primary btn-xs" onclick="sync_item(' + table.cell(index, 0).data() + ',1,1)"><i class="glyphicon glyphicon-plus" style="float: left;padding-top:1px;"></i> &nbsp; Add with Quantity</button>';

        } else {
            var item_action_btn = '<button  title="Delete Item"  class="btn btn-danger btn-xs" onclick="delete_woo_item(' + table.cell(index, 0).data() + ',1)"><i class="glyphicon glyphicon-trash" style="float: left;padding-top:1px;"></i> &nbsp; Delete</button>';
        }

        table.cell(index, 7).data(item_action_btn+"&nbsp;&nbsp;"+item_action_btn2);


    }


}

function refresh_woocommerce_variable_items_table() {
    $(".sk-circle-layer").show();
    $('#woocommerce_variable_items_table').DataTable().ajax.url("?r=woocommerce&f=get_all_items_info&p0=" + ($("#filter_search_woo_variable_items").val() ?? "") + "&p1=" + ($("#woo_v_item_parent_categories").val() ?? "0") + "&p2=" + ($("#woo_v_item_categories").val() ?? "0") + "&p3=1").load(function () {
        $(".sk-circle-layer").hide();
        setTimeout(function () {
            $('#woocommerce_variable_items_table').DataTable().columns.adjust().draw();
        }, 100);
    }, false);

}

function woo_v_categories_list_changed() {
    $('#woo_v_item_categories').empty();
    var woo_categories_options = '<option value="0" >All Sub-Categories</option>';
    $.each(woo_item_categories, function (key, val) {
        if ($("#woo_v_item_parent_categories").val() == val.parent && $("#woo_v_item_parent_categories").val() != 0) {
            woo_categories_options += "<option value=" + val.id + ">" + val.description + "</option>";
        }
        if ($("#woo_v_item_parent_categories").val() == 0) {
            woo_categories_options += "<option value=" + val.id + ">" + val.description + "</option>";
        }
    });
    $("#woo_v_item_categories").append(woo_categories_options);
    refresh_woocommerce_variable_items_table();
}


/*END WOOCOMMERCE ITEMS VARIABLE */
