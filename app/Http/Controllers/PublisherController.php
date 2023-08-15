<?php

namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use App\Actions\UpdateUserAction;
use App\Http\Requests\StorePublisherRequest;
use App\Http\Requests\UpdatePublisherRequest;
use App\Http\Resources\PublisherResource;
use App\Models\Publisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PublisherController extends Controller
{

    public function __construct(private CreateUserAction $createUserAction, private UpdateUserAction $updateUserAction)
    {
        $this->authorizeResource(Publisher::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->sort_by === 'created_at') {
            $publishers = Publisher::orderBy('created_at', $request->sort_desc ? 'desc' : 'asc');
        } else if ($request->sort_by === 'updated_at') {
            $publishers = Publisher::orderBy('updated_at', $request->sort_desc ? 'desc' : 'asc');
        } else {
            $publishers = Publisher::query();
        }
        if ($request->search) {
            $publishers->where('name', 'like', "%$request->search%");
            $publishers->orWhereHas('user', function ($query) use ($request) {
                $query->where('email', 'like', "%$request->search%");
            });
        }
        $itemsPerPage = 10;
        if ($request->per_page) {
            $itemsPerPage = intval($request->per_page);
        }
        return PublisherResource::collection($publishers->paginate($itemsPerPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublisherRequest $request): PublisherResource
    {
        $publisherUser = $this->createUserAction->execute($request->email, $request->password, Auth::id(), role_id: 2);
        $publisher = new Publisher();
        $publisher->user_id = $publisherUser->id;
        $publisher->name = $request->name;
        $publisher->active = !!$request->active;
        $publisher->save();
        return PublisherResource::make($publisher);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher): PublisherResource
    {
        return PublisherResource::make($publisher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublisherRequest $request, Publisher $publisher): PublisherResource
    {
        $publisherUser = $this->updateUserAction->execute($publisher->user, $request->email, Auth::id());
        $publisher->user_id = $publisherUser->id;
        $publisher->name = $request->name;
        $publisher->save();
        return PublisherResource::make($publisher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher): JsonResponse
    {
        $publisher->delete();
        return new JsonResponse(['message' => 'Publisher successfully deleted!', 'success' => true], 200);
    }

    public function togglePublisherStatus(Publisher $publisher): PublisherResource|JsonResponse
    {
        $this->authorize('togglePublisherStatus', Publisher::class);
        $publisher->active = !$publisher->active;
        $publisher->save();
        return PublisherResource::make($publisher);
    }

    public function viewPublisherProfile(): PublisherResource|JsonResponse
    {
        $this->authorize('viewPublisherProfile', Publisher::class);
        return PublisherResource::make(Auth::user()->publisher);
    }
}
