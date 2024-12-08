<?php
$number = 1;
if (!defined('SECURE_ACCESS')) {
   die('Direct access not permitted');
}
?>
    <title>Document</title>
    <link rel="stylesheet" href="/views/style.css">

    <h1>BOOK PAGE</h1>
    <form method="GET" class="d-flex justify-content-beetween align">
        <input type="text" class="form-control" id="search" placeholder="seacrh for...." name="find" required />
        <input type="submit" value="GOOOOOOOOOOOOO" id="submit">
    </form>
    <div class="table table-responsive my-4">
        <table width="100%">
            <thead>
                <tr>
                    <th>No </th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data as $book) : ?>
                <tr>
                    <th><?= $number++ ?></th>
                    <th><?= $book->getTitle() ?></th>
                    <th><?= $book->getAuthor() ?></th>
                    <th><?= $book->getYear() ?></th>
                </tr>
            </tbody>
            <?php endforeach ?>
        </table>

    </div>
</body>
</html>