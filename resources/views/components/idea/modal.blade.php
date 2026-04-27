@php
    use App\IdeaStatus;
    use App\Models\Idea;
@endphp
@props(['idea' => new Idea()])

<x-modal
    name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}"
>
    <form
        x-data="{
            status: @js(old('status', $idea->status->value)),
            newStep: '',
            steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed']))),
            newLink: '',
            links: @js(old('links', $idea->links ?? [])),
            hasImage: false
        }"
        action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
        method="POST"
        x-bind:enctype="hasImage ? 'multipart/form-data' : false"
    >
        @csrf

        @if($idea->exists)
            @method('PATCH')
        @endif

        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter an idea for your title"
                autofocus
                required
                :value="$idea->title"
            />

            <div class="space-y-2">
                <label for="status" class="label">Status</label>

                <div class="flex gap-x-3">
                    @foreach(IdeaStatus::cases() as $status)
                        <button
                            type="button"
                            @click="status = @js($status->value)"
                            data-test="button-status-{{ $status->value }}"
                            class="btn flex-1 h-10"
                            :class="{'btn-outlined': status !== @js($status->value)}"
                        >
                            {{ $status->label() }}
                        </button>
                    @endforeach

                    <input type="hidden" name="status" :value="status" class="input">
                </div>

                <x-form.error name="status"/>
            </div>

            <x-form.field
                label="Description"
                name="description"
                type="textarea"
                placeholder="Describe your idea..."
                :value="$idea->description"
            />

            <div class="space-y-2">
                <label for="image" class="image">Featured image</label>

                @if ($idea->image_path)
                    <div class="space-y-2">
                        <img
                            src="{{ asset('storage/' . $idea->image_path) }}"
                            alt="Featured image"
                            class="w-full h-auto object-cover rounded-lg">

                        <button class="btn btn-outlined h-10 w-full" form="delete-image-form">Remove Image</button>
                    </div>
                @else
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        @change="hasImage = $event.target.files.length > 0"
                    >
                @endif

                <x-form.error name="image"/>
            </div>

            <div>
                <fieldset class="space-y-3">
                    <legend class="legend">Actionable Steps</legend>

                    <template x-for="(step, index) in steps" :key="step.id || index">
                        <div class="flex gap-x-2 items-center">
                            <input
                                :name="`steps[${index}][description]`"
                                x-model="step.description" class="input"
                                readonly
                            >
                            <input
                                type="hidden"
                                :name="`steps[${index}][completed]`"
                                x-model="step.completed ? '1' : '0'"
                                class="input"
                                readonly>

                            <button
                                type="button"
                                aria-label="Remove step"
                                @click="steps.splice(index, 1)"
                                class="form-muted-icon"
                            >
                                <x-icons.close/>
                            </button>
                        </div>
                    </template>

                    <div class="flex gap-x-2 items-center">
                        <input
                            x-model="newStep"
                            id="new-step"
                            data-test="new-step"
                            placeholder="What needs to be done?"
                            class="input flex-1"
                            spellcheck="false"
                        >

                        <button
                            type="button"
                            @click="
                                steps.push({ description: newStep.trim(), completed: false });
                                newStep = '';
                            "
                            :disabled="newStep.trim().length === 0"
                            aria-label="Add new step"
                            data-test="submit-new-step-button"
                            class="form-muted-icon"
                        >
                            <x-icons.close class="rotate-45"/>
                        </button>
                    </div>
                </fieldset>
            </div>


            <div>
                <fieldset class="space-y-3">
                    <legend class="legend">Links</legend>

                    <template x-for="(link, index) in links" :key="link">
                        <div class="flex gap-x-2 items-center">
                            <input name="links[]" x-model="link" class="input" readonly>

                            <button
                                type="button"
                                aria-label="Remove link"
                                @click="links.splice(index, 1)"
                                class="form-muted-icon"
                            >
                                <x-icons.close/>
                            </button>
                        </div>
                    </template>

                    <div class="flex gap-x-2 items-center">
                        <input
                            x-model="newLink"
                            type="url"
                            id="new-link"
                            data-test="new-link"
                            placeholder="http://example.com"
                            autocomplete="url"
                            class="input flex-1"
                            spellcheck="false"
                        >

                        <button
                            type="button"
                            @click="links.push(newLink.trim()); newLink = '';"
                            :disabled="newLink.trim().length === 0"
                            aria-label="Add new link"
                            data-test="submit-new-link-button"
                            class="form-muted-icon"
                        >
                            <x-icons.close class="rotate-45"/>
                        </button>
                    </div>
                </fieldset>
            </div>

            <div class="flex justify-end gap-x-5">
                <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                <button type="submit" class="btn">{{ $idea->exists ? 'Update' : 'Create' }}</button>
            </div>
        </div>
    </form>

    @if ($idea->image_path)
        <form action="{{ route('idea.image.destroy', $idea) }}" method="POST" id="delete-image-form">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>
