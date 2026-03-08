<?php

namespace App\Services\Meta;

use App\Jobs\Meta\FetchMetaLeadJob;
use Illuminate\Http\Request;
use Log;

class MetaLeadWebhookService
{
    public function verifyWebhook(Request $request)
    {
        if(
            $request->get('hub_mode') === 'subscribe' &&
            $request->get('hub_verify_token') === config('services.meta.verify_token')
        ){
            return response($request->get('hub_challenge'), 200);
        }
        return response('Unauthorized', 403);
    }

    public function handleWebhook(Request $request): void
    {
        $entry = $request->input('entry.0.changes.0.value');
        Log::info('Received Meta webhook with payload: ' . json_encode($entry));

        if (!isset($entry['leadgen_id'])) {
            return;
        }
        FetchMetaLeadJob::dispatch($entry['leadgen_id']);
    }
}