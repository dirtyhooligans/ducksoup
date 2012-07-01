<a href="<?=APP_BASE_URL;?>/admin/apps/create">Create App</a><br />
Available Apps:<br>
<?php
echo Model_App_Ducksoup_Apps::getApps();
?>