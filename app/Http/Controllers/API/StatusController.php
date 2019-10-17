<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\StatusResource;
use App\Models\Status;

class StatusController extends Controller 
{

  public function getAll()
  {
    $tasks = Status::all();

    return $this->sendResponse(StatusResource::collection($tasks), 'Status retrieved successfully.');
  }

  public function checkOpen($entity)
  {
    if ($entity->status_id == 1) {
      return true;
    }

    return false;
  }

  public function checkPending($entity)
  {
    if ($entity->status_id == 2) {
      return true;
    }

    return false;
  }


  public function checkInprogress($entity)
  {
    if ($entity->status_id == 3) {
      return true;
    }

    return false;
  }


  public function checkDone($entity)
  {
    if ($entity->status_id == 4) {
      return true;
    }

    return false;
  }  
}

?>