<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class Requestdocs {
    #[OA\Post(
    path: '/api/queue-request',
    summary: 'Adds a new entry to the Queue',
    tags: ['Requests'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['type', 'payload'],
                properties: [
                    new OA\Property(
                        property: 'type',
                        type: 'string',
                        enum: ['new_artist', 'edit_artist', 'new_record', 'edit_record'],
                        example: 'new_artist'
                    ),
                    new OA\Property(
                        property: 'payload',
                        type: 'string',
                        description: 'JSON encoded object. Structure depends on `type`.',
                        oneOf: [
                            new OA\Schema(
                                title: 'Artist Payload',
                                required: ['artistName', 'isGroup'],
                                properties: [
                                    new OA\Property(property: 'artistName', type: 'string', maxLength: 64, example: 'Metallica'),
                                    new OA\Property(property: 'activeSince', type: 'integer', example: 1981),
                                    new OA\Property(property: 'artistNationality', type: 'string', maxLength: 3, example: 'usa'),
                                    new OA\Property(property: 'artistWebsite', type: 'string', maxLength: 255, example: 'https://www.metallica.com'),
                                    new OA\Property(property: 'isGroup', type: 'integer', enum: [0, 1], example: 1),
                                ]
                            ),
                            new OA\Schema(
                                title: 'Record Payload',
                                required: ['recordName', 'typeId'],
                                properties: [
                                    new OA\Property(property: 'recordName', type: 'string', maxLength: 64, example: 'Master of Puppets'),
                                    new OA\Property(property: 'typeId', type: 'integer', example: 1),
                                    new OA\Property(property: 'releaseYear', type: 'integer', example: 1986),
                                    new OA\Property(property: 'length', type: 'integer', minimum: 1, example: 54),
                                ]
                            ),
                        ]
                    ),
                    // Only used when type is new_artist or edit_artist
                    new OA\Property(property: 'artistIcon', type: 'string', format: 'binary', description: 'JPG only.'),
                    new OA\Property(property: 'artistCover', type: 'string', format: 'binary', description: 'JPG only.'),
                    // Only used when type is new_record or edit_record
                    new OA\Property(property: 'recordFile', type: 'string', format: 'binary', description: 'JPG only, 1:1 ratio, max 1280px.'),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Entry queued successfully'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 422, description: 'Validation failed'),
    ])]
    public function addRequest() {}


    #[OA\Get(
    path: '/api/requests',
    summary: 'Returns all pending requests',
    description: 'Returns all requests with status "pending". Requires authentication.',
    tags: ['Requests'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: 'List of pending requests',
            content: new OA\JsonContent(
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'userId', type: 'integer', example: 3),
                        new OA\Property(property: 'userName', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'type', type: 'string', enum: ['new_artist', 'edit_artist', 'new_record', 'edit_record'], example: 'new_artist'),
                        new OA\Property(property: 'data', type: 'object', description: 'Payload data. Structure depends on type.'),
                        new OA\Property(property: 'createdAt', type: 'string', format: 'date-time', example: '2026-03-24T10:00:00Z'),
                    ]
                )
            )
        ),
        new OA\Response(response: 401, description: 'Unauthorized'),
    ])]
    public function getRequests() {}

    #[OA\Get(
    path: '/api/requests/{requestId}',
    summary: 'Returns a request with the provided id',
    tags: ['Requests'],
    security: [['bearerAuth' => []]],
    parameters:[
        new OA\Parameter(
            name:'requestId',
            in:'path',
            required:true,
            description:'Requests Id',
            schema: new OA\Schema(type:'integer')
        )
    ],
    responses: [
        new OA\Response(response:200, description:'The request on that id'),
        new OA\Response(response:401, description: 'Unauthorized'),
        new OA\Response(response:404, description:'Request not found')
    ])]
    
    public function getRequestsById() {}

    #[OA\Get(
    path: '/api/requests/user/{userId}',
    summary: 'Returns requests with the provided user id',
    description: 'Users are only allowed to view their own Requests',
    tags: ['Requests'],
    security: [['bearerAuth' => []]],
    parameters:[
        new OA\Parameter(
            name:'userId',
            in:'path',
            required:true,
            description:'An users id',
            schema: new OA\Schema(type:'integer')
        )
    ],
    responses: [
        new OA\Response(response:200, description:'The users requests')
    ])]
    public function getRequestsWithUserId() {}


    #[OA\Patch(
    path: '/api/request/{requestId}/accept',
    summary: 'Accepts a pending request',
    description: 'Accepts a pending request and inserts its payload into the relevant table (records or artists). Only available to Admins.',
    tags: ['Requests'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'requestId',
            in: 'path',
            required: true,
            description: 'The ID of the request to accept',
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    requestBody: new OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'adminNote', type: 'string', nullable: true, example: 'Looks good!'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Request accepted and payload inserted into the database'),
        new OA\Response(response: 401, description: 'Unauthorized / Not an admin'),
        new OA\Response(response: 404, description: 'Request not found'),
        new OA\Response(response: 409, description: 'Request has already been accepted or rejected'),
        new OA\Response(response: 422, description: 'Unknown request type'),
    ])]
    public function acceptRequest() {}


    #[OA\Patch(
    path: '/api/request/{requestId}/reject',
    summary: 'Rejects a pending request',
    description: 'Rejects a pending request. The request will no longer appear in the pending list. Only available to Admins.',
    tags: ['Requests'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'requestId',
            in: 'path',
            required: true,
            description: 'The ID of the request to reject',
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    requestBody: new OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'adminNote', type: 'string', nullable: true, example: 'Duplicate entry.'),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Request rejected successfully'),
        new OA\Response(response: 401, description: 'Unauthorized / Not an admin'),
        new OA\Response(response: 404, description: 'Request not found'),
        new OA\Response(response: 409, description: 'Request has already been accepted or rejected'),
    ])]
    public function rejectRequest() {}
}