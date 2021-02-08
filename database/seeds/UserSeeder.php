<?php

use think\migration\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $data = [];
        $salt = $this->randSalt();
        $data[0] = [
            'id'                => 1,
            'username'          => 'admin',
            'password'          => md5($salt. '123456' .$salt),
            'salt'              => $salt,
            'email'             => 'm@wangxiaoda.com',
            'contact'           => json_encode(['qq' => '918247855', 'wx' => '888888', 'phone' => '15888888888']),
            'is_admin'          => 1,
            'status'            => 1,
            'create_time'       => time(),
            'update_time'       => time(),
        ];
        for ($i = 0; $i < 500; $i++) {
            // 随机生成盐值
            $salt = $this->randSalt();
            $data[] = [
                'username'      => $faker->userName,
                'password'      => md5($salt . '123456' . $salt),
                'salt'          => $salt,
                'email'         => $faker->email,
                'contact'       => json_encode(['qq' => rand(10000, 99999), 'phone' => $faker->e164PhoneNumber]),
                'is_admin'      => 0,
                'status'        => rand(0,1),
                'create_time'   => $faker->unixTime,
                'update_time'   => $faker->unixTime,
            ];
        }

        $this->insert('user', $data);
    }

    /**
     * 生成随机盐值
     * @return string
     */
    public function randSalt() {
        return strtoupper(substr(md5(rand(10000000, 99999999)), rand(1, 24), 8));
    }
}