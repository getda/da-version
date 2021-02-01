<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserTableOrAddData extends Migrator
{
    /**
     * @var string 表名
     */
    protected $table_name = 'user';

    /**
     * 新增表 user 并增加一个超级管理员账号
     * 因为这里需要插入数据所以在 up 方法内建表
     */
    public function up()
    {
        $table = $this->table($this->table_name, ['comment' => '用户表']);
        $table->addColumn('username', 'string',['limit' => 20, 'default' => '', 'comment' => '用户名'])
            ->addColumn('password', 'string',['limit' => 32, 'default' => md5('123456'), 'comment' => '密码'])
            ->addColumn('salt', 'string',['limit' => 8, 'default' => '', 'comment' => '密码盐'])
            ->addColumn('remember_token', 'string', ['limit' => 32, 'default' => '', 'comment' => '持久登录'])
            ->addColumn('remember_timeout', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '登录过期时间'])
            ->addColumn('email', 'string',['limit' => 50, 'default' => '', 'comment' => '邮箱'])
            ->addColumn('contact', 'json',['comment' => '联系方式'])
            ->addColumn('is_admin', 'boolean',['limit' => 1, 'default' => 0, 'comment' => '管理员'])
            ->addColumn('status', 'boolean',['limit' => 1, 'default' => 1, 'comment' => '状态'])
            ->addColumn('last_login_ip', 'string',['limit' => 20, 'default' => '', 'comment' => '最后登录IP'])
            ->addColumn('last_login_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '最后登录时间'])
            ->addColumn('create_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '创建时间'])
            ->addColumn('update_time', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '更新时间'])
            ->addIndex(['username', 'email', 'remember_token'], ['unique' => true])
            ->create();

        // 判断表是否存在 -> 创建数据
        $exists = $this->hasTable($this->table_name);
        if ($exists) {
            // 随机生成盐值
            $salt = strtoupper(substr(md5(rand(10000000, 99999999)), rand(1, 24), 8));
            // 组建数据
            $dataRow = [
                'id'                => 1,
                'username'          => 'admin',
                'password'          => md5($salt. '123456' .$salt),
                'salt'              => $salt,
                'email'             => 'm@wangxiaoda.com',
                'contact'           => json_encode(['qq' => '918247855', 'wx' => '888888', 'phone' => '15888888888']),
                'is_admin'          => true,
                'create_time'       => time(),
                'update_time'       => time(),
            ];

            $table->insert($dataRow);
            $table->saveData();
        }
    }

    public function down()
    {
        // 删除数据表 user
        $this->dropTable($this->table_name);
    }
}
