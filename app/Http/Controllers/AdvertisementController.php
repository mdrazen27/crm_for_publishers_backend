<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdvertisementRequest;
use App\Http\Requests\UpdateAdvertisementRequest;
use App\Http\Resources\AdvertisementResource;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Advertisement::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->sort_by === 'created_at') {
            $advertisements = Advertisement::orderBy('created_at', $request->sort_desc ? 'desc' : 'asc');
        } else if ($request->sort_by === 'updated_at') {
            $advertisements = Advertisement::orderBy('updated_at', $request->sort_desc ? 'desc' : 'asc');
        } else {
            $advertisements = Advertisement::query();
        }
        if ($request->search) {
            $advertisements->where('name', 'like', "%$request->search%")
                ->orWhere('url', 'like', "%$request->search%");
            if (Auth::user()->isAdmin()) {
                $advertisements->orWhereHas('publisher', function ($query) use ($request) {
                    $query->where('name', 'like', "%$request->search%");
                });
            }
        }
        $itemsPerPage = 10;
        if ($request->per_page) {
            $itemsPerPage = intval($request->per_page);
        }
        return AdvertisementResource::collection($advertisements->paginate($itemsPerPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvertisementRequest $request): AdvertisementResource
    {
        $advertisement = new Advertisement();
        $advertisement->url = $request->url;
        $advertisement->name = $request->name;
        $advertisement->publisher_id = Auth::user()->publisher->id;
        $advertisement->active = !!$request->active;
        $advertisement->save();

        return AdvertisementResource::make($advertisement);
    }

    /**
     * Display the specified resource.
     */
    public function show(Advertisement $advertisement): AdvertisementResource
    {
        return AdvertisementResource::make($advertisement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertisementRequest $request, Advertisement $advertisement): AdvertisementResource
    {
        $advertisement->url = $request->url;
        $advertisement->name = $request->name;
        $advertisement->save();

        return AdvertisementResource::make($advertisement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Advertisement $advertisement): JsonResponse
    {
        $advertisement->delete();

        return new JsonResponse(['message' => 'Advertisement deleted!', 'success' => true]);
    }

    public function toggleAdvertisementStatus(Advertisement $advertisement)
    {
        $this->authorize('toggleAdvertisementStatus', [Advertisement::class, $advertisement]);
        $advertisement->active = !$advertisement->active;
        $advertisement->save();

        return AdvertisementResource::make($advertisement);
    }
}
