<?php

namespace App\Services\Leads;

use App\Models\Lead;

class LeadWriterService
{
    public function saveGoogleLead(array $lead): void
    {
        Lead::updateOrCreate(
            [
                'source' => 'google_ads',
                'platform_lead_id' => $lead['platform_lead_id'],
            ],
            [
                'campaign_id'   => $lead['campaign_id'],
                'campaign_name' => $lead['campaign_name'],
                'ad_id'         => $lead['ad_id'],
                'ad_name'       => $lead['ad_name'],
                'name'          => $lead['name'],
                'email'         => $lead['email'],
                'phone'         => $lead['phone'],
                'custom_fields' => $lead['custom_fields'],
                'raw_payload'   => $lead['raw_payload'],
            ]
        );
    }
}