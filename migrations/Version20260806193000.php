<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class Version20260806193000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add English slugs and regenerate Serbian slugs from names';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE categories ADD slug_en VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD slug_en VARCHAR(255) DEFAULT NULL');
    }

    public function postUp(Schema $schema): void
    {
        $slugger = new AsciiSlugger();

        $categories = $this->connection->fetchAllAssociative('SELECT id, name, slug FROM categories');
        $usedCategorySlugs = [];

        foreach ($categories as $category) {
            $srSlug = $this->uniqueSlug(
                strtolower($slugger->slug((string) $category['name'])->toString()),
                $usedCategorySlugs,
            );
            $usedCategorySlugs[] = $srSlug;

            $this->connection->update('categories', [
                'slug_en' => $category['slug'],
                'slug'    => $srSlug,
            ], ['id' => $category['id']]);
        }

        $products = $this->connection->fetchAllAssociative('SELECT id, name, slug FROM products');
        $usedProductSlugs = [];

        foreach ($products as $product) {
            $srSlug = $this->uniqueSlug(
                strtolower($slugger->slug((string) $product['name'])->toString()),
                $usedProductSlugs,
            );
            $usedProductSlugs[] = $srSlug;

            $this->connection->update('products', [
                'slug_en' => $product['slug'],
                'slug'    => $srSlug,
            ], ['id' => $product['id']]);
        }

        $this->connection->executeStatement('CREATE UNIQUE INDEX UNIQ_3AF346687D79C0DC ON categories (slug_en)');
        $this->connection->executeStatement('CREATE UNIQUE INDEX UNIQ_B3BA5A5A7D79C0DC ON products (slug_en)');
    }

    public function down(Schema $schema): void
    {
        $this->connection->executeStatement('DROP INDEX UNIQ_3AF346687D79C0DC ON categories');
        $this->connection->executeStatement('DROP INDEX UNIQ_B3BA5A5A7D79C0DC ON products');

        $categories = $this->connection->fetchAllAssociative('SELECT id, slug_en FROM categories WHERE slug_en IS NOT NULL');
        foreach ($categories as $category) {
            $this->connection->update('categories', [
                'slug' => $category['slug_en'],
            ], ['id' => $category['id']]);
        }

        $products = $this->connection->fetchAllAssociative('SELECT id, slug_en FROM products WHERE slug_en IS NOT NULL');
        foreach ($products as $product) {
            $this->connection->update('products', [
                'slug' => $product['slug_en'],
            ], ['id' => $product['id']]);
        }

        $this->addSql('ALTER TABLE categories DROP slug_en');
        $this->addSql('ALTER TABLE products DROP slug_en');
    }

    /**
     * @param list<string> $used
     */
    private function uniqueSlug(string $base, array $used): string
    {
        $slug = '' !== $base ? $base : 'stavka';
        $candidate = $slug;
        $i = 2;

        while (in_array($candidate, $used, true)) {
            $candidate = $slug.'-'.$i;
            ++$i;
        }

        return $candidate;
    }
}
