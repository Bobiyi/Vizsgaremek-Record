<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class OtherDocs {
    #[OA\Get(
    path: '/api/about',
    summary: 'Get information about the application',
    tags: ['About'],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function about() {}
}