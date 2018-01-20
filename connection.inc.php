<?php
    try{
        $server='CLASSIFIED';
        $user='CLASSIFIED';
        $password='CLASSIFIED';
        $conn = new PDO("sqlsrv:server=$server;Database=VMSII_NET",$user,$password);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e){
        die(print_r($e->getMessage()));
    }
?>