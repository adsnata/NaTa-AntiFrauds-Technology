# NaTa-AntiFrauds-Technology
Advanced PHP Proxy/VPN/Tor Filter &amp; AntiFrauds low quality user
# Documentation
<b>SetKey($key)</b> : Set your IP Quality Score key for connection to the API. This must be set before any checks can be made.<br>
<b>SetStrictness($strictness)</b> 	Set your strictness requirement. Must be an integer between 0 (least thorough / fastest) and 5 (most thorough / slowest). Defaults to 0 if not set.<br>
<b>PassUserAgent($bool)</b> </b> </b> 	Allow the library to pass the User's Agent with each call. Passing the user agent allows for stronger fraud detection. Passed value must be boolean.<br> Defaults to false.
<b>SetFailureRedirect($url)</b> </b> 	If the user is a proxy where do we redirect them to. If no value is passed the code will display a blank white page and halt execution. (Optional)<br>
<b>SetSuccessRedirect($url)</b> 	If the user is NOT a proxy where do we redirect them to. If no value is passed the code will allow the page to continue execution (thus allowing the user to view content on the current page). (Optional)<br>
<b>CheckIP($ip)</b> 	Make a request to our API to check an IP. Calling this function will not halt execution or redirect the user. This is primarily used for bulk lookups or testing. Requires valid IPv4, returns hash array.<br>
<b>ForceRedirect($type = 'proxy')</b> 	Make a request to our API to check the user's current IP and upon returning the library will either halt execution, redirect or allow execution based on parameters previously passed. Optionally a type variable may be passed which allows you to filter visitors based on one of three modes: proxy (all methods of changing your IP), VPN (only VPN users) and Tor (only Tor users).<br>
<b>These are defined as constants</b>:<br>
  *static::TYPE_PROXY<br>
  *static::TYPE_VPN <br>
  *static::TYPE_TOR<br>
  
 # Contact : info@adsnata.com for get more support
 ![alt text](https://i.imgur.com/jffEZ7Y.png)
