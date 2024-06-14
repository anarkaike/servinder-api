<?php

namespace Modules\Users\app\Http\Controllers;

use App\Http\app\Http\Requests\UsersRequest;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;
use Modules\Users\app\Models\User;
use Modules\Users\app\Policies\UsersPolicy;

class UsersController extends ApiController
{
    /**
     * Fully-qualified model class name
     */
    protected $model = User::class;
    /**
     * @var string $policy
     */
    protected $policy = UsersPolicy::class;
    
    /**
     * The list of attributes to select from db
     */
    protected $attributes = ['id', 'email', 'created_at'];
    
    /**
     * @var string $request
     */
    protected $request = UsersRequest::class;
    
    /**
     * @var string $resource
     */
//    protected $resource = CustomMessageResource::class;
    
    /**
     * @var string $collectionResource
     */
//    protected $collectionResource = CustomMessageCollectionResource::class;
    
    
    /**
     * Retrieves currently authenticated user based on the guard.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function resolveUser()
    {
        return Auth::guard('sanctum')->user();
    }

//    /**
//     * Builds Eloquent query for fetching entities in index method.
//     *
//     * @param Request $request
//     * @param array   $requestedRelations
//     *
//     * @return Builder
//     */
//    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
//    {
//        $query = parent::buildIndexFetchQuery($request, $requestedRelations);
//
//        $query->whereNotNull('published_at');
//
//        return $query;
//    }


//    /**
//     * Runs the given query for fetching entities in index method.
//     *
//     * @param Request $request
//     * @param Builder $query
//     * @param int $paginationLimit
//     * @return LengthAwarePaginator
//     */
//    protected function runIndexFetchQuery(Request $request, Builder $query, int $paginationLimit): LengthAwarePaginator
//    {
//        return $query->paginate($paginationLimit, ['id', 'title' 'published_at']);
//    }


//    /**
//     * Fills attributes on the given entity and stores it in database.
//     *
//     * @param Request $request
//     * @param Model $entity
//     * @param array $attributes
//     */
//    protected function performStore(Request $request, Model $entity, array $attributes): void
//    {
//        if ($this->resolveUser()->hasRole('admin')) {
//            $entity->forceFill($attributes);
//        } else {
//            $entity->fill($attributes);
//        }
//        $entity->save();
//    }


//    /**
//     * Builds Eloquent query for fetching entity(-ies).
//     *
//     * @param Request $request
//     * @param array $requestedRelations
//     * @return Builder
//     */
//    protected function buildFetchQuery(Request $request, array $requestedRelations): Builder
//    {
//        $query = parent::buildFetchQuery($request, $requestedRelations);
//
//        $query->whereNotNull('published_at');
//
//        return $query;
//    }


//    /**
//     * Runs the given query for fetching entity.
//     *
//     * @param Request $request
//     * @param Builder $query
//     * @param int|string $key
//     * @return Model
//     */
//    protected function runFetchQuery(Request $request, Builder $query, $key): Model
//    {
//        return $query->select($this->attributes)->findOrFail($key);
//    }
//
//    /**
//     * Runs the given query for fetching entities in index method.
//     *
//     * @param Request $request
//     * @param Builder $query
//     * @param int $paginationLimit
//     * @return LengthAwarePaginator
//     */
//    protected function runIndexFetchQuery(Request $request, Builder $query, int $paginationLimit): LengthAwarePaginator
//    {
//        return $query->paginate($paginationLimit, $this->attributes);
//    }
//
//    /**
//     * Fills attributes on the given entity and stores it in database.
//     *
//     * @param Request $request
//     * @param Model $post
//     * @param array $attributes
//     */
//    protected function performStore(Request $request, Model $post, array $attributes): void
//    {
//        if ($this->resolveUser()->hasRole('admin')) {
//            $post->forceFill($attributes);
//        } else {
//            $post->fill($attributes);
//        }
//        $post->save();
//    }


//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeIndex(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterIndex(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeShow(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterShow(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeStore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterStore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeUpdate(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeSave(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterSave(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeDestroy(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterDestroy(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeRestore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterRestore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeFresh(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeBatchStore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterBatchStore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeBatchUpdate(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterBatchUpdate(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeBatchDestroy(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterBatchDestroy(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function beforeBatchRestore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }
//    /**
//     * @param Request $request
//     * @param Post $post
//     */
//    protected function afterBatchRestore(Request $request, $post)
//    {
//        $post->user()->associate($request->user());
//    }


}
