@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-4 py-4">
        <div class="flex justify-between items-center w-full">
            <h2 class="text-grey text-sm font-normal">My Projects</h2>
            <a class="button" @click.prevent="$modal.show('new-project')">New Project</a>
        </div>
    </header>

    <main class="lg:flex flex-wrap -mx-3">
        @forelse($projects as $project)
            <div class="lg:w-1/3 px-3 pb-6">
                @include ('projects.card')
            </div>
        @empty
            <div>No projects yet.</div>
        @endforelse
    </main>

    <new-project-modal></new-project-modal>
@endsection
