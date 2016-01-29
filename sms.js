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