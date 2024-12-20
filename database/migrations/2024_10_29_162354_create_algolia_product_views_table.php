<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement("CREATE OR REPLACE VIEW algolia_product_views AS
        SELECT
            x.id,
            x.product_number,
            x.product_name,
            x.slug,
            x.sale_price,
            x.model_name,
            x.inventory,
            x.ean_number,
            x.stock_status,
            x.weight,
            x.active_status,
            x.product_type,
            c.name AS category_name,
            x.category_id,
            x.subcategory_id,
            sc.name AS subcategory_name,
            x.brand_id,
            b.name AS brand_name,
            CONCAT(c.name, ' > ', sc.name) AS category_subcategory,
            pi2.is_thumbnail,
            (CASE WHEN pi2.is_thumbnail = 1 THEN pi2.image ELSE NULL END) AS image
        FROM
            products x
            INNER JOIN categories c ON c.id = x.category_id
            INNER JOIN sub_categories sc ON sc.id = x.subcategory_id
            INNER JOIN brands b ON b.id = x.brand_id
            LEFT JOIN product_images pi2 ON pi2.product_id = x.id
        WHERE
            x.active_status = 1;
    ");
    }


    /**
     * Reverse the migrations.
     */
    public
    function down(): void
    {
        Schema::dropIfExists('algolia_product_views');
    }
};
