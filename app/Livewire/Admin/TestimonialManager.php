<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\Testimonial;
use Livewire\Component;
use Livewire\WithFileUploads;

class TestimonialManager extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $clientName = '';

    public string $clientPosition = '';

    public string $company = '';

    public string $quote = '';

    public ?int $rating = 5;

    public ?int $projectId = null;

    public bool $isActive = true;

    public $photo;

    protected function rules(): array
    {
        return [
            'clientName' => ['required', 'string', 'max:255'],
            'clientPosition' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string', 'max:2000'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'projectId' => ['nullable', 'exists:projects,id'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ];
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('testimonials.manage'), 403);
    }

    public function openCreate(): void
    {
        $this->authorizeManage();

        $this->reset(['editingId', 'clientName', 'clientPosition', 'company', 'quote', 'projectId', 'photo']);
        $this->rating = 5;
        $this->isActive = true;
        $this->dispatch('open-testimonial-modal');
    }

    public function edit(int $id): void
    {
        $testimonial = Testimonial::findOrFail($id);

        $this->editingId = $testimonial->id;
        $this->clientName = $testimonial->client_name;
        $this->clientPosition = $testimonial->client_position ?? '';
        $this->company = $testimonial->company ?? '';
        $this->quote = $testimonial->quote;
        $this->rating = $testimonial->rating;
        $this->projectId = $testimonial->project_id;
        $this->isActive = $testimonial->is_active;
        $this->photo = null;

        $this->dispatch('open-testimonial-modal');
    }

    public function save(): void
    {
        $this->authorizeManage();
        $this->validate();

        $data = [
            'client_name' => $this->clientName,
            'client_position' => $this->clientPosition ?: null,
            'company' => $this->company ?: null,
            'quote' => $this->quote,
            'rating' => $this->rating,
            'project_id' => $this->projectId,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            $testimonial = Testimonial::findOrFail($this->editingId);
            $testimonial->update($data);
        } else {
            $data['order'] = Testimonial::max('order') + 1;
            $testimonial = Testimonial::create($data);
        }

        if ($this->photo) {
            $testimonial->addMedia($this->photo->getRealPath())
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('photo');
        }

        $this->dispatch('close-testimonial-modal');
        session()->flash('status', __('Testimonial saved successfully.'));
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        Testimonial::findOrFail($id)->delete();
        session()->flash('status', __('Testimonial removed.'));
    }

    public function reorder(array $orderedIds): void
    {
        $this->authorizeManage();

        foreach ($orderedIds as $index => $id) {
            Testimonial::where('id', $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.testimonial-manager', [
            'testimonials' => Testimonial::with('project')->orderBy('order')->get(),
            'projects' => Project::orderBy('title')->get(['id', 'title']),
        ]);
    }
}
