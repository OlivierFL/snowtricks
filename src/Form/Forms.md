# Trick forms usage

The following documentation explains how the different forms related to a __Trick__ and __TrickMedia / Medias__
associated with the Trick behave.

## File Structure

All forms are available in `/src/Form`.

__Events Listeners__ related to __MediaType__ are available in `/src/Form/EventListener`.

## Usage

### TrickType

The form handles __Trick__ creation and update.

It takes a `new` _option_, passed as a _bool_ value when creating the form :

```php
$this->createForm(TrickType::class, $trick, ['new' => true]);
```

This _option_ is used to dynamically add the __TricksMedia__ (_CollectionType_) field for Trick __creation__. If no value is specified, the default value is __false__, and the form will be created to handle Trick __update__, i.e., the __TricksMedia__ field will not be added.

The `new` _option_ is then passed to the related __TricksMediaType__.

### TricksMediaType

The form handles the __TricksMedia__ _Collection_.

If the _option_ passed to __TrickType__ is `new`, this form will be added to the __TrickType__ as a form field. Inside the __Trick__
creation template, the prototype of this form will be used to add (via _Javascript_) one or many __Media__ entities. The `new` _option_ is then passed to the related __MediaType__.

### MediaType

The form handles __Media__ creation or update.

The `new` _option_ is used to dynamically add fields and constraints to the form.

To improve code readability and maintainability, 4 _events listeners_ are used. Their role is to add the required fields to the __MediaType__, based on the data set on the underlying _object_ (__Media__ entity) in case it exists, and __FormEvents__.

The `FormEvents::POST_SET_DATA` and `FormEvents::PRE_SUBMIT` are used.

Fields added on `FormEvents::POST_SET_DATA` _event_ :

- if `new` _option_ is set to `true` :
    - __type__ (_ChoiceType_) of the __Media__ (_video_ or _image_) with the associated constraint.
    - __image__ (_FileType_) to upload a new image file with the associated constraints.
- if `new` _option_ is set to `true`, or __Media__ object has data (in case of an _update_) :
    - __video_url__ (_TextType_) to handle adding or updating a video, with the associated constraints.
    - __altText__ (_TextType_) to handle adding or updating an alternative text, with the associated constraint.

| _option_ value | true | false |
| :-------------: | :----------: | :----------: |
| __Fields__ added |  |  |
| _type_ | X   | - |
| _image_ | X | - |
| _video_url_ | X | X <br><small>if __Media__ has data (_update_)</small> |
| _altText_ | X | X <br><small>if __Media__ has data (_update_)</small> |

Action called on `FormEvents::PRE_SUBMIT` _event_ :

- if the __Media__ is `new` and has a `video` _type_, `/src/Service/VideoHelper` helper is used to get video title associated with the __video_url__ data. This video title is used to fill the __altText__ field, without requiring the user to handle this field for the _video_ __Media__ _type_.
