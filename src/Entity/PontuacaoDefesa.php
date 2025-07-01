<?php

namespace App\Entity;

use App\Repository\PontuacaoDefesaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PontuacaoDefesaRepository::class)]
class PontuacaoDefesa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrabalhoDefesa $id_trabalho = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo_trabalho = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $form_seguidas = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $citacoes_corretas = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $referencias_adequadas = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $sequencia_logica = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $introducao_elementos_basicos = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $resumo_conteudo_integral = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $revisao_desenvolvida = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $metodologia_explicitada = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $dados_pesquisa_apresentados = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $conclusao_coerente = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $referencias_atuais = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $erros_ortograficos = null;

    #[ORM\Column(length: 3)]
    private ?string $potencial_publicacao = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $observacoes = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $nota_final = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getIdTrabalho(): ?TrabalhoDefesa
    {
        return $this->id_trabalho;
    }

    public function setIdTrabalho(?TrabalhoDefesa $id_trabalho): static
    {
        $this->id_trabalho = $id_trabalho;

        return $this;
    }

    public function getTipoTrabalho(): ?string
    {
        return $this->tipo_trabalho;
    }

    public function setTipoTrabalho(string $tipo_trabalho): static
    {
        $this->tipo_trabalho = $tipo_trabalho;

        return $this;
    }

    public function getFormSeguidas(): ?string
    {
        return $this->form_seguidas;
    }

    public function setFormSeguidas(string $form_seguidas): static
    {
        $this->form_seguidas = $form_seguidas;

        return $this;
    }

    public function getCitacoesCorretas(): ?string
    {
        return $this->citacoes_corretas;
    }

    public function setCitacoesCorretas(string $citacoes_corretas): static
    {
        $this->citacoes_corretas = $citacoes_corretas;

        return $this;
    }

    public function getReferenciasAdequadas(): ?string
    {
        return $this->referencias_adequadas;
    }

    public function setReferenciasAdequadas(string $referencias_adequadas): static
    {
        $this->referencias_adequadas = $referencias_adequadas;

        return $this;
    }

    public function getSequenciaLogica(): ?string
    {
        return $this->sequencia_logica;
    }

    public function setSequenciaLogica(string $sequencia_logica): static
    {
        $this->sequencia_logica = $sequencia_logica;

        return $this;
    }

    public function getIntroducaoElementosBasicos(): ?string
    {
        return $this->introducao_elementos_basicos;
    }

    public function setIntroducaoElementosBasicos(string $introducao_elementos_basicos): static
    {
        $this->introducao_elementos_basicos = $introducao_elementos_basicos;

        return $this;
    }

    public function getResumoConteudoIntegral(): ?string
    {
        return $this->resumo_conteudo_integral;
    }

    public function setResumoConteudoIntegral(string $resumo_conteudo_integral): static
    {
        $this->resumo_conteudo_integral = $resumo_conteudo_integral;

        return $this;
    }

    public function getRevisaoDesenvolvida(): ?string
    {
        return $this->revisao_desenvolvida;
    }

    public function setRevisaoDesenvolvida(string $revisao_desenvolvida): static
    {
        $this->revisao_desenvolvida = $revisao_desenvolvida;

        return $this;
    }

    public function getMetodologiaExplicitada(): ?string
    {
        return $this->metodologia_explicitada;
    }

    public function setMetodologiaExplicitada(string $metodologia_explicitada): static
    {
        $this->metodologia_explicitada = $metodologia_explicitada;

        return $this;
    }

    public function getDadosPesquisaApresentados(): ?string
    {
        return $this->dados_pesquisa_apresentados;
    }

    public function setDadosPesquisaApresentados(string $dados_pesquisa_apresentados): static
    {
        $this->dados_pesquisa_apresentados = $dados_pesquisa_apresentados;

        return $this;
    }

    public function getConclusaoCoerente(): ?string
    {
        return $this->conclusao_coerente;
    }

    public function setConclusaoCoerente(string $conclusao_coerente): static
    {
        $this->conclusao_coerente = $conclusao_coerente;

        return $this;
    }

    public function getReferenciasAtuais(): ?string
    {
        return $this->referencias_atuais;
    }

    public function setReferenciasAtuais(string $referencias_atuais): static
    {
        $this->referencias_atuais = $referencias_atuais;

        return $this;
    }

    public function getErrosOrtograficos(): ?string
    {
        return $this->erros_ortograficos;
    }

    public function setErrosOrtograficos(string $erros_ortograficos): static
    {
        $this->erros_ortograficos = $erros_ortograficos;

        return $this;
    }

    public function getPotencialPublicacao(): ?string
    {
        return $this->potencial_publicacao;
    }

    public function setPotencialPublicacao(string $potencial_publicacao): static
    {
        $this->potencial_publicacao = $potencial_publicacao;

        return $this;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function setObservacoes(?string $observacoes): static
    {
        $this->observacoes = $observacoes;

        return $this;
    }

    public function getNotaFinal(): ?string
    {
        return $this->nota_final;
    }

    public function setNotaFinal(string $nota_final): static
    {
        $this->nota_final = $nota_final;

        return $this;
    }
}
