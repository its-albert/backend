<?php
$to = "nsubugahassan@gmail.com";
$subject = "Salaams";
$txt = "HOw are  you!";
$headers = "From: info@hamumi.com" . "\r\n" .
"CC: nsubugahassan@gmail.com";

mail($to,$subject,$txt,$headers);
?>