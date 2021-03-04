<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepositoryRequest;
use App\Models\Repository;

class RepositoryController extends Controller
{
  public function store(RepositoryRequest $request)
  {
    $request->user()->repositories()->create($request->all());

    return redirect()->route('repositories.index');
  }

  public function update(RepositoryRequest $request, Repository $repository)
  {
    $repository->update($request->all());

    return redirect()->route('repositories.edit', $repository);
  }

  public function destroy(Repository $repository)
  {
    $repository->delete();

    return redirect()->route('repositories.index');
  }
}
