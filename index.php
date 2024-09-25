<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/crawl') {
    $input = json_decode(file_get_contents('php://input'), true); // Here get the request body that the frontend sent

    if (isset($input['url'])) {
        $url = $input['url'];

        $mockData = [
            'products' => [
                ['name' => 'Toode 1', 'price' => '11.89€'],
                ['name' => 'Toode 2', 'price' => '29.99€'],
                ['name' => 'Toode 3', 'price' => '35.00€'],
                ['name' => 'Toode 4', 'price' => '8.19€'],
            ]
        ];

        header('Content-Type: application/json');
        echo json_encode($mockData);
    } else {
        header('Content-Type: application/json', true, 400);
        echo json_encode(['error' => 'URL puudub']);
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="et">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Veebikaapimise rakendus</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Veebilehe analüüsimine</h1>

        <form id="crawlForm">
            <label for="urlInput">Sisesta URL:</label>
            <input type="text" id="urlInput" name="url" placeholder="https://example.com" required>
            <button type="submit">Alusta analüüsi</button>
        </form>

        <div id="results"></div>

        <script src="script.js"></script>
    </body>
</html>