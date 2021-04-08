# Trick forms usage

The following documentation explains how the different forms related to a __Trick__ and __TrickMedia / Medias__
associated with the Trick behave.

## File Structure

All forms are available in `/src/Form`.

__Events Listeners__ related to __MediaType__ are available in `/src/Form/EventListener`.

## Usage

### TrickType

The form handles __Trick__ creation and update.

In addition to the base mapped fields, the form has a __TricksMedia__ field, which is an _embed_ __CollectionType__ form.

### TricksMediaType

The form handles the __TricksMedia__ _Collection_.

It has one field : __MediaType__.

### MediaType

The form handles __Media__ creation or update.

It takes a `new` _option_, passed as a _bool_ value when creating the form :

```php
$this->createForm(MediaType::class, $media, ['new' => false]);
```

The `new` _option_ is used to dynamically add fields and constraints to the form. By default, this _option_ is set to `true`.

To improve code readability and maintainability, 4 _events listeners_ are used. Their role is to add the required fields to the __MediaType__, based on the data set on the underlying _object_ (__Media__ entity) in case it exists, and __FormEvents__.

The `FormEvents::POST_SET_DATA` and `FormEvents::PRE_SUBMIT` are used.

Fields added on `FormEvents::POST_SET_DATA` _event_ :

- if `new` _option_ is set to `true` :
    - __type__ (_ChoiceType_) of the __Media__ (_video_ or _image_) with the associated constraint.
    - __image__ (_FileType_) to upload a new image file with the associated constraints.
    - __video_url__ (_TextType_) to handle adding or updating a video, with the associated constraints.
    - __altText__ (_TextType_) to handle adding or updating an alternative text, with the associated constraint.
- if `new` _option_ is set to `false` :
    - if __Media__ has type _video_ : __video_url__ (_TextType_) to handle adding or updating a video, with the associated constraints.
    - if __Media__ has type _image_ : __altText__ (_TextType_) to handle adding or updating an alternative text, with the associated constraint.

| _option_ value | true | false |
| :-------------: | :----------: | :----------: |
| __Fields__ added |  |  |
| _type_ | X   | - |
| _image_ | X | - |
| _video_url_ | X | X <br><small>if __Media__ has type _video_</small> |
| _altText_ | X | X <br><small>if __Media__ has type _image_</small> |

Action called on `FormEvents::PRE_SUBMIT` _event_ :

- if the __Media__ is `new` and has a `video` _type_, `/src/Service/VideoHelper` helper is used to get video title associated with the __video_url__ data. This video title is used to fill the __altText__ field, without requiring the user to handle this field for the _video_ __Media__ _type_.
