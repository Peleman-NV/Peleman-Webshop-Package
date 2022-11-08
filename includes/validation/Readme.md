# Validation

## validation system
The validation system used in this plugin is meant to be a modular system of validation objects following the **chain of command** design pattern. The `handle` method of each object is to return a boolean value, `true` if the input is considered valid through the validation chain, and `false` the moment a validation fails. Because it is a chain of command, however, it is possible to pass the input through the entire chain, thus checking for any and all issues in validation, and storing them in an object implementing the  ```PWP_I_Notification``` interface.
___
### PWP_Abstract_Term_Handler
The abstract term handler object implements the basic ```PWP_I_Handler``` interface for Term handling.

```php
public function set_next(PWP_Abstract_Term_Handler $next): self
```
Method to define the next Term Handler in the chain of command. returns the handler that was input into the method, allowing for chaining `set_next` methods and easy construction of the chain.

```php
public function handle(PWP_Term_Data $request, PWP_I_Notification $notification): bool
```
Basic method to do validation. `$request` is the data to be validated, `$notification` the object to store validation errors in.

The intent is that the handle function will always call the next object in the chain, and keep track of a series of validation errors in the `$notification` object. If for any reason the chain has to end early, the method can simply return a boolean value indicating success or failure.

```php
protected function handle_next(PWP_Term_Data $request, PWP_I_Notification $notification): bool
```
Internal helper method to help with handling, to be called in the ```handle``` method if the chain is intended to continue to the next step. If there is no next step in the chain, handle_next will automatically return the `is_success` value of `$notification`. 
___