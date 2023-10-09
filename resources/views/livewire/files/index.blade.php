<?php

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

new class extends Component
{
    public Collection $files;

    public function mount(): void
    {
        $this->files = $this->getFiles();
    }

    #[On('file-created')]
    public function index(): void
    {
        $this->files = $this->getFiles();
    }

    protected function getFiles(): Collection
    {
        return File::with('user')
            ->latest()
            ->get();
    }

    public function downloadFile($filePath): StreamedResponse
    {
        return Storage::download($filePath);
    }
};

?>

<div>
    <table class="w-full">
        <thead class="bg-indigo-500 text-white">
            <tr class="border-x-2 border-indigo-500">
                <th class="p-3">Time</th>
                <th class="p-3">File Name</th>
                <th class="p-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($files as $file)
                <tr class="border-b-2 border-black">
                    <td class="border-x-2 border-black p-3">{{ $file->created_at }}<br/>({{ $file->uploaded_time_counter }})</td>
                    <td class="border-x-2 border-black p-3"><a href="#" class="underline text-indigo-700" wire:click="downloadFile('{{ $file->file }}')">{{ $file->file }}</a></td>

                    {{--This will use for updating progress bar in status--}}
                    {{--<td>{{ $file->status === 1 ? $file->row_processed.'/'.$file->row_total : $file->status }}</td>--}}


                    <td class="border-x-2 border-black p-3 text-center">{{ $file->status_label }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
