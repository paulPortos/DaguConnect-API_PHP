<?php
namespace DaguConnect;

use PDO;
use PDOException;

class migration {
    private string $migrationsDirectory;
    private array $executedMigrations = [];
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->migrationsDirectory = __DIR__ . '/Migrations'; // Path to the migrations folder

        // Ensure migrations table exists
        $this->ensureMigrationsTableExists();

        // Run the migrations
        $this->run();
    }

    private function ensureMigrationsTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        try {
            $this->db->exec($sql);
        } catch (PDOException $e) {
            die("Failed to ensure migrations table exists: " . $e->getMessage());
        }
    }

    public function run(): void {
        // Fetch executed migrations
        $stmt = $this->db->query("SELECT migration FROM migrations");
        $this->executedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Get all migration files from the migrations folder
        $files = scandir($this->migrationsDirectory);
        $migrationsToRun = array_diff($files, $this->executedMigrations);

        // Ensure 'users.php' runs first
        usort($migrationsToRun, function ($a, $b) {
            if ($a === 'users.php') return -1;
            if ($b === 'users.php') return 1;
            return strcmp($a, $b);
        });

        foreach ($migrationsToRun as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            $filePath = $this->migrationsDirectory . '/' . $migration;
            $sql = file_get_contents($filePath);

            try {
                // Execute migration SQL
                $this->db->exec($sql);

                // Record the migration in the database
                $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (?)");
                $stmt->execute([$migration]);
                echo "Migration $migration executed successfully.\n";
            } catch (PDOException $e) {
                echo "Failed to execute migration $migration: " . $e->getMessage() . "\n";
            }
        }
    }
}
