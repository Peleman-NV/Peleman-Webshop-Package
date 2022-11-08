<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Enqueue_PDF_JS extends Abstract_Action_Hookable
{
    public function __construct()
    {
        parent::__construct('wp_enqueue_scripts', 'pwp_enqueue_pdf_js');
    }

    public function pwp_enqueue_pdf_js(): void
    {
        $plugin = 'Peleman-Webshop-Package';
        $type = get_post_type();

        if (!($type === 'post' || $type === 'product')) return;

        wp_enqueue_script(
            'pdfjs',
            plugins_url($plugin . '/vendor/clean-composer-packages/pdf-js/build/pdf.js'),
        );

        wp_enqueue_script(
            'pdfworkerjs',
            plugins_url($plugin . '/vendor/clean-composer-packages/pdf-js/build/pdf.js'),
        );

        wp_enqueue_script(
            'pwp-validate-pdf-upload.js',
            plugins_url($plugin . '/publicPage/js/pwp-validate-pdf-upload.js'),
            array(
                'pdfjs',
                'pdfworkerjs',
                'jquery'
            ),
            rand(0, 2000),
        );
    }
}