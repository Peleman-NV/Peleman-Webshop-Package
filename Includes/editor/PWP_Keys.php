<?php

declare(strict_types=1);

namespace PWP\includes\editor;

/**
 * Static utility class; contains all the individual constant keys for product meta data.
 */
class PWP_Keys
{
    public const USE_PDF_CONTENT_KEY = 'pdf_upload_required';
    public const PDF_HEIGHT_KEY = 'pdf_height_mm';
    public const PDF_WIDTH_KEY = 'pdf_width_mm';
    public const PDF_MAX_PAGES_KEY = 'pdf_max_pages';
    public const PDF_MIN_PAGES_KEY = 'pdf_min_pages';
    public const PDF_PRICE_PER_PAGE_KEY = 'price_per_page';

    public const EDITOR_ID_KEY = 'pwp_editor_id';
    public const CUSTOM_LABEL_KEY = 'custom_variation_add_to_cart_label';

    public const PIE_TEMPLATE_ID_KEY = 'pie_template_id';
    public const DESIGN_ID_KEY = 'pie_design_project_id';
    public const COLOR_CODE_KEY = 'pie_color_code';
    public const BACKGROUND_ID_KEY = 'pie_background_id';

    public const USE_IMAGE_UPLOAD_KEY = 'pie_image_upload';
    public const MAX_IMAGES_KEY = 'pie_max_images';
    public const MIN_IMAGES_KEY = 'pie_min_images';

    public const NUM_PAGES_KEY = 'pie_num_pages';
    public const AUTOFILL_KEY = 'pie_autofill';
    public const FORMAT_ID_KEY = 'pie_format_id';

    public const IMAXEL_TEMPLATE_ID_KEY = 'imaxel_template_id';
    public const IMAXEL_VARIANT_ID_KEY = 'imaxel_variant_id';

    public const EDITOR_INSTRUCTIONS_KEY = 'pie_editor_instructions';
    public const INSTRUCTION_PREFIX = 'pie_instruct_';
}
