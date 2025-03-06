<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi bloga ieraksti</title>
</head>
<body>
    <h1>Visi bloga ieraksti</h1>
    <a href="/post/create">Pievienot jaunu ierakstu</a>
    
    <?php foreach ($posts as $post) : ?>
        <p><?= htmlspecialchars($post["content"]) ?></p>
        <a href="/post/<?= $post["id"] ?>">Skatīt</a> |
        <a href="/post/<?= $post["id"] ?>/edit">Labot</a> |
        <form action="/post/<?= $post["id"] ?>" method="POST" style="display:inline;">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit">Dzēst</button>
        </form>
    <?php endforeach; ?>
</body>
</html>