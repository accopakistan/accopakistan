<?php

namespace App\Livewire\Admin;

use App\Models\Redirect;
use Livewire\Component;

class RedirectManager extends Component
{
    public ?int $editingId = null;

    public string $fromPath = '';

    public string $toPath = '';

    public int $type = 301;

    public bool $isActive = true;

    protected function rules(): array
    {
        return [
            'fromPath' => ['required', 'string', 'max:255', 'regex:/^\//'],
            'toPath' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:301,302'],
        ];
    }

    protected array $messages = [
        'fromPath.regex' => 'The "From" path must start with a slash, e.g. /old-page.',
    ];

    public function mount(): void
    {
        if ($prefill = request()->query('from')) {
            $this->fromPath = str_starts_with($prefill, '/') ? $prefill : "/{$prefill}";
        }
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('redirects.manage'), 403);
    }

    public function openCreate(): void
    {
        $this->authorizeManage();

        $from = $this->fromPath;
        $this->reset(['editingId', 'toPath']);
        $this->fromPath = $from;
        $this->type = 301;
        $this->isActive = true;
        $this->dispatch('open-redirect-modal');
    }

    public function edit(int $id): void
    {
        $redirect = Redirect::findOrFail($id);

        $this->editingId = $redirect->id;
        $this->fromPath = $redirect->from_path;
        $this->toPath = $redirect->to_path;
        $this->type = $redirect->type;
        $this->isActive = $redirect->is_active;

        $this->dispatch('open-redirect-modal');
    }

    public function save(): void
    {
        $this->authorizeManage();
        $this->validate();

        $data = [
            'from_path' => $this->fromPath,
            'to_path' => $this->toPath,
            'type' => $this->type,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            Redirect::findOrFail($this->editingId)->update($data);
        } else {
            Redirect::updateOrCreate(['from_path' => $data['from_path']], $data);
        }

        $this->dispatch('close-redirect-modal');
        session()->flash('status', __('Redirect saved successfully.'));
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        Redirect::findOrFail($id)->delete();
        session()->flash('status', __('Redirect removed.'));
    }

    public function render()
    {
        return view('livewire.admin.redirect-manager', [
            'redirects' => Redirect::latest()->get(),
        ]);
    }
}
