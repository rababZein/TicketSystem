<?php

namespace Modules\Topic\Http\Controllers;

use Modules\Topic\Entities\Topic;

use Modules\Topic\Http\Requests\AddTopicRequest;
use Modules\Topic\Http\Requests\UpdateTopicRequest;
use Modules\Topic\Http\Requests\DeleteTopicRequest;

use Modules\Topic\Http\Resources\TopicResource;
use Modules\Topic\Http\Resources\TopicCollection;

use App\Http\Controllers\API\BaseController;

use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotDeletedException;

use Carbon\Carbon;

class TopicController extends BaseController
{
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $topicies = Topic::paginate();

        return $this->sendResponse(new TopicCollection($topicies), 'Topicies retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param AddTopicRequest $request
     * @return Response
     */
    public function store(AddTopicRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;
    
        try {
          $topic = Topic::create($input);
        } catch (Exception $ex) {
          throw new ItemNotCreatedException('Topic');
        }
    
        return $this->sendResponse(new TopicResource($topic), 'Topic created successfully.');
    }

    /**
     * Show the specified resource.
     * @param Topic $topic
     * @return Response
     */
    public function show(Topic $topic)
    {
        return $this->sendResponse(new TopicResource($topic), 'Topic retrieved successfully.');    
    }

    /**
     * Update the specified resource in storage.
     * @param Topic $topic
     * @param UpdateTopicRequest $request
     * @return Response
     */
    public function update(Topic $topic, UpdateTopicRequest $request)
    {
        $input = $request->validated();

        $topic->updated_at = Carbon::now();
        $topic->updated_by = auth()->user()->id;

        try {
        $updated = $topic->fill($input)->save();
        } catch (\Exception $ex) {
        throw new ItemNotUpdatedException('Topic');
        }    

        if (!$updated)
        throw new ItemNotUpdatedException('Topic');

        return $this->sendResponse(new TopicResource($topic), 'Topic updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Topic $topic
     * @param DeleteTopicRequest $request
     * @return Response
     */
    public function destroy(Topic $topic, DeleteTopicRequest $request)
    {
        try {
            $topic->delete();
        } catch (\Exception $ex) {
            throw new ItemNotDeletedException('Topic');
        }
      
        return $this->sendResponse(new TopicResource($topic), 'Topic deleted successfully.');
    }
}
