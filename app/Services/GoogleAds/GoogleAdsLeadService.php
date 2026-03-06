<?php

namespace App\Services\GoogleAds;

use Google\Ads\GoogleAds\Lib\V23\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;

class GoogleAdsLeadService
{
    protected $client;

    public function __construct()
    {
        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->withClientId(config('services.google_ads.client_id'))
            ->withClientSecret(config('services.google_ads.client_secret'))
            ->withRefreshToken(config('services.google_ads.refresh_token'))
            ->build();

        $this->client = (new GoogleAdsClientBuilder())
            ->withDeveloperToken(config('services.google_ads.developer_token'))
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
    }

    public function fetchLeads(): array
    {
        $googleAdsService = $this->client->getGoogleAdsServiceClient();

        $query = "
            SELECT
              lead_form_submission_data.id,
              lead_form_submission_data.create_time,
              lead_form_submission_data.field_values,
              campaign.id,
              campaign.name,
              ad_group_ad.ad.id,
              ad_group_ad.ad.name
            FROM lead_form_submission_data
            WHERE lead_form_submission_data.create_time DURING TODAY
        ";

        // Build a request object as required by the current client library
        $request = new \Google\Ads\GoogleAds\V23\Services\SearchGoogleAdsRequest([
            'customer_id' => config('services.google_ads.customer_id'),
            'query'       => $query,
        ]);

        $response = $googleAdsService->search($request);

        $leads = [];

        foreach ($response->iterateAllElements() as $row) {
            $fields = [];

            foreach ($row->getLeadFormSubmissionData()->getFieldValues() as $field) {
                $fields[$field->getFieldName()] = $field->getStringValue();
            }

            $leads[] = [
                'platform_lead_id' => $row->getLeadFormSubmissionData()->getId(),
                'campaign_id'      => $row->getCampaign()->getId(),
                'campaign_name'    => $row->getCampaign()->getName(),
                'ad_id'            => $row->getAdGroupAd()->getAd()->getId(),
                'ad_name'          => $row->getAdGroupAd()->getAd()->getName(),
                'name'             => $fields['FULL_NAME'] ?? null,
                'email'            => $fields['EMAIL'] ?? null,
                'phone'            => $fields['PHONE_NUMBER'] ?? null,
                'custom_fields'    => $fields,
                'raw_payload'      => json_decode(json_encode($row), true),
            ];
        }

        return $leads;
    }
}