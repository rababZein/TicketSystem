<?php

namespace Modules\File\Http\Controllers;

use Modules\File\Entities\File;

use Modules\File\Http\Requests\AddFileRequest;
use Modules\File\Http\Requests\UpdateFileRequest;
use Modules\File\Http\Requests\DeleteFileRequest;

use Modules\File\Http\Resources\FileResource;
use Modules\File\Http\Resources\FileCollection;

use App\Http\Controllers\API\BaseController;

use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotDeletedException;

use Carbon\Carbon;

class FileController extends BaseController
{
     /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $fileies = File::paginate();

        return $this->sendResponse(new FileCollection($fileies), 'Fileies retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param AddFileRequest $request
     * @return Response
     */
    public function store(AddFileRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;
    
        try {
          $file = File::create($input);
        } catch (Exception $ex) {
          throw new ItemNotCreatedException('File');
        }
    
        return $this->sendResponse(new FileResource($file), 'File created successfully.');
    }

    /**
     * Show the specified resource.
     * @param File $file
     * @return Response
     */
    public function show(File $file)
    {
        return $this->sendResponse(new FileResource($file), 'File retrieved successfully.');    
    }

    /**
     * Update the specified resource in storage.
     * @param File $file
     * @param UpdateFileRequest $request
     * @return Response
     */
    public function update(File $file, UpdateFileRequest $request)
    {
        $input = $request->validated();

        $file->updated_at = Carbon::now();
        $file->updated_by = auth()->user()->id;

        try {
        $updated = $file->fill($input)->save();
        } catch (\Exception $ex) {
        throw new ItemNotUpdatedException('File');
        }    

        if (!$updated)
        throw new ItemNotUpdatedException('File');

        return $this->sendResponse(new FileResource($file), 'File updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param File $file
     * @param DeleteFileRequest $request
     * @return Response
     */
    public function destroy(File $file, DeleteFileRequest $request)
    {
        try {
            $file->delete();
        } catch (\Exception $ex) {
            throw new ItemNotDeletedException('File');
        }
      
        return $this->sendResponse(new FileResource($file), 'File deleted successfully.');
    }
}
