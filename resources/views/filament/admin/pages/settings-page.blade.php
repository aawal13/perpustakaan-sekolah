<x-filament-panels::page>
    <div class="flex flex-col gap-6">
        {{ $this->form }}
    </div>
    <div class="flex justify-start pt-6">
        <x-filament::button wire:click="create" color="primary">
            Simpan
        </x-filament::button>

    </div>
</x-filament-panels::page>
