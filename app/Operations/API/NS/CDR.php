<?php

namespace App\Operations\API\NS;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;

class CDR extends NSCore
{
    /**
     * Get a count of CDR for a time period.
     * @param string $domain
     * @param Carbon $start
     * @param Carbon $end
     * @return object
     * @throws GuzzleException
     */
    public function count(string $domain, Carbon $start, Carbon $end): object
    {
        $result = $this->send("?", 'GET', [
            'object'     => 'cdr',
            'action'     => 'count',
            'format'     => 'json',
            'domain'     => $domain,
            'start_date' => $start->format("Y-m-d H:i:s"),
            'end_date'   => $end->format("Y-m-d H:i:s")
        ]);
        return $result;
    }

    /**
     * Get CDR total and count by date range.
     * @param string $ext
     * @param Carbon $start
     * @param Carbon $end
     * @return object
     * @throws GuzzleException
     */
    public function byUser(string $ext, Carbon $start, Carbon $end): object
    {
        $result = $this->send("?", 'GET', [
            'object'     => 'cdr2',
            'action'     => 'count',
            'format'     => 'json',
            'start_date' => $start->format("Y-m-d H:i:s"),
            'end_date'   => $end->format("Y-m-d H:i:s"),
            'uid'        => $ext // 101@vldemo.11239.service (ex)
        ]);
        if (!$result)
        {
            $result = (object)[
                'total'   => 0,
                'minutes' => 0
            ];
        }
        return $result;
    }

    /**
     * Get CDR By Domain
     * @param string $domain
     * @param Carbon $start
     * @param Carbon $end
     * @return array
     * @throws GuzzleException
     */
    public function byDomain(string $domain, Carbon $start, Carbon $end): array
    {
        $result = $this->send("?", 'GET', [
            'object'     => 'cdr2',
            'action'     => 'read',
            'format'     => 'json',
            'start_date' => $start->format("Y-m-d H:i:s"),
            'end_date'   => $end->format("Y-m-d H:i:s"),
            'domain'     => $domain
        ]);
        return $result;
    }

    /**
     * Find call records that match an id and by start and end times.
     * @param string $id
     * @param Carbon $start
     * @param Carbon $end
     * @return array|null
     * @throws GuzzleException
     */
    public function find(string $id, Carbon $start, Carbon $end) :?object
    {
        $result = $this->send("?", 'GET', [
            'object'     => 'cdr2',
            'action'     => 'read',
            'format'     => 'json',
            'id'         => $id,
            'start_date' => $start->format("Y-m-d H:i:s"),
            'end_date'   => $end->format("Y-m-d H:i:s")
        ]);
        return $result[0] ?? null;
    }

    /**
     * Determine if a recording leg will use the originating or terminating call id.
     * @param object $cdr
     * @return bool
     */
    public function isOriginating(object $cdr) : bool
    {
       return $cdr->type == 1;
    }

    /**
     * Attempt to get a recording url from the recording endpoint.
     * @param string $domain
     * @param string $callid
     * @return string|null
     * @throws GuzzleException
     */
    public function getRecordingUrl(string $domain, string $callid): ?string
    {
        $r = new Recording($this->provider);
        $response = $r->get($domain, null, null, $callid);
        return $response->url ?? null;
    }




}
