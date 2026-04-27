<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class RecordDocs{

    #[OA\Get(
    path: '/api/records',
    summary: 'Get all records',
    tags: ['Records'],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getRecords() {}


    #[OA\Get(
    path: '/api/records/{recordId}',
    summary: 'Get the Record on a given id',
    tags: ['Records'],
    parameters:[
        new OA\Parameter(
            name:'recordId',
            in:'path',
            required:true,
            description:'Record ID',
            schema: new OA\Schema(type:'integer')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getRecord() {}


    #[OA\Get(
    path: '/api/artists',
    summary: 'Get all artists',
    tags: ['Artists'],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getArtists() {}


    #[OA\Get(
    path: '/api/artists/{id}',
    summary: 'Get the Artists on a given id',
    tags: ['Artists'],
    parameters:[
        new OA\Parameter(
            name:'id',
            in:'path',
            required:true,
            description:'Artist ID',
            schema: new OA\Schema(type:'integer')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ]
    )]
    public function getArtist() {}


    #[OA\Get(
    path: '/api/records/types',
    summary: 'Get all record types',
    tags: ['Types'],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getTypes() {}


    #[OA\Get(
    path: '/api/records/types/{type}',
    summary: 'Get the Record with the given type',
    tags: ['Records','Types'],
    parameters:[
        new OA\Parameter(
            name:'type',
            in:'path',
            required:true,
            description:'Type Name',
            schema: new OA\Schema(type:'string')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getRecordsByType() {}


    #[OA\Get(
    path: '/api/records-artist/{recordId}',
    summary: 'Return a given Records All Artists',
    tags: ['Records','Artists'],
    parameters:[
        new OA\Parameter(
            name:'RecordId',
            in:'path',
            required:true,
            description:'RecordId',
            schema: new OA\Schema(type:'integer')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getRecordsArtists() {}


    #[OA\Get(
    path: '/api/artists-record/{artistId}',
    summary: 'Return a given Artist All Records',
    tags: ['Records','Artists'],
    parameters:[
        new OA\Parameter(
            name:'ArtistId',
            in:'path',
            required:true,
            description:'ArtistId',
            schema: new OA\Schema(type:'integer')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getArtistsRecord() {}


    #[OA\Post(
    path: '/api/record',
    summary: 'Create a new record',
    tags: ['Records'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['recordName', 'typeId'],
                properties: [
                    new OA\Property(property: 'recordName', type: 'string', example: 'Master Of Puppets'),
                    new OA\Property(property: 'typeId', type: 'integer', example: 1),
                    new OA\Property(property: 'releaseYear', type: 'integer', example: 1986),
                    new OA\Property(property: 'length', type: 'integer', example: 8),
                    new OA\Property(property: 'recordFile', type: 'string', format: 'binary'),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Record created successfully'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 422, description: 'The request was received but its content contained errors!')
    ])]
    public function addRecord() {}


    #[OA\Post(
    path: '/api/artist',
    summary: 'Create a new artist',
    tags: ['Artists'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['artistName', 'isGroup'],
                properties: [
                    new OA\Property(property: 'artistName', type: 'string', example: 'Metallica'),
                    new OA\Property(property: 'activeSince', type: 'integer', example: 1981),
                    new OA\Property(property: 'artistNationality', type: 'string', example: 'usa'),
                    new OA\Property(property: 'artistWebsite', type: 'string', example: 'https://www.metallica.com'),
                    new OA\Property(property: 'isGroup', type: 'integer', example: 1),
                    new OA\Property(property: 'artistIcon', type: 'string', format: 'binary'),
                    new OA\Property(property: 'artistCover', type: 'string', format: 'binary'),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 201, description: 'Artist created successfully'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 422, description: 'The request was received but its content contained errors!')
    ])]
    public function addArtist() {}


    #[OA\Delete(
    path: '/api/records/{id}',
    summary: 'Deletes the Record on the given Id',
    description: 'Completley removes the Record and all of its links from the database. Only avaiable to Admins.',
    tags: ['Records'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(response: 204, description: 'Record deleted successfully'),
        new OA\Response(response: 401, description: 'No Token provided / Not admin user'),
        new OA\Response(response: 404, description: 'Record not found'),
        new OA\Response(response: 500, description: 'Record found, but server failed to delete the file')
    ])]
    public function deleteRecord() {}


    #[OA\Delete(
    path: '/api/artists/{id}',
    summary: 'Deletes the Artist on the given Id',
    description: 'Completley removes the Artist and all of its links from the database. Only avaiable to Admins.',
    tags: ['Artists'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(response: 204, description: 'Artist deleted successfully'),
        new OA\Response(response: 401, description: 'No Token provided / Not admin user'),
        new OA\Response(response: 404, description: 'Artist not found'),
        new OA\Response(response: 500, description: 'Artist found, but server failed to delete the file')
    ])]
    public function deleteArtist() {}


    #[OA\Put(
    path: '/api/records/{id}',
    summary: 'Updates an existing record',
    tags: ['Records'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 3)
        ),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['recordName', 'typeId'],
                properties: [
                    new OA\Property(property: 'recordName', type: 'string', maxLength: 64, example: 'Master of Puppets'),
                    new OA\Property(property: 'typeId', type: 'integer', example: 1),
                    new OA\Property(property: 'releaseYear', type: 'integer', example: 1986),
                    new OA\Property(property: 'length', type: 'integer', minimum: 1, example: 54),
                    new OA\Property(property: 'recordFile', type: 'string', format: 'binary', description: 'JPG only, 1:1 ratio, max 1280px. Replaces existing file.'),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Record updated successfully'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 404, description: 'Record not found'),
        new OA\Response(response: 422, description: 'Validation failed'),
        new OA\Response(response: 500, description: 'Failed to update record'),
    ])]
    public function updateRecord() {}


    #[OA\Put(
    path: '/api/artists/{id}',
    summary: 'Updates an existing artist',
    tags: ['Artists'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                required: ['artistName', 'isGroup'],
                properties: [
                    new OA\Property(property: 'artistName', type: 'string', maxLength: 64, example: 'Metallica'),
                    new OA\Property(property: 'activeSince', type: 'integer', example: 1981),
                    new OA\Property(property: 'artistNationality', type: 'string', maxLength: 3, example: 'usa'),
                    new OA\Property(property: 'artistWebsite', type: 'string', maxLength: 255, example: 'https://www.metallica.com'),
                    new OA\Property(property: 'isGroup', type: 'integer', enum: [0, 1], example: 1),
                    new OA\Property(property: 'artistIcon', type: 'string', format: 'binary', description: 'JPG only. Replaces existing icon.'),
                    new OA\Property(property: 'artistCover', type: 'string', format: 'binary', description: 'JPG only. Replaces existing cover.'),
                ]
            )
        )
    ),
    responses: [
        new OA\Response(response: 200, description: 'Artist updated successfully'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 404, description: 'Artist not found'),
        new OA\Response(response: 422, description: 'Validation failed'),
        new OA\Response(response: 500, description: 'Failed to update artist'),
    ])]
    public function updateArtist() {}


    #[OA\Post(
    path: '/api/link-artist-record',
    summary: 'Links an Artist to a Record',
    tags: ['Artists','Records'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['artistId', 'recordId'],   
            properties: [
                new OA\Property(property: 'artistId', type: 'integer', example: 1),
                new OA\Property(property: 'recordId', type: 'integer', example: 2),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: '[ArtistName] linked to [RecordNAme]'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 422, description: 'The request was recieved but its content contained errors!'),
        new OA\Response(response: 500, description: 'The request was recieved but the serve failde to link the records!')
    ])]
    public function linkArtistRecord() {}

}