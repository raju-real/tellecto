<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('track-order', function () {
    $response = \App\Models\Order::trackOrder(52);
    dd($response);
});

Route::get('check-mail-template', function () {
    $order = \App\Models\Order::find(68);
    //\App\Models\Order::saveBusinessInvoice($order->id);
    $user = \App\Models\User::find(3);
    $row = \App\Models\Agent::find(5);

//    $mail_data = [
//        'activity_type' => 'registration_request_placed_to_business',
//        'view_file' => 'mail.layouts.app',
//        'to_email' => 'mkraju.eatl@gmail.com',
//        'to_name' => $order->business->name,
//        'subject' => 'Order #' . $order->tellecto_order_no . ' Cancelled by TELLECTO',
//        'order' => $order,
//        'attachment_path' => "assets/files/invoice/"."business_invoice_" . $order->tellecto_order_no . ".pdf",
//        'attachment_name' => 'order_'.$order->tellecto_order_no.'_invoice'.'.pdf',
//        'attachment_mime' => 'application/pdf',
//    ];

    $mail_data = [
            'activity_type' => 'public_contact_message_to_admin',
            'view_file' => 'mail.layouts.app',
            'to_email' => 'info@tellecto.se',
            'to_name' => "Admin",
            'subject' => $user->name . '(' . $user->phone . ') wants to contact with you.',
            'message' => $user->message,
            'name' => $user->name ?? '',
            'email' => $user->email ?? '',
            'phone' => $user->phone ?? ''
        ];
    //return $mail_data;
    //sendMail($mail_data);
    return view('mail.order.layouts', compact('mail_data'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
