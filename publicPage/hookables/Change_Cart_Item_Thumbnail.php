<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use DOMDocument;
use DOMXPath;
use PWP\includes\editor\Keys;
use PWP\includes\hookables\abstracts\Abstract_Filter_Hookable;

class Change_Cart_Item_Thumbnail extends Abstract_Filter_Hookable
{
    private ?DOMDocument $dom;
    public function __construct()
    {
        parent::__construct('woocommerce_cart_item_thumbnail', 'pwp_override_cart_item_thumbnail', 15, 3);
        $this->dom = null;
    }

    public function pwp_override_cart_item_thumbnail(string $image, array $cart_item, $cart_item_key): string
    {
        if (!isset($cart_item['_project_id'])) return $image;
        $projectId = $cart_item['_project_id'];
        // $projectId = '22110222b2c-4a9b';
        $product = $cart_item['data'];

        if (boolval($product->get_meta(Keys::OVERRIDE_CART_THUMBNAIL))) {

            if (!$this->dom) {
                $this->dom = new DOMDocument();
            }

            $this->dom->loadHTML($image);
            $x = new DOMXPath($this->dom);

            foreach ($x->query("//img") as $node) {

                $classes = $node->getAttribute("class");
                $node->setAttribute("class", "{$classes} pwp-fetch-thumb");
                $node->setAttribute("projid", $projectId);
            }
            $image = $this->dom->saveHTML();
            return $image;

            $url = $this->generate_thumbnail_request_url($projectId);

            try {
                //TODO:
                //once the api returns a proper response when it cannot find a thumbnail,
                //we can remove the error control operator
                //right now we use it to simply suppress warnings in the error log
                //avoiding clutter
                //important to note that the error control operator doesn't ignore errors, it simply stops them from being logged.
                //as such, the try/catch block still works as intended.

                @$img = base64_encode(file_get_contents($url, true));

                if (!$img || $img === false)  return $image;

                $image = sprintf(
                    '<img src="%s" alt="%s">',
                    // '<img width="450" height="450" src="%s" class="woocommerce-placeholder wp-post-image" alt="%s" decoding="async" loading="lazy" sizes="(max-width: 450px) 100vw, 450px" />',
                    'data:image/jpeg;base64, ' . $img,
                    'project thumbnail'
                );
            } catch (\Throwable $error) {
                // error_log((string)$error);
                return $image;
            }
        }
        return $image;
    }

    private function generate_thumbnail_request_url(string $projectId): string
    {
        $domain = get_option('pie_domain');

        $query = array(
            'projectid' => $projectId,
            'customerapikey' => get_option('pie_api_key'),
        );

        return $domain . "/editor/api/getprojectthumbnailAPI.php" . '?' . http_build_query($query);
    }
}
