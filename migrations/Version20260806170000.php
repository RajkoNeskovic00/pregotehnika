<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260806170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add English translation columns for frontend CMS content';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products ADD name_en VARCHAR(255) DEFAULT NULL, ADD short_description_en LONGTEXT DEFAULT NULL, ADD description_en LONGTEXT DEFAULT NULL, ADD meta_title_en VARCHAR(255) DEFAULT NULL, ADD meta_description_en LONGTEXT DEFAULT NULL, ADD meta_keywords_en LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD name_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE faqs ADD question_en VARCHAR(255) DEFAULT NULL, ADD answer_en LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE nav_menus ADD name_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product_documents ADD title_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE product_images ADD alt_text_en VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP name_en, DROP short_description_en, DROP description_en, DROP meta_title_en, DROP meta_description_en, DROP meta_keywords_en');
        $this->addSql('ALTER TABLE categories DROP name_en');
        $this->addSql('ALTER TABLE faqs DROP question_en, DROP answer_en');
        $this->addSql('ALTER TABLE nav_menus DROP name_en');
        $this->addSql('ALTER TABLE product_documents DROP title_en');
        $this->addSql('ALTER TABLE product_images DROP alt_text_en');
    }
}
