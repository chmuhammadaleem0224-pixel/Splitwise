# Architecture Notes

## Request flow
Route -> Form Request -> Controller -> Service -> MongoDB -> API response.

Controllers stay thin. Expense, balance, group authorization, authentication and settlement rules live in services.

## Authentication
Registration/login use `bin2hex(random_bytes(32))`. The resulting token is stored in the `tokens` collection. `AuthenticateToken` reads `Authorization: Bearer ...`, looks up the token, loads the user and attaches it to the request.

## MongoDB modeling
- users: independent user documents
- tokens: separate authentication documents so multiple login tokens can exist
- groups: owner and member IDs are references
- expenses: participant shares are embedded because they are part of one expense
- settlements: independent payment records referenced by group/user IDs

## Balance calculation
For each expense: add the full amount to the payer and subtract each participant share. Then settlements add the payment to the payer and subtract it from the receiver. Positive net means receivable; negative means payable.

## Debt simplification
The balance service separates creditors and debtors and repeatedly matches the largest available debt against a creditor until both sides are settled.
