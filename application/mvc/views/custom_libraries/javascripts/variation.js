function _inoutdetails() {
    var dt = $('#items_table').DataTable();
    var sdata = dt.row('.selected', 0).data();
    var id = sdata[0].split("-")[1]; 
    
    var sdata_name = dt.row('.selected', 0).data();
    var item_name = sdata_name[3];
                
    show_inoutdetails(id,item_name);
}

function show_inoutdetails(item_id,item_name) {
    $(".sk-circle-layer").show();
        var content =
        '<div class="modal" data-backdrop="static" id="logsModal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Variation of <span class="itemlog_name">'+item_name+'</span> Created By <span id="itemlog_admin_name"></span><i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'logsModal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >\n\
                                <table id="logs_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width: 120px !important;">Date</th>\n\
                                            <th>Action</th>\n\
                                            <th style="width: 40px !important;">Qty</th>\n\
                                            <th style="width: 100px !important;">Stock Variation</th>\n\
                                            <th style="width: 60px !important;">Price</th>\n\
                                            <th style="width: 80px !important;">Profit</th>\n\
                                            <th style="width: 80px !important;">Profit/Unit</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tfoot>\n\
                                        <tr>\n\
                                            <th>Date</th>\n\
                                            <th>Action</th>\n\
                                            <th>Qty</th>\n\
                                            <th>Stock Variation</th>\n\
                                            <th>Unit Price</th>\n\
                                            <th>Profit</th>\n\
                                            <th>Profit/Unit</th>\n\
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
        $("#logsModal").remove();
        $("body").append(content);
        $('#logsModal').on('show.bs.modal', function (e) {

            var h_logs = null;
            var search_fields = [0,1,2,3,4];
            var index = 0;
            $('#logs_table tfoot th').each( function () {
                if(jQuery.inArray(index, search_fields) !== -1){
                    var title = $(this).text();
                    $(this).html( '<div class="inner-addon left-addon"><input style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder=" '+title+'" /></div>' );
                    index++;
                }
            });

            h_logs = $('#logs_table').DataTable({
                ajax: {
                    url: "?r=items&f=get_variation&p0="+item_id+"&p1="+current_store_id,
                    type: 'POST',
                    error:function(xhr,status,error) {
                        //logged_out_warning();
                    },
                    dataSrc: function (json) {
                        if(json.data=="-1"){
                            swal("Network error");
                        }
                        return json.data;
                    }
                },
                orderCellsTop: true,
                pageLength: 100,
                aoColumnDefs: [
                    { "targets": [0], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                ordering: false,
                scrollY: '50vh',
                initComplete: function(settings, json) {

                    $.getJSON("?r=items&f=get_item_by_id&p0=" + item_id, function (data) {
                        if(data[0].user_name.length>0){
                            $("#itemlog_admin_name").html(data[0].user_name+" At "+data[0].creation_date);
                        }
                    }).done(function () {

                    });
                    $(".sk-circle-layer").hide();
                },
                //fnDrawCallback: updateRows_history_cashbox,
            });

            $("#logs_table").on("mousedown", "tr", function(event) {
                $('#logs_table .selected').removeClass("selected");
                var dt = $('#logs_table').DataTable();
                var index = dt.row(this).index();
                dt.row(index).select(index);
            });

            $('#logs_table').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
                //var sdata = items_search.row('.selected', 0).data();
                //returnQty(parseInt(sdata[0].split("-")[1]));
            });

            $('#logs_table').on('key-focus.dt', function(e, datatable, cell){
                $(h_logs.row(cell.index().row).node()).addClass('selected');
            });

            $('#logs_table').on('key-blur.dt', function(e, datatable, cell){
                $(h_logs.row(cell.index().row).node()).removeClass('selected');
            });

            $('#logs_table').on('key.dt', function(e, datatable, key, cell, originalEvent){
                if(key === 13){
                     //var sdata = items_search.row('.selected', 0).data();
                    //returnQty(parseInt(sdata[0].split("-")[1]));
                }
            });

            $('#logs_table').DataTable().columns().every( function () {
                var that = this;
                $( 'input', this.footer() ).on( 'keyup change', function () {
                    h_logs.keys.disable();
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                    h_logs.keys.enable();
                } );
            } );
        });

        $('#logsModal').on('shown.bs.modal', function (e) {
            /*$('.date_s').daterangepicker({
                dateLimit:{month:1},
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });

            $('.date_s').on('apply.daterangepicker', function(ev) {
                ev.preventDefault();
                ev.stopPropagation();
            });*/

        });
        $('#logsModal').on('hide.bs.modal', function (e) {
            $("#logsModal").remove();
        });
        $('#logsModal').modal('show');
}

