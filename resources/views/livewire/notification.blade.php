<?php

use App\Jobs\ProcessProduct;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    public bool $notifyUser = false;
    public array $notifyData = [];

    public function mount(): void
    {
        $this->notifyUser = false;
        $this->notifyData = [];
    }

    public function getListeners(): array
    {
        $authId = auth()->id();
        return [
            "echo-private:files,FileStatusUpdated" => 'sendNotify',
        ];
    }

    public function sendNotify(mixed $notify): void
    {
        $this->notifyUser = true;
        $this->notifyData = $notify;
        $this->dispatch('notification-update', $notify);
        $this->dispatch($this->notifyData['module'] . '-created');
    }

    #[On('notification-update')]
    public function updateNotification($data): void
    {
        $this->notifyUser = true;
        $this->notifyData = $data;
    }

    public function closeNotification(): void
    {
        $this->notifyUser = false;
        $this->notifyData = [];
        $this->dispatch('notification-update', $notify);
    }
};

?>

<div>
    <div x-show="$wire.notifyUser" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-2">
        <div class="bg-gray-700 text-white p-2">
            @if ($notifyData)
                Your file [<strong>{{ $notifyData['data']['file'] }}</strong>] status has been updated
            @endif
            <a href="#" class="float-right" wire:click="notifyUser = false">X</a>
        </div>
    </div>
</div>
