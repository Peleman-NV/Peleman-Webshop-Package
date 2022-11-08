# API

## PWP_API_Plugin

because of wordpress's loading system, API endpoints can only be registered after the
`rest_api_init` action is called.

This class serves to register all the endpoints and wait until registering all endpoints until the right action is called, as well as ensuring all endpoints work with a uniform namespace and authenticator.
___
## PWP_API_LOGGER

as the API logic runs, there might be a need to keep track of events that happen within, especially with batch calls. the `PWP_API_Logger` class is meant to provide this functionality.

    CURRENTLY UNDER CONSTRUCTION
___
## PWP_API_Log

utility class for `PWP_API_Logger`. each `PWP_API_Log` is a single logged event stored within the logger, to be converted into a string and returned in an array to the user after a request is completed.

    CURRENTLY UNDER CONSTRUCTION

___