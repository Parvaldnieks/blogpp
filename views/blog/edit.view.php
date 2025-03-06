<h1>Labot ierakstu</h1>
<form action="/post/<?= $post["id"] ?>" method="POST">
    <input type="hidden" name="_method" value="PATCH">
    <textarea name="content" required><?= htmlspecialchars($post["content"] ?? "") ?></textarea>
    <button type="submit">Saglabāt</button>
</form>
<a href="/">Atpakaļ</a>