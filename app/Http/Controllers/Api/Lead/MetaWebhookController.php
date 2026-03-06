<?php

namespace App\Http\Controllers\Api\Lead;

use App\Http\Controllers\Controller;
use App\Services\Meta\MetaLeadWebhookService;
use Illuminate\Http\Request;

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

        return response()->json(['success' => true]);
    }
}