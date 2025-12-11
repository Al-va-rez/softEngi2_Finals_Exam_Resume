<?php
header("Content-Type: application/json");

require_once "dynamic/backend/dbConfig.php";

try {

    // ========== FETCH FUNCTIONS ==========
    function fetchAll($pdo, $query) {
        return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========== ABOUT ME ==========
    $aboutMe = fetchAll($pdo, "SELECT * FROM aboutme ORDER BY id DESC LIMIT 1");

    // ========== TECH SKILLS ==========
    $techSkills = fetchAll($pdo, "SELECT id, name, level FROM techskills ORDER BY name");

    // ========== PROJECTS ==========
    $projects = fetchAll($pdo, "
        SELECT id, img_src, title, category, description, github_link
        FROM projects
        ORDER BY date_added DESC
    ");

    // ========== EDUCATION ==========
    $education = fetchAll($pdo, "
        SELECT id, school_name, year_start, year_end, description
        FROM education
        ORDER BY year_start DESC
    ");

    // ========== CERTIFICATES ==========
    $certificates = fetchAll($pdo, "
        SELECT id, img_src, title, year_obtained
        FROM certificates
        ORDER BY year_obtained DESC
    ");

    // ========== COMBINE ALL CONTENT ==========
    $fullExport = [
        "aboutMe"     => $aboutMe ? $aboutMe[0] : null,
        "techSkills"  => $techSkills,
        "projects"    => $projects,
        "education"   => $education,
        "certificates"=> $certificates
    ];

    // ========== EXPORT TO STATIC FOLDER ==========
    $jsonFile = "static/load_content.json";
    file_put_contents($jsonFile, json_encode($fullExport, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    echo json_encode([
        "success" => true,
        "message" => "Content exported successfully!",
        "file" => "static/load_content.json"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Export error: " . $e->getMessage()
    ]);
}
?>