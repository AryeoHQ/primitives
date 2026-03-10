<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return (new Configuration())
    ->addPathRegexToExclude('~Test(Cases)?\.php$~');
