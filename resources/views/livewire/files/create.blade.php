<?php

use App\Jobs\ProcessProduct;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public bool $errors = false;
    public string $errorMessages = '';
    public bool $uploaded = false;
    public bool $uploading = false;
    public int $progress = 0;

    #[Rule('required|file|mimes:csv|max:102400')]
    public $file;

    public function setErrors(): void
    {
        $this->errors = false;
        $this->errorMessages = '';
    }

    public function setUploaded(): void
    {
        $this->uploaded = ! $this->uploaded;
        $this->uploading = ! $this->uploading;
    }

    public function setUploading(bool $state): void
    {
        $this->uploading = $state;
    }

    public function store(): void
    {
        $validated = $this->validate();
        DB::beginTransaction();
        try {
            $requiredHeader = (new \App\Models\Product)->getCsvHeader();
            $csv = \App\Helper\csvHelper::getCsvDetail($this->file, array_values($requiredHeader));
            if ($csv['status'] === 'error') {
                $this->errors = true;
                $this->errorMessages = $csv['message'];
                $this->resetState();
                return;
            }
            $input['user_id'] = auth()->id();
            $input['file'] = $this->file->store('file');
            $input['row_count'] = $csv['data']['rows'];
            $input['row_processed'] = 0;
            $input['status'] = 0;
            $fileModel = File::create($input);
            ProcessProduct::dispatch($csv['data']['data'], $fileModel->id);
            $this->resetState();
            $this->dispatch('file-created');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function resetState(): void
    {
        $this->file = null;
        $this->uploading = false;
        $this->uploaded = false;
    }
};

?>

<div>
    <div x-show="$wire.errors" class="bg-red-500 text-white p-2 mb-3">
        {{ $errorMessages }}
        <a href="#" class="float-right" wire:click="setErrors(false)">X</a>
    </div>
    <form wire:submit="store">
        <div
            class="px-3"
            x-on:livewire-upload-start="$wire.setUploading(true)"
            x-on:livewire-upload-finish="$wire.setUploaded()"
            x-on:livewire-upload-error="$wire.setUploading(false)"
            x-on:livewire-upload-progress="$wire.progress = $event.detail.progress"
        >
            <label for="file-upload">
                <input type="file" wire:model="file" id="file-upload"/>

                <x-primary-button x-show="$wire.uploaded" wire:loading.remove class="float-right">{{ __('Submit file') }}</x-primary-button>
                <div x-show="$wire.uploaded" class="float-right" wire:loading>
                    Submitting file...
                </div>
            </label>
            <div class="mt-3" x-show="$wire.uploading">
                <progress max="100" x-bind:value="$wire.progress"></progress> Uploading...
            </div>
        </div>
    </form>
</div>
