function latest_prices_for_customer(item_id,source,inv_item_id){

    $(".sk-circle").center();
    $(".sk-circle-layer").show(); 
    var table_name = "modal_all_history_prices_table";
    var modal_name = "modal_all_history__prices___";
    var modal_title = "History Prices";
    
    var content =
    '<div class="modal small" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input type="hidden" id="ltp_item_id" value="'+item_id+'" />\n\
                <input type="hidden" id="source" value="'+source+'" />\n\
                <input type="hidden" id="inv_item_id" value="'+inv_item_id+'" />\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table style="width:100%;font-size:14px;" id="'+table_name+'" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width:120px;">Date Time</th>\n\
                                        <th style="width:80px;">Invoice ID</th>\n\
                                        <th>Price</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
                                        <th style="width:60px;">&nbsp;</th>\n\
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
        
        $('#'+table_name).show();
        
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
        
        var customer_id = 0;
        if(source=="pos"){
            var customer_i = inv.getcustomer_info(); 
            customer_id = customer_i[0].id;
        }
        if(source=="admin"){
             customer_id = $("#invoice_customer_id").val();  
        }   

        _cards_table__var = $('#'+table_name).DataTable({
            ajax: {
                url: "?r=pos&f=customer_latest_price&p0="+customer_id+"&p1="+item_id,
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
                { "targets": [1], "searchable": false, "orderable": false, "visible": true },
                { "targets": [2], "searchable": false, "orderable": false, "visible": true },
                { "targets": [3], "searchable": false, "orderable": false, "visible": true },
                { "targets": [4], "searchable": false, "orderable": false, "visible": false },
            ],
            scrollCollapse: true,
            paging: true,
            bPaginate: false,
            bLengthChange: false,
            bFilter: true,
            bInfo: false,
            bSort:false,
            bAutoWidth: true,
            dom: '<"toolbar_cuurency">frtip',
            initComplete: function(settings, json) {
                 $("div.toolbar_cuurency").html('\n\
                    ');        
                $(".sk-circle-layer").hide();
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $(nRow).addClass(aData[0]);
            },
            fnDrawCallback: setLatestPriceOptions,
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
        
        /*$('#'+table_name).DataTable().columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                search_in_datatable(this.value,that.index(),100,table_name);
            } );
        } );*/
       
    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}

function setLatestPriceOptions(){
    var table = $('#modal_all_history_prices_table').DataTable();
    var p = table.rows({ page: 'current' }).nodes();
    for (var k = 0; k < p.length; k++){
        var index = table.row(p[k]).index();
        table.cell(index,3).data('<button onclick="user_latest_price('+table.cell(index,4).data()+')" type="button" class="btn btn-default btn-sm btn-htable" style="width:100%">USE</button>');
    }
}

function user_latest_price(latest_price){
    if($("#source").val()=="pos"){
        inv.change_discount($("#ltp_item_id").val(),latest_price,0);
    }
    if($("#source").val()=="admin"){
        
        var percentage = 0;
        var old_price = $("#inv_it_price_"+$("#inv_item_id").val()).val();
        var new_price = latest_price;
        

        percentage = 100-((100*new_price)/old_price);

        
        $("#inv_it_dis_"+$("#inv_item_id").val()).val(percentage);
        $("#inv_it_dis_"+$("#inv_item_id").val()).trigger("change");
        
        
    }  

    $('#modal_all_history__prices___').modal('hide');
}