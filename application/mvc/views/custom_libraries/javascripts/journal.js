function show_journal(){
    var modal_name = "modal_journal_modal__";
    var modal_title = "Journal";
    var content =
    '<div class="modal maxlarge" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header" style="padding-top:5px;padding-bottom:5px;">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" style="padding-top:2px;">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%" id="journal_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:125px;">Date</th>\n\
                                        <th>Description</th>\n\
                                        <th style="width:110px;">Payment Method</th>\n\
                                        <th style="width:90px;">Purchases</th>\n\
                                        <th style="width:90px;">Sales</th>\n\
                                        <th style="width:90px;">Payments</th>\n\
                                        <th style="width:90px;">Receipts</th>\n\
                                    </tr>\n\
                                </thead>\n\
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
        var table_name = "journal_table";
        var new_table__var =null;

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=journal&f=get_journal&p0=today&p1=0&p2=0&p3=0&p4=0",
                type: 'POST',
                error:function(xhr,status,error) {
                },
            },
            responsive: true,
            orderCellsTop: true,
            scrollX: true,
            scrollY: "70vh",
            iDisplayLength: 200,
            aoColumnDefs: [
                { "targets": [0], "searchable": false, "orderable": true,"visible": true },
                { "targets": [1], "searchable": false, "orderable": true,"visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
            ],
            scrollCollapse: true,
            paging: false,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bAutoWidth: true,
            bSort:false,
            dom: '<"toolbar_journal">frtip',
            initComplete: function(settings, json) { 
                
                $("div.toolbar_journal").html('\n\
                    <div class="row" style="margin-top:5px">\n\
                        <div class="col-lg-10 col-md-10 col-sm-10">\n\
                            <div class="btn-group tab_toolbar" role="group" aria-label="">\n\
                                <input id="date_filter" class="form-control" type="text" placeholder="" style="cursor:pointer;width:200px;" />\n\
                            </div>\n\
                        </div>\n\
                        <div class="col-lg-2 col-md-2 col-sm-2">\n\
                            <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                <div class="btn-group" id="buttons" style="float:right"></div>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    ');
                
                var start = moment();
                var end = moment();
                        
                $('#date_filter').daterangepicker({
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

                $("#date_filter").change(function() {
                    refresh_journal();
                });
                
                
                
                
                var buttons = new $.fn.dataTable.Buttons(_cards_table__var, {
                    buttons: [
                      {
                            extend: 'excel',
                            text: 'Export excel',
                            className: 'exportExcel',
                            filename: 'Journal ',
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
            //fnDrawCallback: updateRowsManualInvoice,
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


function refresh_journal(){
    var table = $('#journal_table').DataTable();
    table.ajax.url("?r=journal&f=get_journal&p0="+$("#date_filter").val()+"&p1=0&p2=0&p3=0&p4=0").load(function () {
       
    },false);
}