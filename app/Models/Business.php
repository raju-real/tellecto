<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use \OwenIt\Auditing\Auditable as Audit;
use OwenIt\Auditing\Contracts\Auditable;

class Business extends Authenticable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable, Audit;
    protected $table = "user_information";
    protected string $guard = 'user_information';
    protected $fillable = [
        'user_id',
        'company_name',
        'company_email',
        'password',
        'org_no',
        'contact_person',
        'phone',
        'website_url',
        'business_type',
        'vat_no',
        'mobile_no',
        'street',
        'city',
        'zip_code',
        'name',
        'email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function fetchProduct($businessId)
    {
        Log::info("I'm on business...");
        $products = Product::limit(1000)->get();
        foreach ($products as $product) {
            $price = $product->price;
            $profit_type = $product->profit_type;
            $profit = $product->profit;

            $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);

            $row = new BusinessProductPrice();
            $row->business_id = $businessId;
            $row->product_number = $product->product_number;
            $row->previous_price = $product->previous_price;
            $row->price = $price;
            $row->profit_type = $profit_type;
            $row->profit = $profit;
            $row->profit_amount = $calculatedValues['profit_amount'];
            $row->sale_price = $calculatedValues['sale_price'];
            $row->active_status = $product->active_status;
            $row->updated_at = now();
            $row->save();
        }
        //return "Total ".$products->count(). " Inserted successfully";
    }
    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
