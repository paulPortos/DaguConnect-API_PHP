<?php

namespace DaguConnect\Seeders;
use PDO;

class Seed {
    use User_Seed;
    use Job_Seed;
    use Chat_Seed;
    use Admin_Seed;
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->seed_data();
    }

    private function seed_data(): void {
        self::seed_user();
        self::seed_jobs();
        self::seed_chat_message();
        self::seed_admin();
    }

}