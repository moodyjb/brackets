<?php

?>
<h3>Enhancements and error fixes</h3>
<ul>
    <li>March 15 2020
    <ul>
        <li>Guides print function corrected. Also menu item shows for any logged in user.</li>
        <li>Guides update function added for admins.</li>
    </ul></li>
    <li>March 11 2020
    <ul>
        <li>access control allows '@' to confirmations</li>
        <li>confirmations/communications debugging code to introduce email and mobile address errors was removed.</li>
        <li>Mismatch between player and team gradegroups now shows as a input field error.</li>
        <li>Password coding mistake made all changes to the logged on user; not the selected user.</li>
    </ul></li>
    <li>Feb 26 2020
    <ul>
        <li>"/etc/apache2/sites-enables/000-default-le-ssl.conf" changed to have 'indexDemo'</li>
        <li>Test database mode for learning and instruction</li>
        <li>local & remote databases have the same user:coach and password</li>
        <li>web.php and webDemo.php both in git; Db username/password the local & remote</li>
        <li>Tested adding parent and player and was OK</li>
    </ul></li>
    <li>Feb 13 2020
    <ul>
        <li>access control "ruleConfig' => ['class' => 'app\components\AccessRule']," was missing in some controllers</li>
        <li>Provided site/captcha permissions</li>
        <li>Site/login logic error; any password error not shown.</li>
    </ul></li>
    <li>Jan 22 2020
    <ul>
        <li>Limited registrar access Feb 11 & Mar 10  from 5-7 pm (see configurations).</li>
        <li>Suspicious team assignments different from previous seasson</li>
        <li>Remove 'TravelBall' from registration</li>
        <li>Add medical conditions to roster print outs</li>
        <li>Corrected roster email options</li>
        <li>Add clean up data / analyze to manually remove duplicate users</li>
        <li>Allowed debug option on production code</li>
        <li>views/editableText.txt should not be in git. Permissions are coach:www-data</li>
    </ul></li>


</ul>