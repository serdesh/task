<?php

use app\modules\drive\models\Drive;

?>

    <div class="drive-default-index">
        <h1><?= $this->context->action->uniqueId ?></h1>
        <p>
            This is the view content for action "<?= $this->context->action->id ?>".
            The action belongs to the controller "<?= get_class($this->context) ?>"
            in the "<?= $this->context->module->id ?>" module.
        </p>
        <p>
            You may customize this page by editing the following file:<br>
            <code><?= __FILE__ ?></code>
        </p>
    </div>
<?php
// Get the API client and construct the service object.
$client = Drive::getClient();

$service = new Google_Service_Drive($client);

// Print the names and IDs for up to 10 files.
$optParams = array(
    'pageSize' => 10,
    'fields' => 'nextPageToken, files(id, name)'
);
$results = $service->files->listFiles($optParams);

if (count($results->getFiles()) == 0) {
    print "No files found.\n";
} else {
    print "Files:\n";
    foreach ($results->getFiles() as $file) {
        printf("%s (%s)\n", $file->getName(), $file->getId());
    }
}
?>