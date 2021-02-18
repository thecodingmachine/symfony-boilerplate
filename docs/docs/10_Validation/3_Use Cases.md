---
title: Use Cases
slug: /validation/use-cases
---

In the previous chapter, we saw how to validate models against many rules/constraints.

Yet, in your use cases, you may want to control when to validate these models.

## Simple Scenario

This scenario occurs when you are manipulating only one model in your use case.

For instance, let's say you want to save a `User` instance and validate its data:

```php
/**
 * @throws InvalidModel
 */
public function createUser(string $firstName, string $lastName): User
{
    $user = new User($firstName, $lastName);
    $this->userDao->save($user);
}
```

Here the `save` method from the DAO validates the `User` instance. No rocket science!

## "Complex" Scenario

Now let's say users have an optional profile picture.

You have to verify that:

1. The `User` instance is valid.
2. If it exists, the storable `ProfilePicture` is valid.
3. The instructions' order will not leave the data in a dirty state.

```php
/**
 * @throws InvalidModel
 * @throws InvalidStorable
 */
public function createUser(string $firstName, string $lastName, ?ProfilePicture $profilePicture = null): User
{
    $user = new User($firstName, $lastName);
    
    // We validate the user first.
    $this->userDao->validate($user);

    // Ok, the user is valid. 
    // If not, the previous instruction throws an InvalidModel exception.
    
    if ($profilePicture === null) {
        // No profile picture, let's save the user...
        $this->userDao->save($user);
        
        // ... and exit the method right away.
        return $user;
    }

    // We have a profile picture to upload to the storage.
    // Reminder: the method write validate the storable.
    $filename = $this->profilePictureStorage->write($profilePicture);
    $user->setProfilePicture($filename);

    // The profile picture has been uploaded to the storage,
    // and we know that our user is valid.
    // Unless something goes really wrong, we're good!
    $this->userDao->save($user);

    return $user;
}
```
