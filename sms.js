function toggleSettings() {
    var settingsForm = document.getElementsByName('settings_form')[0];
    var button = document.getElementsByName('showSettingsButton')[0];

    if (settingsForm.hasAttribute('hidden')){
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
    var data = {
        'sid': sid,
        'phone': phone
    }

    $.post({
			url: url,
			data: data,
			success: function(data){
				var returnArr = $.parseJSON(data);
                if (returnArr['status'] == "success"){
                    $('.updated p strong').text("Settings updated");
                } else {
                    $('.updated p strong').text("Error: Settings could not be updated");
                }
                $('.updated').removeAttr('hidden');
			}
		});
}

function editPersonList(){
    console.log("Cakked");
}