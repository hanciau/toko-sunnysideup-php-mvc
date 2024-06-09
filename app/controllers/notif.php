<?php

class notif extends Controller {

    public function __construct()
    {
        $this->requireLogin();
        $this->adminmustgo();
    }


    public function handleNotification() {
        $result = $this->model('notif_db')->updateTransactionStatus($_POST);

        if ($result === true) {
            echo 'Notification handled successfully';
        } else {
            echo $result;
        }
    }
}
?>
