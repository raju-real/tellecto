<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function scopeAdminSelectedFields($query) {
        $query->select('id','user_id','employee_id','joining_date');
    }

    public function scopeBusinessSelectedFields($query) {
        $query->select('id','user_id','company_name','org_no','vat_no','contact_person','business_type','website_url','phone','company_email','logo','street','city','zip_code');
    }
}
