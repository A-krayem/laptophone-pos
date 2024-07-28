function show_cash_statement(){
    var modal_name = "modal_cash_statement_modal__";
    var modal_title = "Cash Statement";
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <form id="cashinout_form" action="" method="post" enctype="multipart/form-data" >\n\
                    <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                    <div class="modal-header">\n\
                        <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-footer" style=" text-align: left;">\n\
                        <div class="row">\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                                <table style="width:100%" id="cash_statement_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:135px;">Date</th>\n\
                                            <th style="width:300px;">Description</th>\n\
                                            <th style="width:120px;">In</th>\n\
                                            <th style="width:120px;">Out</th>\n\
                                            <th style="width:120px;">Balance</th>\n\
                                            <th>Note</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#"+modal_name).remove();
    $("body").append(content);


    $('#'+modal_name).on('show.bs.modal', function (e) {

    });

    $('#'+modal_name).on('shown.bs.modal', function (e) {

        var table_name = "cash_statement_table";
        var _cards_table__var =null;

            var search_fields = [];
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
                    url: "?r=cashstatement&f=get_cs&p0=today&p1=0&p2=0&p3=0&p4=0&p5=0",
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                },
                //order: [[1, 'asc']],
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 100,
                aoColumnDefs: [
                    { "targets": [0], "searchable": false, "orderable": true,"visible": true },
                    { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                    { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bSort:false,
                bInfo: false,
                bAutoWidth: true,
                dom: '<"toolbarcashinout">frtip',
                initComplete: function(settings, json) { 

                    $("div.toolbarcashinout").html('\n\
                    <div class="row">\n\
                        <div class="col-lg-2 col-md-2 col-xs-2" style="padding-left:15px;padding-right:5px;">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                <input id="cashstatementDate" class="form-control" type="text" placeholder="Select dat" style="cursor:pointer;width:100%;" />\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');
                    /*
                    $('#cashstatementDate').daterangepicker({
                        dateLimit:{month:12},
                        locale: {
                            format: 'YYYY-MM-DD'
                        },
                    });*/
                    
                    var start = moment();
                    var end = moment();

                    $('#cashstatementDate').daterangepicker({
                        dateLimit:{month:12},
                        startDate: start,
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

                    $('#cashstatementDate').on('apply.daterangepicker', function(ev) {
                        ev.preventDefault();
                        ev.stopPropagation();
                    });

                    $( "#cashstatementDate" ).change(function() {
                        refresh_cashstatement();
                    });

                    //$("#date_cashinout").selectpicker();
                    //$('#set_status_all').val(status);
                    //$('#set_status_all').selectpicker('refresh');

                    $(".sk-circle-layer").hide();
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    $(nRow).addClass(aData[0]);
                },
                 fnDrawCallback: function(){

                    var table = $('#'+table_name).DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        //table.cell(index,9).data('<i class="glyphicon glyphicon-trash" onclick="delete_cashin_out(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
                    }
                 },
            });

            $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
            });


            $('#'+table_name).on('click', 'td', function () {
                if ($(this).index() == 4 || $(this).index() == 5) {
                    //return false;
                }
            });

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function refresh_cashstatement(){
    var table_details = $("#cash_statement_table").DataTable();
    table_details.ajax.url("?r=cashstatement&f=get_cs&p0="+$("#cashstatementDate").val()+"&p1=0&p2=0&p3=0&p4=0&p5=0").load(function () {

    },false);
}