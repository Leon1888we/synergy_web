php namespace App\Http\Controllers;

use App\Models\Post; use App\Models\Tag; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;

class PostController extends Controller { public function index() { $posts = Post::public() ->with(['user', 'tags', 'comments.user']) ->latest() ->paginate(10);

    $tags = Tag::all();

    return view('posts.index', compact('posts', 'tags'));
}

public function create()
{
    $tags = Tag::all();
    return view('posts.create', compact('tags'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'is_public' => 'sometimes|boolean',
        'is_request_only' => 'sometimes|boolean',
        'tags' => 'sometimes|array',
        'tags.*' => 'exists:tags,id'
    ]);

    $post = Auth::user()->posts()->create($validated);

    if ($request->has('tags')) {
        $post->tags()->sync($request->tags);
    }

    return redirect()->route('posts.show', $post)->with('success', 'Post created successfully!');
}

public function show(Post $post)
{
    if (!$post->is_public && $post->user_id !== Auth::id()) {
        if ($post->is_request_only && !$post->view_requests()->where('user_id', Auth::id())->exists()) {
            abort(403, 'This post is available by request only');
        }
    }

    return view('posts.show', [
        'post' => $post->load(['user', 'tags', 'comments.user'])
    ]);
}

public function edit(Post $post)
{
    $this->authorize('update', $post);
    $tags = Tag::all();
    return view('posts.edit', compact('post', 'tags'));
}

public function update(Request $request, Post $post)
{
    $this->authorize('update', $post);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'is_public' => 'sometimes|boolean',
        'is_request_only' => 'sometimes|boolean',
        'tags' => 'sometimes|array',
        'tags.*' => 'exists:tags,id'
    ]);

    $post->update($validated);

    if ($request->has('tags')) {
        $post->tags()->sync($request->tags);
    }

    return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
}

public function destroy(Post $post)
{
    $this->authorize('delete', $post);
    $post->delete();
    return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
}

public function feed()
{
    $subscriptions = Auth::user()->subscriptions()->pluck('target_id');
    $posts = Post::whereIn('user_id', $subscriptions)
        ->with(['user', 'tags', 'comments.user'])
        ->latest()
        ->paginate(10);

    return view('posts.feed', compact('posts'));
}

public function requestView(Post $post)
{
    if ($post->is_request_only && $post->user_id !== Auth::id()) {
        $post->view_requests()->firstOrCreate([
            'user_id' => Auth::id()
        ]);
        
        return back()->with('success', 'View request sent to author');
    }

    return back();
}

}