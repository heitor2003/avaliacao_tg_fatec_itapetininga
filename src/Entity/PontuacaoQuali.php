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

    // NOVO: Relacionamento ManyToOne com TrabalhoQuali
    #[ORM\ManyToOne(targetEntity: TrabalhoQuali::class)]
    #[ORM\JoinColumn(name: 'id_trabalho', referencedColumnName: 'id', nullable: false)] // Ajuste 'id_trabalho' para o nome da sua FK no BD
    private ?TrabalhoQuali $trabalho = null; // Nome da propriedade que será usada na DQL

    // CORRIGIDO: Propriedade em camelCase e tipo
    #[ORM\Column(length: 255)]
    private ?string $tipoTrabalho = null;

    // CORRIGIDO: Propriedades em camelCase e tipo float para decimais
    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $capa = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $folhaDeRosto = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $sumario = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $referencias = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $delimitacaoDoTema = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $justificativa = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $objetivos = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $problematizacao = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $hipotese = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $metodologia = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $revisaoBibliografica = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 2)]
    private ?float $aspectosQualitativos = null;

    #[ORM\Column(length: 3)]
    private ?string $consonanciaPlano = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $justificativaConsonancia = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $consideracoesFinais = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?float $notaFinal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    // NOVO: Getter para a associação 'trabalho'
    public function getTrabalho(): ?TrabalhoQuali
    {
        return $this->trabalho;
    }

    // NOVO: Setter para a associação 'trabalho'
    public function setTrabalho(?TrabalhoQuali $trabalho): static
    {
        $this->trabalho = $trabalho;
        return $this;
    }

    // Getters e Setters para as propriedades corrigidas (camelCase e float)

    public function getTipoTrabalho(): ?string
    {
        return $this->tipoTrabalho;
    }

    public function setTipoTrabalho(string $tipoTrabalho): static
    {
        $this->tipoTrabalho = $tipoTrabalho;
        return $this;
    }

    public function getCapa(): ?float
    {
        return $this->capa;
    }

    public function setCapa(float $capa): static
    {
        $this->capa = $capa;
        return $this;
    }

    public function getFolhaDeRosto(): ?float
    {
        return $this->folhaDeRosto;
    }

    public function setFolhaDeRosto(float $folhaDeRosto): static
    {
        $this->folhaDeRosto = $folhaDeRosto;
        return $this;
    }

    public function getSumario(): ?float
    {
        return $this->sumario;
    }

    public function setSumario(float $sumario): static
    {
        $this->sumario = $sumario;
        return $this;
    }

    public function getReferencias(): ?float
    {
        return $this->referencias;
    }

    public function setReferencias(float $referencias): static
    {
        $this->referencias = $referencias;
        return $this;
    }

    public function getDelimitacaoDoTema(): ?float
    {
        return $this->delimitacaoDoTema;
    }

    public function setDelimitacaoDoTema(float $delimitacaoDoTema): static
    {
        $this->delimitacaoDoTema = $delimitacaoDoTema;
        return $this;
    }

    public function getJustificativa(): ?float
    {
        return $this->justificativa;
    }

    public function setJustificativa(float $justificativa): static
    {
        $this->justificativa = $justificativa;
        return $this;
    }

    public function getObjetivos(): ?float
    {
        return $this->objetivos;
    }

    public function setObjetivos(float $objetivos): static
    {
        $this->objetivos = $objetivos;
        return $this;
    }

    public function getProblematizacao(): ?float
    {
        return $this->problematizacao;
    }

    public function setProblematizacao(float $problematizacao): static
    {
        $this->problematizacao = $problematizacao;
        return $this;
    }

    public function getHipotese(): ?float
    {
        return $this->hipotese;
    }

    public function setHipotese(float $hipotese): static
    {
        $this->hipotese = $hipotese;
        return $this;
    }

    public function getMetodologia(): ?float
    {
        return $this->metodologia;
    }

    public function setMetodologia(float $metodologia): static
    {
        $this->metodologia = $metodologia;
        return $this;
    }

    public function getRevisaoBibliografica(): ?float
    {
        return $this->revisaoBibliografica;
    }

    public function setRevisaoBibliografica(float $revisaoBibliografica): static
    {
        $this->revisaoBibliografica = $revisaoBibliografica;
        return $this;
    }

    public function getAspectosQualitativos(): ?float
    {
        return $this->aspectosQualitativos;
    }

    public function setAspectosQualitativos(float $aspectosQualitativos): static
    {
        $this->aspectosQualitativos = $aspectosQualitativos;
        return $this;
    }

    public function getConsonanciaPlano(): ?string
    {
        return $this->consonanciaPlano;
    }

    public function setConsonanciaPlano(string $consonanciaPlano): static
    {
        $this->consonanciaPlano = $consonanciaPlano;
        return $this;
    }

    public function getJustificativaConsonancia(): ?string
    {
        return $this->justificativaConsonancia;
    }

    public function setJustificativaConsonancia(?string $justificativaConsonancia): static
    {
        $this->justificativaConsonancia = $justificativaConsonancia;
        return $this;
    }

    public function getConsideracoesFinais(): ?string
    {
        return $this->consideracoesFinais;
    }

    public function setConsideracoesFinais(?string $consideracoesFinais): static
    {
        $this->consideracoesFinais = $consideracoesFinais;
        return $this;
    }

    public function getNotaFinal(): ?float
    {
        return $this->notaFinal;
    }

    public function setNotaFinal(float $notaFinal): static
    {
        $this->notaFinal = $notaFinal;
        return $this;
    }
}