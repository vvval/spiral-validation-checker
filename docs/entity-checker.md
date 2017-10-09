## Entity checker
Checks if current value is unique, best solution for user emails.

#### Usage example
```php
class UserRequest extends RequestFilter
{
    const SCHEMA = [
        'email' => 'data:email',
    ];

    const VALIDATES = [
        'email' => [
            // other email field validation rules
            ['\Vvval\Spiral\Validation\Checkers\EntityChecker::isUnique', UserSource::class, 'email'],
        ],
    ]
}
```
> 2nd rule argument is RecordSource class name,<br/> 3rd one is the field, which is used to match uniqueness.

If you're editing existing user, don't forget to set user entity to a request (validator) context:
```php
/**
 * @var UserRequest $request
 * @var User        $user
 */
$request->setContext($user);
```