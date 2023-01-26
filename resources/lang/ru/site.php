<?php

use App\Models\Translate;

$Translate = new Translate();
$TranslateList = Translate::all()->toArray();

$ReturnArray = [];

foreach ($TranslateList as $Item) {
    $ItemJson = json_decode($Item['value'], true);
    $ReturnArray[$Item['key']] = $ItemJson['ru'];
}

return $ReturnArray;