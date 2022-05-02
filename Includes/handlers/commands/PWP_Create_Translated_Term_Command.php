<?php

declare(strict_types=1);

namespace PWP\includes\handlers\commands;

use PWP\includes\utilities\response\PWP_Response;
use PWP\includes\utilities\response\PWP_I_Response;
use PWP\includes\exceptions\PWP_Invalid_Input_Exception;

final class PWP_Create_Translated_Term_Command extends PWP_Create_Term_Command
{
    public function do_action(): PWP_I_Response
    {
        if (!$this->service->get_item_by_slug($this->translationData->get_english_slug())) {
            throw new PWP_Invalid_Input_Exception("invalid English slug {$this->translationData->get_english_slug()} has been passed.");
        }

        $term = $this->create_term();

        $Englishparent =  $this->service->get_item_by_slug($this->translationData->get_english_slug());
        $this->service->set_translation_data($term, $Englishparent, $this->translationData->get_language_code());

        return new PWP_Response("successfully created category {$term->slug}", (array)$term->data);
    }

    public function undo_action(): PWP_I_Response
    {
        return new PWP_Response("not implemented");
    }
}
