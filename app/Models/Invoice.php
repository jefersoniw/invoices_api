<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'paid',
        'payment_date',
        'value'
    ];

    public function createInvoice(array $request = [])
    {
        try {
            $invoice = self::create($request);
            if (!$invoice) {
                throw new Exception;
            }
            return $invoice;
        } catch (Exception $error) {
            return false;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
