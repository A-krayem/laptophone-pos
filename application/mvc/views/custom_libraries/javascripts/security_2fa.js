function enable_changed(){
    if ($('#sw_btn').is(':checked')) {
        var result = [];
        $.getJSON("?r=ga_2fa&f=enable_2fa", function (data) {
            result = data;
        }).done(function () {
            $("#2fa_qrcode").attr("src",result.qr);
            $(".2fa_qrcode_container").show();
        });
    } else {
        var result = [];
        $.getJSON("?r=ga_2fa&f=disable_2fa", function (data) {
            result = data;
        }).done(function () {
            $(".2fa_qrcode_container").hide();
        });
    }
}

function disable_save(){
    if($("#ga_code").val().length==0){
        $("#ga_code").addClass("error");
        return;
    }else{
        $("#ga_code").removeClass("error");
    }
    var result = [];
    $("#disable_save_btn").prop("disabled",true);
    $.getJSON("?r=ga_2fa&f=disable_save&p0="+$("#ga_code").val(), function (data) {
        result = data;
    }).done(function () {
        $("#disable_save_btn").prop("disabled",false);
        if(result.valid==1){
            location.reload();
        }else{
            $("#ga_code").val("");
             $("#ga_code").focus();
        }
    });
}

function enable_save(){
    var result = [];
    $("#enable_save_btn").prop("disabled",true);
    $.getJSON("?r=ga_2fa&f=enable_save&p0="+$("#ga_code").val(), function (data) {
        result = data;
    }).done(function () {
        if(result.valid_code==0){
           $.confirm({
                title: 'Alert!',
                content: 'Invalid code',
                buttons: {
                    ok: function () {
                       $("#enable_save_btn").prop("disabled",false);
                    },
                }
            });
        }else{
            location.reload();
        }
    });
}

function security_2fa(default_value){
    
    var body_modal='\n\
        <div class="row">\n\
            <div class="col-md-12">\n\
                Google Authenticator is a mobile app that enhances security by providing two-factor authentication (2FA) for online accounts<br/>\n\
                <br/><b class="text-primary">Step 1</b><br/>Download and install Google Authenticator from the Google Play Store (for Android) or the Apple App Store (for iOS).<br/><br/>\n\
            </div>\n\
        </div>\n\
        <div class="row">\n\
            <div class="col-md-12"><b class="text-primary">Step 2</b><br/>Enable 2FA<br/><label class="custom-switch"><input id="sw_btn" type="checkbox" data-old="" onchange="enable_changed()" class="custom-switch-input"><span class="custom-switch-label"></span></label></div>\n\
        </div>\n\
        <div class="row 2fa_qrcode_container" style="display:none">\n\
            <div class="col-md-12">\n\
                <b class="text-primary">Step 3</b><br/>\n\
                <img id="2fa_qrcode" src="" alt="Scan this QR code with Google Authenticator">\n\
                <br/>Scan this QR code with Google Authenticator\n\
            </div>\n\
        </div>\n\
        <div class="row 2fa_qrcode_container" style="display:none;margin-top:5px;">\n\
            <div class="col-md-4">\n\
             <b class="text-primary">Step 4</b>\n\
                <input id="ga_code" class="form-control" type="text" placeholder="Enter the code from Google Authenticator" style="width:100%;" autocomplete="off">\n\
            </div>\n\
        </div>\n\
        <div class="row 2fa_qrcode_container" style="display:none; margin-top:10px;">\n\
            <div class="col-lg-2 col-md-2 col-sm-12 pr2">\n\
                <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                    <button id="enable_save_btn" type="button" class="btn btn-primary" onclick="enable_save()" style="width:100%;">ENABLE</button>\n\
                </div>\n\
            </div>\n\
        </div>';
    if(default_value==1){
        body_modal='\n\
            <div class="row">\n\
                <div class="col-lg-4 col-md-4 col-sm-12">\n\
                    <b style="color:#5cb85c">Two-factor authentication is enabled. To disable it, enter the code and click on \'Disable 2FA\'</b>\n\
                </div>\n\
            </div>\n\
            <div class="row" style="margin-top:10px;">\n\
                <div class="col-lg-4 col-md-4 col-sm-12">\n\
                    <input id="ga_code" class="form-control" type="text" placeholder="Enter the code from Google Authenticator" style="width:100%;" autocomplete="off">\n\
                </div>\n\
            </div>\n\
            <div class="row" style="margin-top:10px;">\n\
            <div class="col-lg-4 col-md-4 col-sm-12">\n\
                <div class="btn-group" role="group" aria-label="" style="width:100%;">\n\
                    <button id="disable_save_btn" type="button" class="btn btn-danger" onclick="disable_save()" style="width:100%;">DISABLE 2FA</button>\n\
                </div>\n\
            </div>\n\
        </div>';
        
    }
    
    var modal_name = "modal_2fa_modal__";
    var modal_title = "2FA Security - Google Authenticator";
    var content =
    '<div class="modal medium-plus" data-backdrop="static" id="'+modal_name+'" tabindex="-1" role="dialog" aria-labelledby="payment_info__" aria-hidden="true">\n\
        <div class="modal-dialog" role="document">\n\
            <div class="modal-content">\n\
                <input id="id_to_edit" name="id_to_edit" value="0" type="hidden" />\n\
                <input id="counter_type" name="counter_type" value="2" type="hidden" />\n\
                <div class="modal-header">\n\
                    <h3 class="modal-title">'+modal_title+'<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\''+modal_name+'\')"></i></h3>\n\
                </div>\n\
                <div class="modal-body">\n\
                    '+body_modal+'\n\
                </div>\n\
                <div class="modal-footer" style=" text-align: left;">\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>';
    $('#'+modal_name).modal('hide');
    $("body").append(content);
            
    $('#'+modal_name).on('show.bs.modal', function (e) {

    });
    
    $('#'+modal_name).on('shown.bs.modal', function (e) {

    });
    $('#'+modal_name).on('hide.bs.modal', function (e) {
        $("#"+modal_name).remove();
    });
    $('#'+modal_name).modal('show');
}