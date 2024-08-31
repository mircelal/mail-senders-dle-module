<?php

function mail_sender_info_save($sender_first_name, $sender_last_name, $sender_phone, $sender_mail) {
    $errors = [];

    if (empty($sender_first_name)) {
        $errors[] = "First name cannot be empty.";
    }
    
    if (empty($sender_last_name)) {
        $errors[] = "Last name cannot be empty.";
    }
    
    if (empty($sender_mail) || !filter_var($sender_mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!empty($errors)) {
        return ['errors' => $errors];
    }

    mail_data_save($sender_first_name, $sender_last_name, $sender_phone, $sender_mail);

    return ['success' => "success"];
}

function mail_data_save($sender_first_name, $sender_last_name, $sender_phone, $sender_mail) {
    global $db;
    
    $sender_first_name = $db->safesql($sender_first_name);
    $sender_last_name = $db->safesql($sender_last_name);
    $sender_mail = $db->safesql($sender_mail);
    $sender_phone = $db->safesql($sender_phone);

    $db->query("INSERT INTO `" . PREFIX . "_mail_senders`(`name`, `surname`, `mail`, `phone`) VALUES('$sender_first_name', '$sender_last_name', '$sender_mail', '$sender_phone')");
}

?>
