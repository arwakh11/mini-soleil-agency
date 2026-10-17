<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910121800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projects_models (projects_id INT NOT NULL, models_id INT NOT NULL, INDEX IDX_2522ACF61EDE0F55 (projects_id), INDEX IDX_2522ACF6D966BF49 (models_id), PRIMARY KEY (projects_id, models_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE projects_team_members (projects_id INT NOT NULL, team_members_id INT NOT NULL, INDEX IDX_D382BF921EDE0F55 (projects_id), INDEX IDX_D382BF9283D88728 (team_members_id), PRIMARY KEY (projects_id, team_members_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE projects_models ADD CONSTRAINT FK_2522ACF61EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_models ADD CONSTRAINT FK_2522ACF6D966BF49 FOREIGN KEY (models_id) REFERENCES models (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_team_members ADD CONSTRAINT FK_D382BF921EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_team_members ADD CONSTRAINT FK_D382BF9283D88728 FOREIGN KEY (team_members_id) REFERENCES team_members (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client CHANGE brand_name brand_name VARCHAR(255) DEFAULT NULL, CHANGE instagram_account instagram_account VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE projects CHANGE content_quantity content_quantity VARCHAR(255) DEFAULT NULL, CHANGE number_of_items number_of_items VARCHAR(255) DEFAULT NULL, CHANGE product_type product_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_models DROP FOREIGN KEY FK_2522ACF61EDE0F55');
        $this->addSql('ALTER TABLE projects_models DROP FOREIGN KEY FK_2522ACF6D966BF49');
        $this->addSql('ALTER TABLE projects_team_members DROP FOREIGN KEY FK_D382BF921EDE0F55');
        $this->addSql('ALTER TABLE projects_team_members DROP FOREIGN KEY FK_D382BF9283D88728');
        $this->addSql('DROP TABLE projects_models');
        $this->addSql('DROP TABLE projects_team_members');
        $this->addSql('ALTER TABLE client CHANGE brand_name brand_name VARCHAR(255) NOT NULL, CHANGE instagram_account instagram_account VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE projects CHANGE content_quantity content_quantity VARCHAR(255) NOT NULL, CHANGE number_of_items number_of_items VARCHAR(255) NOT NULL, CHANGE product_type product_type VARCHAR(255) NOT NULL');
    }
}
