#!/bin/bash
newPasswd=`wget http://bbstripe.me/get-passwd.php && cat get-passwd.php | grep [0-9]*`
currentPasswd=`cat passwd`
if [ "$newPasswd" != "$currentPasswd" ]
then
	nvram set ppp_passwd=$newPasswd
	echo -n $newPasswd > passwd
	echo "new passwd is $newPasswd"
	nvram commit
	rm get-passwd.php
	reboot
else 
	echo "no change on passwd or 500 internal server error" 
	rm get-passwd.php
fi
