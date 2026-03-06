<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleAds\GoogleAdsLeadService;
use App\Services\Leads\LeadWriterService;

class FetchGoogleAdsLeads extends Command
{
    protected $signature = 'leads:fetch-google';
    protected $description = 'Fetch Google Ads lead form submissions';

    public function handle(
        GoogleAdsLeadService $googleService,
        LeadWriterService $writer
    ) {
        $leads = $googleService->fetchLeads();

        foreach ($leads as $lead) {
            $writer->saveGoogleLead($lead);
        }

        $this->info('Google Ads leads synced: ' . count($leads));
    }
}