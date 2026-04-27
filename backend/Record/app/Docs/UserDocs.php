<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class UserDocs {

    #[OA\Post(
    path: '/api/user/login',
    summary: 'Checks if the recieved name and password matches a database record with the same name and hashed password.',
    tags: ['Users'],
    requestBody: new OA\RequestBody(
    required: true,
    content: new OA\JsonContent(
        required: ['userName', 'password'],   
        properties: [
            new OA\Property(property: 'userName', type: 'string', example: 'John Doe'),
            new OA\Property(property: 'password', type: 'string', example: 'verystrongpassword123'),
        ]
    )),
    responses: [
        new OA\Response(response: 200, description: 'Username and password matched a record! API Token generated and granted!'),
        new OA\Response(response: 403, description: 'User has been found but password didnt match!'),
        new OA\Response(response: 404, description: 'User not found!'),
        new OA\Response(response: 422, description: 'The request was recieved but its content contained errors!')
    ])]
    public function login() {}


    #[OA\Post(
    path: '/api/user/register',
    summary: 'Creates a new Record in the database with the provided details.',
    tags: ['Users'],
    requestBody: new OA\RequestBody(
    required: true,
    content: new OA\JsonContent(
        required: ['userName', 'password'],   
        properties: [
            new OA\Property(property: 'userName', type: 'string', example: 'John Doe'),
            new OA\Property(property: 'password', type: 'string', example: 'verystrongpassword123'),
        ]
    )),
    responses: [
        new OA\Response(response: 201, description: 'Successfully logged in!'),
        new OA\Response(response: 422, description: 'The request was recieved but its content contained errors!')
    ])]
    public function register() {}


    #[OA\Post(
    path: '/api/user/logout',
    summary: 'Deletes the Users API Token from the database',
    description:'Doesnt delete the actual Users account from the database',
    tags: ['Users'],
    security:[['bearerAuth'=>[]]],
    responses: [
        new OA\Response(response: 205, description: 'Users API Token successfully deleted!'),
        new OA\Response(response: 401, description: 'The request and its contents was recieved but the token was either missing or it was invalid!'),
        new OA\Response(response: 500, description: 'The request and its contents was recieved but the server failed to delete the token!')
    ])]
    public function logout() {}


    #[OA\Delete(
    path: '/api/user/delete/{user}',
    summary: 'Delete an User',
    description: 'Completley removes the Users Account from the database. Requires User API Token',
    tags: ['Users'],
    security: [['bearerAuth' => []]],
    parameters: [
        new OA\Parameter(
            name: 'user',
            in: 'path',
            required: true,
            description: 'The ID of the user to delete',
            schema: new OA\Schema(type: 'integer', example: 1)
        )
    ],
    responses: [
        new OA\Response(response: 204, description: 'User deleted successfully'),
        new OA\Response(response: 401, description: 'No Token Provided / Not Admin User'),
        new OA\Response(response: 404, description: 'Not found'),
    ])]
    public function deleteAccount() {}


    #[OA\Post(
    path: '/api/favourite',
    summary: 'Favourites a Record',
    description:'If called with a pair that already exits it deletes that link. (Unfavourites the Record.)',
    tags: ['Records','Users','Favourites'],
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['recordId'],   
            properties: [
                new OA\Property(property: 'recordId', type: 'integer', example: 1),
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: '[UserName] favourtied [RecordId] Record(ID)'),
        new OA\Response(response: 200, description: '[UserName] un favourtied [RecordId] Record(ID)'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 422, description: 'The request was recieved but its content contained errors!'),
        new OA\Response(response: 500, description: 'The request was recieved but the serve failde to link the records!')
    ])]
    public function favourite() {}


    #[OA\Get(
    path: '/api/favourite/{userId}',
    summary: 'Returns all favourited record IDs for a given user',
    tags: ['Favourites','Users'],
    parameters: [
        new OA\Parameter(
            name: 'userId',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(response: 200, description: 'List of favourited record IDs'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 404, description: 'User not found'),
    ])]
    public function getFavouritesById() {}


    #[OA\Get(
    path: '/api/favourite/{userId}/list',
    summary: 'Returns all favourited records for a given user',
    tags: ['Favourites','Users'],
    parameters: [
        new OA\Parameter(
            name: 'userId',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'integer', example: 1)
        ),
    ],
    responses: [
        new OA\Response(response: 200, description: 'List of favourited records'),
        new OA\Response(response: 401, description: 'Unauthorized'),
        new OA\Response(response: 404, description: 'User not found'),
    ])]
    public function getFavouritesListById() {}


    #[OA\Get(
    path: '/api/users',
    summary: 'Returns all Users',
    description: 'Returns all Users, only avaible to Admins.',
    tags: ['Users'],
    security: [['bearerAuth' => []]],
    responses: [
        new OA\Response(response: 200, description: 'Successful response')
    ])]
    public function getUsers() {}
}