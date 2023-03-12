<?php

namespace App\Http\Livewire\Admin;

use App\Enums\Core\CommKey;
use App\Enums\Core\ThreadType;
use App\Enums\Files\FileType;
use App\Models\LOFile;
use App\Models\Thread;
use App\Models\ThreadComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class ThreadComponent extends Component
{
    use WithFileUploads;

    /**
     * What are we working with?
     * @var Model
     */
    public Model  $object;
    public ?Thread $thread;
    public bool   $uploadVisible   = false;
    public int    $commentReply    = 0;
    public string $newComment      = '';
    public string $newCommentReply = '';
    public        $file;

    public array $rules = [
        'newComment' => 'required'
    ];

    /**
     * Take the object given and get thread and comments.
     * @return void
     */
    public function mount(): void
    {
        $type = ThreadType::getByModel($this->object::class);
        $this->thread = Thread::where('type', $type->value)->where('refid', $this->object->id)->first();
        if (!$this->thread || !$this->thread->id)
        {
            $this->thread = (new Thread)->create([
                'type'    => $type->value,
                'refid'   => $this->object->id,
                'user_id' => 0
            ]);
        }
        if (!$this->thread || !$this->thread->user_id)
        {
            $this->thread->update(['user_id' => user() ? user()->id : 0]);
        }
    }

    public function poll(): void
    {

    }


    /**
     * Save photo in LO File
     * @return int
     */
    public function saveFile(): int
    {
        $location = "projects";
        $name = $this->file->getClientOriginalName();
        $ext = explode(".", $name);
        if (!isset($ext[1])) $ext[1] = '.unk';               // Don't crash, call this an unknown file ext.
        $real = sprintf("%s.%s", uniqid("LO-"), $ext[1]);    /// 1235h123.pdf
        Storage::disk('local')->putFileAs($location, $this->file, $real);
        $lo = (new LOFile)->create(
            [
                'hash'          => uniqid(),
                'filename'      => $name,
                'real'          => $real,
                'description'   => "Created " . now()->toDayDateTimeString(),
                'location'      => $location,
                'type'          => FileType::Image,
                'ref_id'        => $this->object->id,
                'mime_type'     => $this->file->getMimeType(),
                'filesize'      => $this->file->getSize(),
                'account_id'    => 0,
                'auth_required' => 0
            ]
        );
        _log($lo, 'File has been uploaded');
        CommKey::GlobalFiles->clear();
        return $lo->id;
    }

    /**
     * Toggle Upload Visibility
     * @return void
     */
    public function toggleUpload(): void
    {
        $this->uploadVisible = !$this->uploadVisible;
    }

    /**
     * Toggle Comment Reply Visibility
     * @param int $cid
     * @return void
     */
    public function toggleCommentReply(int $cid): void
    {
        if ($this->commentReply == $cid)
        {
            $this->commentReply = 0;
        }
        else $this->commentReply = $cid;
    }


    /**
     * Add a new root comment to the thread
     * @return void
     */
    public function addRootComment(): void
    {
        if (!$this->newComment) return;
        $comment = $this->thread->comments()->create([
            'thread_comment_id' => null,
            'user_id'           => user() ? user()->id : 0,
            'comment'           => $this->newComment,
            'public'            => true
        ]);
        $this->newComment = '';
        $this->uploadVisible = false;
        $this->handleUpload($comment);
    }

    public function addCommentReply(int $cid): void
    {
        if (!$this->newCommentReply) return;
        $comment = $this->thread->comments()->create([
            'thread_comment_id' => $cid,
            'user_id'           => user() ? user()->id : 0,
            'comment'           => $this->newCommentReply,
            'public'            => true
        ]);
        $this->newCommentReply = '';
        $this->commentReply = 0;
        $this->uploadVisible = false;
        $this->handleUpload($comment);
    }

    public function render(): View
    {
        $this->emit('initDrop');
        return view('admin.partials.core.thread');
    }

    /**
     * Handle Upload
     * @param ThreadComment $comment
     * @return void
     */
    private function handleUpload(ThreadComment $comment): void
    {
        if (!$this->file) return;
        $id = $this->saveFile();
        $comment->files()->create([
            'comment_id' => $comment->id,
            'thread_id'  => $this->thread->id,
            'user_id'    => user()->id,
            'file_id'    => $id
        ]);
        $this->file = null;
    }

}
