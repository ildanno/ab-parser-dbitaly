# DB Italy account balances parser
Parser for DeutscheBank Italy account balances documents

## Usage
```
$parser = new Parser([
    new MovimentoAnnualeStrategy(),
    new MovimentoAnnualeWithValutaStrategy(),
]);

// DeutscheBankCreditCardTransaction[]
$output = $parser->parse($textContent);
```
