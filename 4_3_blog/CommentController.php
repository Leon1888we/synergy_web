php namespace App\Http\Controllers;

use App\Models\Comment; use App\Models\Post; use Illuminate\Http\Request;

class CommentController extends Controller { public function store(Post $post, Request $request) { $validated = $request->validate([ 'content' => 'required|string|max:500' ]);

    $comment = new Comment($validated);  
    $comment->user_id = auth()->id();
    $post->comments()->save($comment);

    return back()->with('success', 'Comment added successfully!');
}

public function destroy(Comment $comment)
{
    $this->authorize('delete', $comment);
    $comment->delete();
    return back()->with('success', 'Comment deleted successfully!');
}

}