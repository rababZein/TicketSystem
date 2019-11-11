<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Category\Entities\Category;

use Modules\Category\Http\Requests\AddCategoryRequest;
use Modules\Category\Http\Requests\UpdateCategoryRequest;
use Modules\Category\Http\Requests\DeleteCategoryRequest;

use Modules\Category\Http\Resources\CategoryResource;
use Modules\Category\Http\Resources\CategoryCollection;

use App\Http\Controllers\API\BaseController;

use App\Exceptions\InvalidDataException;
use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\ItemNotDeletedException;

use Carbon\Carbon;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $categoryies = Category::paginate();

        return $this->sendResponse(new CategoryCollection($categoryies), 'Categoryies retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     * @param AddCategoryRequest $request
     * @return Response
     */
    public function store(AddCategoryRequest $request)
    {
        $input = $request->validated();
        $input['created_at'] = Carbon::now();
        $input['created_by'] = auth()->user()->id;
    
        try {
          $category = Category::create($input);
        } catch (Exception $ex) {
          throw new ItemNotCreatedException('Category');
        }
    
        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    }

    /**
     * Show the specified resource.
     * @param Category $category
     * @return Response
     */
    public function show(Category $category)
    {
        return $this->sendResponse(new CategoryResource($category), 'Category retrieved successfully.');    
    }

    /**
     * Update the specified resource in storage.
     * @param Category $category
     * @param UpdateCategoryRequest $request
     * @return Response
     */
    public function update(Category $category, UpdateCategoryRequest $request)
    {
        $input = $request->validated();

        $category->updated_at = Carbon::now();
        $category->updated_by = auth()->user()->id;

        try {
            $updated = $category->fill($input)->save();
        } catch (\Exception $ex) {
            throw new ItemNotUpdatedException('Category');
        }    

        if (!$updated)
        throw new ItemNotUpdatedException('Category');

        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Category $category
     * @param DeleteCategoryRequest $request
     * @return Response
     */
    public function destroy(Category $category, DeleteCategoryRequest $request)
    {
        if($category->topics->isNotEmpty()) {
            throw new InvalidDataException([
                'topics' => $category->topics->toArray()
            ],
            'Can\'t delete!, Category has topics.');
        }

        try {
            $category->delete();
        } catch (\Exception $ex) {
            throw new ItemNotDeletedException('Category');
        }
      
          return $this->sendResponse(new CategoryResource($category), 'Category deleted successfully.');
    }
}
