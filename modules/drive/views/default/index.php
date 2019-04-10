<?php

use app\modules\drive\models\Drive;
use yii\helpers\Url;

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
try {
    $client = Drive::getClient();
//    $client = Yii::$app->authClientCollection->getClient('google');
} catch (Google_Exception $e) {
    Yii::$app->session->setFlash('error', $e->getMessage());
    Yii::error($e->getTraceAsString(), __METHOD__);
} catch (Exception $e) {
    Yii::$app->session->setFlash('error', $e->getMessage());
    Yii::error($e->getTraceAsString(), __METHOD__);
}

$service = new Google_Service_Drive($client);

// Print the names and IDs for up to 10 files.
$optParams = array(
    'pageSize' => 10,
    'fields' => 'nextPageToken, files(id, name)'
);
$results = $service->files->listFiles($optParams);

if (count($results->getFiles()) == 0) {
    print "Файлы не найдены.\n";
} else {
echo "Файлы:" . PHP_EOL;
foreach ($results->getFiles() as $file): ?>
    <div>
        <?php printf("<b>%s</b> (%s)" . PHP_EOL, $file->getName(), $file->getId()); ?>
    </div>
<?php endforeach; ?>
<?php }?>

<?php
    $files = [Url::to('@web/images/testfile_1004_1.txt')]

?>
