<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;
    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->middleware('auth:sanctum')->only(['store', 'update']);
        $this->invoice = $invoice;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // return InvoiceResource::collection(Invoice::with('user')->get());
        return (new Invoice())->filter($request);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->tokenCan('invoice-store')) {
            return $this->error('Unauthorized', 403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required:max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable',
            'value' => 'required|numeric|between:1,9999.99'
        ]);

        if ($validator->fails()) {
            return $this->error('Data Invalid', 422, $validator->errors());
        }

        $invoice = $this->invoice->createInvoice($validator->validated());

        if (!$invoice) {
            return $this->response('Invoice not Created', 400);
        }

        return $this->response('Invoice Created', 200, new InvoiceResource($invoice->load('user')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required:max:1',
            'paid' => 'required|numeric|between:0,1',
            'payment_date' => 'nullable|date_format:Y-m-d H:i:s',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Failed', 422, $validator->errors());
        }

        $invoice = $this->invoice->updateInvoice($invoice, $validator->validated());

        if (!$invoice) {
            return $this->error('Invoice not updated', 400);
        }

        return $this->response('Invoice Updated', 200, new InvoiceResource($invoice->load('user')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();

        if ($deleted) {
            return $this->response('Invoice Deleted', 200);
        }

        return $this->error('Invoice not deleted', 400);
    }
}
