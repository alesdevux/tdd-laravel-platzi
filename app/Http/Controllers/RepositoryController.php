<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepositoryRequest;
use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
  public function store(RepositoryRequest $request)
  {
    $request->user()->repositories()->create($request->all());

    return redirect()->route('repositories.index');
  }

  public function update(RepositoryRequest $request, Repository $repository)
  {
    if ($request->user()->id != $repository->user_id) {
      abort(403);
    }

    $repository->update($request->all());

    return redirect()->route('repositories.edit', $repository);
  }

  public function destroy(Request $request, Repository $repository)
  {
    if ($request->user()->id != $repository->user_id) {
      abort(403);
    }

    $repository->delete();

    return redirect()->route('repositories.index');
  }
}
