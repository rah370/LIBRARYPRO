<?php
// Simple migration/populate script for adding `cover` column and mapping files
$dbPath = __DIR__ . '/../library.db';
$booksDir = __DIR__ . '/../frontend/assets/books';
if (!file_exists($dbPath)) {
    echo "DB not found: $dbPath\n";
    exit(1);
}
if (!is_dir($booksDir)) {
    echo "Books dir not found: $booksDir\n";
    exit(1);
}
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if cover column exists
    $stmt = $pdo->query("PRAGMA table_info(books)");
    $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $hasCover = false;
    foreach ($cols as $c) {
        if (isset($c['name']) && $c['name'] === 'cover') { $hasCover = true; break; }
    }
    if (!$hasCover) {
        echo "Adding cover column...\n";
        $pdo->exec("ALTER TABLE books ADD COLUMN cover VARCHAR(255);");
    } else {
        echo "cover column already exists.\n";
    }

    // Load all books
    $books = [];
    $rows = $pdo->query('SELECT id, title, isbn FROM books')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $books[$r['id']] = $r;
        // normalize
        $books[$r['id']]['isbn_norm'] = preg_replace('/[^a-z0-9]/i', '', strtolower($r['isbn']));
        $books[$r['id']]['title_alnum'] = preg_replace('/[^a-z0-9]/i', '', strtolower($r['title']));
        $books[$r['id']]['title_norm'] = strtolower(trim(preg_replace('/[^a-z0-9]+/i',' ', $r['title'])));
    }

    // Scan files
    $files = glob($booksDir . '/*');
    $updated = 0;
    foreach ($files as $f) {
        if (is_dir($f)) continue;
        $base = basename($f);
        $name = pathinfo($base, PATHINFO_FILENAME);
        $name_lc = strtolower($name);
        $name_alnum = preg_replace('/[^a-z0-9]/i','', $name_lc);
        // try to match isbn first, then title exact alnum, then substring
        $matchedId = null;
        foreach ($books as $id => $b) {
            if (!empty($b['isbn_norm']) && $name_alnum === $b['isbn_norm']) { $matchedId = $id; break; }
        }
        if (!$matchedId) {
            foreach ($books as $id => $b) {
                if (!empty($b['title_alnum']) && $name_alnum === $b['title_alnum']) { $matchedId = $id; break; }
            }
        }
        if (!$matchedId) {
            foreach ($books as $id => $b) {
                if (!empty($b['isbn_norm']) && strpos($name_lc, $b['isbn_norm']) !== false) { $matchedId = $id; break; }
                if (!empty($b['title_norm']) && strpos($name_lc, $b['title_norm']) !== false) { $matchedId = $id; break; }
            }
        }
        if ($matchedId) {
            $coverPath = 'frontend/assets/books/' . $base;
            $upd = $pdo->prepare('UPDATE books SET cover = :cover WHERE id = :id');
            $upd->execute([':cover' => $coverPath, ':id' => $matchedId]);
            $updated++;
            echo "Mapped $base -> book id $matchedId\n";
        } else {
            echo "No match for $base\n";
        }
    }
    echo "Done. Updated $updated covers.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
