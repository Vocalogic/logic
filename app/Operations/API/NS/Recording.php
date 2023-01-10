<?php

namespace App\Operations\API\NS;

use GuzzleHttp\Exception\GuzzleException;

class Recording extends NSCore
{
    /**
     * Get a call recording by an ID
     * @param string      $domain
     * @param string|null $origId
     * @param string|null $termId
     * @param string|null $callId
     * @return object
     * @throws GuzzleException
     */
    public function get(string $domain, ?string $origId = null, ?string $termId = null, ?string $callId = null): object
    {
        $payload = [
            'object' => 'recording',
            'domain' => $domain,
            'format' => 'json',
            'action' => 'read'
        ];
        if ($origId)
        {
            $payload['orig_callid'] = $origId;
        }
        if ($termId)
        {
            $payload['term_callid'] = $termId;
        }
        if ($callId)
        {
            $payload['callid'] = $callId;
        }
        $result = $this->send("?", 'GET', $payload);
        if (empty($result)) $result = (object) [];
        if (is_array($result))
        {
            return $result[0];
        }
        return $result;
    }

}
