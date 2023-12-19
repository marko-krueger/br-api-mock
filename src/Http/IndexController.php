<?php
declare(strict_types=1);

namespace Thomann\BrMockServer\Http;

use Symfony\Component\HttpFoundation\Response;
use function NC\Routing\response;

class IndexController
{
    public function index(): Response
    {
        return response("Index! Nothing to se here.");
    }
}