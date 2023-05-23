# lloyds-payfrom-bank-api-client

A fully working PHP 8 integration of the UK Lloyds Bank PayFrom Open Banking API

# Limitations

Every order id has to be unique. As this is a stateless app with no db or concept of state, we are using the number of
hours since the epoch, along with the user data, to generate a unique order id

This will also limit a user from paying twice in the same hour, with the same address and the same amount. But I think
we can live with that.

# links

## Live Production

 - Production Merchant Administrator Link: https://lloydsbankpayfrombank.gateway.mastercard.com/ma/login.s

## MasterCard API 

 - Create Session https://lloydsbankpayfrombank.gateway.mastercard.com/api/documentation/apiDocumentation/rest-json/version/latest/operation/Session%3a%20Create%20Session.html?locale=en_US
 - Update session https://lloydsbankpayfrombank.gateway.mastercard.com/api/documentation/apiDocumentation/rest-json/version/latest/operation/Session%3a%20Update%20Session.html?locale=en_US
 - Get Order https://lloydsbankpayfrombank.gateway.mastercard.com/api/documentation/apiDocumentation/rest-json/version/latest/operation/Transaction%3a%20%20Retrieve%20Order.html?locale=en_US
 - Get Transaction https://lloydsbankpayfrombank.gateway.mastercard.com/api/documentation/apiDocumentation/rest-json/version/latest/operation/Transaction%3a%20%20Retrieve%20Transaction.html?locale=en_US

# When going live

Dont deploy 

 - .env.example (manually create .env on your server)
 - rector.php
 - README.md
 - ecs.php
 - build/*
