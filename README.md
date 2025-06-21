[Русский](README.ru.md) | English

Embed Video for Laravel
=======================

The package is designed to create a URL for an embedded video and a URL for a cover image from various video hosting sites from a video URL in any format.  
The package can be used both to process user-entered data before adding it to the database and to display the result on the page.  
The package includes handlers for videos from YouTube, Rutube and VK Video.  
If necessary, you can add your own handler classes for other video hostings.


## Installation

Install the latest version with

```bash
composer require vhar/embed-video
```

## Usage

Call the `handle` method of the `Vhar\EmbedVideo\Facades\EmbedVideo` facade, passing it the URL of the video in any format as an argument.
```php
try {
    return EmbedVideo::handle('https://www.youtube.com/watch?v=gpn_4tWz1w8');
} catch (\Exception $exception) {
    return ['error' => $exception->getMessage()];
}
```

If the passed URL is successfully processed, the `handle` method will return an instance of `\Vhar\EmbedVideo\Handlers\EmbedDataDTO` class containing the ID, the URL for the embedded video, and the URL for the cover image.  
```php
{
  id: "gpn_4tWz1w8"
  video: "https://www.youtube.com/embed/gpn_4tWz1w8"
  cover: "https://img.youtube.com/vi/gpn_4tWz1w8/0.jpg"
}
```
or an exception with an error message, such as:
```
The argument is not valid URL-address to a YouTube video.
```

You can also call a specific handler using the `hosting` method passing an alias of an available handler as an argument.  
```php
try {
    return EmbedVideo::hosting('youtube')->handle('https://www.youtube.com/watch?v=gpn_4tWz1w8');
} catch (\Exception $exception) {
    return ['error' => $exception->getMessage()];
}
```

The following aliases are currently available:  
* `youtube` for YouTube;  
* `rutube` for Rutube;
* `vkvideo` for VK Video;

## Validation

To validate the video URL, pass the validator an instance of the EmbedVideoRule class with your other validation rules:
```php
use Vhar\EmbedVideo\Rules\EmbedVideoRule;

$request->validate([
    'name' => ['required', 'url', new EmbedVideoRule],
])
```

## Creating your own class-handler

Сreate a class implementing the interface `Vhar\EmbedVideo\Contracts\EmbedVideoInterface`.  
In the `handle` method, create the code to handle the URL string. This method should return an instance of `\Vhar\EmbedVideo\Handlers\EmbedDataDTO` class containing a property with an identifier (`id`), a URL for the embedded video (`video`), and a URL for the cover image (`cover`).  
```php
public function handle(string $url): EmbedDataDTO
{
    ...    
    return new EmbedDataDTO(
        id: $id, 
        video: $embedURL, 
        cover: $coverURL
    );
}
``` 

The `availableDomains` method must return an array with the hosting domain names for this handler.  
```php
public function allowedDomains(): array
{
    return [
        'youtu.be',
        'youtube.com',
    ];
}
```

Add your handler registration to the `register` method of the `App\Providers\AppServiceProvider` provider.  
```php
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ...
        \Vhar\EmbedVideo\Facades\VideoHosting::hosting('you_alias', \YouNamespace\YouClassHandlerService::class);
    }
}
```

Now, you can call the link handling from the new handler, just like for the built-in services, by calling the `handle` method of `Vhar\EmbedVideo\Facades\EmbedVideo` facade.  
