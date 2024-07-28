function items_autocomplete(element_id){
    /*
    $.get("?r=autocomplete_data&f=getitems_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });*/
}

function car_autocomplete(element_id){
    $.get("?r=autocomplete_data&f=getcars_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}

function getcartype_for_type_head(element_id){
    $.get("?r=autocomplete_data&f=getcartype_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}


function getcarmodel_for_type_head(element_id){
    $.get("?r=autocomplete_data&f=getcarmodel_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}

function getsup_name_for_type_head(element_id){
    $.get("?r=autocomplete_data&f=getsup_name_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}


function getfirstname_for_type_head(element_id){
    $.get("?r=autocomplete_data&f=getfirstname_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}


function getmiddlename_for_type_head(element_id){
    $.get("?r=autocomplete_data&f=getmiddlename_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}

function getlastname_for_type_head(element_id){
    $.get("?r=autocomplete_data&f=getlastname_for_type_head", function(data){
        var $input = $("#"+element_id);
        $input.typeahead({
            source: data,
            autoSelect: true,
            //fitToElement:false,
        });
    },'json')
    .done(function(){
    })
    .fail(function() {
    })
    .always(function() {
    });
}

