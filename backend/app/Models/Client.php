<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_email',
        'phone',
        'address',
        'status',
    ];

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
}