<?php
declare(strict_types=1);

namespace Thomann\BrMockServer\Http;

use NC\Routing\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use function NC\Routing\emptyResponse;
use function NC\Routing\jsonResponse;

class ApiController
{
    private string $logDir;

    public function __construct(string $logDir)
    {
        $this->logDir = $logDir;
    }

    public function ingest(Request $request, int $accountId, string $catalogName): Response
    {
        $jobId = Uuid::uuid4()->toString();
        $lastIngest = $jobId . '|' . $accountId . '|' . $catalogName . '|ingest|full';
        file_put_contents($this->logDir . '/ingest.log', $lastIngest . PHP_EOL, FILE_APPEND);

        return jsonResponse(['jobId' => $jobId]);
    }

    public function index(Request $request): Response
    {
        return jsonResponse([]);
    }

    public function status(Request $request): Response
    {
        $lastEntries = file($this->logDir . '/ingest.log');

        if (!is_array($lastEntries) || empty($lastEntries)) {
            return emptyResponse();
        }

        $lastEntry = array_pop($lastEntries);
        $last = explode('|', $lastEntry);

        $jobId = $request->get('jobId');
        $accountId = $last[1];
        $catalogName = $last[2];
        $lastAction = $last[3];
        $lastActionType = $last[4];

        if ($lastAction === 'ingest') {
            $lastAction = 'index';
            $lastActionType = 'update';
            $lastActionWrite = "{$jobId}|{$accountId}|{$catalogName}|{$lastAction}|$lastActionType";

            file_put_contents($this->logDir . '/ingest.log', $lastActionWrite . PHP_EOL, FILE_APPEND);
        }
        return jsonResponse([
            'id' => $jobId,
            'catalog_name' => $last[2],
            'account_id' => $last[1],
            'status' => 'success',
            'created_at' => '2023-11-15T10:08:07.801755Z',
            'started_at' => '2023-11-15T10:08:07.897168Z',
            'stopped_at' => '2023-11-15T10:08:16.987618Z',
            'type' => "{$lastAction} {$lastActionType}",
        ]);
    }
}