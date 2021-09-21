# OAuth FreeScout
This module is intended to provide oauth authentication to freescout.

Module was tested on keycloak oauth provider with confidential openid-connect client.

Currently module fully replace login form with redirection to oauth provider login form. 
If you need to perform ordinary login with basic form, add `disable_oauth` get parameter to login path (`/login?disable_oauth=1`) 

## INSTALL
- place module source to Modules folder of your FreeScout installation
- enable module in modules admin panel
- configure module on settings page (client id/secret/etc) 
