# Orthanc
A php webdirectory which enables communication between skribbtlypo/js and Palantir/c# discord bot.

Most querystrings are processed as json, as well as the return values are json to enable easy js processing.

### Login
Verifies a login string which was obtained by messaging the Palantir Bot and returns a member object, which contains the stored member data.

### Verify
Verifies if a member object and a given token are valid and adds the corresponding guild to the member in stored members.

### Memberstate
Checks if the sent token matches a guild of the member included in the player status report string. If so, the report string is placed as json to be recognized by the bot.
The member status equals "playing", "searching" or "waiting".

### Report
Checks if the sent token matches a guild of the member included in the lobby report string. If so, the report is also placed so the bot detects it.
Existing reports with the same filename (lobby id) are overwritten (-> lobby refreshed).

### Status
Checks with same parameters as above if the member is allowed to request active lobbies of a guild.
If so, the lobbies status is returned which the client can display on the frontpage.

### Idprovider
Checks if a member object is valid and creates either a new lobby or updates an existing one.
Lobby keys are dynamic and represent a value which every player in a lobby can generate by an algorithm, whereas the ID is static and always assigned to a lobby key.
If the lobby hanges (player leave etc) the key is updated.

### Player
Early version of Report, not used anymore




### Probably, the json stuff should be replaced with any kind of database to avoid concurrent write/read
