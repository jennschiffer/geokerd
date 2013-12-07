# geokerd
## geocoding mysql tables of addresses with ease

### What this is

While making the [Cost of Radiology app](https://github.com/jennschiffer/hackjersey-radiology) for Hack Jersey, we scraped a lot of addresses from reporter-generated spreadsheets - and when I say "reporter," I mean *actual people*. The addresses were not geocoded, and there were far to many to have anyone retrieve them manually during a 24-hour hackathon. I built this app so no one has that problem ever again.

### What you need to start

* Mysql table that is populated with addresses
* Your table must have a column for latitude values and a column for longitude values, empty or full, and both must be of type FLOAT
* Credentials which allow you to update said table
* A Google Maps API key - [see the Google API docs for info](https://developers.google.com/maps/documentation/javascript/tutorial#api_key)

### How to install

* Download contents of this app and put into desired directory
* Update 'config.php' with your database connection, table, and API info
* Go to your installed app directory on your browser, read the directions, and click the button
* Have fun!

### Made with love by [Jenn Schiffer](http://jenn.ws)
I hope this is as useful to some of you out there as it has been for me. If you used it for something cool, let me know through my site or [through le twitter](http://twitter.com/jennschiffer).