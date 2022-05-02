<?php

declare(strict_types=1);

namespace PWP\includes\handlers;

use PWP\includes\handlers\services\PWP_Product_Category_SVC;
use PWP\includes\handlers\services\PWP_Term_SVC;

/**
 * Undocumented class
 * 
 * @deprecated version
 */
class PWP_Category_Handler extends PWP_Term_Handler
{
    public function __construct()
    {
        parent::__construct(new PWP_Term_SVC('b','d','c'));
    }
}
