<?php

declare(strict_types=1);

namespace PWP\includes\editor;

use PWP\includes\services\ImaxelService;

class PWP_Imaxel_Service_Wrapper
{
    public function add_to_cart()
    {
        $variant_id = sanitize_text_field($_GET['variant']);
		$content_file_id = sanitize_text_field($_GET['content']);
		$user_id = get_current_user_id();

		// if no variant Id present, return
		if ($variant_id === null || empty($variant_id)) {
			$response['status'] = "success";
			wp_send_json_success($response);
		}

		$response['isCustomizable'] = !empty(wc_get_product($variant_id)->get_meta('template_id')) ? 'yes' : 'no';

		// if not customizable, no need to call Imaxel
		if ($response['isCustomizable'] === 'no') {
			$projectDetails = [
				'user_id' => $user_id,
				'product_id' => $variant_id,
				'content_filename' => $content_file_id,
			];
			$this->insertOrUpdateProject($projectDetails);
			$response['status'] = "success";
			wp_send_json_success($response);
		}

		$imaxel_response = $this->getImaxelData($variant_id, $content_file_id);
		if ($imaxel_response['status'] == "error") {
			$response['status'] = 'error';
			$response['information'] = $imaxel_response['information'];
			$response['message'] = __('Something went wrong.  Please refresh the page and try again.', PPI_TEXT_DOMAIN);
			wp_send_json_error($response);
		}

		$project_id = $imaxel_response['project_id'];
		$projectDetails = [
			'user_id' => $user_id,
			'product_id' => $variant_id,
			'project_id' => $project_id,
			'content_filename' => $content_file_id,
		];
		$this->insertOrUpdateProject($projectDetails);

		$response['url'] = $imaxel_response['url'];
		$response['project-id'] = $project_id;

		$response['status'] = "success";
		$response['variant'] = $variant_id;

		wp_send_json_success($response);
    }

    private function insertOrUpdateProject($paramArray)
	{
		global $wpdb;
		$table_name = PPI_USER_PROJECTS_TABLE;

		// Project ID / Content file ID exists?  Only search if not empty
		$query = "SELECT id FROM {$table_name} WHERE ";

		if (!empty($paramArray['project_id'])) {
			$whereClause[] = "project_id = '{$paramArray['project_id']}' ";
		}
		if (!empty($paramArray['content_filename'])) {
			$whereClause[] = "content_filename = '{$paramArray['content_filename']}' ";
		}
		$result = $wpdb->get_row($query . implode(' or ', $whereClause) . ';');

		$query = [];
		if (is_null($result)) {
			$date = new \DateTime;
			$defaultName = 'Project (' . $date->format('d/m/Y') . ')';
			$paramArray['name'] = $defaultName;
			$query = $paramArray;
			$wpdb->insert($table_name, $query);
		} else {
			$existingRowId = $result->id;
			$wpdb->update($table_name, $paramArray, ['id' => $existingRowId]);
		}
	}

    private function getImaxelData($variant_id, $content_file_id = NULL)
	{
		if (!is_null($_POST['variant_id'])) {
			$variant_id = sanitize_text_field($_POST['variant_id']);
		}

		$template_id =  wc_get_product($variant_id)->get_meta('template_id');
		$variant_code = wc_get_product($variant_id)->get_meta('variant_code');

		if (empty($template_id) || empty($variant_code)) {
			return array(
				'status' => 'success',
				'url' => 'no_editor_url'
			);
		}

		$imaxel = new ImaxelService();
		$create_project_response = $imaxel->create_project($template_id, $variant_code);

		if ($create_project_response['response']['code'] == 200) {
			$status = 'success';
		} else {
			$status = 'error';
			$information = $create_project_response['body'];
		}
		$backUrl =  explode("?", get_permalink($variant_id), 2)[0];
		$encoded_response = json_decode($create_project_response['body']);
		$project_id = $encoded_response->id;
		$lang = isset($_COOKIE['wp-wpml_current_language']) && $_COOKIE['wp-wpml_current_language'] ? sanitize_text_field($_COOKIE['wp-wpml_current_language']) : 'en';
		$defaultLanguageCode = apply_filters('wpml_default_language', NULL);
		$urlLanguageSuffix = $defaultLanguageCode !== $lang ? '/' . $lang : '';
		$siteUrl = get_site_url() . $urlLanguageSuffix;
		$editorUrl = $imaxel->get_editor_url($project_id, $backUrl, $lang, $siteUrl . '/?add-to-cart=' . $variant_id . '&project=' . $project_id . '&content_file_id=' . $content_file_id ?? '');

		return array(
			'status' => $status,
			'project_id' => $project_id,
			'information' => $information ?? '',
			'url' => $editorUrl
		);
	}
}
