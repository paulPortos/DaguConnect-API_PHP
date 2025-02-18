<?php

namespace DaguConnect\Seeders;
use PDO;
use Thread;

class Seed {
    use User_Seed;
    use Job_Seed;
    use Chat_Seed;
    use Admin_Seed;
    use Resume_Seed;
    use clientbooking_Seed;
    use Client_Profile_Seed;
    private PDO $db;
    const RED = "\033[31m";
    const GREEN = "\033[32m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const RESET = "\033[0m"; // Resets text color to default

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->seed_data();
    }

    private function seed_data(): void {
        echo self::YELLOW . "--Seeding database--" . self::RESET . PHP_EOL;
        $seeders = [
            'seed_user' => 'User',
            'seed_jobs' => 'Jobs',
            'seed_chat_message' => 'Chat Messages',
            'seed_admin' => 'Admin',
            'seedResume' => 'Resumes',
            'seedClientBooking' => 'Client Bookings',
            'seed_client_profile' => 'Client Profiles',
        ];

        foreach ($seeders as $method => $name) {
            echo "Seeding $name           ";
            for ($i = 0; $i < 3; $i++) {
                echo ".";
                usleep(500000); // 0.5-second delay for effect
            }
            echo " Done!" . PHP_EOL;
            self::$method(); // Call the seeding method dynamically
        }
        echo "Seeding Complete!" . PHP_EOL;
    }
}