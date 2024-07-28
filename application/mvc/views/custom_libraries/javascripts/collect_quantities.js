function collect_quantities(){
    var default_low_qty = 3;
    var _data = [];
    var stores_options = "";
    
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.getJSON("?r=store&f=getStores", function (data) {
        _data = data;
    }).done(function () {
        var store_column = "";
        for(var t=0;t<_data.length;t++){
            store_column += "<th style='width:70px;'>"+_data[t].location+"</th>";
            //if(_data[t].warehouse==0)
                stores_options += "<option selected value=" + _data[t].id + ">" + _data[t].location + "</option>";
        }
        
        var modal_name = "modal_allquantities_modal__";
        var modal_title = "All Quantities";
        var content =
        '<div class="modal large" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <form id="cashinout_form" action="" method="post" enctype="multipart/form-data" >\n\
                        <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                        <div class="modal-header" style="padding-top:5px;padding-bottom:5px;">\n\
                            <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close__(\''+modal_name+'\')"></i></h3>\n\
                        </div>\n\
                        <div class="modal-body" style="padding-top:2px;">\n\
                            <div class="row">\n\
                            </div>\n\
                            <div class="row">\n\
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="min-height:400px;">\n\
                                    <table width="100%" id="all_quantites_table" class="table table-striped table-bordered" cellspacing="0">\n\
                                        <thead>\n\
                                            <tr>\n\
                                                <th style="width:45px;">Id</th>\n\
                                                <th style="width:70px;">Barcode</th>\n\
                                                <th>Decription</th>\n\
                                                <th style="width:70px;">Color</th>\n\
                                                <th style="width:70px;">Cost</th>\n\
                                                <th style="width:70px;">Price</th>\n\
                                                '+store_column+'\n\
                                            </tr>\n\
                                        </thead>\n\
                                        <tbody></tbody>\n\
                                        <tfoot>\n\
                                            <tr>\n\
                                                <th>Id</th>\n\
                                                <th>Barcode</th>\n\
                                                <th>Decription</th>\n\
                                                <th>Color</th>\n\
                                                <th>Cost</th>\n\
                                                <th>Price</th>\n\
                                                '+store_column+'\n\
                                            </tr>\n\
                                        </tfoot>\n\
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

            $(".sk-circle-layer").hide();

            var table_name = "all_quantites_table";
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
                        url: "?r=all_stores_data&f=get_all_quantities&p0="+default_low_qty+"&p1=1&p2=0&p3=0&p4=0&p5=0",
                        type: 'POST',
                        error:function(xhr,status,error) {
                        },
                    },
                    //order: [[1, 'asc']],
                    responsive: true,
                    orderCellsTop: true,
                    scrollX: true,
                    scrollY: "55vh",
                    iDisplayLength: 50,
                    aoColumnDefs: [

                    ],
                    scrollCollapse: true,
                    paging: true,
                    bPaginate: false,
                    bLengthChange: false,
                    bFilter: true,
                    bInfo: true,
                    bAutoWidth: true,
                    dom: '<"toolbarallqty">frtip',
                    initComplete: function(settings, json) { 
                        
                        $("div.toolbarallqty").html('\n\
                            <div class="row" style="margin-top:10px;">\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-right:5px;">\n\
                                    <div class="form-group" style="width:100%;">\n\
                                        <input style="width:100%;height:33px;" id="max_qty" type="number" value="3" class="" placeholder="Less than x in branches"/>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <div class="form-group">\n\
                                        <select data-live-search="true" id="availibility" class="selectpicker form-control" style="width:100%"><option selected value="1">Only if available in warehouse</option><option value="0">Even not available in warehouse</option></select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                    <div class="form-group" style="width:100%">\n\
                                        <select multiple data-live-search="true" id="stores_sel" name="stores_sel" class="selectpicker form-control" style="width:100%">'+stores_options+'</select>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-left:5px;padding-right:5px;">\n\
                                    <div class="form-group">\n\
                                        <button style="width:100%;font-size:14px;" type="button" class="btn btn-default btn-sm" onclick="refresh_low_qty_in_branches()">Update List</button>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col-lg-4 col-md-4 col-sm-4" >\n\
                                    <div class="btn-group" role="group" aria-label="" style="width:100%">\n\
                                        <div class="btn-group" id="buttons" style="float:right"></div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        ');
                        
                        
                        var buttons = new $.fn.dataTable.Buttons(_cards_table__var, {
                            buttons: [
                              {
                                    extend: 'excel',
                                    text: 'Export excel',
                                    className: 'exportExcel',
                                    filename: 'Low Quantities',
                                    customize: _customizeExcelOptions,
                                    exportOptions: {
                                        modifier: {
                                            page: 'all'
                                        },
                                        //columns: [0,1,2,3,4,5,6],
                                        format: {
                                            body: function ( data, row, column, node ) {
                                                // Strip $ from salary column to make it numeric
                                                return  data; //column == 8 ? parseInt(table.cell(row,0).data()) :
                                            }
                                        }
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
                        
                        $(".selectpicker").selectpicker();
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
                            //table.cell(index,10).data('<i class="glyphicon glyphicon-trash" onclick="delete_cashin_out(\''+parseInt(table.cell(index, 0).data())+'\')" style="font-size:18px;cursor:pointer" ></i>');
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
    });
    
}

var tmo = null;
function search_in_datatable(val,index,delay,table_name){
    clearTimeout(tmo);
    tmo = setTimeout(function(){
        $('#'+table_name).DataTable().columns(index).search(val).draw();
    },delay); 
}

function refresh_low_qty_in_branches(){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var table = $('#all_quantites_table').DataTable();
    table.ajax.url("?r=all_stores_data&f=get_all_quantities&p0="+$("#max_qty").val()+"&p1="+$("#availibility").val()+"&p2="+$("#stores_sel").val()+"&p3=0&p4=0&p5=0").load(function () { 
         $(".sk-circle-layer").hide();
    },false);
}