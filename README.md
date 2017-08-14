Instagram Photo URL
===================

A simple utility to get the raw image URL from an Instagram post 

## Contributors
* [Andrew Norcross](https://github.com/norcross)

## About
Pass an Instagram post URL and retrieve the raw image URL from Instagram's CDN. Intended to replace having to screenshot things you want to share or save. An example can be [viewed here](https://ig.norcrossadmin.com/)

## Current Actions
* Displays a simple form to enter the Instagram URL and processes via a POST request.
* Allows for passing two query strings: `image-url` and `image-id` for processing via a GET request.

## Examples
* Passing the image URL: `https://example.com/?image-url=https://www.instagram.com/p/someImageID/`
* Passing the image ID: `https://example.com/?image-id=someImageID`

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Notes
This only works on public Instagram accounts.


#### [Pull requests](https://github.com/norcross/instagram-photo-url/pulls) are very much welcome and encouraged.