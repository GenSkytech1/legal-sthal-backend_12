<?php

namespace App\Http\Controllers\Api\Lead;

use App\Http\Controllers\Controller;
use App\Services\Meta\MetaLeadWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class MetaWebhookController extends Controller
{
    protected MetaLeadWebhookService $service;

    public function __construct(MetaLeadWebhookService $service)
    {
        $this->service = $service;
    }

    /**
     * Verify Meta webhook subscription
     */
    public function verify(Request $request)
    {
        return $this->service->verifyWebhook($request);
    }

    /**
     * Handle incoming webhook payload
     */
    public function handle(Request $request)
    {
        $this->service->handleWebhook($request);
        Log::info('Meta webhook received and processed successfully.');
        return response()->json(['success' => true]);
    }
}