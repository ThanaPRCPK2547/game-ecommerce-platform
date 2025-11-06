<?php
header('Content-Type: application/json');
require_once 'config.php';

// ฟังก์ชันดึงข้อมูลเกมจาก Steam API
function getSteamGames($appids = []) {
    $games = [];
    foreach ($appids as $appid) {
        $url = "https://store.steampowered.com/api/appdetails?appids={$appid}";
        $response = @file_get_contents($url);
        if ($response) {
            $data = json_decode($response, true);
            if ($data[$appid]['success']) {
                $game = $data[$appid]['data'];
                $games[] = [
                    'steam_id' => $appid,
                    'name' => $game['name'],
                    'price' => $game['price_overview']['final_formatted'] ?? 'Free',
                    'description' => substr(strip_tags($game['short_description']), 0, 200),
                    'image' => $game['header_image'],
                    'genres' => implode(', ', array_column($game['genres'] ?? [], 'description'))
                ];
            }
        }
        sleep(1); // หลีกเลี่ยง rate limit
    }
    return $games;
}

// ฟังก์ชันดึงเกมฟรีจาก Epic Games
function getEpicFreeGames() {
    $url = "https://store-site-backend-static.ak.epicgames.com/freeGamesPromotions";
    $response = @file_get_contents($url);
    if (!$response) return [];
    
    $data = json_decode($response, true);
    $games = [];
    
    foreach ($data['data']['Catalog']['searchStore']['elements'] as $game) {
        if ($game['promotions'] && $game['price']['totalPrice']['discountPrice'] == 0) {
            $games[] = [
                'name' => $game['title'],
                'price' => 'Free',
                'description' => $game['description'] ?? 'Epic Games Free Game',
                'image' => $game['keyImages'][0]['url'] ?? '',
                'genres' => 'Free Game'
            ];
        }
    }
    return array_slice($games, 0, 5);
}

// จัดการ API requests
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'steam':
        // เกมยอดนิยมจาก Steam
        $popularGames = [271590, 730, 570, 440, 1085660]; // GTA V, CS:GO, Dota 2, TF2, Destiny 2
        echo json_encode(getSteamGames($popularGames));
        break;
        
    case 'epic':
        echo json_encode(getEpicFreeGames());
        break;
        
    case 'sync_steam':
        // บันทึกเกมจาก Steam ลงฐานข้อมูล
        $games = getSteamGames([271590, 730, 570]); // ตัวอย่าง 3 เกม
        foreach ($games as $game) {
            $stmt = $pdo->prepare("INSERT IGNORE INTO products (name, price, description, image, category) VALUES (?, ?, ?, ?, 'steam')");
            $stmt->execute([$game['name'], $game['price'], $game['description'], $game['image']]);
        }
        echo json_encode(['success' => true, 'message' => 'Synced ' . count($games) . ' games']);
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
}
?>
