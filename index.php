<?php

function fetch_website_content($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode(['error' => 'cURL error: ' . curl_error($ch)]);
        return false;
    }
    curl_close($ch);
    return $response;
}

function parse_html($html) {
    if (!$html) {
        return ['error' => 'HTML source is empty'];
    }
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    $result = [];
    $category_containers = $xpath->query("//div[contains(@class, 'product-container')]");

    foreach ($category_containers as $container) {
        $category_title = $xpath->query(".//h2", $container)->item(0)->nodeValue;
        $products = [];
        $product_elements = $xpath->query(".//li[contains(@class, 'glide__slide')]", $container);

        foreach ($product_elements as $product) {
            $name = $xpath->query(".//div[@class='name']", $product)->item(0)->nodeValue;

            $price_new = $xpath->query(".//span[@class='price-new']", $product);
            $price_old = $xpath->query(".//span[@class='price-old']", $product);
            $price_single = $xpath->query(".//div[@class='price']", $product);

            if ($price_new->length > 0 && $price_old->length > 0) {
                $new_price = floatval(str_replace(['€', ','], ['', '.'], $price_new->item(0)->nodeValue));
                $old_price = floatval(str_replace(['€', ','], ['', '.'], $price_old->item(0)->nodeValue));
                $discount_percentage = round((($old_price - $new_price) / $old_price) * 100);
                $products[] = [
                    'name' => trim($name),
                    'price' => number_format($new_price, 2) . ' €',
                    'old_price' => number_format($old_price, 2) . ' €',
                    'discount' => $discount_percentage . '%'
                ];
            } elseif ($price_single->length > 0) {
                $single_price = trim($price_single->item(0)->nodeValue);

                $single_price = str_replace(['â', '¬'], '', $single_price);

                $products[] = [
                    'name' => trim($name),
                    'price' => $single_price . '€',
                    'old_price' => '',
                    'discount' => 'Ei ole allahindlust'
                ];
            }
        }
        $result[] = [
            'category' => trim($category_title),
            'products' => $products
        ];
    }
    return $result;
}

$data = json_decode(file_get_contents('php://input'), true);
$url = $data['url'] ?? null;

if ($url) {
    $html_content = fetch_website_content($url);
    if ($html_content) {
        $parsed_data = parse_html($html_content);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($parsed_data);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Andmete toomine saidilt nurjus']);
    }
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'URL-i ei saadetud']);
}
exit();

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