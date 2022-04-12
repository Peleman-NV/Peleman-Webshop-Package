<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use DateTime;

class PWP_Project
{
    //id of the project itself
    private int $projectId;
    //name of the project, for user convenience
    private string $name;
    //id of the user who owns this project
    private int $userId;
    //id of the template used for this project
    private int $TemplateId;
    //id of the product to which this project applies
    private int $productId;

    //if applicable, the amount of pages in the project.
    private int $pages;

    //date project was created
    private DateTime $created;
    //date project was last modified
    private DateTime $modified;

    public function __construct()
    {
    }
}
