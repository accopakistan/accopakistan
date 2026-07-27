<?php

namespace App\Livewire\Admin;

use App\Models\TeamMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class TeamMemberManager extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public string $name = '';

    public string $position = '';

    public string $department = '';

    public string $bio = '';

    public string $email = '';

    public string $phone = '';

    public string $linkedinUrl = '';

    public string $twitterUrl = '';

    public bool $isActive = true;

    public $photo;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedinUrl' => ['nullable', 'string', 'max:255'],
            'twitterUrl' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ];
    }

    protected function authorizeManage(): void
    {
        abort_unless(auth()->user()->can('team.manage'), 403);
    }

    public function openCreate(): void
    {
        $this->authorizeManage();

        $this->reset(['editingId', 'name', 'position', 'department', 'bio', 'email', 'phone', 'linkedinUrl', 'twitterUrl', 'photo']);
        $this->isActive = true;
        $this->dispatch('open-member-modal');
    }

    public function edit(int $id): void
    {
        $member = TeamMember::findOrFail($id);

        $this->editingId = $member->id;
        $this->name = $member->name;
        $this->position = $member->position ?? '';
        $this->department = $member->department ?? '';
        $this->bio = $member->bio ?? '';
        $this->email = $member->email ?? '';
        $this->phone = $member->phone ?? '';
        $this->linkedinUrl = $member->linkedin_url ?? '';
        $this->twitterUrl = $member->twitter_url ?? '';
        $this->isActive = $member->is_active;
        $this->photo = null;

        $this->dispatch('open-member-modal');
    }

    public function save(): void
    {
        $this->authorizeManage();
        $this->validate();

        $data = [
            'name' => $this->name,
            'position' => $this->position ?: null,
            'department' => $this->department ?: null,
            'bio' => $this->bio ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'linkedin_url' => $this->linkedinUrl ?: null,
            'twitter_url' => $this->twitterUrl ?: null,
            'is_active' => $this->isActive,
        ];

        if ($this->editingId) {
            $member = TeamMember::findOrFail($this->editingId);
            $member->update($data);
        } else {
            $data['order'] = TeamMember::max('order') + 1;
            $member = TeamMember::create($data);
        }

        if ($this->photo) {
            $member->addMedia($this->photo->getRealPath())
                ->usingFileName($this->photo->getClientOriginalName())
                ->toMediaCollection('photo');
        }

        $this->dispatch('close-member-modal');
        session()->flash('status', __('Team member saved successfully.'));
    }

    public function delete(int $id): void
    {
        $this->authorizeManage();

        TeamMember::findOrFail($id)->delete();
        session()->flash('status', __('Team member removed.'));
    }

    public function reorder(array $orderedIds): void
    {
        $this->authorizeManage();

        foreach ($orderedIds as $index => $id) {
            TeamMember::where('id', $id)->update(['order' => $index]);
        }
    }

    public function render()
    {
        return view('livewire.admin.team-member-manager', [
            'members' => TeamMember::orderBy('order')->get(),
        ]);
    }
}
