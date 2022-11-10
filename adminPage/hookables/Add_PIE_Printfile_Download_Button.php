<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PIE_GET_Queue_Request;
use PWP\includes\exceptions\Invalid_Response_Exception;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Add_PIE_Printfile_Download_Button extends Abstract_Action_Hookable
{
    private ?string $projectId;
    private ?string $editorId;

    public function __construct()
    {
        parent::__construct('pwp_download_project_files_link', 'pwp_create_printfile_download_link', 10, 2);
        $this->add_hook('woocommerce_after_order_itemmeta');
    }

    public function pwp_create_printfile_download_link(int $item_id, \WC_Order_Item $item): void
    {
        $clientDomain = get_option('pie_domain', 'https://deveditor.peleman.com');
        $this->projectId = $item->get_meta('_project_id', true);
        $projectId = $this->projectId;
        // $this->projectId = $item->get_meta('PIE Project ID');

        if (!$this->projectId) return;

        try {
            $queue = PIE_GET_Queue_Request::new(
                $clientDomain,
                get_option('pie_api_key'),
                get_option('pie_customer_id')
            )
                ->set_project_id($this->projectId)
                ->set_output_type('print')
                ->make_request()->data;

            $status = $queue[0]['status'];
            $eta = $queue[0]['renderenddate'];
        } catch (Invalid_Response_Exception $exception) {
            $status = 'error';
        }

        $this->render_printfile_message_html(
            $status,
            $projectId,
            $this->generate_file_download_url($clientDomain)
        );
    }

    private function generate_file_download_url($domain): string
    {
        $endpoint = $domain . '/editor/api/getfile.php';
        return $endpoint . '?' . http_build_query($this->generate_request_array());
    }
    private function generate_request_array(): array
    {
        $request = array(
            'projectid' => $this->projectId,
            'file' => 'printfiles',
        );

        $request = array_filter($request);
        return $request;
    }

    private function render_printfile_message_html(string $status, string $projectId, string $dl_url): void
    {
        switch ($status) {
        }
        ob_start();
        switch ($status) {
            case ('ok'):
            case ('OK'):
?>
                <strong>
                    <a href=<?= $dl_url; ?>>
                        <?= esc_html__(" Download print file."); ?>
                    </a>
                </strong>
            <?php
                break;
            case ('error'):
            case ('Error'):
            ?>
                <strong>
                    <a role="link" aria-disabled="true">
                        <?= esc_html__("Error connecting to editor server; try again later."); ?>
                    </a></strong>
            <?php
                break;
            default:
            ?>
                <strong>
                    <a role="link" aria-disabled="true">
                        <?= esc_html__("Print file processing; not currently available for download."); ?>
                    </a>
                </strong>
<?php
        }
        ob_end_flush();
    }
}
