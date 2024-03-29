<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    # index() - view the Admin: Posts Page
    public function index()
    {
        $all_posts = $this->post->withTrashed()->latest()->paginate(5);

        return view('admin.posts.index')
            ->with('all_posts', $all_posts);
    }

    # hide() - soft delete the post
    public function hide($id)
    {
        $this->post->destroy($id);

        return redirect()->back();
    }

    # unhide()
    public function unhide($id)
    {
        $this->post->onlyTrashed()->findOrFail($id)->restore();

        return redirect()->back();
    }
}
