<?php

namespace App\Console\Commands;

use App\Models\Counselor;
use App\Models\User;
use Google\Client;
use Google_Service_Calendar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateGoogleCalendarToken extends Command
{
    protected $signature = 'google-calendar:oauth-token {--force : Overwrite existing token} {--counselor-id=} {--email=}';
    protected $description = 'Generate OAuth token for Google Calendar';

    public function handle(): int
    {
        $credentialsPath = config('google-calendar.auth_profiles.oauth.credentials_json');
        $tokenPath = config('google-calendar.auth_profiles.oauth.token_json');

        $counselor = null;
        if ($this->option('counselor-id')) {
            $counselor = Counselor::find($this->option('counselor-id'));
        }

        if (!$counselor && $this->option('email')) {
            $email = $this->option('email');
            $counselor = Counselor::where('google_calendar_id', $email)->first();
            if (!$counselor) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $counselor = Counselor::where('user_id', $user->id)->first();
                }
            }
        }

        if (($this->option('counselor-id') || $this->option('email')) && !$counselor) {
            $this->error('Counselor not found for the provided option.');
            return self::FAILURE;
        }

        if ($counselor) {
            $tokenPath = storage_path('app/google-calendar/tokens/' . $counselor->user_id . '.json');
        }

        if (!File::exists($credentialsPath)) {
            $this->error("OAuth credentials file not found: {$credentialsPath}");
            return self::FAILURE;
        }

        if (File::exists($tokenPath) && !$this->option('force')) {
            $this->warn("Token already exists: {$tokenPath}");
            $this->line('Run with --force to overwrite.');
            return self::SUCCESS;
        }

        $client = new Client();
        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        $client->setScopes([Google_Service_Calendar::CALENDAR]);
        $client->setRedirectUri(config('google-calendar.oauth_redirect_uri'));

        $authUrl = $client->createAuthUrl();
        $this->info('Open this URL in your browser to authorize:');
        $this->line($authUrl);

        $code = $this->ask('Paste the authorization code');
        if (!$code) {
            $this->error('Authorization code is required.');
            return self::FAILURE;
        }

        $accessToken = $client->fetchAccessTokenWithAuthCode(trim($code));
        if (isset($accessToken['error'])) {
            $this->error('Failed to fetch access token: ' . ($accessToken['error_description'] ?? $accessToken['error']));
            return self::FAILURE;
        }

        File::ensureDirectoryExists(dirname($tokenPath));
        File::put($tokenPath, json_encode($accessToken, JSON_PRETTY_PRINT));

        $this->info("Token saved to: {$tokenPath}");
        return self::SUCCESS;
    }
}
