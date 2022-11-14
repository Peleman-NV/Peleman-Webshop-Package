<?php

declare(strict_types=1);

namespace PWP\adminPage\hookables;

use PWP\includes\editor\PIE_GET_Queue_Request;
use PWP\includes\exceptions\Invalid_Response_Exception;
use PWP\includes\hookables\abstracts\Abstract_Action_Hookable;

class Add_PIE_Printfile_Download_Button extends Abstract_Action_Hookable
{
    private string $clientDomain;
    public function __construct()
    {
        parent::__construct('pwp_download_project_files_link', 'pwp_create_printfile_download_link', 10, 2);
        $this->add_hook('woocommerce_after_order_itemmeta');

        $this->clientDomain = get_option('pie_domain', '');
    }

    public function pwp_create_printfile_download_link(int $item_id, \WC_Order_Item $item): void
    {
        try {
            if (empty($this->clientDomain)) {
                return;
            }
            $eta = '';
            $status = '';
            $projectId = $item->get_meta('_project_id', true);
            // $projectId = $item->get_meta('PIE Project ID');


            if (!$projectId) return;

            $queue = PIE_GET_Queue_Request::new(
                $this->clientDomain,
                get_option('pie_api_key'),
                get_option('pie_customer_id')
            )
                ->set_project_id($projectId)
                ->set_output_type('print')
                ->make_request()->data;

            $status = $queue[0]['status'] ?: "unset";
            $eta = $queue[0]['renderenddate']  ?: __("unknown",);
        } catch (Invalid_Response_Exception $exception) {
            $status = 'error';
        }

        ob_start();
        $this->render_printfile_message_html(
            $status,
            $this->generate_file_download_url($projectId),
        );
        $this->render_time_to_completion_html($status, $eta);
        ob_end_flush();
    }

    private function generate_file_download_url(string $projectId): string
    {
        $endpoint = $this->clientDomain . '/editor/api/getfile.php';
        return $endpoint . '?' . http_build_query($this->generate_request_array($projectId));
    }
    private function generate_request_array(string $projectId): array
    {
        $request = array(
            'projectid' => $projectId,
            'file' => 'printfiles',
        );

        $request = array_filter($request);
        return $request;
    }

    private function render_printfile_message_html(string $status, string $dl_url): void
    {
        ob_start();
        switch ($status) {
            case ('ok'):
?>
                <span>

                    <a type="button" class="button" href=<?= $dl_url; ?>>
                        <?= esc_html__(" Download print file.", PWP_TEXT_DOMAIN); ?>
                    </a>
                </span>
            <?php
                break;
            case ('error'):
            ?>
                <span>
                    <a role="link" type="button" class="button disabled" aria-disabled="true">
                        <?= esc_html__("Error connecting to editor server; try again later.", PWP_TEXT_DOMAIN); ?>
                    </a>
                </span>
            <?php
                break;
            case ("unset"):
            default:
            ?>
                <span>
                    <a role="link" type="button" class="button disabled" aria-disabled="true">
                        <?= esc_html__("Print file processing; not currently available for download."); ?>
                    </a>
                </span>
        <?php
        }
    }

    private function render_time_to_completion_html(string $status, string $eta): void
    {
        if (empty($eta) || !($status === 'ok' && $status === 'unset')) {
            return;
        }
        ?>
        <span>
            <i><?= sprintf(esc_html__("Estimated completion time: UTC "), PWP_TEXT_DOMAIN); ?><strong><?= esc_html($eta); ?></strong></i>
        </span>
<?php
    }
}
