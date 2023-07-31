# Crypto Currency API

---
### Description
The API retrieves [exchange rate data of currencies](https://www.coinapi.io/) and provides information on currency pairs per hour with the option to modify the output range. The application has the functionality of periodic updating of currency exchange rates from the exchange.

### Setuping Guide
To install the application, follow these steps:
1. Clone the Git repository to your local machine:
   > git clone <repository_url>
2. Change into the project directory:
   > cd <project_directory>
3. Create a database and configure the database connection in the `.env` file:
   > DATABASE_INTERNAL_PORT=3306\
   > DATABASE_EXTERNAL_PORT=3303\
   > DATABASE_USER=test\
   > DATABASE_PASSWORD=test\
   > DATABASE_NAME=test\
   > DATABASE_HOST=mysql_crypto_currency
4. [Create api key](https://www.coinapi.io/) and configure in the `.env` file:
   > X_COIN_API_KEY=your_api_key
5. Edit the cron task for correct execution. Open the crontab editor by running the command:
   > crontab -e
6. Edit the cron task for proper execution. For example, to run the command every 5 minutes:
    > */5 * * * * /path/to/your/project/bin/console update-currency-rates
7. Save the crontab and exit the editor.
8. Start the server:
    > make start

### Endpoints
1. Get list of available currencies: `GET /api/cryptocurrency/currencies`

   \
   Example Response in JSON:
    ```json
    [
        {
            "tag": "EUR",
            "name": "Euro",
            "isCrypto": false
        },
        {
            "tag": "USD",
            "name": "Dollar",
            "isCrypto": false
        },
        {
            "tag": "BTC",
            "name": "Bitcoin",
            "isCrypto": true
        },
        {
            "tag": "ETH",
            "name": "Ethereum",
            "isCrypto": true
        }
    ]
    ```
   
2. Get exchange rate of currency pairs: `GET /api/cryptocurrency/rates`\
    \
    Required request parameters:
   - '**baseTag**' - base currency tag,
   - '**quoteTag**' - quote currency tag,
   - '**from**' - date start point,
   - '**to**' - date endpoint.

   \
   Possible errors a user may encounter:
    - '**InvalidCurrencyPair**' - Invalid currency pair.
    - '**CurrencyNotFound**' - Currency not found.

    \
    Example Request:
    - '**baseTag**' - BTC
    - '**quoteTag**' - USD
    - '**from**' - 31-07-2023,10:00:00
    - '**to**' - 31-07-2023,15:30:00
    ```
    https://127.0.0.1:8000/api/cryptocurrency/rates?baseTag=BTC&quoteTag=USD&from=31-07-2023,10:00:00&to=31-07-2023,15:30:00
    ```

    \
    Example Response in JSON:
    ```json
    [
        {
            "base": {
                "tag": "BTC",
                "name": "Bitcoin"
            },
            "quote": {
                "tag": "USD",
                "name": "Dollar"
            },
            "rate": "29381.47815609249300905503",
            "createdAt": "31/07/2023 11:40:10 +03:00"
        },
        {
            "base": {
                "tag": "BTC",
                "name": "Bitcoin"
            },
            "quote": {
                "tag": "USD",
                "name": "Dollar"
            },
            "rate": "29373.85697329598406213336",
            "createdAt": "31/07/2023 11:45:10 +03:00"
        },
        {
            "base": {
                "tag": "BTC",
                "name": "Bitcoin"
            },
            "quote": {
                "tag": "USD",
                "name": "Dollar"
            },
            "rate": "29377.43543711417078156956",
            "createdAt": "31/07/2023 11:50:10 +03:00"
        }
    ]
    ```