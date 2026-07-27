<?php

namespace App\Livewire\Admin;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientManager extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $name = '';

    public string $websiteUrl = '';

    public bool $isActive = true;

    public $logo;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'websiteUrl' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('clients.manage'), 403);
    }

    public function openCreate(): void
    {
        $this->authorizeManage();

        $this->reset(['editingId', 'name', 'websiteUrl', 'logo']);
        $this->isActive = true;
        $this->dispatch('open-client-modal');
    }

    public function edit(int $id): void
    {
        $client = Client::findOrFail($id);

        $this->editingId = $client->id;
        $this->name = $client->name;
        $this->websiteUrl = $client->website_url ?? '';
        $this->isActive = $client->is_active;
        $this->logo = null;

        $this->dispatch('open-client-modal');
    }

    public function save(): void
    {
        $this->authorizeManage();
        $this->validate();

        $data = [
            'name' => $this->name,
            'website_url' => $this->websiteUrl ?: null,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            $client = Client::findOrFail($this->editingId);
            $client->update($data);
        } else {
            $data['order'] = Client::max('order') + 1;
            $client = Client::create($data);
        }

        if ($this->logo) {
            $client->addMedia($this->logo->getRealPath())
                ->usingFileName($this->logo->getClientOriginalName())
                ->toMediaCollection('logo');
        }

        $this->dispatch('close-client-modal');
        session()->flash('status', __('Client saved successfully.'));
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        Client::findOrFail($id)->delete();
        session()->flash('status', __('Client removed.'));
    }

    public function reorder(array $orderedIds): void
    {
        $this->authorizeManage();

        foreach ($orderedIds as $index => $id) {
            Client::where('id', $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.client-manager', [
            'clients' => Client::orderBy('order')->get(),
        ]);
    }
}
