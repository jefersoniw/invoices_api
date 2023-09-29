<?php

namespace App\Models;

use App\Filters\InvoiceFilter;
use App\Http\Resources\V1\InvoiceResource;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

    public function filter(Request $request)
    {
        $queryFilter = (new InvoiceFilter)->filter($request);

        if (empty($queryFilter)) {
            return InvoiceResource::collection(Invoice::with('user')->get());
        }

        $data = Invoice::with('user');

        if (!empty($queryFilter['whereIn'])) {
            foreach ($queryFilter['whereIn'] as $value) {
                $data->whereIn($value[0], $value[1]);
            }
        }

        $resource = $data->where($queryFilter['where'])->get();

        return InvoiceResource::collection($resource);
    }
}
