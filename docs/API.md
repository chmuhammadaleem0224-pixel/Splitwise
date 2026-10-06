# API Documentation

All responses use `{success,message,data}` for success and `{success,message,errors}` for errors.

## Register
`POST /api/register`

```json
{"name":"Ali","email":"ali@example.com","password":"password","password_confirmation":"password"}
```

Returns a user resource and random token.

## Login
`POST /api/login`

```json
{"email":"ali@example.com","password":"password"}
```

## Create group
`POST /api/groups`

Bearer token required.
```json
{"name":"Dubai Trip","description":"Expenses for Dubai trip"}
```

## Add member
`POST /api/groups/{group}/members`
```json
{"user_id":"USER_ID"}
```
Only the owner can manage membership.

## Create expense
`POST /api/groups/{group}/expenses`

### Equal
```json
{"description":"Dinner","amount":12000,"paid_by":"USER_ID","split_type":"equal","participants":[{"user_id":"USER_1"},{"user_id":"USER_2"},{"user_id":"USER_3"},{"user_id":"USER_4"}]}
```

### Exact
```json
{"description":"Dinner","amount":10000,"paid_by":"USER_ID","split_type":"exact","participants":[{"user_id":"USER_1","amount":4000},{"user_id":"USER_2","amount":3000},{"user_id":"USER_3","amount":2000},{"user_id":"USER_4","amount":1000}]}
```

### Percentage
```json
{"description":"Dinner","amount":10000,"paid_by":"USER_ID","split_type":"percentage","participants":[{"user_id":"USER_1","percentage":40},{"user_id":"USER_2","percentage":30},{"user_id":"USER_3","percentage":20},{"user_id":"USER_4","percentage":10}]}
```

Exact totals must equal the expense amount. Percentage totals must equal 100%.

## Balances
`GET /api/groups/{group}/balances`

A positive balance means the user should receive money; a negative balance means the user owes money.

## Debts
`GET /api/groups/{group}/debts`

Returns simplified payer-to-receiver debts using the net group balances.

## Settlement
`POST /api/groups/{group}/settlements`
```json
{"paid_to":"USER_ID","amount":3000,"note":"Dinner settlement"}
```
The authenticated user is the payer. A settlement cannot exceed the payer's outstanding net debt.
