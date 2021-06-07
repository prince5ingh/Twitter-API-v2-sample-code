<?php

/**
 * To use these samples you will need to set your bearer token as an environment variable by running the following in your terminal:
 * export BEARER_TOKEN='<your_bearer_token>'
 */

const FOLLOWERS_API_URL = 'https://api.twitter.com/2/users/%u/followers';

/**
 * @throws Exception
 */
function get_followers(int $user_id, array $headers, array $params = []): array
{
    $url = sprintf(FOLLOWERS_API_URL, $user_id).'?'.http_build_query($params);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $decoded_response = json_decode($response, true);

    if ($status_code != 200) {
        $errors = print_r($decoded_response['errors'] ?? $decoded_response, true);

        throw new Exception("Unable to get followers: ".$errors, $status_code);
    }

    return $decoded_response['data'] ?? [];
}

try {
    // replace with the user id to pull followers from
    $user_id = 2244994945;

    $headers = [
        'Authorization: Bearer '.getenv('BEARER_TOKEN')
    ];

    // update the array below with your parameters:
    // https://developer.twitter.com/en/docs/twitter-api/users/follows/api-reference/get-users-id-followers
    $params = [
        'user.fields' => 'created_at'
    ];

    $followers = get_followers($user_id, $headers, $params);
} catch (Exception $e) {
    print $e->getMessage();
}
