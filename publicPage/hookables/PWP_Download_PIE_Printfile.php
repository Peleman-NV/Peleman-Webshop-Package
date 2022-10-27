<?php

declare(strict_types=1);

namespace PWP\publicPage\hookables;

use PWP\includes\hookables\abstracts\PWP_Abstract_Action_Hookable;

class PWP_Download_PIE_Printfile extends PWP_Abstract_Action_Hookable
{
    private ?string $projectId;
    private ?string $editorId;

    public function __construct()
    {
        parent::__construct('pwp_download_project_files_link', 'create_file_download_link', 10, 2);
        $this->add_hook('woocommerce_after_order_itemmeta');
    }

    public function create_file_download_link(int $item_id, \WC_Order_Item $item): void
    {
        $clientDomain = get_option('pie_domain', 'https://deveditor.peleman.com');
        $endpoint = $clientDomain . '/editor/api/getfile.php';
        $this->projectId = $item->get_meta('_project_id');
        $this->editorId = $item->get_meta('_editor_id');

        if (!$this->projectId || !$this->editorId) {
            return;
        }

        $url = $endpoint . '?' . http_build_query($this->generate_request_array());

?>
        <div>
            <a href=<?= $url; ?>>download print file</a>
        </div>
<?php
    }

    private function generate_request_array(): array
    {
        $request = array(
            'projectid' => $this->projectId,
            'file' => 'printfile',
        );

        $request = array_filter($request);
        return $request;
    }
}
