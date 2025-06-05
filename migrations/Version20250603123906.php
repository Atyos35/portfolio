<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250602InsertInitialUserData extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insère les données initiales pour un utilisateur et ses expériences, formations et blocs associés';
    }

    public function up(Schema $schema): void
    {
        $passwordHash = password_hash('password', PASSWORD_BCRYPT);
        $this->addSql("
            INSERT INTO user (email, roles, password, firstname, lastname, job, linkedin, age, city, phone, is_verified, profile_picture)
            VALUES (
                'valerian.guemene@gmail.com',
                '[\"ROLE_USER\"]',
                '$passwordHash',
                'Valérian',
                'Guémené',
                'Développeur Web Full-Stack',
                'https://www.linkedin.com/in/valerian-guemene/',
                34,
                'Rennes',
                '0684445191',
                1,
                ''
            );
        ");

        $this->addSql("SET @user_id = (SELECT id FROM user WHERE email = 'valerian.guemene@gmail.com');");

        $this->addSql("
            INSERT INTO experience (user_id, name, company, city, start_date, end_date, description) VALUES
            (@user_id, 'Développeur WEB', 'Groupe Pandora', 'Rennes', '2022-09-01', NULL, 'Évolution et maintenance de l''application de gestion de matériel ainsi que sur le CRM interne.'),
            (@user_id, 'Concepteur Développeur Web', 'Samsic Groupe', 'Cesson-Sévigné', '2021-10-01', '2022-04-01', 'Conception et développement d''une application Web d''export et d''import de données.');
        ");

        $this->addSql("
            INSERT INTO training (user_id, name, school, city, start_date, end_date, description) VALUES
            (@user_id, 'Concepteur Développeur d''Applications', 'ENI Ecole Informatique', 'Rennes', '2021-10-01', '2022-12-01', ''),
            (@user_id, 'Développeur Web et Web Mobile', 'ENI Ecole Informatique', 'Rennes', '2021-01-01', '2021-09-01', '');
        ");
        
        $this->addSql("
            INSERT INTO block (user_id, title, names) VALUES
            (@user_id, 'Frameworks', ['Symfony', 'API Platform', 'NestJS']),
            (@user_id, 'Langages', ['PHP', 'JavaScript', 'Python]);
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM block WHERE user_id = (SELECT id FROM user WHERE email = 'valerian.guemene@gmail.com');");
        $this->addSql("DELETE FROM training WHERE user_id = (SELECT id FROM user WHERE email = 'valerian.guemene@gmail.com');");
        $this->addSql("DELETE FROM experience WHERE user_id = (SELECT id FROM user WHERE email = 'valerian.guemene@gmail.com');");
        $this->addSql("DELETE FROM user WHERE email = 'valerian.guemene@gmail.com';");
    }
}