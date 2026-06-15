<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'username'   => 'admin',
            'email'      => 'admin@gatepass.com', // Optional, but good to have
            'password'   => password_hash('admin123', PASSWORD_DEFAULT), // Hash the password!
            'status'     => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Using Query Builder
        $this->db->table('admins')->insert($data);

        echo "✅ Super Admin Created: (User: admin / Pass: admin123)\n";
    }
}