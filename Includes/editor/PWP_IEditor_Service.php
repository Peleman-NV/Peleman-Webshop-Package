<?php

declare(strict_types=1);

namespace PWP\includes\editor;

interface PWP_IEditor_Service
{
    public function add_to_cart();
    public function create_new_project();
    public function retrieve_project();
}