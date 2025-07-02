<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250702131042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE pontuacao_defesa (id SERIAL NOT NULL, id_trabalho_id INT NOT NULL, tipo_trabalho VARCHAR(255) NOT NULL, form_seguidas NUMERIC(4, 2) NOT NULL, citacoes_corretas NUMERIC(4, 2) NOT NULL, referencias_adequadas NUMERIC(4, 2) NOT NULL, sequencia_logica NUMERIC(4, 2) NOT NULL, introducao_elementos_basicos NUMERIC(4, 2) NOT NULL, resumo_conteudo_integral NUMERIC(4, 2) NOT NULL, revisao_desenvolvida NUMERIC(4, 2) NOT NULL, metodologia_explicitada NUMERIC(4, 2) NOT NULL, dados_pesquisa_apresentados NUMERIC(4, 2) NOT NULL, conclusao_coerente NUMERIC(4, 2) NOT NULL, referencias_atuais NUMERIC(4, 2) NOT NULL, erros_ortograficos NUMERIC(4, 2) NOT NULL, potencial_publicacao VARCHAR(3) NOT NULL, observacoes VARCHAR(500) DEFAULT NULL, nota_final NUMERIC(4, 2) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_340C63D617F47565 ON pontuacao_defesa (id_trabalho_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE pontuacao_quali (id SERIAL NOT NULL, id_trabalho_id INT NOT NULL, capa NUMERIC(4, 2) NOT NULL, folha_de_rosto NUMERIC(4, 2) NOT NULL, sumario NUMERIC(4, 2) NOT NULL, referencias NUMERIC(5, 2) NOT NULL, delimitacao_do_tema NUMERIC(4, 2) NOT NULL, justificativa NUMERIC(4, 2) NOT NULL, objetivos NUMERIC(4, 2) NOT NULL, problematizacao NUMERIC(4, 2) NOT NULL, hipotese NUMERIC(4, 2) NOT NULL, metodologia NUMERIC(4, 2) NOT NULL, revisao_bibliografica NUMERIC(4, 2) NOT NULL, aspectos_qualitativos NUMERIC(4, 2) NOT NULL, consonacia_plano VARCHAR(3) NOT NULL, justificativa_consonancia VARCHAR(400) DEFAULT NULL, consideracoes_finais VARCHAR(500) DEFAULT NULL, nota_final NUMERIC(4, 2) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BC2641F517F47565 ON pontuacao_quali (id_trabalho_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trabalho_defesa (id SERIAL NOT NULL, titulo VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trabalho_quali (id SERIAL NOT NULL, titulo VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, password_hash VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa ADD CONSTRAINT FK_340C63D617F47565 FOREIGN KEY (id_trabalho_id) REFERENCES trabalho_defesa (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali ADD CONSTRAINT FK_BC2641F517F47565 FOREIGN KEY (id_trabalho_id) REFERENCES trabalho_quali (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_defesa DROP CONSTRAINT FK_340C63D617F47565
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pontuacao_quali DROP CONSTRAINT FK_BC2641F517F47565
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pontuacao_defesa
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE pontuacao_quali
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trabalho_defesa
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trabalho_quali
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
