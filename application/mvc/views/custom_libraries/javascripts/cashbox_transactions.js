function show_cashbox_transactions(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_transactions_table";
    var modal_name = "modal_all_transactions__";
    var modal_title = "Cashbox Transactions";
    
    var content =
    '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:80px;">TID</th>\n\
                                        <th style="width:150px;">Transaction Date</th>\n\
                                        <th style="width:150px;">Created By</th>\n\
                                        <th style="width:150px;">Transaction Type</th>\n\
                                        <th style="width:120px;">Amount USD</th>\n\
                                        <th style="width:120px;">Amount LBP</th>\n\
                                        <th>Note</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>TID</th>\n\
                                        <th>Transaction Date</th>\n\
                                        <th>Created By</th>\n\
                                        <th>Transaction Type</th>\n\
                                        <th>Amount USD</th>\n\
                                        <th>Amount LBP</th>\n\
                                        <th>Note</th>\n\
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
        
        var _table__var =null;
        
        var search_fields = [0,1,2,3,4,5,6];
        var index = 0;
        $('#'+table_name+' tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" />' );
                index++;
            }
        });

        _table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=transactions&f=get_all_transactions&p0=thismonth&p1=1",
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
                { "targets": [0], "searchable": true, "orderable": true, "visible":  true },
                { "targets": [1], "searchable": true, "orderable": false, "visible": true },
                { "targets": [2], "searchable": true, "orderable": false, "visible": true },
                { "targets": [3], "searchable": true, "orderable": false, "visible": true },
                { "targets": [4], "searchable": true, "orderable": false, "visible": true },
                { "targets": [5], "searchable": true, "orderable": false, "visible": true },
                { "targets": [6], "searchable": true, "orderable": false, "visible": true },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort: false,
            bAutoWidth: true,
            dom: '<"toolbar_global_ct">frtip',
            initComplete: function(settings, json) {
                $("div.toolbar_global_ct").html('\n\
                <div class="row">\n\
                    <div class="col-lg-2 col-md-2 col-sm-12" >\n\
                        <input id="trcashbox_date_picker" class="form-control" type="text" placeholder="Select date" style="cursor:pointer;width:180px;">\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12 pl2 pr2" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <select data-live-search="true" data-width="100%" id="filter_delete" class="selectpicker" onchange="refresh_cashboxtr()">\n\
                                <option value="0">All</option>\n\
                                <option value="1" selected>Not Deleted</option>\n\
                                <option value="2">Deleted</option>\n\
                            </select>\n\
                        </div>\n\
                    </div>\n\
                    <div class="col-lg-2 col-md-2 col-sm-12" >\n\
                        \n\
                    </div>\n\
                    <div class="col-lg-6 col-md-6 col-sm-6" >\n\
                        <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                            <div class="btn-group" id="buttons" style="float:right"></div>\n\
                        </div>\n\
                    </div>\n\
                    </div>\n\
                ');
                
                $(".selectpicker").selectpicker();
                
                var start = moment().startOf('month');
                var end = moment();
                        
                $('#trcashbox_date_picker').daterangepicker({
                    //dateLimit:{month:12},
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

                $('#trcashbox_date_picker').on('apply.daterangepicker', function(ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                });
                
                
                $('#trcashbox_date_picker').change(function() {
                    refresh_cashboxtr();
                });
                
                
                var buttons = new $.fn.dataTable.Buttons(_table__var, {
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

                    $('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');

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
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            //fnDrawCallback: setGlobalExpensesOptions,
        });
        
        $('#'+table_name).DataTable().on('mousedown',"tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
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


function refresh_cashboxtr(){
    var table = $('#modal_all_transactions_table').DataTable();
        table.ajax.url("?r=transactions&f=get_all_transactions&p0="+$("#trcashbox_date_picker").val()+"&p1="+$("#filter_delete").val()).load(function () {

        },false);
}

