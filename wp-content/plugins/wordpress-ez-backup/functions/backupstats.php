<?php   
        sleep(3);
        $log = "../logs/log.txt";
        $end = "../functions/marker.mk";
        $file = "$log";
        $file = escapeshellarg($file); // for the security concious (should be everyone!)

if (file_exists($end)) {
        $line = `tail -n 30 $file`;
        echo '<pre>';
        print_r($line);
        echo '</pre>';
echo "<meta http-equiv='refresh' content='2'>"; 
}else{
        $line = `tail -n 30 $file`;
        echo '<pre>';
        print_r($line);
        echo '</pre>';
  echo '<form method="post">';
  echo '<input type="submit" value="Close Window" name="submit" onClick="window.close()">';
  echo '</form>';
}


?>