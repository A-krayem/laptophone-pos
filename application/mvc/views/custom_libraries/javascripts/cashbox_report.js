function get_full_report(cashbox_id){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    /*var _data = [];
    $.getJSON("?r=cashinout&f=get_full_report&p0="+cashbox_id, function (data) {
        _data = data;
    }).done(function () { 
        
    });*/
    
    var enable_cashbox_transactions_display="";
    if(enable_cashbox_transactions==0){
        enable_cashbox_transactions_display="display:none;";
    }
    
    var content =
        '<div class="modal large" data-backdrop="static"  id="r_modal" role="dialog" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Cash Report&nbsp;<div class="btn-group" id="buttons" style="padding-top:2px;padding-bottom:2px;"></div><i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'r_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row" >\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <b>DELETED INVOICES:</b> <span class="cash_rep_sec mandatory_field_sign" id="totdel">-</span>\n\
                            </div>\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                \n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                <button style="width:100%;'+enable_cashbox_transactions_display+'" onclick="add_new_transaction()" type="button" class="btn btn-primary ">NEW CASHBOX TRANSACTION</button>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" >\n\
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="">\n\
                                <table style="width:100%" id="drep_table__" class="table table-striped table-bordered" cellspacing="0">\n\
                                    <thead>\n\
                                        <tr>\n\
                                            <th style="width:150px;">Transaction Date</th>\n\
                                            <th>Referance</th>\n\
                                            <th style="width:80px;">Amount</th>\n\
                                            <th style="width:60px;">Rate</th>\n\
                                            <th style="width:60px;">IN USD</th>\n\
                                            <th style="width:70px;">IN LBP</th>\n\
                                            <th style="width:70px;">OUT USD</th>\n\
                                            <th style="width:70px;">OUT LBP</th>\n\
                                            <th style="width:80px;" title="To Cash OUT USD">To OUT USD</th>\n\
                                            <th style="width:80px;" title="To Cash OUT LBP">To OUT LBP</th>\n\
                                            <th style="width:80px;">NET USD</th>\n\
                                            <th style="width:80px;">NET LBP</th>\n\
                                            <th style="width:60px;">&nbsp;</th>\n\
                                            <th style="width:60px;">Payment Type</th>\n\
                                            <th style="width:60px;">Transaction_id</th>\n\
                                        </tr>\n\
                                    </thead>\n\
                                    <tbody></tbody>\n\
                                </table>\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                    <div class="modal-footer">\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                \n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>';
        $("#r_modal").remove();
        $("body").append(content);
        $('#r_modal').on('show.bs.modal', function (e) {
            
        });

        $('#r_modal').on('shown.bs.modal', function (e) {
            
            
            var _cards_table__var =null;
            var table_name = "drep_table__";
            _cards_table__var = $('#'+table_name).DataTable({
                ajax: {
                    url: "?r=cashinout&f=get_full_report_table&p0=0&p1="+cashbox_id,
                    type: 'POST',
                    error:function(xhr,status,error) {
                    },
                    dataSrc: function(json) {
                        $("#totdel").html(json.totdel);
                        console.log(json.data);
                        return json.data;
                    }
                },
                responsive: true,
                orderCellsTop: true,
                scrollX: true,
                scrollY: "55vh",
                iDisplayLength: 100,
                aoColumnDefs: [
                    { "targets": [0,1,2,3,4,5,6,7,8,9], "searchable": true, "orderable": true, "visible": true },
                    { "targets": [13,14], "searchable": true, "orderable": true, "visible": false },
                ],
                scrollCollapse: true,
                paging: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: true,
                bInfo: false,
                bSort: false,
                bAutoWidth: true,
                aaSorting: [[ 1, "asc" ]],
                initComplete: function(settings, json) {    
                    var buttons = new $.fn.dataTable.Buttons(_cards_table__var, {
                        buttons: [
                          {
                                extend: 'excel',
                                text: 'Export excel',
                                className: 'exportExcel',
                                filename: 'CASH DETAILS',
                                customize: _customizeExcelOptions,
                                exportOptions: {
                                    modifier: {
                                        page: 'all'
                                    },
                                    columns: [ 0,1,2,3,4,5,6,7,8,9,10 ]
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
                         //sheet.childNodes[0].childNodes[1].innerHTML = sheet.childNodes[0].childNodes[1].innerHTML + r1;

                         //$('row c[r^="A'+(clR.length+2)+'"]', sheet).attr('s', '48');

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
       
                },
                fnDrawCallback: function(){
                    if(cashbox_id>0){return;}
                    var table = $('#drep_table__').DataTable();
                    var p = table.rows({ page: 'current' }).nodes();
                    for (var k = 0; k < p.length; k++){
                        var index = table.row(p[k]).index();
                        if(table.cell(index, 13).data()==1 || table.cell(index, 13).data()==2)
                            table.cell(index, 12).data('<button onclick="edit_cash('+table.cell(index, 13).data()+','+table.cell(index, 14).data()+')" type="button" class="btn btn-primary btn-sm" style="width:100%;padding:0px !important;font-size:14px !important;">Edit</button>');
                        
                        if(table.cell(index, 13).data()==3)
                            table.cell(index, 12).data('<button onclick="delete_transaction('+table.cell(index, 14).data()+')" type="button" class="btn btn-danger btn-sm" style="width:100%;padding:0px !important;font-size:14px !important;">Delete</button>');
                    }
                },
                
            });
            
            
            $('#drep_table__').DataTable().on('dblclick',"tr", function ( e, dt, type, indexes ) {
                  $(this).addClass('selected');
            });
            
            $('#drep_table__').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
                $('.selected').removeClass("selected");
                $(this).addClass('selected');
            });
            
            $(".sk-circle-layer").hide();   
        });

        $('#r_modal').on('hide.bs.modal', function (e) {
            $("#r_modal").remove();
        });
        $('#r_modal').modal('show');
}