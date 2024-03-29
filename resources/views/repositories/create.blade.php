<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      Repositorios
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
        <form action="{{ route('repositories.store') }}" method="POST" class="max-w-md">
          @csrf

          <label class="block font-medium text-sm text-gray-700" for="url">URL *</label>
          <input
            class="form-input w-full rounded-md shadow-sm"
            type="text" name="url">
          
          <label class="block font-medium text-sm text-gray-700" for="description">
            Descripción *
          </label>
          <textarea
            class="form-input w-full rounded-md shadow-sm"
            type="text" name="description"></textarea>

          <hr class="my-4">

          <input
            class="bg-blue-500 text-white font-bold py-2 px-4 rounded-md" 
            type="submit" value="Guardar repositorio">
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
