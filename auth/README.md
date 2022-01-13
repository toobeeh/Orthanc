## auth

Authorisation for Palantir login via Discord's OAuth2.  

### Flow:
- User gets redirected here after login button press or whatever
- User logs in and Discord OAuth2 completes, provides data 
- If User has no Palantir account yet, they are asked to create one 
- Popup closes and sends a JS message event containing the access token to the window opener
- Opener can use the access token to make API requests or log in to Ithil-Rebirth
