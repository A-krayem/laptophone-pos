function customers_cards(){
    var content =
    '<div class="modal" data-backdrop="static" id="customer_cards_Modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title" '+dir_+'>Customers Cards<i style="float:'+float_+';font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'customer_cards_Modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">\n\
                            <table id="garage_cards_table__" class="table table-striped table-bordered" cellspacing="0">\n\
                                <thead>\n\
                                    <tr>\n\
                                        <th style="width: 85px;">Card ID</th>\n\
                                        <th style="width: 200px;">Client</th>\n\
                                        <th style="width: 300px;">Problem Description</th>\n\
                                        <th style="width: 140px;">Code</th>\n\
                                        <th style="width: 140px;">Company</th>\n\
                                        <th style="width: 140px;">Car Type</th>\n\
                                        <th style="width: 140px;">Model</th>\n\
                                        <th style="width: 140px;">Color</th>\n\
                                        <th style="width: 140px;">Odometer</th>\n\
                                        <th style="width: 140px;">Car #</th>\n\
                                        <th style="width: 125px;">Date In</th>\n\
                                        <th style="width: 125px;">Date Out</th>\n\
                                        <th style="width: 150px;">Oil Change Date</th>\n\
                                        <th style="width: 150px;">Oil Next Change Date</th>\n\
                                        <th style="width: 200px;">Oil Note</th>\n\
                                    </tr>\n\
                                </thead>\n\
                                <tfoot>\n\
                                    <tr>\n\
                                        <th>Card ID</th>\n\
                                        <th>Client</th>\n\
                                        <th>Problem Description</th>\n\
                                        <th>Code</th>\n\
                                        <th>Company</th>\n\
                                        <th>Car Type</th>\n\
                                        <th>Model</th>\n\
                                        <th>Color</th>\n\
                                        <th>Odometer</th>\n\
                                        <th>car #</th>\n\
                                        <th>Date In</th>\n\
                                        <th>Date Out</th>\n\
                                        <th>Oil Change Date</th>\n\
                                        <th>Oil Next Change Date</th>\n\
                                        <th>Oil Note</th>\n\
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
    $("#customer_cards_Modal").remove();
    $("body").append(content);
    $('#customer_cards_Modal').on('show.bs.modal', function (e) {

    });
    
    $('#customer_cards_Modal').on('shown.bs.modal', function (e) {
        $('#garage_cards_table__').show();
        
        var garage_cards_table__var =null;
        
        var search_fields = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
        var index = 0;
        $('#garage_cards_table__ tfoot th').each( function () {

            if(jQuery.inArray(index, search_fields) !== -1){
                var title = $(this).text();
                $(this).html( '<div class="inner-addon left-addon"><input id="idf_'+index+'" style="width: 100% !important;"  class="form-control input-sm" type="text" placeholder="'+title+'" /></div>' );
                index++;
            }
        });

        garage_cards_table__var = $('#garage_cards_table__').DataTable({
            ajax: {
                url: "?r=garage&f=getAllClientsCards&p0=0",
                type: 'POST',
                error:function(xhr,status,error) {
                    logged_out_warning();
                },
            },
            orderCellsTop: true,
            scrollX: true,
            scrollY: true,
            iDisplayLength: 50,
            scrollY: '45vh',
            aoColumnDefs: [
                { "targets": [0], "searchable": true, "orderable": true, "visible": true },
                { "targets": [1], "searchable": true, "orderable": true, "visible": true },
                { "targets": [2], "searchable": true, "orderable": true, "visible": true },
                { "targets": [3], "searchable": true, "orderable": true, "visible": true },
                { "targets": [4], "searchable": true, "orderable": true, "visible": true },
                { "targets": [5], "searchable": true, "orderable": true, "visible": true },
                { "targets": [6], "searchable": true, "orderable": true, "visible": true },
                { "targets": [7], "searchable": true, "orderable": true, "visible": true },
                { "targets": [8], "searchable": true, "orderable": true, "visible": true },
                { "targets": [9], "searchable": true, "orderable": true, "visible": true },
                { "targets": [10], "searchable": true, "orderable": true, "visible": true },
                { "targets": [11], "searchable": true, "orderable": true, "visible": true },
                { "targets": [12], "searchable": true, "orderable": true, "visible": true },
                { "targets": [13], "searchable": true, "orderable": true, "visible": true },
                { "targets": [14], "searchable": true, "orderable": true, "visible": true }
            ],
            scrollCollapse: true,
            paging: true,
            initComplete: function(settings, json) {
                $(garage_cards_table__var.row(1)).addClass('selected');
                $('.dataTables_scrollBody thead tr').css({visibility:'collapse'});
            },
            
        });
        
        
        $('#garage_cards_table__').DataTable().on('mousedown',"tbody tr", function ( e, dt, type, indexes ) { 
            $('.selected').removeClass("selected");
            $(this).addClass('selected');
         });
         
         
    });
    $('#customer_cards_Modal').on('hide.bs.modal', function (e) {
        $("#customer_cards_Modal").remove();
    });
    $('#customer_cards_Modal').modal('show');
}