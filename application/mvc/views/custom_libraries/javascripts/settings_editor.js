function initSettingsEditor() {
    $(".sk-circle").center();
    $(".sk-circle-layer").show();
    $.ajax({
        url: "?r=settings&f=get_settings",
        dataType: "json",
        success: function (response) {
            $(".sk-circle-layer").hide();
            tabs = {};
            tabsContent = {};
            response.tabs.forEach((tab, tabIndex) => {
                tabs[tabIndex] = `<li  class="${tabIndex == 0 ? "active" : ""}" ><a data-toggle="tab" href="#settingsTab${tabIndex}">${tab.title}</a></li>`
                tabsContent[tabIndex] = "";
                tmp = [];
                tab.sections.forEach((section) => {
                    tmp.push(
                        `<div class="col-md-6 col-lg-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">${section.title}</h3>
                                </div>
                                <div class="panel-body" style="background:white">
                                ${prepareSettingsArray(section.settings, response.settings, response.oldValues).join("")}
                                </div>
                            </div>
                        </div>`);
                })
                tabsContent[tabIndex] = ` <div id="settingsTab${tabIndex}" style="padding-top:15px" class="tab-pane fade ${tabIndex == 0 ? "in  active" : ""}">${tmp.join("")}</div>`

            })
            modal_name = "settings_editor_modal"
            modal_title = "<i class='glyphicon glyphicon-cog'></i> Settings Editor"
            var content =
                `<div div class= "modal" data - backdrop="static" id = "${modal_name}" tabindex = "-1" role = "dialog" aria - labelledby="payment_info__" aria - hidden="true" >
                    <div class="modal-dialog" style="margin-top:0!important" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">${modal_title}<i style="float:right;font-size:34px;" class="glyphicon glyphicon-remove" onclick="modal_close(\'${modal_name}\')"></i></h3>
                            </div>
        
                            <div class="modal-body" style="padding-top:2px;">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                    <ul class="nav nav-tabs">
                                    ${Object.values(tabs).join("")}
                                  </ul>
                                
                                  <div class="tab-content">
                                   ${Object.values(tabsContent).join("")}
                                    <div id="section2" class="tab-pane fade">
                                      <h3>Section 2</h3>
                                      <p>This is the content of section 2.</p>
                                    </div>
                                    <div id="section3" class="tab-pane fade">
                                      <h3>Section 3</h3>
                                      <p>This is the content of section 3.</p>
                                    </div>
                                  </div>
                                    </div>
                                </div>
                            </div>
        
                        </div>
                    </div>
                </div> `;
            $("#" + modal_name).remove();
            $("body").append(content);

            $(`#${modal_name}`).modal("show");


            $(`#${modal_name}`).on("hide.bs-modal", () => {
                $(`#${modal_name}`).remove();
            })
        }
    });

}
function prepareSettingsArray(ids, AllSettings, oldValues) {
    returnArray = [];
    ids.forEach((settingsKey, i) => {
        currentSetting = AllSettings[settingsKey];
        template = "";

        if (currentSetting.type == "text" || currentSetting.type == "number") {
            template = `<div class="col-md-12"><input type="${currentSetting.type}" class="form-control" data-old="${oldValues[settingsKey].value}" onchange="updateSettingById('${settingsKey}',this.value,'text',this)" value="${oldValues[settingsKey].value}"/></div>`;
        } else if (currentSetting.type == "boolean") {
            template = `<div class="col-md-12"><label class="custom-switch"><input type="checkbox" data-old="${oldValues[settingsKey].value}" onclick="updateSettingById('${settingsKey}',this.checked?1:0,'checkbox',this)" ${oldValues[settingsKey].value == 1 ? "checked" : ""} class="custom-switch-input"><span class="custom-switch-label"></span></label></div>`
        } else if (currentSetting.type == "optionList") {

            template = `<div class="col-md-12"><select  onchange="updateSettingById('${settingsKey}',this.value,'select',this)" data-old="${oldValues[settingsKey].value}" class="form-control">${currentSetting.options.map((option) => {
                return `<option value="${option.value}" ${option.value == oldValues[settingsKey].value ? "selected" : ""}>${option.name}</option>`
            }).join("")}</select></div>`
        }
        template = `<div class="row" style="${i % 2 == 0 ? "background: #f2f2f2;" : ""}padding: 10px 0px;"><div class="col-md-12">${currentSetting.name}</div>${template}${currentSetting.note ? `<div class="col-md-12">Note: ${currentSetting.note}</div>` : ""}</div>`
        returnArray.push(template);
    })

    return returnArray
}
function updateSettingById(settingsName, value, elementType, element) {
    $.ajax({
        type: "post",
        url: `?r=settings&f=update`,
        data: { value: value, key: settingsName },
        dataType: "json",
        success: function (response) {
            if (response.success == false) {
                if (elementType == "checkbox") {
                    if ($(element).attr("data-old") == 1)
                        $(element).prop("checked", true)
                    else {
                        $(element).prop("checked", false)
                    }
                } else {
                    $(element).val($(element).attr("data-old"));
                }
            }
        }
    });
}