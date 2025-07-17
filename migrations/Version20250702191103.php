<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702191103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa DROP CONSTRAINT fk_340c63d617f47565
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_340c63d617f47565
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa RENAME COLUMN id_trabalho_id TO id_trabalho
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa ADD CONSTRAINT FK_340C63D6F430AA02 FOREIGN KEY (id_trabalho) REFERENCES trabalho_defesa (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_340C63D6F430AA02 ON pontuacao_defesa (id_trabalho)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali DROP CONSTRAINT fk_bc2641f517f47565
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_bc2641f517f47565
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ADD tipo_trabalho VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER capa TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER folha_de_rosto TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER sumario TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER referencias TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER delimitacao_do_tema TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER justificativa TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER objetivos TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER problematizacao TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER hipotese TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER metodologia TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER revisao_bibliografica TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER aspectos_qualitativos TYPE NUMERIC(3, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER justificativa_consonancia TYPE VARCHAR(500)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali RENAME COLUMN id_trabalho_id TO id_trabalho
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali RENAME COLUMN consonacia_plano TO consonancia_plano
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ADD CONSTRAINT FK_BC2641F5F430AA02 FOREIGN KEY (id_trabalho) REFERENCES trabalho_quali (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC2641F5F430AA02 ON pontuacao_quali (id_trabalho)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE users_id_seq
        SQL);
        $this->addSql(<<<'SQL'
            SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER id SET DEFAULT nextval('users_id_seq')
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali DROP CONSTRAINT FK_BC2641F5F430AA02
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BC2641F5F430AA02
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali DROP tipo_trabalho
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER capa TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER folha_de_rosto TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER sumario TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER referencias TYPE NUMERIC(5, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER delimitacao_do_tema TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER justificativa TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER objetivos TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER problematizacao TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER hipotese TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER metodologia TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER revisao_bibliografica TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER aspectos_qualitativos TYPE NUMERIC(4, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ALTER justificativa_consonancia TYPE VARCHAR(400)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali RENAME COLUMN id_trabalho TO id_trabalho_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali RENAME COLUMN consonancia_plano TO consonacia_plano
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ADD CONSTRAINT fk_bc2641f517f47565 FOREIGN KEY (id_trabalho_id) REFERENCES trabalho_quali (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_bc2641f517f47565 ON pontuacao_quali (id_trabalho_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa DROP CONSTRAINT FK_340C63D6F430AA02
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_340C63D6F430AA02
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa RENAME COLUMN id_trabalho TO id_trabalho_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa ADD CONSTRAINT fk_340c63d617f47565 FOREIGN KEY (id_trabalho_id) REFERENCES trabalho_defesa (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_340c63d617f47565 ON pontuacao_defesa (id_trabalho_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ALTER id DROP DEFAULT
        SQL);
    }
}
