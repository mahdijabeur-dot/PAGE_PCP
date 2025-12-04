<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// -------------------------------
// Connexion SQLite
// -------------------------------
$db_path = __DIR__ . "/../pcp_referentiel.db"; // mettez votre fichier ici
$db = new PDO("sqlite:" . $db_path);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Action demandée
$action = $_GET["action"] ?? "";

// -------------------------------
// LISTE DES PCP
// GET api/pcp.php?action=list
// -------------------------------
if ($action === "list") {
    $sql = "SELECT id, processus, description, frequence FROM pcp ORDER BY id DESC";
    $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

// -------------------------------
// GET UN PCP
// GET api/pcp.php?action=get&id=1
// -------------------------------
if ($action === "get") {
    $id = intval($_GET["id"] ?? 0);
    $sql = "SELECT * FROM pcp WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    exit;
}

// -------------------------------
// CREATE
// POST api/pcp.php?action=create
// -------------------------------
if ($action === "create") {

    $sql = "INSERT INTO pcp (processus, description, frequence)
            VALUES (:p, :d, :f)";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":p" => $_POST["processus"] ?? "",
        ":d" => $_POST["description"] ?? "",
        ":f" => $_POST["frequence"] ?? "",
    ]);

    echo json_encode(["status" => "success"]);
    exit;
}

// -------------------------------
// UPDATE
// POST api/pcp.php?action=update&id=1
// -------------------------------
if ($action === "update") {

    $id = intval($_GET["id"] ?? 0);

    $sql = "UPDATE pcp
            SET processus = :p,
                description = :d,
                frequence = :f
            WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ":p"  => $_POST["processus"] ?? "",
        ":d"  => $_POST["description"] ?? "",
        ":f"  => $_POST["frequence"] ?? "",
        ":id" => $id
    ]);

    echo json_encode(["status" => "updated"]);
    exit;
}

// -------------------------------
// DELETE
// GET api/pcp.php?action=delete&id=1
// -------------------------------
if ($action === "delete") {
    $id = intval($_GET["id"] ?? 0);

    $stmt = $db->prepare("DELETE FROM pcp WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["status" => "deleted"]);
    exit;
}


// -------------------------------
// ACTION INCONNUE
// -------------------------------
echo json_encode(["error" => "action not found"]);
exit;
?>
