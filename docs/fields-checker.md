## Fields checker
Checks equality between given value and another field's value.

#### Usage example
```php
class UserRequest extends RequestFilter
{
    const SCHEMA = [
        'password'        => 'data:password',
        'confirmPassword' => 'data:password',
    ];

    const VALIDATES = [        
        'password'        => [
            // password field validation rules         
        ],
        'confirmPassword' => [
            // other confirmPassword field validation rules
            [
                '\Vvval\Spiral\Validation\Checkers\FieldsChecker::equalsTo',
                'password',
                'error' => '[[Your passwords don’t match - please try again.]]'
            ],
        ],
    ]
}
```
> 2nd rule argument is a field name to compare values with.