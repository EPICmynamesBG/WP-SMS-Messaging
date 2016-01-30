function toggleSettings() {
    var settingsForm = document.getElementsByName('settings_form')[0];
    var button = document.getElementsByName('showSettingsButton')[0];

    if (settingsForm.hasAttribute('hidden')) {
        button.value = "Hide";
        settingsForm.removeAttribute('hidden');
    } else {
        button.value = "Show";
        settingsForm.setAttribute('hidden', true);
    }
}

function saveSettings(url) {
    var sid = $('[name="sid"]').val();
    var phone = $('form[name="settings_form"] input[name="phone"]').val();
    if (sid.length != 34 || phone == "") {
        return;
    }
    var data = {
        'sid': sid,
        'phone': phone
    }

    $.post({
        url: url,
        data: data,
        success: function (data) {
            var returnArr = $.parseJSON(data);
            if (returnArr['status'] == "success") {
                $('.updated p strong').text("Settings updated");
            } else {
                $('.updated p strong').text("Error: "+returnArr['message']);
            }
            $('.updated').removeAttr('hidden');
        }
    });
}

function editPersonList(action) {
    var i = action.indexOf("?");
    var url = action.substring(0, i);
    var type = action.substring(i + 1, action.length);
    if (type == "create") {
        var name = $('.smsAddPerson input[name="person"]').val();
        var phone = $('.smsAddPerson input[name="phoneNumber"]').val();
        if (name.length != 0 && phone.length != 0) {
            var data = {
                'type':type,
                'name':name,
                'phone':phone
            };

            $.post({
                url: url,
                data: data,
                success: function (data) {
                    console.log(data);
                    var returnArr = $.parseJSON(data);
                    if (returnArr['status'] == "success") {
                        $('.updated p strong').text("Person added");
                    } else {
                        $('.updated p strong').text("Error: "+returnArr['message']);
                    }
                    $('.updated').removeAttr('hidden');
                }
            });
        } else {
            $('.updated p strong').text("Error: Creation input fields cannot be empty.");
            $('.updated').removeAttr('hidden');
        }

    } else if (type == "delete") {
        var nameList = $('.currentNumbers').children('li');
        var checkedArray = [];
        for (var i = 0; i < nameList.length; i++){
            var li = nameList[i];
            var checkbox = $(li).children('input')[0];
            if( $(checkbox).is(":checked")){
                checkedArray.push($(checkbox).val());
            }
        }
        if (checkedArray.length > 0){
            var data = {"type": type,
                "ids": checkedArray};

            $.post({
                url: url,
                data: data,
                success: function (data) {
                    var returnArr = $.parseJSON(data);
                    if (returnArr['status'] == "success") {
                        $('.updated p strong').text("Person(s) deleted");
                    } else {
                        $('.updated p strong').text("Error: "+returnArr['message']);
                    }
                    $('.updated').removeAttr('hidden');
                }
            });
        } else {
            $('.updated p strong').text("Error: No names selected to delete.");
            $('.updated').removeAttr('hidden');
        }
    } else {
        $('.updated p strong').text("Error: Could not determine correct call type.");
        $('.updated').removeAttr('hidden');
        return;
    }
}