## Registry checker

### Any Value rule
Let's pretend you have html input elements like this:
```html
<input type="checkbox" name="value[]" value="One"/>
<input type="checkbox" name="value[]" value="Two"/>
```

If you're using `notEmpty` condition, you will receive error messages for both check boxes. It is a little bit confusing.
Use `anyValue` rule with defined field name, and you will receive only one error for exact error field.

> This checker should be registered in the "emptyConditions" section in the validation config:
```php
$config = [
    'emptyConditions' => [
        //...
        "registry::anyValue",
    ],
    'checkers'        => [
        //...
        "registry" => RegistryChecker::class,
    ],
]
```

#### Usage example
```php
class UserRequest extends RequestFilter
{
    const SCHEMA = [
        'published' => 'data:published',
    ];

    const VALIDATES = [        
        'published'      => [
            [
                '\Vvval\Spiral\Validation\Checkers\RegistryChecker::anyValue',
                published-error',
                '[[At lease one published option is required.]]',
                'error' => '[[This value is required.]]'
            ],
        ],
    ]
}
```
> 2nd rule argument is an error message placeholder name to render specific error message.
It is an optional field, if not set - you'll receive standard error. <br/>
3rd rule argument is a custom error message which will be returned to specified custom error message placeholder (if placeholder is set)

#### Response examples
```php
// Without 2nd argument (custom message placeholder)
[
    'errors' => [
        'published' => 'This value is required.'
    ]
]

// With 2nd argument (custom message placeholder):
[
    'errors' => [
        'published-error' => 'This value is required.'
    ]
]

// With both arguments (custom message placeholder and error message):
[
    'errors' => [
        'published-error' => 'At lease one published option is required.'
    ]
]
```

### Allowed Values rule
Check if array of given values contain only allowed values.
Allowed values are taken from database or other registry.<br/>
You can overwrite `populate` method to get allowed values from another place, for example from config. By default it's record source entity.

#### Usage example
```php
class UserRequest extends RequestFilter
{
    const SCHEMA = [
        'roles' => 'data:roles',
    ];

    const VALIDATES = [        
        'roles'      => [
            [
                '\Vvval\Spiral\Validation\Checkers\RegistryChecker::allowedValues',
                RoleSource::class,
                'name',
                'role-error-%s',
                '[[Invalid role.]]',
                'error' => '[[Contains not allowed value(s).]]'
            ],
        ],
    ]
}
```
> 2nd rule argument is RecordSource class name, <br/>
> 3rd one is the field, which is used to match valid values,<br/>
> 4th rule argument is an error message placeholder name to render specific error message (formatted with `sprintf`).
It is an optional field, if set - you'll receive additional errors for each invalid value. <br/>
5th rule argument is a custom error message which will be returned to specified custom error message placeholder (if placeholder is set), also optional.

#### Request examples
```php
$inputRoles = ['role1', 'role2', 'role3'];
//Allowed values will be 'role1', 'role2'; 
```
#### Response examples
```php
//

// Without 4th argument (custom message placeholder)
[
    'errors' => [
        'roles' => 'Contains not allowed value(s).'
    ]
]

// With 4th argument (custom message placeholder):
[
    'errors' => [
        'roles'            => 'Contains not allowed value(s).'
        'role-error-role3' => 'This value is not allowed' //default checker rule message, see MESSAGES constant
    ]
]

// With both arguments (custom message placeholder and error message):
[
    'errors' => [
        'roles'            => 'Contains not allowed value(s).'
        'role-error-role3' => 'Invalid role.'
    ]
]
```