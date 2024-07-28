function get_all_qty_if_item(item_id){
    /*
    var _data = [];
    $.getJSON("?r=all_stores_data&f=get_all_items_qty_in_all_stores&p0="+item_id, function (data) {
        _data = data;
    }).done(function () {
        alert(_data);
    });*/
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    
    var table_name = "modal_get_all_qty_if_item_table__";
    var modal_name = "modal_get_all_qty_if_item_modal__";
    var modal_title = "Stock in all stores";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="closeModal(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <input type="hidden" id="search_all_store_item_id" value="'+item_id+'" />\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:100px;">ID</th>\n\
                                        <th style="width:100px;">Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width:100px;">Size</th>\n\
                                        <th style="width:100px;">Color</th>\n\
                                        <th style="width:60px;">Qty</th>\n\
                                        <th style="width:140px;">Branche</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>ID</th>\n\
                                        <th>Barcode</th>\n\
                                        <th>Description</th>\n\
                                        <th>Size</th>\n\
                                        <th>Color</th>\n\
                                        <th>Qty</th>\n\
                                        <th>Branche</th>\n\
                                        <th></th>\n\
                                        <th></th>\n\
                                    </tr>\n\
                                </tfoot>\n\
                                <tbody></tbody>\n\
                            </table>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {
        
        $('#'+table_name).show();
        
        var _cards_table__var =null;
        
        var search_fields = [0,1,2,3,4,5,6];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=all_stores_data&f=get_all_items_qty_in_all_stores&p0="+item_id,
                type: 'POST',
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "55vh",
            iDisplayLength: 100,
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": false, "visible": true },
                { "targets": [8], "searchable": true, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            initComplete: function(settings, json) {                
                //var row = $('#'+table_name+' tr:first-child');
                //$(row).addClass('selected');

                //$('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
                
                $(".sk-circle-layer").hide(); 
            },
            fnDrawCallback: updateRows_stock_transfers,
            
        });
        
        $('#'+table_name).on('key-focus.dt', function(e, datatable, cell){
            $(_cards_table__var.row(cell.index().row).node()).addClass('selected');
        });

        $('#'+table_name).on('key-blur.dt', function(e, datatable, cell){
            $(_cards_table__var.row(cell.index().row).node()).removeClass('selected');
        });
        
        $('#'+table_name).on('click', 'td', function () {
            //if ($(this).index() == 3) {
                //return false;
            //}
        });
        
        $('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable(this.value,that.index(),100,table_name);
            } );
        } );
               
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function updateRows_stock_transfers(){
    if(pos_branches_transfers==0){
        return;
    }
    var table = $('#modal_get_all_qty_if_item_table__').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++) {
        var index = table.row(p[k]).index();
        
        var it=parseInt(table.cell(index, 0).data().split('-')[1]);
        
        if(current_store_id!=table.cell(index, 8).data() && table.cell(index, 8).data()>0){
            table.cell(index, 7).data('<button type="button" class="btn btn-primary btn-sm" style="width:100%;padding:0px !important;font-size:14px !important;" onclick="stock_transfer('+it+','+table.cell(index, 8).data()+')">Transfer</button>');
        }
    }
}

function searchBarcode_All_Stores(item_id_s){
    get_all_qty_if_item(item_id_s);
    /*
    if(cashBox == 0){
        setCashbox();
        return;
    }
    swal({
        title: ""+LG_MANUAL_BARCODE,
        html: true ,
         text: '<input autofocus type="text" id="m_barcode_all_stores" input/>',
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: LG_ADD,
        cancelButtonText: LG_CANCEL,
        closeOnConfirm: true,
        closeOnCancel: true
    },
    function(isConfirm) {
        if (isConfirm) {
            if ($("#m_barcode_all_stores").val() == "" || $("#m_barcode_all_stores").val() == null) {
                return false;
            }else{
                get_all_qty_if_item($("#m_barcode_all_stores").val());
            }
        }else{
            $(".sweet-alert").remove();
            $(".sweet-overlay").remove();
        }
    });
    
    setTimeout(function(){
        $("#m_barcode_all_stores").focus();
    },300);  */ 
}
