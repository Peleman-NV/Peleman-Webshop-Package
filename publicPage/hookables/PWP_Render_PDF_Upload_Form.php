<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;
use PWP\templates\PWP_Template;

class PWP_Render_PDF_Upload_Form extends PWP_Abstract_Action_Hookable

{
    private PWP_Template $template;
    public function __construct(PWP_Template $template)
    {
        parent::__construct('pwp_render_pdf_upload_form', 'render_add_pdf_upload_form', 10, 1);
        $this->template = $template;
    }

    public function render_add_pdf_upload_form(): void
    {
        $params = array(
            'button_label' => esc_html__('Click here to upload your PDF file', PWP_TEXT_DOMAIN),
            'max_file_size' => '100 MB',
        );

        echo $this->template->render('PWP_File_Upload_Form_Template.php', $params);
    }
}
