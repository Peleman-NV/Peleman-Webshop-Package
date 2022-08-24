<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use WC_Product;

class PWP_PIE_Data extends PWP_Product_Meta
{

    public string $templateId;
    public string $designId;
    public string $colorCode;
    public string $backgroundId;

    public bool $usesImageUpload;
    public int $minImages;
    public int $maxImages;

    public const TEMPLATE_ID_KEY = 'pie_template_id';
    public const DESIGN_ID_KEY = 'pie_design_id';
    public const COLOR_CODE_KEY = 'pie_color_code';
    public const BACKGROUND_ID_KEY = 'pie_background_id';

    public const USE_IMAGE_UPLOAD = 'pie_image_upload';
    public const MAX_IMAGES = 'pie_max_images';
    public const MIN_IMAGES = 'pie_min_imaegs';

    public const MY_EDITOR = 'PIE';

    public function __construct(WC_Product $parent)
    {
        parent::__construct($parent);

        $this->templateId = $this->parent->get_meta(self::TEMPLATE_ID_KEY, true) ?? '';
        $this->designId = $this->parent->get_meta(self::DESIGN_ID_KEY, true) ?? '';
        $this->colorCode = $this->parent->get_meta(self::COLOR_CODE_KEY, true) ?? '';
        $this->backgroundId =  $this->parent->get_meta(self::BACKGROUND_ID_KEY, true) ?? '';

        $this->usesImageUpload = boolval($this->parent->get_meta(self::USE_IMAGE_UPLOAD, true));
        $this->minImages = (int)$this->parent->get_meta(self::MIN_IMAGES) ?? 0;
        //if max_images is 0, we can assume there is no limit to the amount of images.
        $this->maxImages = (int)$this->parent->get_meta(self::MAX_IMAGES) ?? 0;
    }

    public function get_template_id(): string
    {
        return $this->templateId;
    }

    public function set_template_id(string $id): void
    {
        $this->templateId = $id;
    }

    public function get_design_id(): string
    {
        return $this->designId;
    }

    public function set_design_id(string $code): void
    {
        $this->designId = $code;
    }

    public function get_color_code(): string
    {
        return $this->colorCode;
    }

    public function set_color_code(string $code): void
    {
        $this->colorCode = $code;
    }

    public function get_background_id(): string
    {
        return $this->backgroundId;
    }

    public function set_background_id(string $id): void
    {
        $this->backgroundId = $id;
    }

    public function get_variant_id(): string
    {
        return $this->variantId;
    }

    public function set_variant_id(string $variantId): void
    {
        $this->variantId = $variantId;
    }

    public function get_uses_image_upload(): bool
    {
        return $this->usesImageUpload;
    }

    public function set_uses_image_upload(bool $useUpload): void
    {
        $this->usesImageUpload = $useUpload;
    }

    public function get_max_images(): int
    {
        return $this->maxImages;
    }

    public function set_max_images(int $count): void
    {
        $count = max(0, $count);
        $this->maxImages = $count;
    }

    public function get_min_images(): int
    {
        return $this->minImages;
    }

    public function set_min_images(int $count): void
    {
        $count = max(0, $count);
        $this->minImages = $count;
    }

    public function set_as_editor(): void
    {
        $this->editorId = "PIE";
    }

    public function update_meta_data(): void
    {
        $this->parent->update_meta_data(self::TEMPLATE_ID_KEY, $this->templateId);
        $this->parent->update_meta_data(self::BACKGROUND_ID_KEY, $this->backgroundId,);
        $this->parent->update_meta_data(self::COLOR_CODE_KEY, $this->colorCode);
        $this->parent->update_meta_data(self::DESIGN_ID_KEY, $this->designId);

        $this->parent->update_meta_data(self::USE_IMAGE_UPLOAD, $this->usesImageUpload ? 1 : 0);
        $this->parent->update_meta_data(self::MIN_IMAGES, $this->minImages);
        $this->parent->update_meta_data(self::MAX_IMAGES, $this->maxImages);

        $this->parent->save_meta_data();
    }

    public function get_editor_params(): array
    {
        $params = array();
        if ($this->get_max_images() > 0)
            $params['maximages'] = $this->get_max_images();

        if ($this->get_min_images() > 0)
            $params['minimages'] = $this->get_min_images();

        return $params;
    }
}
