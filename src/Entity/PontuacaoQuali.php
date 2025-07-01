<?php

namespace App\Entity;

use App\Repository\PontuacaoQualiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PontuacaoQualiRepository::class)]
class PontuacaoQuali
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TrabalhoQuali $id_trabalho = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $capa = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $folha_de_rosto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $sumario = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $referencias = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $delimitacao_do_tema = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $justificativa = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $objetivos = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $problematizacao = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $hipotese = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $metodologia = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $revisao_bibliografica = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $aspectos_qualitativos = null;

    #[ORM\Column(length: 3)]
    private ?string $consonacia_plano = null;

    #[ORM\Column(length: 400, nullable: true)]
    private ?string $justificativa_consonancia = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $consideracoes_finais = null;

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

    public function getIdTrabalho(): ?TrabalhoQuali
    {
        return $this->id_trabalho;
    }

    public function setIdTrabalho(?TrabalhoQuali $id_trabalho): static
    {
        $this->id_trabalho = $id_trabalho;

        return $this;
    }

    public function getCapa(): ?string
    {
        return $this->capa;
    }

    public function setCapa(string $capa): static
    {
        $this->capa = $capa;

        return $this;
    }

    public function getFolhaDeRosto(): ?string
    {
        return $this->folha_de_rosto;
    }

    public function setFolhaDeRosto(string $folha_de_rosto): static
    {
        $this->folha_de_rosto = $folha_de_rosto;

        return $this;
    }

    public function getSumario(): ?string
    {
        return $this->sumario;
    }

    public function setSumario(string $sumario): static
    {
        $this->sumario = $sumario;

        return $this;
    }

    public function getReferencias(): ?string
    {
        return $this->referencias;
    }

    public function setReferencias(string $referencias): static
    {
        $this->referencias = $referencias;

        return $this;
    }

    public function getDelimitacaoDoTema(): ?string
    {
        return $this->delimitacao_do_tema;
    }

    public function setDelimitacaoDoTema(string $delimitacao_do_tema): static
    {
        $this->delimitacao_do_tema = $delimitacao_do_tema;

        return $this;
    }

    public function getJustificativa(): ?string
    {
        return $this->justificativa;
    }

    public function setJustificativa(string $justificativa): static
    {
        $this->justificativa = $justificativa;

        return $this;
    }

    public function getObjetivos(): ?string
    {
        return $this->objetivos;
    }

    public function setObjetivos(string $objetivos): static
    {
        $this->objetivos = $objetivos;

        return $this;
    }

    public function getProblematizacao(): ?string
    {
        return $this->problematizacao;
    }

    public function setProblematizacao(string $problematizacao): static
    {
        $this->problematizacao = $problematizacao;

        return $this;
    }

    public function getHipotese(): ?string
    {
        return $this->hipotese;
    }

    public function setHipotese(string $hipotese): static
    {
        $this->hipotese = $hipotese;

        return $this;
    }

    public function getMetodologia(): ?string
    {
        return $this->metodologia;
    }

    public function setMetodologia(string $metodologia): static
    {
        $this->metodologia = $metodologia;

        return $this;
    }

    public function getRevisaoBibliografica(): ?string
    {
        return $this->revisao_bibliografica;
    }

    public function setRevisaoBibliografica(string $revisao_bibliografica): static
    {
        $this->revisao_bibliografica = $revisao_bibliografica;

        return $this;
    }

    public function getAspectosQualitativos(): ?string
    {
        return $this->aspectos_qualitativos;
    }

    public function setAspectosQualitativos(string $aspectos_qualitativos): static
    {
        $this->aspectos_qualitativos = $aspectos_qualitativos;

        return $this;
    }

    public function getConsonaciaPlano(): ?string
    {
        return $this->consonacia_plano;
    }

    public function setConsonaciaPlano(string $consonacia_plano): static
    {
        $this->consonacia_plano = $consonacia_plano;

        return $this;
    }

    public function getJustificativaConsonancia(): ?string
    {
        return $this->justificativa_consonancia;
    }

    public function setJustificativaConsonancia(?string $justificativa_consonancia): static
    {
        $this->justificativa_consonancia = $justificativa_consonancia;

        return $this;
    }

    public function getConsideracoesFinais(): ?string
    {
        return $this->consideracoes_finais;
    }

    public function setConsideracoesFinais(?string $consideracoes_finais): static
    {
        $this->consideracoes_finais = $consideracoes_finais;

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
