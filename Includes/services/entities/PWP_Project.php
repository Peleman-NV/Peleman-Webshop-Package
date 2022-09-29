<?php

declare(strict_types=1);

namespace PWP\includes\services\entities;

use DateTime;
use JsonSerializable;
use Serializable;
use wpdb;

class PWP_Project implements PWP_I_Entity, JsonSerializable
{
    private int $id;
    private int $user_id;
    private string $project_id;
    private int $product_id;
    private string $file_name;
    private int $pages;
    private float $price_vat_excl;
    private string $created;
    private string $updated;
    private string $ordered;

    private function __construct(int $userId, int $productId, string $fileName, int $pages = 0, float $price_vat_excl = 0.0)
    {
        $this->id = -1;
        $this->user_id = $userId;
        $this->project_id = '';
        $this->product_id = $productId;
        $this->file_name = $fileName;
        $this->pages = $pages;
        $this->price_vat_excl = 0.0;
        $this->created = '';
        $this->updated = '';
        $this->ordered = '';
    }

    public static function get_by_id(int $id): ?self
    {
        global $wpdb;
        if ($wpdb instanceof wpdb) {
            $table_name = $wpdb->prefix . PWP_PROJECTS_TABLE;

            $sql = "SELECT * from {$table_name} where id = %d";
            $row = $wpdb->get_row($wpdb->prepare($sql, $id));

            if (!$row) return null;

            $product = new self(
                $row->user_id,
                $row->product_id,
                $row->file_name,
                $row->pages,
                $row->price_vat_excl,
            );
            $product->id = $row->id;
            $product->project_id = $row->project_id;
            $product->created = $row->created;
            $product->updated = $row->updated;
            $product->ordered = $row->ordered;
            return $product;
        }
        return null;
    }

    public static function create_new(int $userId, int $productId, string $fileName, int $pages = 0, float $price): self
    {
        return new self($userId, $productId, $fileName, $pages, $price);
    }

    #region setters
    public function set_ordered(): void
    {
        $this->ordered = wp_date('Y-m-d H:i:s', time());
    }

    public function set_user_id(int $id): void
    {
        $this->id = $id;
    }

    public function set_project_id(string $id): void
    {
        $this->project_id = $id;
    }

    public function set_product_id(int $id): void
    {
        $this->product_id = $id;
    }

    /**
     * Set file name of the project/pdf file.
     *
     * @param string $file
     * @return void
     */
    public function set_file_name(string $file): void
    {
        $this->file_name = $file;
    }

    /**
     * Set amount of pages in the project. Two pages constitute a sheet
     *
     * @param integer $pages
     * @return void
     */
    public function set_pages(int $pages): void
    {
        $this->pages = $pages;
    }

    /**
     * Set project price without VAT 
     *
     * @param float $price
     * @return void
     */
    public function set_price(float $price): void
    {
        $this->price_vat_excl = $price;
    }

    #endregion
    #region getters
    public function get_id(): int
    {
        return $this->id;
    }

    public function get_created(): DateTime
    {
        return new DateTime($this->created);
    }

    public function get_updated(): DateTime
    {
        return new DateTime($this->updated);
    }

    public function get_ordered(): ?DateTime
    {
        return $this->ordered ? new DateTime($this->ordered) : null;
    }

    public function was_ordered(): bool
    {
        return isset($this->ordered);
    }

    public function get_file_location(): string
    {
        return PWP_UPLOAD_DIR . "/{$this->id}/{$this->file_name}";
    }

    public function get_file_name(): string
    {
        return $this->file_name;
    }

    public function get_user_id(): int
    {
        return $this->user_id;
    }

    public function get_pages(): int
    {
        return $this->pages;
    }

    public function get_price_vat_excl(): float
    {
        return $this->price_vat_excl;
    }

    public function get_project_id(): string
    {
        return $this->project_id ?: null;
    }

    public function get_product_id(): int
    {
        return $this->product_id;
    }
    #endregion

    public function persist(): void
    {
        if (0 <= $this->id) {
            //if id is not 0, this project already exists in the database
            $this->update();
            return;
        }
        $this->save();
    }

    private function save(): void
    {
        global $wpdb;
        // if ($wpdb instanceof \wpdb) {
        $result = $wpdb->insert(
            $wpdb->prefix . PWP_PROJECTS_TABLE,
            $this->db_data_array(),
            $this->db_data_format_array(),
        );
        if (!$result) {
            throw new \Exception("Encountered problem when trying to insert project into database. Check logs for more information");
        }
        $this->id = $wpdb->insert_id;
    }

    private function update(): void
    {
        global $wpdb;
        // if ($wpdb instanceof \wpdb) {
        $result = $wpdb->update(
            $wpdb->prefix . PWP_PROJECTS_TABLE,
            $this->db_data_array(),
            array('id' => $this->id),
            $this->db_data_format_array(),
            array('%d'),
        );
        if (!$result) {
            throw new \Exception("Encountered problem when trying to update project in database. Check logs for more information.");
        }
    }

    private function db_data_array(): array
    {
        return array(
            'user_id'           => $this->user_id,
            'project_id'        => $this->project_id,
            'product_id'        => $this->product_id,
            'file_name'         => $this->file_name,
            'pages'             => $this->pages,
            'price_vat_excl'    => $this->price_vat_excl,
            'ordered'           => $this->ordered,
        );
    }

    private function db_data_format_array(): array
    {
        return array('%d', '%s', '%d', '%s', '%d', '%f', '%s');
    }

    public function data()
    {
        return array(
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'project_id'        => $this->project_id,
            'product_id'        => $this->product_id,
            'file_name'         => $this->file_name,
            'pages'             => $this->pages,
            'price_vat_excl'    => $this->price_vat_excl,
            'created'           => $this->created,
            'updated'           => $this->ordered,
            'ordered'           => $this->orderered,
        );
    }

    public function jsonSerialize(): mixed
    {
        return $this->data();
    }
}
