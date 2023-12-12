<?php

use yii\db\Migration;

class m201007_205659_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'country_code' => $this->string(),
            'time_zone' => $this->string(),
        ], $tableOptions);
        $this->createIndex('idx-company-sid', '{{%company}}', 'sid');

        $this->createTable('{{%twilio}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'token' => $this->string(),
            'domain' => $this->string(),
            'settings' => $this->json(),
        ], $tableOptions);
        $this->createIndex('idx-twilio-sid', '{{%twilio}}', 'sid');
        $this->createIndex('idx-twilio-company_id', '{{%twilio}}', 'company_id');
        $this->addForeignKey('fk-twilio-company', '{{%twilio}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'auth_token' => $this->string(),
            'sip' => $this->string(),
            'mobile_number' => $this->string(),
            'outgoing_config_sid' => $this->string(),
            'settings' => $this->json()
        ], $tableOptions);
        $this->createIndex('idx-user-sid', '{{%user}}', 'sid');
        $this->createIndex('idx-user-company_id', '{{%user}}', 'company_id');
        $this->addForeignKey('fk-user-company', '{{%user}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%incoming_flow}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'config' => $this->json(),
        ]);
        $this->createIndex('idx_incoming_flow-sid', '{{%incoming_flow}}', 'sid');
        $this->createIndex('idx_incoming_flow_company_id', '{{%incoming_flow}}', 'company_id');
        $this->addForeignKey('fk-incoming_flow-company', '{{%incoming_flow}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'sid' => $this->string()->notNull()->unique(),
            'company_id' => $this->integer(),
            'event_sid' => $this->string(),
            'event_type' => $this->string(),
            'status' => $this->string(),
            'time' => $this->integer(),
            'duration' => $this->integer(),
            'direction' => $this->string(),
            'result' => $this->string(),
            'record_url' => $this->string(),
            'checkpoint' => $this->json(),
            'integration_data' => $this->json(),
        ]);
        $this->createIndex('idx-log-sid', '{{%log}}', 'sid');
        $this->createIndex('idx-log-company_id', '{{%log}}', 'company_id');
        $this->addForeignKey('fk-log-company', '{{%log}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%callback_flow}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'config' => $this->json()
        ]);
        $this->createIndex('callback_flow-sid', '{{%callback_flow}}', 'sid');
        $this->createIndex('idx_callback_flow_company_id', '{{%callback_flow}}', 'company_id');
        $this->addForeignKey('fk-cbf-company', '{{%callback_flow}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%black_list}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'config' => $this->json()
        ]);
        $this->createIndex('blacklist-sid', '{{%black_list}}', 'sid');
        $this->addForeignKey('fk-black_list-company', '{{%black_list}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%timetable}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'config' => $this->json()
        ]);
        $this->createIndex('timetable-sid', '{{%timetable}}', 'sid');
        $this->addForeignKey('fk-timetable-company', '{{%timetable}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%voicemail_flow}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'config' => $this->json(),
        ]);
        $this->createIndex('voicemail_flow-sid', '{{%voicemail_flow}}', 'sid');
        $this->addForeignKey('fk-voicemail-company', '{{%voicemail_flow}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%local_presents}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'config' => $this->json()
        ]);
        $this->createIndex('local_presents-sid', '{{%local_presents}}', 'sid');
        $this->addForeignKey('fk-local_presents-company', '{{%local_presents}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'sid' => $this->string()->notNull()->unique(),
            'name' => $this->string(),
            'direction' => $this->string(),
            'config' => $this->json()
        ]);
        $this->createIndex('config-sid', '{{%config}}', 'sid');
        $this->addForeignKey('fk-config-company', '{{%config}}', 'company_id', '{{%company}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {

    }
}
