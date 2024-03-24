<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301080354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', user_id VARCHAR(30) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(255) DEFAULT NULL, gender VARCHAR(6) NOT NULL, contact_number VARCHAR(20) NOT NULL, is_backend_user TINYINT(1) NOT NULL, nic VARCHAR(20) NOT NULL, INDEX IDX_88BDF3E9D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment (id INT AUTO_INCREMENT NOT NULL, patient_id_id INT NOT NULL, doctor_id_id INT DEFAULT NULL, payment_status_id INT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ref_no VARCHAR(100) NOT NULL, ref_doctor VARCHAR(100) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_FE38F844EA724598 (patient_id_id), INDEX IDX_FE38F84432B07E31 (doctor_id_id), INDEX IDX_FE38F84428DE2F95 (payment_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appointment_test_mappings (id INT AUTO_INCREMENT NOT NULL, appointment_id_id INT NOT NULL, test_id INT NOT NULL, technician_id INT DEFAULT NULL, sample_collected DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ready_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', printed_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', comments LONGTEXT DEFAULT NULL, INDEX IDX_FD01F6E59334AFB9 (appointment_id_id), INDEX IDX_FD01F6E51E5D0459 (test_id), INDEX IDX_FD01F6E5E6C5D496 (technician_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, specialization VARCHAR(150) NOT NULL, contact_number VARCHAR(15) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE functionality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, meta_code VARCHAR(255) NOT NULL, function_group VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_payment (id INT AUTO_INCREMENT NOT NULL, appointment_id_id INT NOT NULL, payment_method_id INT NOT NULL, payment_status_id INT NOT NULL, total_amount DOUBLE PRECISION NOT NULL, payment_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_304BD1E69334AFB9 (appointment_id_id), INDEX IDX_304BD1E65AA1164F (payment_method_id), INDEX IDX_304BD1E628DE2F95 (payment_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, name VARCHAR(50) NOT NULL, meta_code VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_requests (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, process_state_id INT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', contact_no VARCHAR(20) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) DEFAULT NULL, preferred_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', tests_info LONGTEXT DEFAULT NULL, preffered_time VARCHAR(30) DEFAULT NULL, INDEX IDX_7D53E4026B899279 (patient_id), INDEX IDX_7D53E402F3296240 (process_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE process_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, meta_code VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technician (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, contact_number VARCHAR(20) NOT NULL, email VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, meta_code VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_D87F7E0C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, meta_code VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_range (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, name VARCHAR(50) NOT NULL, meta_code VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_slot (id INT AUTO_INCREMENT NOT NULL, time_range_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', allocated_patients INT NOT NULL, available_slots INT NOT NULL, INDEX IDX_1B3294A8E07937D (time_range_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, is_active TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, name VARCHAR(100) NOT NULL, meta_code VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role_functionality (user_role_id INT NOT NULL, functionality_id INT NOT NULL, INDEX IDX_DB89712C8E0E3CA6 (user_role_id), INDEX IDX_DB89712C39EDDC8 (functionality_id), PRIMARY KEY(user_role_id, functionality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9D60322AC FOREIGN KEY (role_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F844EA724598 FOREIGN KEY (patient_id_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84432B07E31 FOREIGN KEY (doctor_id_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_FE38F84428DE2F95 FOREIGN KEY (payment_status_id) REFERENCES process_status (id)');
        $this->addSql('ALTER TABLE appointment_test_mappings ADD CONSTRAINT FK_FD01F6E59334AFB9 FOREIGN KEY (appointment_id_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE appointment_test_mappings ADD CONSTRAINT FK_FD01F6E51E5D0459 FOREIGN KEY (test_id) REFERENCES test (id)');
        $this->addSql('ALTER TABLE appointment_test_mappings ADD CONSTRAINT FK_FD01F6E5E6C5D496 FOREIGN KEY (technician_id) REFERENCES technician (id)');
        $this->addSql('ALTER TABLE patient_payment ADD CONSTRAINT FK_304BD1E69334AFB9 FOREIGN KEY (appointment_id_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE patient_payment ADD CONSTRAINT FK_304BD1E65AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE patient_payment ADD CONSTRAINT FK_304BD1E628DE2F95 FOREIGN KEY (payment_status_id) REFERENCES process_status (id)');
        $this->addSql('ALTER TABLE pre_requests ADD CONSTRAINT FK_7D53E4026B899279 FOREIGN KEY (patient_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE pre_requests ADD CONSTRAINT FK_7D53E402F3296240 FOREIGN KEY (process_state_id) REFERENCES process_status (id)');
        $this->addSql('ALTER TABLE test ADD CONSTRAINT FK_D87F7E0C12469DE2 FOREIGN KEY (category_id) REFERENCES test_category (id)');
        $this->addSql('ALTER TABLE time_slot ADD CONSTRAINT FK_1B3294A8E07937D FOREIGN KEY (time_range_id) REFERENCES time_range (id)');
        $this->addSql('ALTER TABLE user_role_functionality ADD CONSTRAINT FK_DB89712C8E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role_functionality ADD CONSTRAINT FK_DB89712C39EDDC8 FOREIGN KEY (functionality_id) REFERENCES functionality (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9D60322AC');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F844EA724598');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84432B07E31');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_FE38F84428DE2F95');
        $this->addSql('ALTER TABLE appointment_test_mappings DROP FOREIGN KEY FK_FD01F6E59334AFB9');
        $this->addSql('ALTER TABLE appointment_test_mappings DROP FOREIGN KEY FK_FD01F6E51E5D0459');
        $this->addSql('ALTER TABLE appointment_test_mappings DROP FOREIGN KEY FK_FD01F6E5E6C5D496');
        $this->addSql('ALTER TABLE patient_payment DROP FOREIGN KEY FK_304BD1E69334AFB9');
        $this->addSql('ALTER TABLE patient_payment DROP FOREIGN KEY FK_304BD1E65AA1164F');
        $this->addSql('ALTER TABLE patient_payment DROP FOREIGN KEY FK_304BD1E628DE2F95');
        $this->addSql('ALTER TABLE pre_requests DROP FOREIGN KEY FK_7D53E4026B899279');
        $this->addSql('ALTER TABLE pre_requests DROP FOREIGN KEY FK_7D53E402F3296240');
        $this->addSql('ALTER TABLE test DROP FOREIGN KEY FK_D87F7E0C12469DE2');
        $this->addSql('ALTER TABLE time_slot DROP FOREIGN KEY FK_1B3294A8E07937D');
        $this->addSql('ALTER TABLE user_role_functionality DROP FOREIGN KEY FK_DB89712C8E0E3CA6');
        $this->addSql('ALTER TABLE user_role_functionality DROP FOREIGN KEY FK_DB89712C39EDDC8');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE appointment_test_mappings');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE functionality');
        $this->addSql('DROP TABLE patient_payment');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE pre_requests');
        $this->addSql('DROP TABLE process_status');
        $this->addSql('DROP TABLE technician');
        $this->addSql('DROP TABLE test');
        $this->addSql('DROP TABLE test_category');
        $this->addSql('DROP TABLE time_range');
        $this->addSql('DROP TABLE time_slot');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE user_role_functionality');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
