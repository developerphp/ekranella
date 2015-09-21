<?php
$contents = file_get_contents('/var/www/html/ekranella/backend/public/githook.sh');
echo shell_exec($contents);

?>