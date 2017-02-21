<?php

namespace Antbank\AccountBalanceParser\DbItaly\Transaction;

use Antbank\TransactionInterchange\TransactionInterface;

class Transaction implements TransactionInterface
{
    /**
     * @var string
     */
    protected $codiceRiferimento;

    /**
     * @var \DateTime
     */
    protected $dataAcquisto;

    /**
     * @var \DateTime
     */
    protected $dataRegistrazione;

    /**
     * @var string
     */
    protected $descrizioneOperazioni;

    /**
     * @var float
     */
    protected $acquisti;

    /**
     * @var float
     */
    protected $pagamenti;

    /**
     * @var string
     */
    protected $valuta;

    /**
     * @var float
     */
    protected $importoValuta;

    /**
     * @var float
     */
    protected $tassoCambio;

    /**
     * @var float
     */
    protected $commissioniCambio;

    /**
     * @return string
     */
    public function getCodiceRiferimento()
    {
        return $this->codiceRiferimento;
    }

    /**
     * @param string $codiceRiferimento
     * @return Transaction
     */
    public function setCodiceRiferimento(string $codiceRiferimento): Transaction
    {
        $this->codiceRiferimento = $codiceRiferimento;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataAcquisto()
    {
        return $this->dataAcquisto;
    }

    /**
     * @param \DateTime $dataAcquisto
     * @return Transaction
     */
    public function setDataAcquisto(\DateTime $dataAcquisto): Transaction
    {
        $this->dataAcquisto = $dataAcquisto;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataRegistrazione()
    {
        return $this->dataRegistrazione;
    }

    /**
     * @param \DateTime $dataRegistrazione
     * @return Transaction
     */
    public function setDataRegistrazione(\DateTime $dataRegistrazione): Transaction
    {
        $this->dataRegistrazione = $dataRegistrazione;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescrizioneOperazioni()
    {
        return $this->descrizioneOperazioni;
    }

    /**
     * @param string $descrizioneOperazioni
     * @return Transaction
     */
    public function setDescrizioneOperazioni(string $descrizioneOperazioni): Transaction
    {
        $this->descrizioneOperazioni = $descrizioneOperazioni;
        return $this;
    }

    /**
     * @return float
     */
    public function getAcquisti()
    {
        return $this->acquisti;
    }

    /**
     * @param float $acquisti
     * @return Transaction
     */
    public function setAcquisti(float $acquisti): Transaction
    {
        $this->acquisti = $acquisti;
        return $this;
    }

    /**
     * @return float
     */
    public function getPagamenti()
    {
        return $this->pagamenti;
    }

    /**
     * @param float $pagamenti
     * @return Transaction
     */
    public function setPagamenti(float $pagamenti): Transaction
    {
        $this->pagamenti = $pagamenti;
        return $this;
    }

    /**
     * @return string
     */
    public function getValuta()
    {
        return $this->valuta;
    }

    /**
     * @param string $valuta
     * @return Transaction
     */
    public function setValuta(string $valuta): Transaction
    {
        $this->valuta = $valuta;
        return $this;
    }

    /**
     * @return float
     */
    public function getImportoValuta()
    {
        return $this->importoValuta;
    }

    /**
     * @param float $importoValuta
     * @return Transaction
     */
    public function setImportoValuta(float $importoValuta): Transaction
    {
        $this->importoValuta = $importoValuta;
        return $this;
    }

    /**
     * @return float
     */
    public function getTassoCambio()
    {
        return $this->tassoCambio;
    }

    /**
     * @param float $tassoCambio
     * @return Transaction
     */
    public function setTassoCambio(float $tassoCambio): Transaction
    {
        $this->tassoCambio = $tassoCambio;
        return $this;
    }

    /**
     * @return float
     */
    public function getCommissioniCambio()
    {
        return $this->commissioniCambio;
    }

    /**
     * @param float $commissioniCambio
     * @return Transaction
     */
    public function setCommissioniCambio(float $commissioniCambio): Transaction
    {
        $this->commissioniCambio = $commissioniCambio;
        return $this;
    }

    public function getImporto(): float
    {
        return $this->getAcquisti() ?? -1 * $this->getPagamenti();
    }

    /**
     * A string that identifies in a uniqe way the transaction within the system it comes from.
     *
     * @return string
     */
    public function getId(): string
    {
        // TODO: Implement getId() method.
    }

    /**
     * A string that describes the transaction
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * Transaction date
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        // TODO: Implement getDate() method.
    }

    /**
     * Optional register date
     *
     * @return \DateTime|null
     */
    public function getRegisterDate(): ?\DateTime
    {
        // TODO: Implement getRegisterDate() method.
    }

    /**
     * Optional currency code
     *
     * @return null|string
     */
    public function getCurrency(): ?string
    {
        // TODO: Implement getCurrency() method.
    }

    /**
     * Gross amount of transaction.
     * Positive amount is intended as income, negative as outcome.
     *
     * @return float
     */
    public function getAmount(): float
    {
        // TODO: Implement getAmount() method.
    }

    /**
     * This array can contains every kind of information (i.e.: partials, net, taxes, original currency).
     *
     * @return array
     */
    public function getExtras(): array
    {
        // TODO: Implement getExtras() method.
    }
}
