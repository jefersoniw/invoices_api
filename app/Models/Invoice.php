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

    public function updateInvoice(Invoice $invoice, array $request = [])
    {
        try {

            $invoice->update([
                'user_id' => $request['user_id'],
                'type' => $request['type'],
                'paid' => $request['paid'],
                'value' => $request['value'],
                'payment_date' => $request['paid'] ? $request['payment_date'] : null,
            ]);

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
