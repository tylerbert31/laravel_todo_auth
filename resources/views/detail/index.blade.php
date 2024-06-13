<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Todo Detail') }}
        </h2>
    </x-slot>

    <div class="py-12 min-w-full">
        <div class="mx-auto sm:px-6 lg:px-8 flex flex-col gap-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 flex gap-2">
                    <form action="/todo/update" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="id" value="{{ $todo->id }}">
                        <input required type="text" name="title" placeholder="Title" value="{{ $todo->title }}" class="input text-sm rounded-md" />
                        <input required type="text" name="description" placeholder="Description" value="{{ $todo->description }}" class="input new-description text-sm rounded-md" />
                        <button type="submit" class="btn">📝</button>
                    </form>
                    <a href="{{ route('dashboard') }}">
                        <button class="btn btn-neutral place-self-end">Home</button>
                    </a>
                </div>
        </div>
    </div>
</x-app-layout>