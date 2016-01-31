<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



    function getCurrentList(){
        global $wpdb;
        $table = $wpdb->prefix . "SMS";

        $sql = "SELECT * FROM $table;";
        $result = $wpdb->get_results($sql);
        if (count($result) == 0){
            echo "<li>Looks like there's no one here yet. Add someone above!</li>";
        } else {
            foreach($result as $value) {
                $name = $value->name;
                $phone = $value->phone_number;
                $id = $value->id;
                $carrier = $value->carrier;
                $pArr = str_split($phone);
                $phone = "(".$pArr[0].$pArr[1].$pArr[2].")-".$pArr[3].$pArr[4].$pArr[5]."-".$pArr[6].$pArr[7].$pArr[8].$pArr[9];
                echo '<li><input type="checkbox" name="checked" form="numbers_form" value="'.$id.'" />'.$name.': '.$phone.' ('.$carrier.')</li>';
            }
        }
    }


    function getCarriers(){
        $gateways = array
        (
            'None' => '',
            'Verizon' => 'vtext.com',
            'ATT'     => 'txt.att.net',
            'Qwest'   => 'qwestmp.com',
            'Sprint'  => 'messaging.sprintpcs.com',
            'T-Mobile'  => 'tmomail.net',
            'Alltel' => 'message.alltel.com',
            'Boost' => 'myboostmobile.com',
            'Cricket' => 'sms.mycricket.com',
            'MetroPCS' => 'mymetropcs.com',
        );
        foreach($gateways as $key => $value){
            echo '<option value="'.$value.'">'.$key.'</option>';
        }
    }

    function getCurrentEmail(){
        global $wpdb;
        $table = $wpdb->prefix . "SMS_config";
        $sql = "SELECT `from_email` FROM `$table` WHERE 1;";
        $result = $wpdb->get_results($sql);
        if (count($result) == 0){
            return "";
        }
        return $result[0]->from_email;
    }

?>
    <div class="wrap">
       <div class="updated" hidden>
       <p>
       <strong></strong>
       </p>
       </div>
        <h1>SMS Messaging: Text Reminders</h1>
        <form name="message_form" >
            <h2 class="title">Create a message</h2>
            <h4 class="title">Notice: Message will be sent to all people in the list below. Max chars: 160.</h4>
            <textarea type="textarea" name="message" autofocus required placeholder="Type a message here!" maxlength="160" class="messageInput large-text code"></textarea>
            <br />
            <p class="submit">
                <input type="button" name="send" value="Send Message" class="button button-primary" class="button button-primary" <?php echo "onclick=sendMessage('". plugins_url( 'sendMessage.php', __FILE__)."')"?> />
            </p>
        </form>
        <hr class="darkHR"/>
        <form class="smsAddPerson" name="newNumber_form">
            <h2 class="title">Add text members</h2>
            <h5 class="title">Selecting carrier is optional, but suggested if known.</h5>
            <input type="text" name="person" placeholder="Johnny Appleseed" class="regular-text code personInput" form="newNumber_form" required/>
            <input type="tel" name="phoneNumber" placeholder="(111)-222-3333" class="regular-text code personInput" form="newNumber_form" required/>
            <select name="carrier">
                <?php getCarriers(); ?>
            </select>
            <input type="button" name="button" value="Add Person" class="button" <?php echo "onclick=editPersonList('". plugins_url( 'numbersList.php', __FILE__)."?create')" ?> />
        </form>
        <hr />
        <form name="numbers_form">
            <h2 class="title">Remove text members</h2>
            <ul class="currentNumbers">
                <?php getCurrentList() ?>
            </ul>
            <p class="submit">
                <input type="button" name="delete" value="Delete Selected" class="button delete-button" class="button button-primary" <?php echo "onclick=editPersonList('". plugins_url( 'numbersList.php', __FILE__)."?delete')" ?> />
            </p>
        </form>
        <hr class="darkHR"/>
        <h1 class="title">Settings <input type="button" value="Show" class="button" onclick="toggleSettings()" name="showSettingsButton" /></h1>
        <form name="settings_form" method="post" hidden formenctype="multipart/form-data" <?php echo "action=". plugins_url( 'editSettings.php', __FILE__)?>
>
           <h3>Enter your Twilio account info here</h3>
            <label>From email: <input type="email" maxlength="40" name="from_email" placeholder="example@example.com" class="regular-text code" form="settings_form" required <?php echo 'value="'.getCurrentEmail().'"'?>/></label>

            <p class="submit">
                <input type="submit" name="save" value="Save Settings" class="button button-primary" form="settings_form" <?php echo "onclick=saveSettings('". plugins_url( 'editSettings.php', __FILE__)."')"?> />
            </p>
        </form>
    </div>