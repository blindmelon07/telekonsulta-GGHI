<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ZoomService
{
    private string $baseUrl = 'https://api.zoom.us/v2';

    public function getAccessToken(): string
    {
        return Cache::remember('zoom_access_token', 55 * 60, function () {
            $accountId = config('services.zoom.account_id');
            $clientId = config('services.zoom.client_id');
            $clientSecret = config('services.zoom.client_secret');

            $response = Http::asForm()
                ->withBasicAuth($clientId, $clientSecret)
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ]);

            return $response->json('access_token');
        });
    }

    /** @param array<string, mixed> $data */
    public function createMeeting(array $data): array
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/users/me/meetings", [
                'topic' => $data['topic'],
                'type' => 2,
                'start_time' => Carbon::parse($data['start_time'])
                    ->timezone('UTC')
                    ->format('Y-m-d\TH:i:s\Z'),
                'duration' => $data['duration'] ?? 30,
                'password' => Str::random(6),
                'settings' => [
                    'waiting_room' => true,
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => false,
                    'auto_recording' => 'none',
                ],
            ]);

        return $response->json();
    }

    public function createMeetingForAppointment(Appointment $appointment): void
    {
        $doctor = $appointment->doctor->user;
        $patient = $appointment->patient;

        $meeting = $this->createMeeting([
            'topic' => "Teleconsultation: Dr. {$doctor->name} & {$patient->name}",
            'start_time' => $appointment->scheduled_at->toIso8601String(),
            'duration' => 30,
        ]);

        $appointment->update([
            'zoom_meeting_id' => $meeting['id'],
            'zoom_join_url' => $meeting['join_url'],
            'zoom_start_url' => $meeting['start_url'],
            'zoom_password' => $meeting['password'],
        ]);
    }

    public function deleteMeeting(string $meetingId): void
    {
        $token = $this->getAccessToken();

        Http::withToken($token)
            ->delete("{$this->baseUrl}/meetings/{$meetingId}");
    }

    /** @param array<string, mixed> $data */
    public function updateMeeting(string $meetingId, array $data): void
    {
        $token = $this->getAccessToken();

        Http::withToken($token)
            ->patch("{$this->baseUrl}/meetings/{$meetingId}", $data);
    }
}
