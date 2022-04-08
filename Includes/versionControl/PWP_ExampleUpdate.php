<?php

declare(strict_types=1);

namespace PWP\includes\versionControl;

use PWP\includes\versionControl\PWP_VersionNumber;

class PWP_ExampleUpdate extends PWP_Update
{
        public function __construct()
        {
            //feed the update's version number into the parent constructor here
            parent::__construct('0.1.0');

            //do other constructor setup here.
        }

        final public function upgrade() : PWP_VersionNumber
        {
            //do upgrade logic here

            //after doing the upgrade logic, return the version number of the upgrade
            //the version controller class will handle updating the local verion number settings
            return $this->get_version_number();
        }

        final public function downgrade(): void
        {
           //do downgrade logic here
           //version controller class will hande updating the local version number settings 
        }
}
