<?php

namespace Antbank\AccountBalanceParser\DbItaly\Transaction;

use Antbank\TransactionInterchange\TransactionInterface;

class DeutscheBankCreditCardTransaction implements TransactionInterface
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setCodiceRiferimento(string $codiceRiferimento): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setDataAcquisto(\DateTime $dataAcquisto): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setDataRegistrazione(\DateTime $dataRegistrazione): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setDescrizioneOperazioni(string $descrizioneOperazioni): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setAcquisti(float $acquisti): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setPagamenti(float $pagamenti): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setValuta(string $valuta): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setImportoValuta(float $importoValuta): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setTassoCambio(float $tassoCambio): DeutscheBankCreditCardTransaction
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
     * @return DeutscheBankCreditCardTransaction
     */
    public function setCommissioniCambio(float $commissioniCambio): DeutscheBankCreditCardTransaction
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
        return $this->getCodiceRiferimento();
    }

    /**
     * A string that describes the transaction
     *
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->getDescrizioneOperazioni();
    }

    /**
     * Transaction date
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->getDataAcquisto();
    }

    /**
     * Optional register date
     *
     * @return \DateTime|null
     */
    public function getRegisterDate(): ?\DateTime
    {
        return $this->getDataRegistrazione();
    }

    /**
     * Optional currency code
     *
     * @return null|string
     */
    public function getCurrency(): ?string
    {
        return null;
    }

    /**
     * Gross amount of transaction.
     * Positive amount is intended as income, negative as outcome.
     *
     * @return float
     */
    public function getAmount(): float
    {
        return $this->getPagamenti() ?: -1 * $this->getAcquisti();
    }

    /**
     * This array can contains every kind of information (i.e.: partials, net, taxes, original currency).
     *
     * @return array
     */
    public function getExtras(): array
    {
        return [];
    }
}
