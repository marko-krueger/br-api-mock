<?php
declare(strict_types=1);

namespace Thomann\BrMockServer\Http;

use NC\Routing\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Thomann\BrMockServer\SQLiteDB;
use function NC\Routing\emptyResponse;
use function NC\Routing\jsonResponse;

class ApiController {
    private SQLiteDB $SQLiteDB;

    public function __construct(SQLiteDB $SQLiteDB ) {
        $this->SQLiteDB = $SQLiteDB;
    }

    public function ingest(int $accountId, string $catalogName): Response {
        $jobId = Uuid::uuid4()->toString();
        $this->SQLiteDB->execute('INSERT INTO jobs (id, account_id, catalog_name, type, type_action, time) VALUES (?, ?, ?, ?, ?, ?)', [$jobId, $accountId, $catalogName, 'ingest', 'full', time()]);

        return jsonResponse(['jobId' => $jobId]);
    }

    public function index(Request $request, int $accountId, string $catalogName): Response {
        $jobId = Uuid::uuid4()->toString();

        return jsonResponse(['jobId' => $jobId]);
    }

    public function status(string $jobId): Response {
        $rows = $this->SQLiteDB->query('SELECT * FROM jobs WHERE id = :jobId', ['jobId' => $jobId]);

        if(!isset($rows[0])) {
            return jsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $row = $rows[0];

        return jsonResponse([
                'id' => $jobId,
                'catalog_name' => $row['catalog_name'],
                'account_id' => $row['account_id'],
                'status' => 'success',
                'created_at' => '2023-11-15T10:08:07.801755Z',
                'started_at' => '2023-11-15T10:08:07.897168Z',
                'stopped_at' => '2023-11-15T10:08:07.897168Z',
                'type' => "{$row['type']} {$row['type_action']}",
            ]
        );

    }
}