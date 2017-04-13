# Multiplatform Emoji client 😎

This Module allows you to use Emoji in your Web application interface.

If Emoji are natively supported by user's paltform, it will be rendered as-is. Otherwhise Module will provide fallback images.

## User Guide
To use Emoji in your interface, follow this steps.

- Include `specc-emoji.js` on your site. Bottom of the `<body>` tag prefered.
- Mark all Elements which may content Emoji with `js-emoji-included` class name.
- Fire
```javascript
Emoji.parse();
```

That's it.

😏

### How it works 🤔

First of all it checks for native Emoji supporting. You can access this feature by `Emoji.supported` property
```javascript
if ( Emoji.supported ){
	console.log('Emoji natively supported.');
} else {
	console.log('Emoji does not supported.');
}
```

If user's platform does not supports Emoji, Module will find and relpace all smiles with fallback images that looks like

```hmtl
<em class="emoji" data-emoji="😏"></em>
```

Cutted Unicode symbols stores in `data-emoji` attribute. Next, CSS class `emoji.css` will stylize this `<em>` tag with fallback images.


Note that Module requires special CSS file `emoji.css`, which placed in `../css/` (from original script) directory. If you need to specify your own style location, open `specc-emoji.js` and change `CSS_FILE_PATH` property to your path.
```javascript
/**
* @private
* Path where styles stored
*/
var CSS_FILE_PATH = 'css/emoji.css';
```

Same thing with CSS file: it uses fallback images by `../emoji/<FILENAME>` address.


## Example

Open `example.html` file.

## Questions

Fell free to give me a feedback on <a href="mailto::specc.dev@gmail.com">specc.dev@gmail.com</a> 😼


