<?php

namespace Tests\Unit;

use App\Models\Note;
use App\Models\User;
use App\Policies\NotePolicy;
use PHPUnit\Framework\TestCase;

class NotePolicyTest extends TestCase
{
    private NotePolicy $policy;
    private User $user;
    private Note $note;


    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new NotePolicy();
        $this->user = new User();
        $this->note = new Note();
    }

    public function test_owner_can_view_note(): void
    {
        $this->user->id = 1;
        $this->note->user_id = 1;
        $this->assertTrue($this->policy->view($this->user, $this->note));
    }

    public function test_non_owner_cannot_view_note(): void
    {
        $this->user->id = 2;
        $this->note->user_id = 1;
        $this->assertFalse($this->policy->view($this->user, $this->note));
    }

    public function test_owner_can_update_note(): void
    {
        $this->user->id = 1;
        $this->note->user_id = 1;
        $this->assertTrue($this->policy->update($this->user, $this->note));
    }

    public function test_non_owner_cannot_update_note(): void
    {
        $this->user->id = 2;
        $this->note->user_id = 1;
        $this->assertFalse($this->policy->update($this->user, $this->note));
    }

    public function test_owner_can_delete_note(): void
    {
        $this->user->id = 1;
        $this->note->user_id = 1;
        $this->assertTrue($this->policy->delete($this->user, $this->note));
    }

    public function test_non_owner_cannot_delete_note(): void
    {
        $this->user->id = 2;
        $this->note->user_id = 1;
        $this->assertFalse($this->policy->delete($this->user, $this->note));
    }

}
