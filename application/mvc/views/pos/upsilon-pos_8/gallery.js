function refresh_gallery(is_admin){
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=items&f=get_items_gallery&p0="+$("#categories_list").val()+"&p1="+$("#sub_categories_list").val(), function (data) {
        _data = data;
    }).done(function () {
        $("#gallery_container").empty();
        for(var i=0;i<_data.gal.length;i++){
            
            
            var onclik_to_add_invoice='add_to_invoive('+_data.gal[i].item_id+',1)';
            if(is_admin==1){
                onclik_to_add_invoice='add_to_invoive_admin('+_data.gal[i].item_id+',1)';
            }
                
            $("#gallery_container").append('\
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 img_section">\n\
                <a href="data/images_items/'+_data.gal[i].path+'" data-lightbox="image-1"><img style="cursor:pointer" src="data/images_items/'+_data.gal[i].path+'" class="img-thumbnail" alt="" style="width:100%"></a>\n\
                <div id="itt_'+_data.gal[i].item_id+'" class="gallery_table_add_item" onclick="'+onclik_to_add_invoice+'">\n\
                    <i class="glyphicon glyphicon-plus-sign "></i>\n\
                </div>\n\
                <div class="gallery_table_description" >\n\
                    <span class="g_searchable">'+_data.gal[i].description+'</span><br/><span class="gprice">'+_data.gal[i].price+''+_data.gal[i].cur+'</span>  <span class="gqty">/'+_data.gal[i].qty+'</span> <span class="gqty">'+_data.gal[i].qty_b+'</span>\n\
                </div>\n\
            </div>');
        }
        hide_gqty_changed();
        hide_gprice_changed();
        $(".sk-circle-layer").hide();
    });
}

var hd=false;
function closeModal_OnlyHide(object){
    if(hd){
        $("#"+object).hide();
        $(".modal-backdrop").hide();
    }
}


function reload_gallery(){
    $("#gallery_items_modal").modal("hide");
    setTimeout(function(){
        open_gallery(0);
    },500);
}

function open_gallery(is_admin){
    
    if (!navigator.onLine) {
            //swal("Check your internet connection");
            //return;
        }
        
    if($("#gallery_items_modal").length>0){
        $("#gallery_items_modal").show();
        return;
    }   
        
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    var _data = [];
    $.getJSON("?r=items&f=get_items_gallery&p0=1&p1=1", function (data) {
        _data = data;
    }).done(function () { 
        var categories_options="";
        var sub_categories_options="";
        hd=true;
        
        var sub_cat_sel="";
        sub_categories_options+="<option value='0'>All Subcategories</option>";
        for(var i=0;i<_data.sub_categories.length;i++){
            sub_cat_sel="";
            if(i==0){
                sub_cat_sel="selected";
            }
            sub_categories_options+="<option "+sub_cat_sel+" value='"+_data.sub_categories[i].id+"'>"+_data.sub_categories[i].description+"</option>";
        }
        
        var cat_sel="";
        categories_options+="<option value='0'>All Categories</option>";
        for(var i=0;i<_data.categories.length;i++){
            cat_sel="";
            if(i==0){
                cat_sel="selected";
            }
            categories_options+="<option "+cat_sel+" value='"+_data.categories[i].id+"'>"+_data.categories[i].name+"</option>";
        }
        
        var content =
        '<div class="modal large" data-backdrop="static" id="gallery_items_modal" role="dialog" >\n\
            <div class="modal-dialog" role="document">\n\
                <div class="modal-content">\n\
                    <div class="modal-header"> \n\
                        <h3 class="modal-title">Gallery of Items<i style="float:right;font-size:35px" class="glyphicon glyphicon-remove" onclick="closeModal_OnlyHide(\'gallery_items_modal\')"></i></h3>\n\
                    </div>\n\
                    <div class="modal-body">\n\
                        <div class="row">\n\
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                    <select data-live-search="true" data-width="100%" id="categories_list" class="selectpicker" onchange="refresh_gallery('+is_admin+')">\n\
                                        ' + categories_options + '\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                    <select data-live-search="true" data-width="100%" id="sub_categories_list" class="selectpicker" onchange="refresh_gallery('+is_admin+')">\n\
                                        ' + sub_categories_options + '\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                    <select data-live-search="true" data-width="100%" id="hide_gprice" class="selectpicker" onchange="hide_gprice_changed()">\n\
                                        <option value="0" selected>Hide Price</option>\n\
                                        <option value="1">Show Price</option>\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                    <select data-live-search="true" data-width="100%" id="hide_gqty" class="selectpicker" onchange="hide_gqty_changed()">\n\
                                        <option value="0" selected>Hide QTY</option>\n\
                                        <option value="1">Show QTY</option>\n\
                                    </select>\n\
                                </div>\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">\n\
                                <div class="btn-group" role="group" aria-label="" style="width:100% !important;">\n\
                                    <button onclick="reload_gallery()" type="button" class="btn btn-primary" style="width:100%;">RELOAD</button>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="row" style="margin-top:20px;">\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                \n\
                            </div>\n\
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">\n\
                                <input oninput="g_search()" placeholder="SEARCH BY ITEM DESCRIPTION, SKU" autocomplete="off" id="search_gallery" name="" class="form-control med_input" value="">\n\
                            </div>\n\
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">\n\
                                \n\
                            </div>\n\
                        </div>\n\
                        <div class="row" id="gallery_container">\n\
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
 
        $('#gallery_items_modal').modal('hide');
        $("body").append(content);
        $('#gallery_items_modal').on('show.bs.modal', function (e) {
            
        });

        $('#gallery_items_modal').on('shown.bs.modal', function (e) {
            
            for(var i=0;i<_data.gal.length;i++){
                
                var onclik_to_add_invoice='add_to_invoive('+_data.gal[i].item_id+',1)';
                if(is_admin==1){
                    onclik_to_add_invoice='add_to_invoive_admin('+_data.gal[i].item_id+',1)';
                }
                
                $("#gallery_container").append('\
                <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 img_section">\n\
                    <a href="'+_data.gal[i].mainpath+'data/images_items/'+_data.gal[i].path+'" data-lightbox="image-1"><img style="cursor:pointer" src="'+_data.gal[i].mainpath+'data/images_items/'+_data.gal[i].path+'" class="img-thumbnail" alt="" style="width:100%"></a>\n\
                    <div id="itt_'+_data.gal[i].item_id+'" class="gallery_table_add_item" onclick="'+onclik_to_add_invoice+'">\n\
                        <i class="glyphicon glyphicon-plus-sign "></i>\n\
                    </div>\n\
                    <div class="gallery_table_description" >\n\
                        <span class="g_searchable">'+_data.gal[i].description+'</span><br/><span class="gprice">'+_data.gal[i].price+' '+_data.gal[i].cur+'</span> <span class="gqty">/'+_data.gal[i].qty+'</span><span class="gqty">'+_data.gal[i].qty_b+'</span>\n\
                    </div>\n\
                </div>');
            }
            $('#gallery_items_modal .modal-body') .css({'max-height': (($(window).height())-150)+'px','overflow-y':'auto'});//$(window).height())-
            $(window).resize(function(){
                $('#gallery_items_modal .modal-body') .css({'height': (($(window).height())-150)+'px','overflow-y':'auto'});
            }).trigger('resize');
            
            
            $("#categories_list").selectpicker();
            $("#sub_categories_list").selectpicker();
            $("#hide_gqty").selectpicker();
            $("#hide_gprice").selectpicker();
            
            hide_gqty_changed();
            hide_gprice_changed();
            $(".sk-circle-layer").hide(); 
            
            
            $("#gallery_items_modal").css("z-index","5000");
            
        });

        $('#gallery_items_modal').on('hide.bs.modal', function (e) {
            $("#gallery_items_modal").remove();
        });
        $('#gallery_items_modal').modal('show');
    }).fail(function() {
        swal("Check your internet connection");
    }).always(function() {
        $(".sk-circle-layer").hide();
        
    });
}

function g_search(){
    //$(".highlight").removeClass("highlight");
    //$("span.g_searchable:contains('" + $("#search_gallery").val() + "')").addClass("highlight");
    
    var searchText = $("#search_gallery").val();
    $('.g_searchable').each(function() {
        var text = $(this).text();
        var highlightedText = text.replace(new RegExp(searchText, 'gi'), function(match) {
            return '<span class="highlight">' + match + '</span>';
        });
        $(this).html(highlightedText);
    });
    
    var searchText = $("#search_gallery").val().toLowerCase(); // Convert search text to lowercase for case-insensitive search
    $("span.g_searchable").each(function() {
        var $parentParent = $(this).parent().parent(); // Cache parent elements for efficiency
        if ($(this).text().toLowerCase().indexOf(searchText) === -1) { // Check if the search text is not found
            $parentParent.hide(); // Hide the parent elements if the search text is not found
        } else {
            $parentParent.show(); // Otherwise, show the parent elements
        }
    });
    
}

function hide_gprice_changed(){
    if($("#hide_gprice").val()==0){
        $(".gprice").hide();
    }else{
        $(".gprice").show();
    }
}

function hide_gqty_changed(){
    if($("#hide_gqty").val()==0){
        $(".gqty").hide();
    }else{
        $(".gqty").show();
    }
}

function item_added_notification(msg){
    $.alert(msg, {
        type:'success',
        closeTime: 3000,
    });
}