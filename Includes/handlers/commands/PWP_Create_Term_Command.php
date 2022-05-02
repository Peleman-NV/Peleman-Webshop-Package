<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\wrappers\PWP_Term_Data;
use PWP\includes\handlers\services\PWP_Term_SVC;
use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Resource_Already_Exists_Exception;

class PWP_Create_Term_Command implements PWP_I_Command
{
    private PWP_Term_SVC $service;
    private string $slug;
    private PWP_Term_Data $creationData;

    public function __construct(PWP_Term_SVC $service, string $slug, PWP_Term_Data $data)
    {
        $this->service = $service;
        $this->slug = $slug;
        $this->creationData = $data;
    }

    public function do_action(): PWP_I_Response
    {
        if ($this->service->get_item_by_slug($this->slug)) {
            throw new PWP_Resource_Already_Exists_Exception("{$this->service->get_beauty_name()} with the slug {$this->slug} already exists. Slugs should be unique to avoid confusion.");
        }
        $term = $this->create_term();
        return new PWP_Response("successfully created category {$term->slug}", (array)$term->data);
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }

    final protected function create_term(): \WP_Term
    {
        $parentId = $this->find_parent_id($this->creationData->get_parent_id(), $this->creationData->get_parent_slug());

        return $this->service->create_item(
            $this->creationData->get_name(),
            $this->creationData->get_slug(),
            $this->creationData->get_description(),
            $parentId
        );
    }

    protected function find_parent_id(?int $id, ?string $slug): int
    {
        if (!empty($id)) {
            $parent = $this->service->get_item_by_id($id);
            if (!empty($parent)) {
                return $parent->term_id;
            }
        }

        if (!empty($slug)) {
            $parent = $this->service->get_item_by_slug($slug);
            if (!empty($parent)) {
                return $parent->term_id;
            }
        }

        return 0;
    }

    final protected function does_slug_exist(string $slug): bool
    {
        return !is_null($this->service->get_item_by_slug($slug));
    }
}
