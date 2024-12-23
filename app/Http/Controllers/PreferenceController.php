<?php

namespace App\Http\Controllers;

use App\Actions\Preference\GetUserPreferencesAction;
use App\Actions\Preference\SetUserPreferenceAction;
use App\Http\Requests\AddPreferenceRequest;
use App\Http\Resources\PreferenceResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PreferenceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/preferences",
     *     summary="Get user preferences",
     *     description="Get list of user preferences",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User preferences retrieved",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Preference")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(GetUserPreferencesAction $getUserPreferencesAction): AnonymousResourceCollection
    {
        $preferences = $getUserPreferencesAction->execute();

        return PreferenceResource::collection($preferences);
    }

    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     summary="Set user preference",
     *     description="Create or update user preference",
     *     tags={"Preferences"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *                 required={"preferencable_id", "preferencable_type"},
     *
     *                 @OA\Property(
     *                     property="preferencable_id",
     *                     type="integer",
     *                     description="ID of the preferencable"
     *                 ),
     *                 @OA\Property(
     *                     property="preferencable_type",
     *                     type="string",
     *                     enum={"category", "author", "source"},
     *                     description="Type of the preferencable entity"
     *                 )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Preference created/updated",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Preference"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Invalid input data"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(AddPreferenceRequest $request, SetUserPreferenceAction $setUserPreferenceAction): PreferenceResource
    {
        $requestData = $request->validated();

        $preference = $setUserPreferenceAction->execute($requestData);

        return new PreferenceResource($preference);
    }
}
