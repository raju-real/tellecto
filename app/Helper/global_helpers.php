<?php

use App\Jobs\SendEmail;
use App\Mail\SendNotificationEmail;
use App\Models\BusinessProductPrice;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

if (!function_exists('imageInfo')) {
    function imageInfo($image): array
    {
        return [
            'is_image' => isImage($image),
            'extension' => fileExtension($image),
            'width' => imageWidthHeight($image)['width'],
            'height' => imageWidthHeight($image)['height'],
            'size' => $image->getSize(),
            'mb_size' => fileSizeInMB($image->getSize())
        ];
    }
}

if (!function_exists('isImage')) {
    function isImage($file): bool
    {
        return $fileType = $file->getClientMimeType();
        $text = explode('/', $fileType)[0];
        return $text == "image";

    }
}

if (!function_exists('fileExtension')) {
    function fileExtension($file): mixed
    {
        if (isset($file)) {
            return $file->getClientOriginalExtension();
        } else {
            return "Invalid file";
        }
    }
}

if (!function_exists('imageWidthHeight')) {
    function imageWidthHeight($image): array
    {
        $imageSize = getimagesize($image);
        $width = $imageSize[0];
        $height = $imageSize[1];
        return array('width' => $width, 'height' => $height);
    }
}

if (!function_exists('fileSizeInMB')) {
    function fileSizeInMB($size): mixed
    {
        if ($size > 0) {
            return number_format($size / 1048576, 2);
        }
        return $size;
    }
}

if (!function_exists('ecommerceIcon')) {
    function ecommerceIcon(): string
    {
        return 'assets/common/images/ecommerce.png';
    }
}

if (!function_exists('userAvatar')) {
    function userAvatar(): string
    {
        return 'assets/common/images/avatar.png';
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($file, string $folderName = "partial/", $size = "", $width = "", $height = ""): string
    {
        $folderPath = "assets/files/images/" . $folderName;
        File::isDirectory($folderPath) || File::makeDirectory($folderPath, 0777, true, true);
        $imageName = time() . '-' . $file->getClientOriginalName();
        $image = Image::make($file->getRealPath());
        if ((isset($height)) && (isset($width))) {
            $image->resize($width, $height);
        }
        if (isset($size)) {
            $image->filesize($size);
        }
        $image->save($folderPath . "/" . $imageName);
        return $folderPath . "/" . $imageName;
    }
}

if (!function_exists('uploadFile')) {
    function uploadFile($file, string $path = "files/"): string
    {
        $uniqueFileName = time() . '_' . '.' . $file->getClientOriginalExtension();
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $file->move($path, $uniqueFileName);
        return $uniqueFileName;
    }
}

if (!function_exists('getVatPolicy')) {
    function getVatPolicy()
    {
        $vat_policy = [
            'vat_percentage' => 25,
            'max_vat' => 100000
        ];
        return response()->json([
            'status' => 'success',
            'data' => $vat_policy
        ]);
    }
}

if (!function_exists('getVatInfo')) {
    function getVatInfo()
    {
        $vatPolicy = getVatPolicy();
        $vat_content = json_decode($vatPolicy->content(), true)['data'];
        $vat_info = [];
        $vat_info['vat_percentage'] = $vat_content['vat_percentage'];
        $vat_info['max_vat'] = $vat_content['max_vat'];
        return (object)$vat_info;
    }
}

if (!function_exists('decimalFormat')) {
    function decimalFormat($number)
    {
        return round($number, 2);
        $decimalPart = explode('.', $number)[1]; // Get the decimal part
        $twoDigitsAfterDecimal = substr($decimalPart, 0, 2); // Get the first two digits

        echo $twoDigitsAfterDecimal;
    }
}

if (!function_exists('setBusinessProductPrice')) {
    function setBusinessProductPrice($product_id, $active_status)
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit

        foreach (User::business()->active()->get() as $business) {
            $product = Product::find($product_id);
            $price = $product->sale_price;

            $identify = [
                'product_id' => $product_id,
                'business_id' => $business->id
            ];
            $business_product = BusinessProductPrice::where($identify)->first();

            if (isset($business_product)) {
                $profit_type = $business_product->profit_type;
                $profit = $business_product->profit;
            } else {
                $profit_type = "FLAT";
                $profit = 0.00;
            }

            $calculatedValues = Product::calculateProfit($price, $profit_type, $profit);

            $data = [
                'business_id' => $business->id,
                'product_id' => $product_id,
                'product_number' => $product->product_number,
                'previous_price' => $product->sale_price,
                'price' => $price,
                'profit_type' => $profit_type,
                'profit' => $profit,
                'profit_amount' => $calculatedValues['profit_amount'],
                'sale_price' => $calculatedValues['sale_price'],
                'created_at' => now(),
                'updated_at' => now()
            ];
            BusinessProductPrice::updateOrInsert($identify, $data);
        }
    }
}

if (!function_exists('authUserId')) {
    function authUserId()
    {
        return \Illuminate\Support\Facades\Auth::id();
        //return auth()->user()->id;
    }
}

if (!function_exists('userRoleType')) {
    function userRoleType()
    {
        $user = User::with('role_info')->find(authUserId());
        return $user->role_info->type;
        //return Cache::get('auth_info')['role_type'] ?? "Unauthenticated";
    }
}

if (!function_exists('isSuper')) {
    function isSuper()
    {
        return userRoleType() === "SUPER";
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return userRoleType() === "ADMIN";
    }
}

if (!function_exists('isBusiness')) {
    function isBusiness()
    {
        return userRoleType() === "BUSINESS";
    }
}

if (!function_exists('authAgentInfo')) {
    function authAgentInfo()
    {
        $data['agent_id'] = Auth::guard('agent')->check() ? Auth::guard('agent')->user()->id : null;
        $data['business_id'] = Auth::guard('agent')->check() ? Auth::guard('agent')->user()->business_id : null;
        $data['user_type'] = Auth::guard('agent')->check() ? "Agent" : null;
        return $data;
        //return Cache::get('agent_info');
    }
}

if (!function_exists('cartKey')) {
    function cartKey()
    {
        return authAgentInfo()['agent_id'] . '_carts';
        //return Cache::get('agent_info');
    }
}

if (!function_exists('sendMail')) {
    function sendMail($mail_data)
    {
        Mail::to($mail_data['to_email'])->send(new SendNotificationEmail($mail_data));
        //SendEmail::dispatch($mail_data);
    }
}

if (!function_exists('agentCartItem')) {
    function agentCartItem()
    {
        $cartItems = Cache::get(cartKey(), []);
        $cartItemsArray = array_values($cartItems);

        $totalItems = 0;
        $totalUniqueItems = count($cartItemsArray);
        $totalPrice = 0;
        $totalWeight = 0;
        $isEmpty = true;

        // Fetch sale prices and restructure the cart item data
        foreach ($cartItemsArray as &$item) {
            $totalItems += 1;

            $condition = [
                'business_id' => authAgentInfo()['business_id'],
                'product_id' => $item['product_id'],
            ];

            $productPrice = BusinessProductPrice::with('product')->where($condition)->first();

            if ($productPrice && isset($productPrice->product)) {

                $weight = is_numeric($productPrice->product->weight) ? (float)$productPrice->product->weight : 0;
                $quantity = is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
                $price = is_numeric($productPrice->sale_price) ? (float)$productPrice->sale_price : 0;

                $itemTotalWeight = $weight * $quantity;
                $itemTotalPrice = $quantity * $price;

                $formattedItem = [
                    'id' => $item['product_id'],
                    'name' => $productPrice->product->product_name ?? "Product",
                    'slug' => $productPrice->product->slug,
                    'unit' => 'unit',
                    'image' => $productPrice->product->product_image,
                    'stock' => $productPrice->product->inventory,
                    'price' => $price,
                    'vat_type' => $productPrice->product->category->vat_type ?? 'VAT',
                    'size_id' => $item['size_id'] ?? null,
                    'color_id' => $item['color_id'] ?? null,
                    'quantity' => $quantity,
                    'item_total' => round($itemTotalPrice, 2),
                    'weight' => $weight,
                    'item_total_weight' => round($itemTotalWeight, 2),
                ];

                $item = $formattedItem;
                $totalPrice += $item['item_total'];
                $totalWeight += $item['item_total_weight'];
            } else {
                $item = [];
            }
        }


        if ($totalItems > 0) {
            $isEmpty = false;
        }


        return response()->json([
            'items' => $cartItemsArray,
            'isEmpty' => $isEmpty,
            'totalItems' => $totalItems,
            'totalUniqueItems' => $totalUniqueItems,
            'total' => round($totalPrice, 2),
            'totalWeight' => round($totalWeight, 2),
            'meta' => null,
        ]);
    }
}


if (!function_exists('orderInfo')) {
    function orderInfo($order_id)
    {
        return Order::with([
            'agent' => function ($agent) {
                $agent->select('id', 'first_name', 'last_name');
            },
            'business' => function ($query) {
                $query->select('id', 'name', 'email', 'mobile');
            },
            'order_items' => function ($item) {
                $item->with([
                    'product' => function ($product) {
                        $product->select('id', 'product_number', 'product_name');
                    }
                ]);
            }
        ])->where('id', $order_id)->first();
    }
}

if (!function_exists('getUserNameByID')) {
    function getUserNameByID($id)
    {
        return User::find($id)->name ?? '';
    }
}

if (!function_exists('userRoleTypeById')) {
    function userRoleTypeById($user_id)
    {
        $user = User::with('role_info')->find($user_id);
        return $user->role_info->type;
    }
}

if (!function_exists('userInformationById')) {
    function userInformationById($user_id)
    {
        $data = \App\Models\UserInformation::where('user_id', $user_id);
        if (userRoleTypeById($user_id) === "ADMIN") {
            $data->adminSelectedFields();
        } elseif (userRoleTypeById($user_id) === "Business") {
            $data->businessSelectedFields();
        }
        return $data->first();
    }
}

if(! function_exists('updateBusinessByRawQuery')) {
    function updateBusinessByRawQuery($product_id) {
        DB::table('business_product_prices as bpp')
                    ->whereIn('bpp.product_id', [$product_id])
                    ->join('products as p', 'bpp.product_number', '=', 'p.product_number')
                    ->update([
                        'bpp.price' => DB::raw('p.sale_price'),
                        'bpp.sale_price' => DB::raw("
                                CASE
                                    WHEN bpp.profit_type = 'PERCENTAGE' THEN p.sale_price * (1 + (bpp.profit / 100))
                                    WHEN bpp.profit_type = 'FLAT' THEN p.sale_price + bpp.profit
                                    ELSE p.sale_price
                                END
                            "),
                        'bpp.previous_price' => DB::raw("
                                CASE
                                    WHEN bpp.price != p.sale_price THEN bpp.price
                                    ELSE bpp.previous_price
                                END
                            "),
                        'bpp.updated_at' => DB::raw('NOW()')
                    ]);
    }
}
