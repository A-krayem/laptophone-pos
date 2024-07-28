function show_all_customer_user_log(id){
    var content =
    '<div class="modal" data-backdrop="static" id="user_customers_log_Modal" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <div class="modal-header"> \n\
                    <h3 class="modal-title">Users/Customers Logs<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal(\'user_customers_log_Modal\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body" id="noBarcodeItems">\n\
                    <div class="row">\n\
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="user_customers_log_details"></div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $("#user_customers_log_Modal").remove();
    $("body").append(content);
    $('#user_customers_log_Modal').on('show.bs.modal', function (e) {
        $.getJSON("?r=logs&f=get_user_customers_logs_by_id&p0="+id, function (data) {
            $("#user_customers_log_details").append(data.customer_info);
            $.each(data.logs, function (key, val) {
                $("#user_customers_log_details").append(val.description);
            });
        }).done(function () {
            
        }); 
    });
    
    $('#user_customers_log_Modal').on('shown.bs.modal', function (e) {
        
    });
    $('#user_customers_log_Modal').on('hide.bs.modal', function (e) {
        $("#user_customers_log_Modal").remove();
    });
    $('#user_customers_log_Modal').modal('show');
}