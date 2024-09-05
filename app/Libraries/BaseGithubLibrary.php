<?php

namespace App\Libraries;

use App\Libraries\BaseLibrary;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Cache;
use JsonException;

class BaseGithubLibrary extends BaseLibrary
{

    private static function getAppJWT(): string
    {
        $jwt = Cache::remember('github.jwt', 59 * 5, function () {
            $privateKey = file_get_contents(storage_path('app/secret/vatsim-germany-homepage.private-key.pem'));
            $payload = [
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->addMinutes(5)->timestamp,
                'iss' => config('github.app.id')
            ];
            return JWT::encode($payload, $privateKey, 'RS256');
        });
        return $jwt;
    }

    protected static function constructGithubClient(string $jwt = "", array $config = []): Client
    {
        $config['headers'] = array_merge($config['headers'] ?? [], [
            'Authorization' => 'Bearer ' . $jwt,
            'Accept' => 'application/vnd.github.v3+json',
        ]);
        return parent::constructClient($config);
    }


    protected static function getInstallationJWTs(): array
    {
        try {
            return Cache::remember('github.installations', 59 * 5, function () {
                $client = self::constructGithubClient(self::getAppJWT());
                $api_url = "https://api.github.com/app/installations";
                $installations = json_decode($client->get($api_url)->getBody(), false, flags: JSON_THROW_ON_ERROR);
                $tokens = [];
                foreach ($installations as $installation) {
                    $api_url = $installation->access_tokens_url;
                    $token = json_decode($client->post($api_url)->getBody(), false, flags: JSON_THROW_ON_ERROR);
                    if ($installation->target_type == 'Organization') {
                        $tokens[] = (object)[
                            'id' => $installation->id,
                            'organization_login' => $installation->account->login,
                            'organization_id' => $installation->account->id,
                            'token' => $token->token,
                        ];
                    }
                }
                return $tokens;
            });
        } catch (JsonException|GuzzleException $e) {
            return [];
        }
    }


    static function github_is_in_organization(User $user, string $organization_login): bool
    {
        $github_name = $user->settings->github_username;
        if (!$github_name) return false;

        $installations = self::getInstallationJWTs();

        foreach ($installations as $installation) {
            try {
                if (strtolower($organization_login) != strtolower($installation->organization_login)) continue;
                $client = self::constructGithubClient($installation->token);
                $api_url = "https://api.github.com/orgs/$installation->organization_login/members/$github_name";
                $response = $client->get($api_url);
                return $response->getStatusCode() == 204;
            } catch (JsonException|GuzzleException $e) {
            }
        }
        return false;
    }

    static function github_add_to_organization(User $user, string $organization_login): bool
    {
        $github_id = $user->settings->github_userid;
        if (!$github_id) return false;

        $installations = self::getInstallationJWTs();

        foreach ($installations as $installation) {
            try {
                if (strtolower($organization_login) != strtolower($installation->organization_login)) continue;
                $client = self::constructGithubClient($installation->token);
                $api_url = "https://api.github.com/orgs/$installation->organization_login/invitations";
                $client->post($api_url, ["json" => [
                    'invitee_id' => $github_id,
                    'role' => 'direct_member'
                ]]);
                return true;
            } catch (JsonException|GuzzleException $e) {
            }
        }
        return false;
    }

    static function github_remove_from_organization(User $user, string $organization_login): bool
    {
        $github_name = $user->settings->github_username;
        if (!$github_name) return false;


        $installations = self::getInstallationJWTs();

        foreach ($installations as $installation) {
            try {
                if (strtolower($organization_login) != strtolower($installation->organization_login)) continue;
                $client = self::constructGithubClient($installation->token);
                $api_url = "https://api.github.com/orgs/$organization_login/memberships/$github_name";
                $client->delete($api_url);
                return true;
            } catch (JsonException|GuzzleException $e) {
            }
        }
        return false;
    }

    static function github_repo_contents(string $repo, string $branch, string $filepath): ?object
    {
        try {
            $client = self::constructClient();
            $api_url = "https://api.github.com/repos/$repo/contents/$filepath?ref=$branch";
            return json_decode($client->get($api_url)->getBody(), false, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException|GuzzleException $e) {
            return null;
        }

    }

    public static function github_dl_file(string $repo, string $branch, string $filepath, bool $parse_json = true): string|object|null
    {
        try {
            $client = self::constructClient();
            $info = self::github_repo_contents($repo, $branch, $filepath);
            $dl_url = $info->download_url;
            $contents = $client->get($dl_url)->getBody();
            if ($parse_json) {
                $contents = json_decode($contents, false, flags: JSON_THROW_ON_ERROR);
            }
        } catch (JsonException|GuzzleException $e) {
            return null;
        }
        return $contents;
    }

    public static function github_get_file_list(string $repo, string $branch, string $path): array
    {
        try {
            $info = self::github_repo_contents($repo, $branch, $path);
            $files = [];
            foreach ($info as $file) {
                if ($file->type == 'file'
                    && !empty($file->download_url)
                    && !empty($file->name)
                ) {
                    $files[] = $file;
                }
            }
        } catch (\Exception $e) {
            return [];
        }
        return $files;
    }

}
