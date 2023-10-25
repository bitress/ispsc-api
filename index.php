<?php

$filename = 'data.json';

// Read data from the JSON file
function readData($filename) {
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        return json_decode($data, true);
    }
    return [];
}

// Write data to the JSON file
function writeData($filename, $data) {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($filename, $jsonData);
}

// Create a new record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = readData($filename);
    $newRecord = [
        'id' => uniqid(),
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'date' => $_POST['date']
    ];
    $data[] = $newRecord;
    writeData($filename, $data);
}

// Read all records
$data = readData($filename);

// Update a record
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $newTitle = $_POST['newTitle'];
    $newContent = $_POST['newContent'];
    $newDate = $_POST['newDate'];
    foreach ($data as &$record) {
        if ($record['id'] == $id) {
            $record['title'] = $newTitle;
            $record['content'] = $newContent;
            $record['date'] = $newDate;
        }
    }
    writeData($filename, $data);
}

// Delete a record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    foreach ($data as $key => $record) {
        if ($record['id'] == $id) {
            unset($data[$key]);
        }
    }
    writeData($filename, $data);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD with JSON and Bootstrap</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="display-4">ISPSC Website</h1>

        <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Create</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="Announcement Title" required>
                        </div>
                        <div class="form-group">
                            <textarea rows="5" name="content" class="form-control" placeholder="Announcement Content" required></textarea>
                        </div>
                        <div class="form-group">
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">

        <ul class="list-group">
    <?php foreach ($data as $record): ?>
        <li class="list-group-item">
            <div class="row">
                <div class="col-8">
                    <h4><?php echo $record['title']; ?></h4>
                    <p><?php echo $record['content']; ?></p>
                    <p>Date: <?php echo $record['date']; ?></p>
                </div>
                <div class="col-4 text-right">
                    <a href="?delete=<?php echo $record['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </div>
            </div>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
                <div class="form-group">
                    <input type="text" name="newTitle" class="form-control" placeholder="New Title" required>
                </div>
                <div class="form-group">
                    <textarea rows="5" name="newContent" class="form-control" placeholder="New Content" required></textarea>
                </div>
                <div class="form-group">
                    <input type="date" name="newDate" class="form-control" required>
                </div>
                <button type="submit" name="update" class="btn btn-success btn-sm">Update</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>

    </div>

        </div>

        </div>

</body>
</html>