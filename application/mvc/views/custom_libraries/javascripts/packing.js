function addPacks(item_id){
    var content =
            '<div class="modal" data-backdrop="static" data-keyboard="false" id="packing_qty_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                    <h3 class="modal-title">Packing Quantity<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'packing_qty_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <input autocomplete="off" id="pack_balue" value="" name="pack_balue" type="text" class="form-control med_input" placeholder="" />\n\
                            </div>\n\
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">\n\
                                <button type="button" class="btn btn-primary" onclick="add_pack_qty('+item_id+')" style="">UPDATE</button>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-primary" data-dismiss="modal" style="">Close</button>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('body').append(content);

        $('#packing_qty_modal').on('hidden.bs.modal', function (e) {
            $('#packing_qty_modal').remove();
        });
        
        $('#packing_qty_modal').on('shown.bs.modal', function (e) {
            $("#pack_balue").focus();
        });
        
        $('#packing_qty_modal').modal('show');
}

function add_pack_qty(item_id){
    $(".sk-circle-layer").show();
    $.getJSON("?r=packing&f=add_pack_qty&p0="+item_id+"&p1="+$("#pack_balue").val(), function (data) {
        
    }).done(function () {
        $("#pack_balue").val("");
        $(".sk-circle-layer").hide();
        $('#packing_qty_modal').modal('hide');
        refresh_packs_table(item_id);
    });
}

function refresh_packs_table(item_id){
    var table = $('#packtable').DataTable();
    table.ajax.url("?r=packing&f=get_packing&p0=0&p1=0&p2=0&p3=0&p4=0").load(function () { 
        $("."+item_id).addClass("selected");
    },false);
}

function packing(){
    $(".sk-circle-layer").show();
    $(".sk-circle-layer").hide();
        var content =
            '<div class="modal" data-backdrop="static" data-keyboard="false" id="packing_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                    <h3 class="modal-title">Packing<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'packing_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="packtable" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:55px;">Pack ID</th>\n\
                                            <th>Pack Description</th>\n\
                                            <th>Pack Qty</th>\n\
                                            <th>Composite Item ID</th>\n\
                                            <th>Composite Description</th>\n\
                                            <th>Composite Qty</th>\n\
                                            <th style="width:100px;">Cost<br/><b id="total_cost_packs" style="font-size:12px;"></b></th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="">Close</button>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';

        $('body').append(content);

        $('#packing_modal').on('hidden.bs.modal', function (e) {
            $('#packing_modal').remove();
        });
        
        $('#packing_modal').on('shown.bs.modal', function (e) {
            $(".sk-circle-layer").hide();
            var table_name = "packtable";

            var _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=packing&f=get_packing&p0=0&p1=0&p2=0&p3=0&p4=0",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                    dataSrc: function (json) {
                        $("#total_cost_packs").html("Total: "+json.total);
                        return json.data;
                    }
                },
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "50vh",
                iDisplayLength: 50,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": true,"visible": true },
                    { "targets": [1], "searchable": true, "orderable": true,"visible": true },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: true,
                bLengthChange: true,
                bFilter: true,
                bInfo: true,
                bAutoWidth: true,
                bSort:true,
                dom: '<"toolbar_packtableq">frtip',
                initComplete: function(settings, json) { 
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                //fnDrawCallback: updateRowsManualInvoice,
            });

            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
            });

        });
        
        $('#packing_modal').modal('show');    
    
}