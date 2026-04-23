<?php
require_once __DIR__ . '/../../Models/dbConnect.php';

// Set headers for JSON response and CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

try {
    $database = new Database();
    $conn = $database->conn;

    // total users - count only approved users
    $resultUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE approved = 1");
    if (!$resultUsers) {
        throw new Exception("Error counting users: " . $conn->error);
    }
    $totalUsers = $resultUsers->fetch_assoc()['total'];

    // total leave requests - count only approved leaves
    $resultLeaves = $conn->query("SELECT COUNT(*) AS total FROM leaves WHERE status = 'approved'");
    if (!$resultLeaves) {
        throw new Exception("Error counting leaves: " . $conn->error);
    }
    $totalLeaves = $resultLeaves->fetch_assoc()['total'];

    // celebrations - retrieve from celebrations table dynamically
    $celebrations = [];
    
    // Get current date and date 30 days from now for filtering upcoming celebrations
    $today = date('Y-m-d');
    $futureDate = date('Y-m-d', strtotime('+30 days'));
    
    // Check what columns exist in the celebrations table
    $columnsResult = $conn->query("SHOW COLUMNS FROM celebrations");
    $columns = [];
    if ($columnsResult) {
        while ($col = $columnsResult->fetch_assoc()) {
            $columns[] = $col['Field'];
        }
    }
    
    // Build query based on available columns
    $hasCelebrationDate = in_array('celebration_date', $columns) || in_array('date', $columns);
    $hasCelebrationType = in_array('celebration_type', $columns) || in_array('type', $columns);
    $hasUserName = in_array('name', $columns) || in_array('username', $columns) || in_array('user_name', $columns);
    $hasMessage = in_array('message', $columns);
    
    // Determine field names
    $nameField = in_array('name', $columns) ? 'name' : (in_array('username', $columns) ? 'username' : (in_array('user_name', $columns) ? 'user_name' : null));
    $typeField = in_array('celebration_type', $columns) ? 'celebration_type' : (in_array('type', $columns) ? 'type' : null);
    $dateField = in_array('celebration_date', $columns) ? 'celebration_date' : (in_array('date', $columns) ? 'date' : null);
    
    // Build the SELECT query dynamically
    $selectFields = [];
    if ($hasUserName && $nameField) {
        $selectFields[] = "$nameField AS name";
    } elseif ($hasMessage) {
        // Try to extract name from message if it's in format "Name|Type|Date|Message"
        $selectFields[] = "SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 1), '|', -1) AS name";
    } else {
        $selectFields[] = "'Celebration' AS name";
    }
    
    if ($hasCelebrationType && $typeField) {
        $selectFields[] = "$typeField AS type";
    } elseif ($hasMessage) {
        // Try to extract type from message
        $selectFields[] = "SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 2), '|', -1) AS type";
    } else {
        $selectFields[] = "'Reminder' AS type";
    }
    
    if ($hasCelebrationDate && $dateField) {
        $selectFields[] = "DATE_FORMAT($dateField, '%M %d') AS date";
        $selectFields[] = "$dateField AS full_date";
    } elseif ($hasMessage) {
        // Try to extract date from message
        $selectFields[] = "DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%M %d') AS date";
        $selectFields[] = "STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d') AS full_date";
    } elseif (in_array('created_at', $columns)) {
        $selectFields[] = "DATE_FORMAT(created_at, '%M %d') AS date";
        $selectFields[] = "DATE(created_at) AS full_date";
    } else {
        $selectFields[] = "DATE_FORMAT(NOW(), '%M %d') AS date";
        $selectFields[] = "CURDATE() AS full_date";
    }
    
    $selectClause = implode(', ', $selectFields);
    
    // Build WHERE clause for upcoming celebrations
    $whereClause = "status = 'active'";
    if ($hasCelebrationDate && $dateField) {
        $whereClause .= " AND (
            DATE_FORMAT($dateField, '%m-%d') >= DATE_FORMAT('$today', '%m-%d')
            AND DATE_FORMAT($dateField, '%m-%d') <= DATE_FORMAT('$futureDate', '%m-%d')
            OR DATE_FORMAT($dateField, '%m-%d') < DATE_FORMAT('$today', '%m-%d')
            AND DATE_FORMAT($dateField, '%m-%d') <= DATE_FORMAT('$futureDate', '%m-%d')
        )";
    } elseif ($hasMessage) {
        // Filter by date extracted from message
        $whereClause .= " AND message LIKE '%|%|%' 
            AND STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d') IS NOT NULL
            AND (
                DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%m-%d') >= DATE_FORMAT('$today', '%m-%d')
                AND DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%m-%d') <= DATE_FORMAT('$futureDate', '%m-%d')
                OR DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%m-%d') < DATE_FORMAT('$today', '%m-%d')
                AND DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%m-%d') <= DATE_FORMAT('$futureDate', '%m-%d')
            )";
    } elseif (in_array('created_at', $columns)) {
        // If no celebration_date, use created_at for recent celebrations
        $whereClause .= " AND created_at >= DATE_SUB('$today', INTERVAL 30 DAY)";
    }
    
    // Build ORDER BY clause
    $orderClause = "";
    if ($hasCelebrationDate && $dateField) {
        $orderClause = "ORDER BY 
            CASE 
                WHEN DATE_FORMAT($dateField, '%m-%d') >= DATE_FORMAT('$today', '%m-%d') 
                THEN 0 
                ELSE 1 
            END,
            DATE_FORMAT($dateField, '%m-%d')
        ";
    } elseif ($hasMessage) {
        $orderClause = "ORDER BY 
            CASE 
                WHEN DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%m-%d') >= DATE_FORMAT('$today', '%m-%d') 
                THEN 0 
                ELSE 1 
            END,
            DATE_FORMAT(STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(message, '|', 3), '|', -1), '%Y-%m-%d'), '%m-%d')
        ";
    } elseif (in_array('created_at', $columns)) {
        $orderClause = "ORDER BY created_at DESC";
    } else {
        $orderClause = "ORDER BY id DESC";
    }
    
    // Execute the query
    $query = "SELECT $selectClause FROM celebrations WHERE $whereClause $orderClause LIMIT 10";
    
    $resultCelebrations = $conn->query($query);
    
    if ($resultCelebrations) {
        if ($resultCelebrations->num_rows > 0) {
            while ($row = $resultCelebrations->fetch_assoc()) {
                $celebrations[] = [
                    'name' => $row['name'] ?? 'Celebration',
                    'type' => $row['type'] ?? 'Reminder',
                    'date' => $row['date'] ?? date('M d')
                ];
            }
        }
    } else {
        // If query fails, try a simpler fallback query
        $fallbackQuery = "SELECT id, message, created_at FROM celebrations WHERE status = 'active' ORDER BY created_at DESC LIMIT 10";
        $fallbackResult = $conn->query($fallbackQuery);
        if ($fallbackResult && $fallbackResult->num_rows > 0) {
            while ($row = $fallbackResult->fetch_assoc()) {
                // Try to parse message if it's in format "Name|Type|Date|Message"
                $messageParts = explode('|', $row['message']);
                if (count($messageParts) >= 3) {
                    $celebrations[] = [
                        'name' => $messageParts[0],
                        'type' => $messageParts[1],
                        'date' => date('M d', strtotime($messageParts[2]))
                    ];
                } else {
                    $celebrations[] = [
                        'name' => substr($row['message'], 0, 30) . (strlen($row['message']) > 30 ? '...' : ''),
                        'type' => 'Celebration',
                        'date' => date('M d', strtotime($row['created_at']))
                    ];
                }
            }
        }
    }

    echo json_encode([
        "totalUsers" => (int)$totalUsers,
        "totalLeaves" => (int)$totalLeaves,
        "celebrations" => $celebrations
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => $e->getMessage(),
        "totalUsers" => 0,
        "totalLeaves" => 0,
        "celebrations" => []
    ]);
}
