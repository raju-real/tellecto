<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    protected $table = "products";

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($item) {
            // Check if the status is being changed to active
            if ($item->isDirty('active_status') || $item->isDirty('source_active_status')) {
                if ($item->active_status == 1 && $item->source_active_status == 1 && $item->sale_price > 0) {
                    $item->searchable(); // Add to Algolia
                } else {
                    // Remove from Algolia otherwise
                    $item->unsearchable();
                }


            }
        });
    }

    protected $guarded = [];
    protected $fillable = [
        'product_number', 'price', 'profit_type', 'profit', 'tellecto_last_price',
        'profit_amount', 'sale_price', 'inventory', 'stock_status',
        'active_status', 'previous_price', 'filtered_as', 'updated_at'
    ];
    protected $appends = ['product_image'];

    /**
     * Get the query used to retrieve models when importing via Scout.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function makeAllSearchableUsing($query)
    {
        // Add condition to only import active products
        return $query->publicVisible();
    }

    public function getProductImageAttribute()
    {
        $image = ProductImage::where('product_id', $this->id)->where('is_thumbnail', 1)->first();
        if (isset($image)) {
            return $image['image'];
        } else {
            $image = ProductImage::where('product_id', $this->id)->oldest('id')->first();
            if (isset($image)) {
                return $image['image'];
            } else {
                return null;
            }
        }
    }

    public function price_info()
    {
        return $this->hasOne(BusinessProductPrice::class, 'product_id', 'id')->where('business_id', authAgentInfo()['business_id']);
    }

    public function business_price()
    {
        return $this->hasOne(BusinessProductPrice::class, 'product_id', 'id')->where('business_id', Auth::id());
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class, 'product_id', 'id');
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id', 'id');
    }

    public function types()
    {
        return $this->hasMany(ProductType::class, 'product_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('active_status', 1)->whereNotNull('product_name');
    }

    public function scopePublicVisible($query)
    {
        return $query->active()->where('source_active_status', 1)->where('sale_price', '>', 0);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('filtered_as', 1)->whereNotNull('product_name');
    }

    public function scopeSelectedFields($query)
    {
        // Columns to exclude
        $excludedFields = ['deleted_at', 'created_by', 'updated_by', 'created_at'];
        // Get all columns from the 'products' table
        $allColumns = Schema::getColumnListing('products');
        $selectedFields = array_diff($allColumns, $excludedFields);
        return $query->select($selectedFields);
    }

    public function scopePublicSelectedFields($query)
    {
        // Columns to exclude
        $excludedFields = ['dcs_last_price', 'tellecto_last_price', 'price', 'profit_type', 'profit', 'profit_amount', 'sale_price', 'deleted_at', 'created_by', 'updated_by', 'created_at', 'updated_at'];
        // Get all columns from the 'products' table
        $allColumns = Schema::getColumnListing('products');
        $selectedFields = array_diff($allColumns, $excludedFields);
        return $query->select($selectedFields);
    }

    public function scopeSelectedFieldsForBusiness($query)
    {
        return $query->select('id', 'product_number', 'category_id', 'subcategory_id', 'product_name', 'slug', 'sale_price', 'model_name', 'brand_id');
    }

    public function scopeAgentSelectedFields($query)
    {
        return $query->select('id', 'product_number', 'category_id', 'brand_id', 'subcategory_id', 'product_name', 'slug', 'model_name');
    }

    /**
     * Calculate the sale price and profit amount based on the profit type.
     *
     * @param float $price
     * @param string $profitType
     * @param float $profit
     * @return array
     */
    public static function calculateProfit($price, $profitType, $profit)
    {
        // $salePrice = $price;
        $profitAmount = 0.00;

        if ($profitType === 'FLAT') {
            $salePrice = $price + $profit;
            $profitAmount = $profit;
        } elseif ($profitType === 'PERCENTAGE') {
            $profitAmount = ($price * $profit) / 100;
            $salePrice = $price + $profitAmount;
        }

        return [
            'sale_price' => round($salePrice),
            'profit_amount' => round($profitAmount)
        ];
    }

    public static function calculateProfitForUpdateProduct($source_purchase_price, $sale_price)
    {
        return $sale_price == 0 ? 0 : ($sale_price - $source_purchase_price);
    }

    public static function getCategoryID($category_name)
    {
        if (empty($category_name)) {
            return Category::whereName('Uncategorized')->first()->id ?? Null;
        }
        if (Category::whereName($category_name)->exists()) {
            return Category::whereName($category_name)->first()->id;
        } else {
            $category = new Category();
            $category->name = $category_name;
            $category->slug = Str::slug($category_name);
            $category->vat_type = "VAT";
            $category->save();
            return $category->id;
        }
    }

    public static function getSubCategoryID($category_name, $subcategory_name)
    {
        if (empty($subcategory_name)) {
            return SubCategory::whereName('Unsubcategorized')->first()->id ?? Null;
        }
        if (SubCategory::whereName($subcategory_name)->exists()) {
            return SubCategory::whereName($subcategory_name)->first()->id;
        } else {
            $subcategory = new SubCategory();
            $subcategory->category_id = Product::getCategoryID($category_name) == Null ? 0 : Product::getCategoryID($category_name);
            $subcategory->name = $subcategory_name;
            $subcategory->slug = Str::slug($subcategory_name);
            $subcategory->save();
            return $subcategory->id;
        }
    }

    public static function getBrandID($brand_name)
    {
        if (empty($brand_name)) {
            return Brand::whereName('Non brand')->first()->id ?? Null;
        }
        if (Brand::whereName($brand_name)->exists()) {
            return Brand::whereName($brand_name)->first()->id;
        } else {
            $brand = new Brand();
            $brand->name = $brand_name;
            $brand->slug = Str::slug($brand_name);
            $brand->save();
            return $brand->id;
        }
    }

    public static function productNumberByID($id)
    {
        return Product::where('id', $id)->first()->product_number ?? '';
    }

    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class)->where('is_thumbnail', true);
    }

    public function getImageAttribute($value)
    {
        return $value == null ? null : url('/') . '/' . $value;
    }

    public function searchableAs(): string
    {
        return 'product_index';
    }


    public function toSearchableArray()
    {
        // Check if the item is active
//        if ($this->active_status != 1) {
//            return []; // Return an empty array to prevent indexing
//        }
        return [
            'id' => $this->id,
            'product_number' => $this->product_number,
            'product_name' => $this->product_name,
            'slug' => $this->slug,
            'model_name' => $this->model_name,
            'inventory' => $this->inventory,
            'ean_number' => $this->ean_number,
            'stock_status' => $this->stock_status,
            'weight' => $this->weight,
            'categories' => [
                'lvl0' => $this->category->name ?? null,  // Main category (lvl0)
                'lvl1' => $this->subcategory->name ?? null,  // Subcategory (lvl1)
            ],
            'brand_name' => $this->brand->name ?? null,
            'active_status' => $this->active_status,
            'source_active_status' => $this->source_active_status,
            'thumbnail' => $this->images()->where('is_thumbnail', 1)->value('image'),

        ];
    }
}
