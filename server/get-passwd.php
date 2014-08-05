<?php

/**
 * @author Elence Yuan
 * @title ChinaNet password update server
 * 
 * !!Notice!! be careful of the permission of get-passwd.php, passwd.txt and log.html
 */

session_start();
$passwdPath = 'passwd.txt';
$logPath = 'log.html';

if (startsWith($_SERVER['HTTP_USER_AGENT'], 'Wget')) {
	//record new log
        if (file_exists($logPath) === true) {
            $current = file_get_contents($logPath);
            $current .= '<div>'.date("Y-m-d H:i:s").' wget </div>';
            file_put_contents($logPath, $current);
        } else {
            $fl = fopen($logPath, 'w');
            fwrite($fl, '<h1>Record of Password Changes</h1>');
            fwrite($fl, '<div>'.date("Y-m-d H:i:s").' wget </div>');
            fclose($fl);
        }
	getPasswd($passwdPath);

} else {
    $passwd = filter_var($_GET['passwd'], FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/\d{6}/")));

    if ($passwd === false) {
        echo '<div>201</div><div>wrong input</div>';
    } else {
        //print the webpage for success updating
        echo '<div>200</div>';
        echo '<div>updating password</div>';
        echo '<div>new passwd = ' . $passwd . '</div>';
        echo '<div>set new passwd success</div>';

        //store new password in passwd.txt
        $fp = fopen($passwdPath, 'w');
        fwrite($fp, $passwd);
        fclose($fp);

        //record new log
        if (file_exists($logPath) === true) {
            $current = file_get_contents($logPath);
            $current .= '<div>'.date("Y-m-d H:i:s") . ' ' . $passwd.'</div>';
            file_put_contents($logPath, $current);
        } else {
            $fl = fopen($logPath, 'w');
            fwrite($fl, '<h1>Record of Password Changes</h1>');
            fwrite($fl, '<div>'.date("Y-m-d H:i:s") . ' ' . $passwd.'</div>');
            fclose($fl);
        }
    }
}

function startsWith($stringToScan, $pattern) {
    return $pattern === "" || strpos($stringToScan, $pattern) === 0;
}

function getPasswd($passwdPath) {
    $fOpened = fopen($passwdPath, 'r') or die('<div>199</div><div>error when open the file</div>');
    $passwd = fgets($fOpened, 1024);
    fclose($fOpened);
    echo $passwd;
}

session_destroy();
