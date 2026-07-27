<?php

namespace App\Livewire\Admin;

use App\Models\Faq;
use Livewire\Component;

class FaqManager extends Component
{
    public ?int $editingId = null;

    public string $question = '';

    public string $answer = '';

    public string $category = '';

    public bool $isActive = true;

    protected function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:3000'],
            'category' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('faqs.manage'), 403);
    }

    public function openCreate(): void
    {
        $this->authorizeManage();

        $this->reset(['editingId', 'question', 'answer', 'category']);
        $this->isActive = true;
        $this->dispatch('open-faq-modal');
    }

    public function edit(int $id): void
    {
        $faq = Faq::findOrFail($id);

        $this->editingId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->category = $faq->category ?? '';
        $this->isActive = $faq->is_active;

        $this->dispatch('open-faq-modal');
    }

    public function save(): void
    {
        $this->authorizeManage();
        $this->validate();

        $data = [
            'question' => $this->question,
            'answer' => $this->answer,
            'category' => $this->category ?: null,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            Faq::findOrFail($this->editingId)->update($data);
        } else {
            $data['order'] = Faq::max('order') + 1;
            Faq::create($data);
        }

        $this->dispatch('close-faq-modal');
        session()->flash('status', __('FAQ saved successfully.'));
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        Faq::findOrFail($id)->delete();
        session()->flash('status', __('FAQ removed.'));
    }

    public function reorder(array $orderedIds): void
    {
        $this->authorizeManage();

        foreach ($orderedIds as $index => $id) {
            Faq::where('id', $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.faq-manager', [
            'faqs' => Faq::orderBy('order')->get(),
        ]);
    }
}
