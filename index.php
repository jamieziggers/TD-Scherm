<html>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
    <body class="bodykiosk">
        <style type="text/css">
*
{
    margin: 0px;
    padding: 0px;
}
td,p
{
    font-size:200%;
}
th{
    font-size:250%;
}
</style>
    <script>
    setTimeout("location.reload(true);", 15000); // elke 15 seconden refresh
    </script>
</html>
<?php
    /* Code By JamieZiggers || JamieZiggers.nl */
 
 
 
    // Laad de gegevens van de connection
 
    include 'connection.inc.php';
    //De tabel maken
   
    echo "<table style='width=100%;height=100%' class='table table-bordered'>"; // Start de tabel
    echo "<thead class='thead-inverse' <tr>  <th>Klant</th> <th>Betreft</th> <th>Medewerker</th> <th>Ontvangstdatum</th> <th>Locatie</th></tr></thead>";
 
    // Start de bovenste rij en vult hem met text
 
    // Maakt de query die alle info van die table binnen haalt
    $repairQuery = $conn->query('SELECT * FROM dbo.T_REPAIR_HEADER ORDER BY CREATION_DATETIME'); // Selecteer alle reparaties
 
    //De query fetchen en laten loopen
    while($repairResult= $repairQuery->fetch(PDO::FETCH_OBJ)){
        if($repairResult->STATUS != 4) continue; // Als hij niet in behandeling is, doorgaan naar de volgende row
        if (strpos($repairResult->PRODUCT_TYPE, 'Buitendienst') !== false) continue;
        $cID = intval($repairResult->INVOICE_CONTACT_ID); // Het binden van het contactpersoonnummer aan een var
 
        $eID = intval($repairResult->REPAIR_EMPLOYEE_ID); // het binden van het monteurnummer aan een var
 
        $contactQuery = 'SELECT * FROM dbo.T_CONTACT WHERE CONTACT_ID = :cID'; // De text van de query voor contactpersoon
 
        $customerQuery = 'SELECT * FROM dbo.T_CUSTOMER WHERE CUSTOMER_ID = :cID'; // De text van de query voor het bedrijf
 
        $employeeQuery = 'SELECT * FROM dbo.T_EMPLOYEE WHERE EMPLOYEE_ID = :eID';
        $statement = ''; // Statements leegmaken
 
        $statement= $conn->prepare($contactQuery); // Voorbereiden van de query
        $statement->execute(array(':cID'=> $cID)); // uitvoeren van de query
        $contactResult =  $statement->fetch(); // De uitkomst in een array fetchen.
        $statement = ''; // Statements leegmaken
 
        $statement= $conn->prepare($employeeQuery); // Voorbereiden van de query
        $statement->execute(array(':eID'=> $eID)); // uitvoeren van de query
        $employeeResult =  $statement->fetch(); // De uitkomst in een array fetchen.
        $statement = ''; // Statements leegmaken
 
        $statement= $conn->prepare($customerQuery); // Voorbereiden van de query
        $statement->execute(array(':cID'=> $cID)); // uitvoeren van de query
        $customerResult =  $statement->fetch(); // De uitkomst in een array fetchen.
        $customerName = $contactResult[2]; // Het binden van de naam aan een var
        $statement = ''; // Statements leegmaken
 
        if(!empty($customerResult[1])){ // Als het geen bedrijf is
            $customerName = $customerResult[1]; // Gebruik de naam van het contactpersoon
        }
        $spoedColor='#000000'; // Kleur van de text bij geen spoed.
 
        $date = substr($repairResult->CREATION_DATETIME, 0,10);
        $newdate = date('d-m-y', strtotime($date));
 
        if (stripos($repairResult->ADDITIONAL_INFO, 'Spoed')!== false){ // Indien er wel spoed is
            $spoedColor = '#F62626'; // verander de textkleur naar rood
        }
 
        echo "<tr style='color:" . $spoedColor .";font-weight:bold;'>"; // Opmaak van de text
        echo '<td id=\'KioskKlantnaam\'>' . $customerName .'</td>'; // Klant naam
        echo '<td id=\'KioskBetreft\'>' . $repairResult->PRODUCT_TYPE . '</td>'; // Wat voor product
        echo '<td id=\'KioskMedewerker\'>' . $employeeResult[1] .'</td>'; // Wie er mee bezig is
        echo '<td id=\'KioskOntvangstDatum\'>' . $newdate . '</td>'; // De ontvangstdatum
        echo '<td id=\'KioskLocatie\'>' . $repairResult->REPAIR_LABEL_NUMBER . '</td>'; // Werkplek
        $cID="";
        $eID=""; // De vars clean maken
    }
    echo "</table>"; // De tabel sluiten
    echo "<br><br><center>"; // Centreren
    echo '<p id=\'Kioskdate\'>' . date("H:i d-m-Y") . "</p></center>"; // De datum weergeven onder de tabel
?>