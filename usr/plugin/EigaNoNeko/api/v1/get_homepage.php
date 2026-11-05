<?php
$indexBuf = [];
$indexBuf['getContinueWatched'] = __EnnCatalogues->getContinueWatched();
$indexBuf['getLastUpdated'] = __EnnCatalogues->getLastUpdated();
$indexBuf = array_merge($indexBuf, __EnnCatalogues->getAllByCategorie());


header("Content-Type: application/json");
echo __OmsyJSON->encode($indexBuf);