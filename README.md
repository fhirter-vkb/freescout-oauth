# OAuth FreeScout

This module is intended to provide oauth authentication to freescout.

Module was tested on keycloak oauth provider with confidential openid-connect client.

Module is require php curl extension on server.

Currently module fully replace login form with redirection to oauth provider login form.
If you need to perform ordinary login with basic form, add `disable_oauth` get parameter to login path (`/login?disable_oauth=1`)

User must be registered before oauth login.

## Installation

- place module source to Modules folder of your FreeScout installation, module must have **OAuth** folder name to work propperly. If you are clonning repo with git, just add folder name in the end of git clone command.
- enable module in modules admin panel
- configure module on settings page (client id/secret/etc)

## Provider Specific

### Azure Active Directory (AAD)

Register an App Registration in Azure Active Directory with scopes `openid`, `email` and `profile`.

| Setting                        | Value                                                                                            |
| ------------------------------ | ------------------------------------------------------------------------------------------------ |
| **Client ID**                  | <_App Registration Client ID_>                                                                   |
| **Client Secret**              | <_App Registration Client secret_>                                                               |
| **Authorization Endpoint URL** | _https://login.microsoftonline.com/{tenant-id}/oauth2/v2.0/authorize?scope=email+profile+openid_ |
| **Token Endpoint URL**         | _https://login.microsoftonline.com/{tenant-id}/oauth2/v2.0/token_                                |
| **User Info Endpoint URL**     | _https://graph.microsoft.com/oidc/userinfo_                                                      |
