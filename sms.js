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
    var accountSID = $('[name="accountSID"]').val();
    var accountAuth = $('[name="accountAuth"]').val();
    var serviceSID = $('[name="serviceSID"]').val();
    var phone = $('form[name="settings_form"] input[name="phone"]').val();

    if (accountSID.length != 34 || accountAuth.length != 32 || serviceSID.length != 34) {
        if (accountSID.length != 34){
            $('.updated p strong').text("Error: AccountSID must be 34 chars");
        } else if (accountAuth.length != 32){
            $('.updated p strong').text("Error: AccountAuth must be 32 chars");
        } else {
            $('.updated p strong').text("Error: ServiceSID must be 34 chars");
        }
        $('.updated').removeAttr('hidden');
        return;
    }
    var data = {
        'account_sid': accountSID,
        'account_auth':accountAuth,
        'service_sid':serviceSID,
        'phone': phone
    };

    $.post({
        url: url,
        data: data,
        success: function (data) {
            var returnArr = $.parseJSON(data);
            if (returnArr['status'] == "success") {
                $('.updated p strong').text("Settings updated");
            } else {
                $('.updated p strong').text("Error: " + returnArr['message']);
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
                'type': type,
                'name': name,
                'phone': phone
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
                        $('.updated p strong').text("Error: " + returnArr['message']);
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
        for (var i = 0; i < nameList.length; i++) {
            var li = nameList[i];
            var checkbox = $(li).children('input')[0];
            if ($(checkbox).is(":checked")) {
                checkedArray.push($(checkbox).val());
            }
        }
        if (checkedArray.length > 0) {
            var data = {
                "type": type,
                "ids": checkedArray
            };

            $.post({
                url: url,
                data: data,
                success: function (data) {
                    var returnArr = $.parseJSON(data);
                    if (returnArr['status'] == "success") {
                        $('.updated p strong').text("Person(s) deleted");
                    } else {
                        $('.updated p strong').text("Error: " + returnArr['message']);
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

function sendMessage(url) {
    var message = $('form[name="message_form"] textarea').val();
    if (message.length == 0) {
        $('.updated p strong').text("Error: Message cannot be empty.");
        $('.updated').removeAttr('hidden');
        return;
    }

    var data = {
        'message': message
    };
    var sendButton = $('form[name="message_form"] input[name="send"]');
    sendButton.val("Sending...");
    sendButton.prop('disabled', true);

    $.post({
        url: url,
        data: data,
        success: function (data) {
            customUpdate($.parseJSON(data));
            sendButton.val("Send Message");
            sendButton.prop('disabled', false);
        }
    });

    function customUpdate(returnData){
        console.log(returnData);
        var buildStr = "Message send results.\n";
        for (var i = 0; i < returnData.length; i++){
            var item = returnData[i];
            var temp = "\tMessage to "+ item['To'] +" "+ item['Status']+"\n";
            buildStr += temp;
        };
        $('.updated p strong').text(buildStr);
        $('.updated').removeAttr('hidden');
    };
}