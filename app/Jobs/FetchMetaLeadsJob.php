<?php

namespace App\Jobs;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchMetaLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
         
        Log::info('🚀 Meta Leads Job Started');

        $token = env('META_PAGE_TOKEN');

        if (!$token) {
            Log::error('❌ META_PAGE_TOKEN missing in .env');
            return;
        }

        $formIds = [
            '1926949631242184',
            '873297168788336'
        ];

        foreach ($formIds as $formId) {

            Log::info("📡 Fetching leads for Form ID: {$formId}");

            $url = "https://graph.facebook.com/v19.0/{$formId}/leads";

            $response = Http::get($url, [
                'access_token' => $token
            ]);

            if (!$response->successful()) {
                Log::error('❌ Meta API Error', [
                    'form_id' => $formId,
                    'response' => $response->json()
                ]);
                continue;
            }

            $data = $response->json();
            $leads = $data['data'] ?? [];

            Log::info("✅ Leads fetched", [
                'form_id' => $formId,
                'count' => count($leads)
            ]);

            foreach ($leads as $lead) {

                Log::info("🔍 Processing Lead", [
                    'lead_id' => $lead['id']
                ]);

                // Avoid duplicate
                if (Lead::where('platform_lead_id', $lead['id'])->exists()) {
                    Log::warning("⚠️ Duplicate Lead Skipped", [
                        'lead_id' => $lead['id']
                    ]);
                    continue;
                }

                $fields = $this->mapFields($lead['field_data']);

                Lead::create([
                    'source' => 'facebook',
                    'platform_lead_id' => $lead['id'],
                    'form_id' => $formId,

                    'name' => $fields['full_name'] ?? null,
                    'email' => $fields['email'] ?? null,
                    'phone' => $fields['phone'] ?? null,

                    'status' => 'new',
                    'raw_payload' => $lead
                ]);

                Log::info("✅ Lead Stored Successfully", [
                    'lead_id' => $lead['id']
                ]);
            }
        }

        Log::info('🏁 Meta Leads Job Finished');
    }

    private function mapFields($fieldData)
    {
        $mapped = [];

        foreach ($fieldData as $field) {
            $mapped[$field['name']] = $field['values'][0] ?? null;
        }

        return $mapped;
    }
}