<?php
if($_GET['key'] == 'sfhsafjnasf;ashjf;aspiofsadpihsadnf'){


    header("Content-Type: application/json; charset=utf-8");

    $page = shell_exec("phantomjs " .  $_SERVER['DOCUMENT_ROOT'] ."/bot/parser.js " . $_REQUEST["link"]);

    $productData = json_decode(explode(";\n    window.__YOULA_TEST__ = {", explode('window.__YOULA_STATE__ = ', $page)[1])[0], true);

    if (!$productData){
        http_response_code(500);

        die(json_encode([
            "status" => "error"
        ], JSON_UNESCAPED_UNICODE));
    }

    $id = $productData["entities"]["products"][0]["id"];
    $name = $productData["entities"]["products"][0]["name"];
    $price = substr($productData["entities"]["products"][0]["price"], 0, strlen($productData["entities"]["products"][0]["price"]) - 2);
    $image = $productData["entities"]["products"][0]["images"][0]["urlForSize"];
    $sellerName = $productData["entities"]["products"][0]["contractor"]["name"];
    $sellerImage = $productData["entities"]["products"][0]["contractor"]["image"]["urlForSize"];

    echo json_encode([
        "status" => "ok",
        "item" => [
            "id" => $id,
            "name" => $name,
            "price" => $price,
            "image" => $image
        ],
        "seller" => [
            "name" => $sellerName,
            "image" => $sellerImage
        ]
    ], JSON_UNESCAPED_UNICODE);
}
?>