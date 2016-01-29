<?php
    function getCurrentList(){
        global $wpdb;
        global $table_name;

        $sql = "SELECT name, phone_number FROM $table_name;";
        $result = mysqli_query($wpdb, $sql);
        echo $result;
    }

<li><input type="checkbox" name="checked" form="numbers_form" />Name Here </li>

?>
    <div class="wrap">
        <h1>SMS Messaging: Text Reminders</h1>
        <form name="message_form" method="post" <?php echo "action=". plugins_url( 'sendMessage.php', __FILE__)?> >
            <h2 class="title">Create a message</h2>
            <textarea type="textarea" name="message" autofocus required placeholder="Type a message here!" maxlength="160" class="messageInput large-text code"></textarea>
            <br />
            <p class="submit">
                <input type="submit" name="send" value="Send Message" class="button button-primary" class="button button-primary" />
            </p>
        </form>
        <hr class="darkHR"/>
        <form class="smsAddPerson" name="newNumber_form" method="post" <?php echo "action=". plugins_url( 'editNumbersList.php', __FILE__)?>>
            <h2 class="title">Add text members</h2>
            <input type="hidden" name="type" value="create">
            <input type="text" name="person" placeholder="Johnny Appleseed" class="regular-text code personInput" form="newNumber_form" />
            <input type="tel" name="phoneNumber" placeholder="(111)-222-3333" class="regular-text code personInput" form="newNumber_form" />
            <input type="submit" name="submit" value="Add Person" class="button" form="newNumber_form" />
        </form>
        <hr />
        <form name="numbers_form" method="post" <?php echo "action=". plugins_url( 'editNumbersList.php', __FILE__)?>>
            <h2 class="title">Remove text members</h2>
            <input type="hidden" name="type" value="create" form="numbers_form">
            <ul class="currentNumbers">
                <?php getCurrentList() ?>
            </ul>
            <p class="submit">
                <input type="submit" name="delete" value="Delete Selected" class="button delete-button" class="button button-primary" />
            </p>
        </form>
        <hr class="darkHR"/>
        <h1 class="title">Settings <input type="button" value="Show" class="button" onclick="toggleSettings()" name="showSettingsButton" /></h1>
        <form name="settings_form" method="post" hidden>
           <h3>Enter your Twilio account SID and phone number here</h3>
            <label>SID: <input type="text" maxlength="34" name="sid" placeholder="Enter your Twilio SID here" class="regular-text code" form="settings_form" required/></label>
            <br/>
            <label>Phone: <input type="tel" name="phone" placeholder="(111)-222-3333" class="regular-text code" form="settings_form" required/></label>
            <p class="submit">
                <input type="submit" name="save" value="Save Settings" class="button button-primary" form="settings_form"/>
            </p>
        </form>
    </div>