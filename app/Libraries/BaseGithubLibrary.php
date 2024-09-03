<?php

namespace App\Libraries;

use App\Libraries\BaseLibrary;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class BaseGithubLibrary extends BaseLibrary
{

    static function github_repo_contents(string $repo, string $branch, string $filepath): ?object
    {
        try {
            $client = self::constructClient();
            $api_url = "https://api.github.com/repos/$repo/contents/$filepath?ref=$branch";
            $api_res = json_decode($client->get($api_url)->getBody(), false, flags: JSON_THROW_ON_ERROR);
            return $api_res;
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
