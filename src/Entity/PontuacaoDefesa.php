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

    // CORRIGIDO: Nome da propriedade da associação de id_trabalho para trabalho
    #[ORM\ManyToOne(targetEntity: TrabalhoDefesa::class)]
    #[ORM\JoinColumn(name: 'id_trabalho', referencedColumnName: 'id', nullable: false)]
    private ?TrabalhoDefesa $trabalho = null; // Renomeado de $id_trabalho para $trabalho

    #[ORM\Column(length: 255)]
    private ?string $tipo_trabalho = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $form_seguidas = null; // CORRIGIDO: Tipo de string para float

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $citacoes_corretas = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $referencias_adequadas = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $sequencia_logica = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $introducao_elementos_basicos = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $resumo_conteudo_integral = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $revisao_desenvolvida = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $metodologia_explicitada = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $dados_pesquisa_apresentados = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $conclusao_coerente = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $referencias_atuais = null; // CORRIGIDO

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $erros_ortograficos = null; // CORRIGIDO

    #[ORM\Column(length: 3)]
    private ?string $potencial_publicacao = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $observacoes = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $nota_final = null; // CORRIGIDO

    public function getId(): ?int
    {
        return $this->id;
    }

    // REMOVIDO: setId(int $id) para entidade autoincremental

    // CORRIGIDO: Getter para 'trabalho'
    public function getTrabalho(): ?TrabalhoDefesa
    {
        return $this->trabalho;
    }

    // CORRIGIDO: Setter para 'trabalho'
    public function setTrabalho(?TrabalhoDefesa $trabalho): static
    {
        $this->trabalho = $trabalho;
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

    public function getFormSeguidas(): ?float
    {
        return $this->form_seguidas;
    }

    public function setFormSeguidas(float $form_seguidas): static
    {
        $this->form_seguidas = $form_seguidas;
        return $this;
    }

    public function getCitacoesCorretas(): ?float
    {
        return $this->citacoes_corretas;
    }

    public function setCitacoesCorretas(float $citacoes_corretas): static
    {
        $this->citacoes_corretas = $citacoes_corretas;
        return $this;
    }

    public function getReferenciasAdequadas(): ?float
    {
        return $this->referencias_adequadas;
    }

    public function setReferenciasAdequadas(float $referencias_adequadas): static
    {
        $this->referencias_adequadas = $referencias_adequadas;
        return $this;
    }

    public function getSequenciaLogica(): ?float
    {
        return $this->sequencia_logica;
    }

    public function setSequenciaLogica(float $sequencia_logica): static
    {
        $this->sequencia_logica = $sequencia_logica;
        return $this;
    }

    public function getIntroducaoElementosBasicos(): ?float
    {
        return $this->introducao_elementos_basicos;
    }

    public function setIntroducaoElementosBasicos(float $introducao_elementos_basicos): static
    {
        $this->introducao_elements_basicos = $introducao_elementos_basicos; // Note: possible typo in previous line
        return $this;
    }

    public function getResumoConteudoIntegral(): ?float
    {
        return $this->resumo_conteudo_integral;
    }

    public function setResumoConteudoIntegral(float $resumo_conteudo_integral): static
    {
        $this->resumo_conteudo_integral = $resumo_conteudo_integral;
        return $this;
    }

    public function getRevisaoDesenvolvida(): ?float
    {
        return $this->revisao_desenvolvida;
    }

    public function setRevisaoDesenvolvida(float $revisao_desenvolvida): static
    {
        $this->revisao_desenvolvida = $revisao_desenvolvida;
        return $this;
    }

    public function getMetodologiaExplicitada(): ?float
    {
        return $this->metodologia_explicitada;
    }

    public function setMetodologiaExplicitada(float $metodologia_explicitada): static
    {
        $this->metodologia_explicitada = $metodologia_explicitada;
        return $this;
    }

    public function getDadosPesquisaApresentados(): ?float
    {
        return $this->dados_pesquisa_apresentados;
    }

    public function setDadosPesquisaApresentados(float $dados_pesquisa_apresentados): static
    {
        $this->dados_pesquisa_apresentados = $dados_pesquisa_apresentados;
        return $this;
    }

    public function getConclusaoCoerente(): ?float
    {
        return $this->conclusao_coerente;
    }

    public function setConclusaoCoerente(float $conclusao_coerente): static
    {
        $this->conclusao_coerente = $conclusao_coerente;
        return $this;
    }

    public function getReferenciasAtuais(): ?float
    {
        return $this->referencias_atuais;
    }

    public function setReferenciasAtuais(float $referencias_atuais): static
    {
        $this->referencias_atuais = $referencias_atuais;
        return $this;
    }

    public function getErrosOrtograficos(): ?float
    {
        return $this->erros_ortograficos;
    }

    public function setErrosOrtograficos(float $erros_ortograficos): static
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

    public function getNotaFinal(): ?float
    {
        return $this->nota_final;
    }

    public function setNotaFinal(float $nota_final): static
    {
        $this->nota_final = $nota_final;
        return $this;
    }
}